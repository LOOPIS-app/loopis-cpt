<?php
/**
 * Function for config
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

// Create a submenu link to the import information page
/*add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=faq',      // under CPT menu
        'FAQ Import Settings',         // page title
        'Import',                      // menu title
        'manage_options',              // capability
        'faq-import-settings',         // menu slug
        'loopis_faq_import_page'       // callback
    );
});*/

?>