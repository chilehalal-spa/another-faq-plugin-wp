<?php

defined( 'ABSPATH' ) || exit;

class AFQ_Shortcodes {

    /**
     * Preguntas mostradas en la página, para el marcado FAQPage del pie.
     *
     * @var array<int, array{q: string, a: string}>
     */
    private $schema_items = [];

    /**
     * Encola los assets del acordeón en el frontend.
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        $css = 'assets/public/css/afq-faq.css';
        $js  = 'assets/public/js/afq-faq.js';

        // Versión basada en la fecha de modificación del archivo: el caché se invalida solo en cada cambio
        wp_enqueue_style( 'afq-faq-css', AFQ_PLUGIN_URL . $css, [], filemtime( AFQ_PLUGIN_PATH . $css ) );
        wp_enqueue_script( 'afq-faq-js', AFQ_PLUGIN_URL . $js, [], filemtime( AFQ_PLUGIN_PATH . $js ), true );
    }

    /**
     * Renderiza el acordeón de Preguntas Frecuentes.
     *
     * Uso: [faq categoria="envios" limite="10" orden="menu_order" direccion="ASC" exclusivo="si"]
     * También acepta [faq id="123"] para mostrar una sola pregunta.
     *
     * Con "titulo" se arma en dos columnas: a la izquierda etiqueta, título, texto y
     * botón de contacto; a la derecha las preguntas.
     * [faq titulo="Resolvemos tus dudas" whatsapp="si" boton="Contacta con un profesional"]
     * "whatsapp" acepta "si" (usa el número del botón flotante del tema) o un número;
     * "enlace" acepta cualquier URL y tiene prioridad.
     *
     * @param array $atts Atributos del shortcode.
     * @return string Marcado HTML del acordeón.
     */
    public function render_faq( $atts ) {
        $atts = shortcode_atts( [
            'id'        => 0,
            'categoria' => '',
            'limite'    => -1,
            'orden'     => 'menu_order',
            'direccion' => 'ASC',
            'exclusivo' => 'si',
            'titulo'    => '',
            'etiqueta'  => 'Preguntas frecuentes',
            'texto'     => '¿No encuentras tu respuesta? Nuestro equipo te responde directamente.',
            'boton'     => 'Contacta con un profesional',
            'enlace'    => '',
            'whatsapp'  => '',
        ], $atts, 'faq' );

        $query_args = [
            'post_type'      => 'afq_faq',
            'posts_per_page' => intval( $atts['limite'] ),
            'orderby'        => sanitize_key( $atts['orden'] ),
            'order'          => 'DESC' === strtoupper( $atts['direccion'] ) ? 'DESC' : 'ASC',
        ];

        if ( intval( $atts['id'] ) > 0 ) {
            $query_args['p'] = intval( $atts['id'] );
        } elseif ( ! empty( $atts['categoria'] ) ) {
            $query_args['tax_query'] = [ [
                'taxonomy' => 'afq_categoria',
                'field'    => 'slug',
                'terms'    => sanitize_title( $atts['categoria'] ),
            ] ];
        }

        $faqs = get_posts( $query_args );

        if ( empty( $faqs ) ) return '';

        $exclusivo = 'no' === strtolower( $atts['exclusivo'] ) ? 'no' : 'si';

        ob_start();
        ?>
        <div class="afq-accordion" data-exclusivo="<?php echo esc_attr( $exclusivo ); ?>">
            <?php foreach ( $faqs as $faq ) :
                $answer = apply_filters( 'the_content', $faq->post_content );
                $this->schema_items[ $faq->ID ] = [ 'q' => get_the_title( $faq ), 'a' => $answer ];
            ?>
                <details class="afq-item">
                    <summary class="afq-question">
                        <h3 class="afq-question-text"><?php echo esc_html( get_the_title( $faq ) ); ?></h3>
                        <span class="afq-icon" aria-hidden="true"></span>
                    </summary>
                    <div class="afq-answer">
                        <div class="afq-answer-inner"><?php echo $answer; ?></div>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
        <?php
        $accordion = ob_get_clean();

        if ( '' === trim( $atts['titulo'] ) ) {
            return $accordion;
        }

        return $this->render_two_columns( $atts, $accordion );
    }

    /**
     * Envuelve el acordeón con la columna de título y contacto.
     *
     * @param array  $atts      Atributos del shortcode ya normalizados.
     * @param string $accordion Marcado del acordeón.
     * @return string
     */
    private function render_two_columns( $atts, $accordion ) {
        $link = $this->contact_link( $atts );
        $is_whatsapp = false !== strpos( $link, 'wa.me/' );

        ob_start();
        ?>
        <div class="afq-layout">
            <div class="afq-intro">
                <?php if ( $atts['etiqueta'] ) : ?>
                    <p class="afq-eyebrow"><?php echo esc_html( $atts['etiqueta'] ); ?></p>
                <?php endif; ?>
                <h2 class="afq-title"><?php echo esc_html( $atts['titulo'] ); ?></h2>
                <?php if ( $atts['texto'] ) : ?>
                    <p class="afq-text"><?php echo esc_html( $atts['texto'] ); ?></p>
                <?php endif; ?>
                <?php if ( $link && $atts['boton'] ) : ?>
                    <a class="afq-contact<?php echo $is_whatsapp ? ' afq-contact-whatsapp' : ''; ?>" href="<?php echo esc_url( $link ); ?>"<?php echo $is_whatsapp ? ' target="_blank" rel="noopener"' : ''; ?>>
                        <?php if ( $is_whatsapp ) : ?><i class="fa-brands fa-whatsapp" aria-hidden="true"></i><?php endif; ?>
                        <?php echo esc_html( $atts['boton'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php echo $accordion; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Destino del botón de contacto: "enlace", o WhatsApp con el número indicado o el del tema.
     *
     * @param array $atts Atributos del shortcode.
     * @return string URL o vacío.
     */
    private function contact_link( $atts ) {
        if ( $atts['enlace'] ) {
            return $atts['enlace'];
        }

        $wa = strtolower( trim( $atts['whatsapp'] ) );
        if ( '' === $wa || 'no' === $wa ) {
            return '';
        }

        $number = 'si' === $wa ? get_theme_mod( 'gob_whatsapp_number', '' ) : $wa;
        $number = preg_replace( '/\D/', '', (string) $number );

        return $number ? 'https://wa.me/' . $number : '';
    }

    /**
     * Imprime el marcado FAQPage con todas las preguntas mostradas en la página,
     * para que Google pueda mostrarlas en los resultados de búsqueda.
     *
     * @return void
     */
    public function print_schema() {
        if ( empty( $this->schema_items ) ) {
            return;
        }

        $entities = [];
        foreach ( $this->schema_items as $item ) {
            $entities[] = [
                '@type'          => 'Question',
                'name'           => wp_strip_all_tags( $item['q'] ),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => trim( wp_strip_all_tags( $item['a'] ) ),
                ],
            ];
        }

        $schema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];

        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
}
