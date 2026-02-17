<?php
/**
 * Function to create custom field groups and custom fields
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

// Load scripts and files for the datetime picker 

add_action( 'admin_enqueue_scripts', 'loopis_enqueue_datetime_picker' );
function loopis_enqueue_datetime_picker( $hook ) {

    // Only load on post edit screens
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }

    // Optional: only on certain CPTs
    // This loads the scripts only for editing the specified post types: post, FAQ, forum and support
    $screen = get_current_screen();
    if ( ! in_array( $screen->post_type, [ 'post', 'supportz' ], true ) ) {
        return;
    }

    // Flatpickr CSS
    wp_enqueue_style(
        'flatpickr',
        'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        [],
        '4.6.13'
    );

    // Custom CSS
    /*wp_enqueue_style(
        'custom-css',
         plugin_dir_url( __FILE__ ) . '../assets/css/loopis-datetime.css',       
        [],
        '1.0'
    );*/

    // Flatpickr JS
    wp_enqueue_script(
        'flatpickr',
        'https://cdn.jsdelivr.net/npm/flatpickr',
        [],
        '4.6.13',
        true
    );

    // Local JS init script for datetime
    wp_enqueue_script(
        'loopis-datetime',
        plugin_dir_url( __FILE__ ) . '../assets/js/loopis-datetime.js',
        [ 'flatpickr' ],
        '1.0',
        true
    );

}

// Field groups with custom fields

