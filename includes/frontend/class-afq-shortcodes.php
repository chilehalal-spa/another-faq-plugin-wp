<?php

defined( 'ABSPATH' ) || exit;

class AFQ_Shortcodes {

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
            <?php foreach ( $faqs as $faq ) : ?>
                <details class="afq-item">
                    <summary class="afq-question">
                        <h2 class="afq-question-text"><?php echo esc_html( get_the_title( $faq ) ); ?></h2>
                    </summary>
                    <div class="afq-answer">
                        <div class="afq-answer-inner"><?php echo apply_filters( 'the_content', $faq->post_content ); ?></div>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
