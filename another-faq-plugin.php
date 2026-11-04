<?php
/**
 * Plugin Name: Another FAQ Plugin
 * Description: Plugin de Preguntas Frecuentes con CPT y shortcode [faq].
 * Version: 1.0
 * Author: Zumito
 * Text Domain: another-faq-plugin
 */

defined( 'ABSPATH' ) || exit;

// Definimos la ruta base para facilitar los includes
define( 'AFQ_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'AFQ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AFQ_PLUGIN_PATH . 'includes/core/class-afq-loader.php';

/**
 * Inicializa el plugin.
 *
 * @return void
 */
function afq_init_plugin() {
    $loader = new AFQ_Loader();
    $loader->run();
}

add_action( 'plugins_loaded', 'afq_init_plugin' );

/**
 * Registra el CPT y regenera las reglas de reescritura al activar el plugin,
 * para que el enlace permanente funcione sin tener que guardar los permalinks
 * manualmente.
 *
 * @return void
 */
function afq_activate_plugin() {
    require_once AFQ_PLUGIN_PATH . 'includes/core/class-afq-cpt.php';
    ( new AFQ_CPT() )->register_post_type();
    ( new AFQ_CPT() )->register_taxonomy();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'afq_activate_plugin' );

/**
 * Limpia las reglas de reescritura al desactivar el plugin.
 *
 * @return void
 */
function afq_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'afq_deactivate_plugin' );
