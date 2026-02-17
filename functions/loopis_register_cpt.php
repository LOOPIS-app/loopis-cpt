<?php
/**
 * Function to register custom CPTs
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}



function register_cpts() {

 $cpts = [

    // CTP faqz

    'faqz' => [
        'labels' => [
            'name'          => 'Faqz',
            'singular_name' => 'FAQ-singular',
			'add_new_item'  => 'Add new FAQ',
            'search_items'  => 'Search FAQs',
        ],

        'public'                => true,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'exclude_from_search'   => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-sticky',
        'hierarchical'          => true,
        'has_archive'           => 'faqzz',
        'query_var'             => 'faqzz',
        'rest_base'             => 'faqz', // rest_base added
        'map_meta_cap'          => true,
        'menu_position'         => 13,

        'rewrite' => [
            'slug'          => 'faqzz',
            'with_front'    => true,
            'pages'         => true,
            'feeds'         => true,
            'ep_mask'       => EP_PERMALINK,
        ],

        'supports' => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
        ],
    ],

    // CPT forumz

    'forumz' => [
        'labels' => [
            'name'          => 'Forumz',
            'singular_name' => 'Forum-singular',
			'add_new_item'  => 'Add new Forum post',
            'search_items'  => 'Search Forum posts',
        ],

        'public'                => true,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'exclude_from_search'   => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-admin-comments',
        'hierarchical'          => true,
        'has_archive'           => 'forumzz',
        'query_var'             => 'forumzz',
        'rest_base'             => 'forumz', // rest_base added
        'map_meta_cap'          => true,
        'menu_position'         => 14,

        'rewrite' => [
            'slug'          => 'forumzz',
            'with_front'    => true,
            'pages'         => true,
            'feeds'         => true,
            'ep_mask'       => EP_PERMALINK,
        ],

        'supports' => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
        ],
    ],

    // CPT supportz
    
    'supportz' => [
        'labels' => [
            'name'          => 'Supportz',
            'singular_name' => 'Support-singular',
            'add_new_item'  => 'Add new supportz',
            'search_items'  => 'Search supportzs',
        ],

        'public'                => true,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'exclude_from_search'   => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_icon'             => 'dashicons-sos',
        'hierarchical'          => true,
        'has_archive'           => 'supportzz',
        'query_var'             => 'supportzz',
        'rest_base'             => 'supportz', // rest_base added
        'map_meta_cap'          => true,
        'menu_position'         => 15,

        'rewrite' => [
            'slug'          => 'supportzz',
            'with_front'    => true,
            'pages'         => true,
            'feeds'         => true,
            'ep_mask'       => EP_PERMALINK,
        ],

        'supports' => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
        ],
    ],

    // add more CPTs here

    /*
    'supportz' => [
        'labels' => [
            'name' => 'Supportz',
            'singular_name' => 'Support',
        ],

        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-sos',
        'menu_position' => 15,

        'supports' => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
        ],

        'has_archive' => true,

        'rewrite' => [
            'slug' => 'lp_support',
            'with_front' => false,
        ],
    ],*/

    ];

    foreach ( $cpts as $post_type => $args ) {
    
        register_post_type( $post_type, $args );
    }

}

add_action( 'init', 'register_cpts' );

/*add_action('rest_api_init', function() {
    $routes = rest_get_server()->get_routes();
    echo '<pre>!!!';
    print_r(array_keys($routes));
    echo '</pre>';
    exit;
});*/

?>