function loopis_get_field_groups() {

    return [

        // field group: 'support_meta', custom fields: 'priority', 'deadline'

        'support_meta' => [
            'title' => 'Support Fields',
            'post_types' => ['supportz'],
            'fields' => [
                'title' => [
                    'label' => 'Title',
                    'type'  => 'text',
                    'nullable' => true,
                ],
                'link' => [
                    'label' => 'Link',
                    'type'  => 'url',
                    'nullable' => true,
                ],
                'status' => [
                    'label' => 'Status',
                    'type'  => 'taxonomy',
                    'nullable' => true,
                ],
                'invited' => [
                    'label' => 'Invited',
                    'type'  => 'user_ajax',
                    'multiple' => true, // needed for multiple users
                    'nullable' => true,
                ],

            ],
        ],

        // field group: 'post_meta', custom fields: 'subtitle'
        // översätt label, engelska
        // allow null, påslaget för alla fält

        'post_meta' => [
            'title' => 'Post Data Fields',
            'post_types' => ['post'],
            'fields' => [
                'location' => [
                    'label' => 'Hämtning',
                    'type'  => 'text',
                    'nullable' => true,
                ],
                'custom_location' => [
                    'label' => 'Hämtning (custom)',
                    'type'  => 'text',
                    'nullable' => true,
                ],
                'locker_number' => [
                    'label' => 'Skåpsnummer',
                    'type'  => 'number',
                    'nullable' => true,
                ],
                'image_2' => [
                    'label' => 'Extra bild?',
                    'type'  => 'image', // kolla databas test.loopis.app, kan räcka med ID (+ ev utöka med tredje bild)
                    'nullable' => true,
                ],
                'participants' => [ // flera deltagare, lägg till så att fler användare kan läggas till
                    'label' => 'Deltagare',
                    'type'  => 'user_ajax',
                    'multiple' => true, // needed for multiple users
                    'nullable' => true,
                ],
                'fetcher' => [
                    'label' => 'Mottagare',
                    'type'  => 'user_ajax',
                    'multiple' => false, // needed for single user
                    'nullable' => true,
                ],
                'queue' => [
                    'label' => 'Kö',
                    'type'  => 'user_ajax',
                    'multiple' => true, // needed for multiple users
                    'nullable' => true,
                ],
                'raffle_date' => [
                    'label' => 'Datum lottning',
                    'type'  => 'datetime', // datetime is a custom created format
                    'nullable' => true,
                ],
                'book_date' => [
                    'label' => 'Datum paxning',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'locker_date' => [
                    'label' => 'Datum skåp',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'fetch_date' => [
                    'label' => 'Datum hämtning',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'forward_date' => [
                    'label' => 'Datum forward',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'remove_date' => [
                    'label' => 'Datum borttagen',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'pause_date' => [
                    'label' => 'Datum pausad',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'archive_date' => [
                    'label' => 'Datum arkiverad',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'extend_date' => [
                    'label' => 'Datum förnyad',
                    'type'  => 'datetime',
                    'nullable' => true,
                ],
                'forward_post' => [
                    'label' => 'Forward post',
                    'type'  => 'number',
                    'nullable' => true,
                ],
                'previous_post' => [
                    'label' => 'Previous post',
                    'type'  => 'number',
                    'nullable' => true,
                ],
                'reminder_leave' => [
                    'label' => 'Påminnelse lämna',
                    'type'  => 'number',
                    'nullable' => true,
                ],
                'reminder_fetch' => [
                    'label' => 'Påminnelse hämta',
                    'type'  => 'number',
                    'nullable' => true,
                ],
            ],
        ],

        // Add more groups here ...

    ];
}

// Add meta box function

add_action( 'add_meta_boxes', 'loopis_register_field_groups' );

function loopis_register_field_groups() {

    foreach ( loopis_get_field_groups() as $group_key => $group ) {

        foreach ( $group['post_types'] as $post_type ) {

            add_meta_box(
                'loopis_' . $group_key,
                $group['title'],
                'loopis_render_meta_box',
                $post_type,
                'normal',
                'default',
                [
                    'group_key' => $group_key,
                ]
            );
        }
    }
}

// Meta box render function

function loopis_render_meta_box( $post, $box ) {

    $groups = loopis_get_field_groups();
    $group  = $groups[ $box['args']['group_key'] ];

    wp_nonce_field( 'loopis_save_fields', 'loopis_fields_nonce' );

    echo '<table class="form-table">';

    foreach ( $group['fields'] as $key => $field ) {

        $value = get_post_meta( $post->ID, $key, true );

        echo '<tr>';
        echo '<th><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th>';
        echo '<td>';

        switch ( $field['type'] ) {

            case 'text': // används eventuellt inte 
                echo '<input type="url" class="regular-text" 
                name="' . esc_attr( $key ) . '" 
                value="' . esc_attr( $value ) . '">';
                break;
            
            case 'number': // kontrollera att "text" är rätt, och inte t.ex. "input type=number" = ska vara, kolla upp rimligt "class name" 
                echo '<input type="number" class="regular-number" 
                 name="' . esc_attr( $key ) . '" 
                 value="' . esc_attr( $value ) . '">';
                break;

            case 'user_ajax':

                // Avgör om fältet ska vara multi eller single
                $multiple = ! empty( $field['multiple'] );
                $mode     = $multiple ? 'multi' : 'single';

                // Hämta värdet från post_meta
                $value = get_post_meta( $post->ID, $key, true );

                // Säkerställ att $user_ids alltid är array
                if ( $multiple ) {
                    $user_ids = is_array( $value ) ? $value : [];
                } else {
                    $user_ids = [];
                    if ( is_array( $value ) && ! empty( $value[0] ) ) {
                        $user_ids[] = intval( $value[0] );
                    } elseif ( $value ) {
                        $user_ids[] = intval( $value );
                    }
                }

                // Öppna wrapper DIV med data-mode korrekt
                echo '<div class="loopis-user-ajax" data-key="' . esc_attr( $key ) . '" data-mode="' . esc_attr( $mode ) . '">';

                // Container för redan valda användare
                echo '<div class="loopis-user-selected">';

                foreach ( $user_ids as $uid ) {
                    $u = get_userdata( $uid );
                    if ( $u ) {
                        echo '<span class="loopis-user-chip" data-id="' . esc_attr( $uid ) . '">';
                        echo esc_html( $u->display_name );
                        echo '<button type="button">×</button>';

                        // Hidden input: array för multi, single för single
                        if ( $multiple ) {
                            echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $uid ) . '">';
                        } else {
                            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $uid ) . '">';
                        }

                        echo '</span>';
                    }
                }

                echo '</div>'; // slut på .loopis-user-selected

                // Sökfält och resultatcontainer
                echo '<input type="text" class="loopis-user-search" placeholder="Search users..." autocomplete="off">';
                echo '<div class="loopis-user-results"></div>';

                echo '</div>'; // slut på wrapper
                break;

            case 'url':
                echo '<input type="url" class="regular-text" 
                 name="' . esc_attr( $key ) . '" 
                 value="' . esc_attr( $value ) . '">';
                break;

            case 'taxonomy':
                $taxonomy = $field['taxonomy'];

                if ( ! taxonomy_exists( $taxonomy ) ) {
                    echo '<p>Taxonomin finns inte.</p>';
                    break;
                }

                $terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ]);

                $selected_terms = wp_get_object_terms( $post->ID, $taxonomy, ['fields' => 'ids'] );
                $selected = $selected_terms[0] ?? '';

                echo '<select name="' . esc_attr( $key ) . '">';

                echo '<option value="">— Välj —</option>';

                foreach ( $terms as $term ) {
                    echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $selected, $term->term_id, false ) . '>';
                    echo esc_html( $term->name );
                    echo '</option>';
                }

                echo '</select>';

                break;

            // pattern="\d{4}-\d{2}-\d{2} \d{2}:\d{2}"
            // placeholder="YYYY-MM-DD HH:MM"
            //  title="Format: YYYY-MM-DD HH:MM" - oklart om pattern behövs ... när JS inte fungerar, fungerar inte gutenberg öht
            //  inputmode="none"
            //  autocomplete="off"

            case 'datetime': // inputmode, autocomplete = get rid of chrome auto insertion of html5 datetime picker
                echo '<input type="text"
                name="' . esc_attr( $key ) . '"
                value="' . esc_attr( $value ) . '"
                class="loopis-datetime"
                pattern="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}"
                placeholder="YYYY-MM-DD HH:MM:SS"
                title="Format: YYYY-MM-DD HH:MM:SS"
                >';
                break;

            case 'image':
                echo '<input type="text" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text">';
                echo '<p class="description">Ange bildens media-ID</p>';
                break;
        }

        echo '</td></tr>';
    }

    echo '</table>';
}

// Save function

add_action( 'save_post', 'loopis_save_fields' );

function loopis_save_fields( $post_id ) {

    if ( ! isset( $_POST['loopis_fields_nonce'] ) ) return;

    if ( ! wp_verify_nonce( $_POST['loopis_fields_nonce'], 'loopis_save_fields' ) ) return;

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    foreach ( loopis_get_field_groups() as $group ) {

        foreach ( $group['fields'] as $key => $field ) {

            // Check for nullable fields
            $field['nullable'] = $field['nullable'] ?? true;

            if ( ! isset( $_POST[ $key ] ) ) {

            if ( ! empty( $field['nullable'] ) ) {
                delete_post_meta( $post_id, $key );
            }

            continue;
            }

            $value = $_POST[ $key ];

            switch ( $field['type'] ) {
                case 'number':
                    $value = floatval( $value );
                    break;

                case 'url':
                    $value = esc_url_raw( $value );
                    break;
                
                case 'user_ajax':

                    if ( ! empty( $field['multiple'] ) ) {
                        $val = isset($_POST[$key]) ? array_map('intval', (array) $_POST[$key]) : [];
                        update_post_meta($post_id, $key, $val);
                    } else {
                        $val = isset($_POST[$key]) ? intval($_POST[$key]) : '';
                        update_post_meta($post_id, $key, $val);
                    }
                    break;

                case 'taxonomy':
                    $taxonomy = $field['taxonomy'];

                    $term_id = intval( $_POST[ $key ] );

                    if ( $term_id ) {
                        wp_set_object_terms( $post_id, [ $term_id ], $taxonomy, false );
                    } else {
                        wp_set_object_terms( $post_id, [], $taxonomy, false );
                    }
                    break;
                
                default:
                    $value = sanitize_text_field( $value );
            }

            update_post_meta( $post_id, $key, $value );
        }
    }
}