<?php
/**
 * Function to create default terms
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

function loopis_add_default_terms() {

    // Function to add default tags at the right time (before register_activation_hook)
    loopis_register_tax();

    $defaults = [

        // FAQ-kategorier
        'faq_categoryz' => [
            [
                'name' => 'Instruktioner',
                'slug' => 'instruktioner',
            ],
            [
                'name' => 'Medlemskap',
                'slug' => 'medlemskap',
            ],
            [
                'name' => 'LOOPIS.app',
                'slug' => 'loopis-app',
            ],
            [
                'name' => 'LOOPIS skåp',
                'slug' => 'loopis-skap',
            ],
            [
                'name' => 'Om föreningen',
                'slug' => 'om-foreningen',
            ],
        ],

        // Forumkategorier
        'forum_categoryz' => [
            [
                'name' => '✨ Nyhet',
                'slug' => 'news',
            ],
            [
                'name' => '🌈 Aktuellt',
                'slug' => 'current',
            ],
            [
                'name' => '🗨 Feedback',
                'slug' => 'feedback',
            ],
            [
                'name' => '🙌 Hjälp önskas',
                'slug' => 'help',
            ],
            [
                'name' => '🔔 Startsidan',
                'slug' => 'start',
            ],
                        [
                'name' => '📌 Tips',
                'slug' => 'tips',
            ],
        ],

        // Supportkategorier
        'support_categoryz' => [
            [
                'name' => '⚠ Pågående',
                'slug' => 'active',
            ],
            [
                'name' => '✅ Besvarad',
                'slug' => 'inactive',
            ],
        ],

    ];

    foreach ( $defaults as $taxonomy => $terms ) {

        if ( ! taxonomy_exists( $taxonomy ) ) {
            continue;
        }

        foreach ( $terms as $term ) {

            if ( term_exists( $term['slug'], $taxonomy ) ) {
                continue;
            }

            wp_insert_term(
                $term['name'],
                $taxonomy,
                [
                    'slug' => $term['slug'],
                ]
            );
        }
    }
}

// add_action('init', 'add_default_terms'); // Denna kan du senare flytta till register_activation_hook

// register_activation_hook( __FILE__, 'add_default_terms' );

?>