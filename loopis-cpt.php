<?php
/**
* Plugin Name: LOOPIS CPT
* Plugin URI:  https://github.com/LOOPIS-app/loopis-cpt/
* Description: Plugin for handling custom post types & related taxonomies
* Version: 0.1
* Author: nissegit
* Text Domain: loopis-cpt
*/

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

// Load CPTs

require_once plugin_dir_path( __FILE__ ) . '/functions/loopis_register_cpt.php';

// Load taxonomies

require_once plugin_dir_path( __FILE__ ) . '/functions/loopis_register_tax.php';

// Load default terms in taxonomies

require_once plugin_dir_path( __FILE__ ) . '/functions/loopis_default_terms.php';

// Load custom fields

require_once plugin_dir_path( __FILE__ ) . '/functions/loopis_custom_fields.php';

// Load config

// require_once plugin_dir_path( __FILE__ ) . '/functions/loopis_cpt_config.php';

// Load deactivation function (cleanup) ... move this out of loops-cpt.php to "functions/loopis_cpt_deactivate.php

// Load Ajax JS

add_action('admin_enqueue_scripts', 'loopis_enqueue_admin_scripts');

function loopis_enqueue_admin_scripts() {

     // Local JS ajax script (jQuery) for adding single or multiple users
    wp_enqueue_script(
        'loopis-user-ajax',
        plugin_dir_url(__FILE__) . '/assets/js/loopis-user-ajax.js',
        ['jquery'],
        '1.0',
        true
    );
 
    // Using WP admin-ajax for single or multiple users
    wp_localize_script('loopis-user-ajax', 'loopisUserAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('loopis_user_search'),
    ]);

    // CSS styling for single or multiple users
    wp_enqueue_style(
        'custom-css',
         plugin_dir_url( __FILE__ ) . '/assets/css/loopis-user-ajax.css',       
        [],
        '1.0'
    );

}

// Load PHP-Ajax handler for single and multiple user select in custom fields
add_action('wp_ajax_loopis_user_search', 'loopis_user_ajax_search');

function loopis_user_ajax_search() {

    check_ajax_referer('loopis_user_search', 'nonce');

    if ( ! current_user_can('edit_posts') ) {
        wp_send_json_error();
    }

    $q = sanitize_text_field($_POST['q'] ?? '');

    if ( strlen($q) < 2 ) {
        wp_send_json_success([]);
    }

    $users = get_users([
        'search'         => '*' . esc_attr($q) . '*',
        'search_columns'=> ['user_login', 'display_name', 'user_email'],
        'number'         => 10,
        'orderby'        => 'display_name',
        'order'          => 'ASC',
    ]);

    $results = [];

    foreach ( $users as $user ) {
        $results[] = [
            'id'    => $user->ID,
            'label' => $user->display_name . ' (' . $user->user_email . ')',
        ];
    }

    wp_send_json_success($results);
} 

// Save function for taxonomy field

function loopis_save_taxonomy_field( $post_id ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    if ( ! isset($_REQUEST['status']) ) return;

    $taxonomy = 'support_categoryz';
    $term_id  = intval( $_REQUEST['status'] );

    if ( $term_id ) {
        wp_set_object_terms( $post_id, [ $term_id ], $taxonomy, false );
    } else {
        wp_set_object_terms( $post_id, [], $taxonomy, false );
    }

}
add_action('save_post', 'loopis_save_taxonomy_field', 20);

?>