<?php
add_action('init', function () {
    if (isset($_POST['rating'], $_POST['post_id'])) {
        $post_id = (int) $_POST['post_id'];
        $rating  = (int) $_POST['rating'];

        if ($rating >= 1 && $rating <= 5) {
            $total = (int) get_post_meta($post_id, '_rating_total', true);
            $count = (int) get_post_meta($post_id, '_rating_count', true);

            update_post_meta($post_id, '_rating_total', $total + $rating);
            update_post_meta($post_id, '_rating_count', $count + 1);
        }

        wp_redirect(get_permalink($post_id));
        exit;
    }
});