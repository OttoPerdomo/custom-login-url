<?php
/*
Plugin Name: Custom Login URL
Plugin URI: https://ansolidata.com/plugins
Description: A plugin to hide the wp-admin login URL and customize it.
Version: <?php echo get_github_latest_release_version('ottoperdomo', 'custom-login-url'); ?>
Author: Your Name
Author URI: https://github.com/OttoPerdomo/
*/

// Hook into the 'init' action
add_action('init', 'custom_login_url_init');

function custom_login_url_init() {
    // Retrieve the custom login URL from the options
    $custom_login_url = get_option('custom_login_url');

    // Check if the request is for the login page
    if (strpos($_SERVER['REQUEST_URI'], '/wp-login.php') !== false) {
        // Redirect to the custom login URL
        wp_redirect(home_url($custom_login_url));
        exit;
    }
}

// Add a filter to update the login URL in forms and redirects
add_filter('login_url', 'custom_login_url', 10, 2);

function custom_login_url($login_url, $redirect) {
    // Retrieve the custom login URL from the options
    $custom_login_url = get_option('custom_login_url');

    // Check if the request is for the admin area
    if (strpos($redirect, 'wp-admin') !== false) {
        // Modify the admin login URL to match the custom login URL
        $login_url = home_url($custom_login_url . '/wp-login.php');
    }
    return $login_url;
}

// Add an action to create an options page in the admin panel
add_action('admin_menu', 'custom_login_url_options_page');

function custom_login_url_options_page() {
    // Add a new submenu page under "Settings"
    add_options_page(
        'Custom Login URL',
        'Custom Login URL',
        'manage_options',
        'custom-login-url-settings',
        'custom_login_url_settings_page'
    );
}

// Register the custom login URL setting
add_action('admin_init', 'custom_login_url_register_settings');

function custom_login_url_register_settings() {
    register_setting('custom-login-url-group', 'custom_login_url');
}

// Create the settings page content
function custom_login_url_settings_page() {
    ?>
    <div class="wrap">
        <h1>Custom Login URL</h1>
        <form method="post" action="options.php">
            <?php settings_fields('custom-login-url-group'); ?>
            <?php do_settings_sections('custom-login-url-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Custom Login URL</th>
                    <td>
                        <input type="text" name="custom_login_url" value="<?php echo esc_attr(get_option('custom_login_url')); ?>" />
                        <p class="description">Enter the custom login URL segment (e.g., 'my-login') without slashes.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function get_github_latest_release_version($username, $repository) {
    $url = "https://api.github.com/repos/{$username}/{$repository}/releases/latest";
    $response = wp_remote_get($url);
    if (!is_wp_error($response) && $response['response']['code'] === 200) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        return $data['tag_name'];
    }
    return '';
}
