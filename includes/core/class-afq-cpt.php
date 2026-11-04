<?php

defined( 'ABSPATH' ) || exit;

class AFQ_CPT {

    /**
     * Registra el tipo de post 'afq_faq'.
     *
     * @return void
     */
    public function register_post_type() {
        $labels = [
            'name'          => 'Preguntas Frecuentes',
            'singular_name' => 'Pregunta Frecuente',
            'add_new_item'  => 'Añadir Nueva Pregunta',
            'edit_item'     => 'Editar Pregunta',
            'search_items'  => 'Buscar Preguntas',
            'menu_name'     => 'Preguntas Frecuentes',
        ];

        $args = [
            'labels'       => $labels,
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-editor-help',
            'supports'     => [ 'title', 'editor', 'page-attributes' ],
            'show_in_rest' => true,
        ];

        register_post_type( 'afq_faq', $args );
    }

    /**
     * Registra la taxonomía 'afq_categoria' para agrupar preguntas.
     *
     * @return void
     */
    public function register_taxonomy() {
        $labels = [
            'name'          => 'Categorías de FAQ',
            'singular_name' => 'Categoría de FAQ',
            'search_items'  => 'Buscar Categorías',
            'edit_item'     => 'Editar Categoría',
            'add_new_item'  => 'Añadir Nueva Categoría',
            'menu_name'     => 'Categorías',
        ];

        $args = [
            'labels'       => $labels,
            'hierarchical' => true,
            'public'       => true,
            'show_in_rest' => true,
        ];

        register_taxonomy( 'afq_categoria', [ 'afq_faq' ], $args );
    }

    /**
     * Define las columnas personalizadas para el listado de preguntas.
     *
     * @param array $columns Columnas existentes.
     * @return array Columnas modificadas.
     */
    public function add_admin_columns( $columns ) {
        $new_columns = [];
        foreach ( $columns as $key => $title ) {
            $new_columns[ $key ] = $title;
            if ( 'title' === $key ) {
                $new_columns['afq_shortcode'] = 'Shortcode';
            }
        }
        return $new_columns;
    }

    /**
     * Renderiza el contenido de las columnas personalizadas.
     *
     * @param string $column Nombre de la columna.
     * @param int    $post_id ID del post actual.
     * @return void
     */
    public function render_admin_columns( $column, $post_id ) {
        if ( 'afq_shortcode' === $column ) {
            // Creamos un input readonly para facilitar la copia con un clic
            $shortcode = sprintf( '[faq id="%d"]', $post_id );
            echo sprintf(
                '<input type="text" value="%s" readonly style="width:100%%; background:#f9f9f9; border:1px solid #ddd; padding:5px; box-shadow:none;" onclick="this.select();">',
                esc_attr( $shortcode )
            );
        }
    }
}
