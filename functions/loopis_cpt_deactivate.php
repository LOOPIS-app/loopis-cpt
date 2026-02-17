<?php
/**
 * Deactivation function
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}


// Clean up function for deactivation
/*
function loopis_faq_cleanup() {
*/
    // Careful: this will delete all FAQ posts, do not use/uncomment unless you have backup of the FAQ posts
    /*$faqs = get_posts([
        'post_type' => 'faq',
        'numberposts' => -1,
        'post_status' => 'any',
    ]);

    foreach ($faqs as $faq) {
        wp_delete_post($faq->ID, true); // permanent delete
    }*/
    
    //  Fetch and delete all categories in faq_kategori taxonomy - categories that are not included in the "default categories function" will be lost
    /*$categories = get_terms([
        'taxonomy'   => 'faq_category',
        'hide_empty' => false,
    ]);
    if (!is_wp_error($categories)) {
        foreach ($categories as $cat) {
            wp_delete_term($cat->term_id, 'faq_category');
        }
    }

    // Fetch and delete all tags in faq_tag taxonomy - tags that are not included in the "default tags function" will be lost
    $tags = get_terms([
        'taxonomy'   => 'faq_tag',
        'hide_empty' => false,
    ]);
    if (!is_wp_error($tags)) {
        foreach ($tags as $tag) {
            wp_delete_term($tag->term_id, 'faq_tag');
        }
    }
}

// Register the cleanup function

register_deactivation_hook(__FILE__, 'loopis_faq_cleanup');
*/

?>