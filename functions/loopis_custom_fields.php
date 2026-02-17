<?php
/**
 * Function to create custom fields
 *
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

// Run only in admin area -- "could be needed here, for meta boxes etc" ... but maybe not depending on who is going to use it...
/*if (!is_admin()) {
    return;
}*/

// Field groups with custom fields

function loopis_get_field_groups() {

    return [

        // field group: 'support_meta', custom fields: 'priority', 'deadline'

        'support_meta' => [
            'title' => 'Support Fields',
            'post_types' => ['supportz'],
            'fields' => [
                'priority' => [
                    'label' => 'Prioritet',
                    'type'  => 'number',
                ],/*
                'deadline' => [
                    'label' => 'Deadline',
                    'type'  => 'date',
                ],*/
            ],
        ],

        // field group: 'post_meta', custom fields: 'subtitle'

        'post_meta' => [
            'title' => 'Post Data Fields',
            'post_types' => ['post'],
            'fields' => [
                'location' => [
                    'label' => 'Hämtning',
                    'type'  => 'text',
                ],
                'custom_location' => [
                    'label' => 'Hämtning (custom)',
                    'type'  => 'text',
                ],
                'locker_number' => [
                    'label' => 'Skåpsnummer',
                    'type'  => 'number',
                ],
                'image_2' => [
                    'label' => 'Extra bild?',
                    'type'  => 'image',
                ],
                'participants' => [
                    'label' => 'Deltagare',
                    'type'  => 'user',
                ],
                'fetcher' => [
                    'label' => 'Mottagare',
                    'type'  => 'user',
                ],
                'queue' => [
                    'label' => 'Kö',
                    'type'  => 'user',
                ],
                'raffle_date' => [
                    'label' => 'Datum lottning',
                    'type'  => 'date', // or datetime-local
                ],
                'book_date' => [
                    'label' => 'Datum paxning',
                    'type'  => 'date',
                ],
                'locker_date' => [
                    'label' => 'Datum skåp',
                    'type'  => 'date',
                ],
                'fetch_date' => [
                    'label' => 'Datum hämtning',
                    'type'  => 'date',
                ],
                'forward_date' => [
                    'label' => 'Datum forward',
                    'type'  => 'date',
                ],
                'remove_date' => [
                    'label' => 'Datum borttagen',
                    'type'  => 'date',
                ],
                'pause_date' => [
                    'label' => 'Datum pausad',
                    'type'  => 'date',
                ],
                'archive_date' => [
                    'label' => 'Datum arkiverad',
                    'type'  => 'date',
                ],
                'extend_date' => [
                    'label' => 'Datum förnyad',
                    'type'  => 'date',
                ],
                'forward_post' => [
                    'label' => 'Forward post',
                    'type'  => 'number',
                ],
                'previous_post' => [
                    'label' => 'Previous post',
                    'type'  => 'number',
                ],
                'reminder_leave' => [
                    'label' => 'Påminnelse lämna',
                    'type'  => 'nummer',
                ],
                'reminder_fetch' => [
                    'label' => 'Påminnelse hämta',
                    'type'  => 'nummer',
                ],
            ],
        ],

        // Add more groups here ...

    ];
}

add_action('add_meta_boxes', 'loopis_register_meta_boxes');

function loopis_register_meta_boxes() {

    $groups = loopis_get_field_groups();

    foreach ( $groups as $group_key => $group ) {

        foreach ( $group['post_types'] as $post_type ) {

            add_meta_box(
                $group_key,
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

/*
function loopis_render_meta_box( $post, $box ) {

    $groups = loopis_get_field_groups();

    if ( empty( $box['args']['group_key'] ) || ! isset( $groups[ $box['args']['group_key'] ] ) ) {
        return;
    }

    $group = $groups[ $box['args']['group_key'] ];

    wp_nonce_field( 'loopis_save_fields', 'loopis_nonce' );

    foreach ( $group['fields'] as $key => $field ) {

        $value = get_post_meta( $post->ID, $key, true );

        ?>
        <p>
            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label><br>
            <input type="text" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
        </p>
        <?php
    }
}
*/

function loopis_render_meta_box( $post, $box ) {
    $groups = loopis_get_field_groups();

    if ( empty( $box['args']['group_key'] ) || ! isset( $groups[ $box['args']['group_key'] ] ) ) {
        return;
    }

    $group = $groups[ $box['args']['group_key'] ];

    wp_nonce_field( 'loopis_save_fields', 'loopis_nonce' );

    foreach ( $group['fields'] as $key => $field ) {
        // Se till att meta key är “ren”
        $meta_key = sanitize_key( $key );
        $value = get_post_meta( $post->ID, $meta_key, true );
        ?>
        <p>
            <label for="<?php echo esc_attr( $meta_key ); ?>"><?php echo esc_html( $field['label'] ); ?></label><br>
            <input type="text" id="<?php echo esc_attr( $meta_key ); ?>" name="<?php echo esc_attr( $meta_key ); ?>" value="<?php echo esc_attr( $value ); ?>">
        </p>
        <?php
    }
}

add_action('save_post', 'loopis_save_fields');

function loopis_save_fields( $post_id ) {

    error_log('loopis_save_fields triggered for post_id: ' . $post_id);

    // Autosave och revision-skydd
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    // Nonce
    if ( empty( $_POST['loopis_nonce'] ) || ! wp_verify_nonce( $_POST['loopis_nonce'], 'loopis_save_fields' ) ) {
        return;
    }

    // Rättigheter
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $groups = loopis_get_field_groups();

    $post_type = get_post_type( $post_id );

    foreach ( $groups as $group ) {
        // Hoppa om field group inte gäller denna post type
        if ( ! in_array( $post_type, $group['post_types'], true ) ) continue;

        foreach ( $group['fields'] as $key => $field ) {

            // Säker meta key
            $meta_key = sanitize_key( $key );

            // Om fält finns i POST
            if ( isset( $_POST[ $meta_key ] ) ) {
                $value = sanitize_text_field( $_POST[ $meta_key ] );
                update_post_meta( $post_id, $meta_key, $value );
            }
            // Om fält inte skickas → radera meta (för checkbox etc)
            else {
                delete_post_meta( $post_id, $meta_key );
            }
        }
    }
}

?>