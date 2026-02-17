<?php
/**
 * Function to create custom fields - one off for testing only adding a single custom field to forumz - remove this file
 */

// Prevent direct access
if (!defined('ABSPATH')) { 
    exit; 
}

add_action( 'add_meta_boxes', 'loopis_add_simple_meta_box' );

function loopis_add_simple_meta_box() {

    add_meta_box(
        'loopis_simple_box',
        'Testfält',
        'loopis_render_simple_meta_box',
        'forumz',      // ← ändra till din CPT
        'normal',
        'default'
    );
}

function loopis_render_simple_meta_box( $post ) {

    wp_nonce_field( 'loopis_simple_save', 'loopis_simple_nonce' );

    $value = get_post_meta( $post->ID, '_loopis_test_field', true );

    ?>
    <p>
        <label for="loopis_test_field">Testfält</label><br>
        <input type="text"
               id="loopis_test_field"
               name="loopis_test_field"
               value="<?php echo esc_attr( $value ); ?>"
               style="width:100%;">
    </p>
    <?php
}

add_action( 'save_post', 'loopis_save_simple_meta' );

function loopis_save_simple_meta( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;

    if (
        ! isset( $_POST['loopis_simple_nonce'] ) ||
        ! wp_verify_nonce( $_POST['loopis_simple_nonce'], 'loopis_simple_save' )
    ) return;

    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['loopis_test_field'] ) ) {
        update_post_meta(
            $post_id,
            '_loopis_test_field',
            sanitize_text_field( $_POST['loopis_test_field'] )
        );
    }
}

?>