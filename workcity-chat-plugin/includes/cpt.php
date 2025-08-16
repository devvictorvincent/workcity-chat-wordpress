<?php
function workcity_register_chat_session_cpt() {
    $labels = array(
        'name' => 'Chat Sessions',
        'singular_name' => 'Chat Session',
        'menu_name' => 'Chat Sessions',
        'name_admin_bar' => 'Chat Session',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Chat Session',
        'new_item' => 'New Chat Session',
        'edit_item' => 'Edit Chat Session',
        'view_item' => 'View Chat Session',
        'all_items' => 'All Chat Sessions',
        'search_items' => 'Search Chat Sessions',
        'not_found' => 'No chat sessions found.',
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'custom-fields'), // For messages, participants
        'hierarchical' => false,
        'has_archive' => false,
        'menu_icon' => 'dashicons-format-chat',
        'rewrite' => false,
    );

    register_post_type('chat_session', $args);

    // Custom meta boxes for participants, timestamps, etc.
    add_action('add_meta_boxes', 'workcity_add_chat_meta_boxes');
    add_action('save_post', 'workcity_save_chat_meta');
}

add_action('init', 'workcity_register_chat_session_cpt');

function workcity_add_chat_meta_boxes() {
    add_meta_box('chat_participants', 'Participants', 'workcity_chat_participants_callback', 'chat_session');
    add_meta_box('chat_timestamps', 'Timestamps', 'workcity_chat_timestamps_callback', 'chat_session');
}

function workcity_chat_participants_callback($post) {
    $participants = get_post_meta($post->ID, '_chat_participants', true);
    wp_nonce_field('workcity_chat_meta_nonce', 'chat_meta_nonce');
    echo '<input type="text" name="chat_participants" value="' . esc_attr($participants) . '" style="width:100%;">';
}

function workcity_chat_timestamps_callback($post) {
    $start_time = get_post_meta($post->ID, '_chat_start_time', true);
    echo '<input type="text" name="chat_start_time" value="' . esc_attr($start_time) . '" style="width:100%;">';
}

function workcity_save_chat_meta($post_id) {
    if (!isset($_POST['chat_meta_nonce']) || !wp_verify_nonce($_POST['chat_meta_nonce'], 'workcity_chat_meta_nonce')) {
        return;
    }
    if (isset($_POST['chat_participants'])) {
        update_post_meta($post_id, '_chat_participants', sanitize_text_field($_POST['chat_participants']));
    }
    if (isset($_POST['chat_start_time'])) {
        update_post_meta($post_id, '_chat_start_time', sanitize_text_field($_POST['chat_start_time']));
    }
}