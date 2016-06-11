<?php
/**
 * Plugin Name: Stage Mirror
 * Plugin URI: http://github.com/hgcummings/stage-mirror
 * Description: For creating a staging mirror of a live wordpress site
 * Version: 0.1
 * Author: hgcummings
 * Author URI: http://hgc.io
 * License: MIT
 */

function is_staging() {
	$options = get_option('stage_mirror');
	return (DB_NAME == $options['db_name']);
}

if (is_staging()) {
    function override_admin_color() {
        return 'sunrise';
    }

    add_filter('get_user_option_admin_color', 'override_admin_color');

    remove_all_actions('admin_color_scheme_picker');
}

add_action('admin_init', 'stage_mirror_options_init' );
add_action('admin_menu', 'stage_mirror_options_add_page');

function stage_mirror_options_init(){
    register_setting('stage_mirror_options', 'stage_mirror');
}

function stage_mirror_options_add_page() {
	add_options_page('Stage Mirror', 'Stage Mirror', 'manage_options', 'stage_mirror_options', 'stage_mirror_options_do_page');
}

add_action('admin_post_stage_mirror_do_mirror','do_stage_mirror');

function do_stage_mirror() {
    check_admin_referer('stage_options_verify');

    echo "Not yet implemented";

    exit;
}

function stage_mirror_options_do_page() {
    ?>
    <div class="wrap">
        <h2>Stage Mirror options</h2>
        <form method="post" action="options.php">
            <?php settings_fields('stage_mirror_options'); ?>
            <?php $options = get_option('stage_mirror'); ?>
            <table class="form-table">
                <tr valign="top"><th scope="row">Stage database name</th>
                    <td><input type="text" name="stage_mirror[db_name]" value="<?php echo $options['db_name']; ?>" /></td>
                </tr>
                <tr valign="top"><th scope="row">Stage directory path</th>
                    <td><input type="text" name="stage_mirror[dir_path]" value="<?php echo $options['dir_path']; ?>" /></td>
                </tr>
            </table>
            <p class="submit">
            	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
        <?php if (!is_staging()) { ?>
        <form method="post" action="admin-post.php">
            <input type="hidden" name="action" value="stage_mirror_do_mirror" />
            <?php wp_nonce_field( 'stage_options_verify' ); ?>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Do mirror now!') ?>" />
            </p>
        </form>
        <?php } ?>
    </div>
    <?php  
}