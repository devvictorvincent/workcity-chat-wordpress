<?php
function workcity_register_rest_api() {
    register_rest_route('workcity-chat/v1', '/conversations', array(
        'methods' => 'GET',
        'callback' => 'workcity_get_conversations',
        'permission_callback' => '__return_true',  
    ));

    register_rest_route('workcity-chat/v1', '/messages', array(
        'methods' => 'POST',
        'callback' => 'workcity_create_message',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'workcity_register_rest_api');

function workcity_get_conversations(WP_REST_Request $request) {
     
    $args = array('post_type' => 'chat_session', 'posts_per_page' => -1);
    $conversations = get_posts($args);
    return rest_ensure_response($conversations);
}

function workcity_create_message(WP_REST_Request $request) {
    $params = $request->get_json_params();
    
    $post_id = wp_insert_post(array(
        'post_type' => 'chat_session',
        'post_title' => 'New Chat: ' . $params['participants'],
        'post_content' => $params['message'],
        'post_status' => 'publish'
    ));
 
    wp_remote_post(WORKCITY_CHAT_BACKEND_URL . '/api/messages', array('body' => json_encode($params)));
    return rest_ensure_response(array('success' => true, 'post_id' => $post_id));
}