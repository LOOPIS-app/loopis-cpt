<?php
/**
 * Function to register custom taxonomies
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

function register_taxonomies() {

    $taxonomies = [

        // A taxonomy for the CPT type 'faqz' with the name 'faq_categoryz' etc
        
        'faq_categoryz' => [
            'post_type' => 'faqz',
            'slug' => 'faqz-kategori',
            'name' => 'FAQz-kategori',
            'hierarchical' => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_admin_column' => true,
        ],

        'forum_categoryz' => [
            'post_type' => 'forumz',
            'slug' => 'forumz-kategori',
            'name' => 'Forumz-kategori',
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_tagcloud'     => true,
        ],
        
        'support_categoryz' => [
            'post_type' => 'supportz',
            'slug' => 'supportz-kategori',
            'name' => 'Supportz-kategori',
            'hierarchical' => true,
            'show_tagcloud'     => true,
            'show_admin_column' => true,
        ],

        // Add more taxonomies here
    ];

    foreach ( $taxonomies as $taxonomy => $tax ) {

        register_taxonomy( $taxonomy, $tax['post_type'], [
            'labels' => [
                'name' => $tax['name'],
            ],
            'hierarchical' => $tax['hierarchical'],
            'rewrite' => [ 'slug' => $tax['slug'] ],
            'show_in_rest'      => true,
            'show_admin_column' => $tax['show_admin_column'],
            'show_tagcloud'     => $tax['show_tagcloud'],
        ] );
    }

}

add_action( 'init', 'register_taxonomies' );

?>