<?php

defined( 'ABSPATH' ) || exit;

require_once AFQ_PLUGIN_PATH . 'includes/core/class-afq-cpt.php';
require_once AFQ_PLUGIN_PATH . 'includes/frontend/class-afq-shortcodes.php';

class AFQ_Loader {

    /**
     * Registra todos los hooks del plugin.
     *
     * @return void
     */
    public function run() {
        // Core: CPT y Taxonomía
        $cpt = new AFQ_CPT();
        add_action( 'init', [ $cpt, 'register_post_type' ] );
        add_action( 'init', [ $cpt, 'register_taxonomy' ] );

        // Core: Columna de Shortcode en el listado de Admin
        add_filter( 'manage_afq_faq_posts_columns', [ $cpt, 'add_admin_columns' ] );
        add_action( 'manage_afq_faq_posts_custom_column', [ $cpt, 'render_admin_columns' ], 10, 2 );

        // Frontend: Shortcode & Assets
        $shortcodes = new AFQ_Shortcodes();
        add_shortcode( 'faq', [ $shortcodes, 'render_faq' ] );
        add_action( 'wp_enqueue_scripts', [ $shortcodes, 'enqueue_frontend_assets' ] );
        add_action( 'wp_footer', [ $shortcodes, 'print_schema' ] );
    }
}
