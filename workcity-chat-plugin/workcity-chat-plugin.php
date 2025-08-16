<?php
/**
 * Plugin Name: Workcity Chat Plugin
 * Plugin URI: https://github.com/yourusername/workcity-chat-wordpress
 * Description: Integrates real-time chat for eCommerce with shortcode, and REST API.
 * Version: 1.0.0
 * Author: Victor Okorie
 * Author URI: https://victorpremium.site
 * License: GPL-2.0+
 */

if (!defined('ABSPATH')) {
    exit; 
}

define('WORKCITY_CHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WORKCITY_CHAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WORKCITY_CHAT_BACKEND_URL', 'http://localhost:5000'); 

  
require_once WORKCITY_CHAT_PLUGIN_DIR . 'includes/cpt.php';
require_once WORKCITY_CHAT_PLUGIN_DIR . 'includes/shortcode.php';
require_once WORKCITY_CHAT_PLUGIN_DIR . 'includes/rest-api.php';
require_once WORKCITY_CHAT_PLUGIN_DIR . 'includes/woo-integration.php';
 

function workcity_chat_enqueue_assets() {
    wp_enqueue_script('workcity-chat-widget', WORKCITY_CHAT_PLUGIN_URL . 'assets/chat-widget.js', array('jquery'), '1.0.0', true);
    wp_localize_script('workcity-chat-widget', 'workcityChat', array(
        'backendUrl' => WORKCITY_CHAT_BACKEND_URL,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('workcity_chat_nonce')
    ));
    wp_enqueue_style('workcity-chat-style', WORKCITY_CHAT_PLUGIN_URL . 'assets/chat-widget.css');
}
add_action('wp_enqueue_scripts', 'workcity_chat_enqueue_assets');