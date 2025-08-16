// assets/chat-widget.js
document.addEventListener('DOMContentLoaded', function() {
    const widgets = document.querySelectorAll('#workcity-chat-widget');
    widgets.forEach(widget => {
        const conversationId = widget.getAttribute('data-conversation-id');
        const productId = widget.getAttribute('data-product-id');
        
        // Example: Load React bundle or iframe
        // For simplicity, iframe the frontend app
        const iframe = document.createElement('iframe');
        iframe.src = 'http://localhost:3000/chat?conv=' + conversationId + '&prod=' + productId; // Point to your React app
        iframe.style.width = '100%';
        iframe.style.height = '500px';
        iframe.style.border = 'none';
        widget.innerHTML = '';
        widget.appendChild(iframe);

        // AJAX for messaging (bonus)
        jQuery.ajax({
            url: workcityChat.ajaxUrl,
            type: 'POST',
            data: {
                action: 'workcity_send_message',
                message: 'Hello', // Example
                nonce: workcityChat.nonce
            },
            success: function(response) {
                console.log('Message sent:', response);
            }
        });
    });
});

// Handle AJAX action in PHP (add to shortcode.php or main file)
add_action('wp_ajax_workcity_send_message', 'workcity_handle_send_message');
add_action('wp_ajax_nopriv_workcity_send_message', 'workcity_handle_send_message');

function workcity_handle_send_message() {
    check_ajax_referer('workcity_chat_nonce', 'nonce');
    $message = sanitize_text_field($_POST['message']);
    // Send to backend via wp_remote_post
    $response = wp_remote_post(WORKCITY_CHAT_BACKEND_URL . '/api/messages', array(
        'body' => json_encode(array('text' => $message)),
        'headers' => array('Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $_SESSION['jwt']) // Assume JWT from login
    ));
    wp_send_json_success($response['body']);
}