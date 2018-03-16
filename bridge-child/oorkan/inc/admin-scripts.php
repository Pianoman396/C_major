<?php
    security();
 ?>
<?php

/* Add admin js and css */

if (!function_exists('qode_admin_jquery')) {
    function qode_admin_jquery() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('style', QODE_ROOT.'/css/admin/admin-style.css', false, '1.0', 'screen');
        wp_enqueue_style('colorstyle', QODE_ROOT.'/css/admin/colorpicker.css', false, '1.0', 'screen');
        wp_register_script('colorpickerss', QODE_ROOT.'/js/admin/colorpicker.js', array('jquery'), '1.0.0', false );
        wp_enqueue_script('colorpickerss');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-accordion');
        wp_register_script('default', QODE_ROOT.'/js/admin/default.js', array('jquery'), '1.0.0', false );
        wp_enqueue_script('default');
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');
    }
}
add_action('admin_enqueue_scripts', 'qode_admin_jquery');



if(!function_exists('qode_writable_assets_folders_notice')) {
    /**
     * Function that prints notice that css and js folders aren't writable. Hooks to admin_notices action
     *
     * @version 0.1
     * @link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
     */
    function qode_writable_assets_folders_notice() {
        global $pagenow;

        $is_theme_options_page = isset($_GET['page']) && strstr($_GET['page'], 'qode_theme_menu');

        if($pagenow === 'admin.php' && $is_theme_options_page) {
            if(!qode_assets_folders_writable()) { ?>
                <div class="error">
                    <p><?php _e('Note that writing permissions aren\'t set for folders containing css and js files on your server.
                    We recommend setting writing permissions in order to optimize your site performance.
                    For further instructions, please refer to our <a target="_blank" href="http://demo.qodeinteractive.com/bridge-new-help/#!/getting_started">documentation</a>.', 'qode'); ?></p>
<!--                    <p>--><?php //_e('It seams that css and js files in theme folder aren\'t writable.', 'qode'); ?><!--</p>-->
                </div>
            <?php }
        }
    }
    if(!is_multisite()) {
        add_action('admin_notices', 'qode_writable_assets_folders_notice');
    }
}

 ?>