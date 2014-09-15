<?php

// add profile image option for contributors roles and higher
function ct_ignite_user_profile_image_setting( $user ) { ?>

    <?php
    $user_id = get_current_user_id();

    // only added for contributors and above
    if ( ! current_user_can( 'edit_posts', $user_id ) ) return false;
    ?>

    <table id="profile-image-table" class="form-table">

        <tr>
            <th><label for="user_profile_image"><?php _e( 'Profile image', 'ignite' ); ?></label></th>
            <td>
                <!-- Outputs the image after save -->
                <img id="image-preview" src="<?php echo esc_url( get_the_author_meta( 'user_profile_image', $user->ID ) ); ?>" style="width:100px;"><br />
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="user_profile_image" id="user_profile_image" value="<?php echo esc_url( get_the_author_meta( 'user_profile_image', $user->ID ) ); ?>" class="regular-text" />
                <!-- Outputs the save button -->
                <input type='button' id="user-profile-upload" class="button-primary" value="<?php _e( 'Upload Image', 'ignite' ); ?>"/><br />
                <span class="description"><?php _e( 'Upload an image here to use instead of your Gravatar. Perfectly square images will not be cropped.', 'ignite' ); ?></span>
            </td>
        </tr>

    </table><!-- end form-table -->
<?php } // additional_user_fields

add_action( 'show_user_profile', 'ct_ignite_user_profile_image_setting' );
add_action( 'edit_user_profile', 'ct_ignite_user_profile_image_setting' );

/**
 * Saves additional user fields to the database
 */
function ct_ignite_save_user_profile_image( $user_id ) {

    // only saves if the current user can edit user profiles
    if ( ! current_user_can( 'edit_user', $user_id ) )
        return false;

    update_user_meta( $user_id, 'user_profile_image', esc_url_raw( $_POST['user_profile_image'] ) );
}

add_action( 'personal_options_update', 'ct_ignite_save_user_profile_image' );
add_action( 'edit_user_profile_update', 'ct_ignite_save_user_profile_image' );
