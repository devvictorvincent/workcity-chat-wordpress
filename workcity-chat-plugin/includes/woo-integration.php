<?php
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
   
    add_action('woocommerce_new_order', 'workcity_sync_chat_to_order');
    function workcity_sync_chat_to_order($order_id) {
        $chat_id = get_transient('current_chat_id'); 
                if ($chat_id) {
            update_post_meta($order_id, '_linked_chat', $chat_id);
        }
    }

     
    add_action('woocommerce_after_add_to_cart_button', 'workcity_add_product_chat_button');
    function workcity_add_product_chat_button() {
        echo do_shortcode('[workcity_chat product_id="' . get_the_ID() . '"]');
    }

    }