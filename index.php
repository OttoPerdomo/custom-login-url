<?php
/*
Plugin Name: Custom Login URL
Plugin URI: https://ansolidata.com/plugins
Description: A plugin to hide the wp-admin login URL and customize it.
Version: <?php echo get_github_latest_release_version('ottoperdomo', 'custom-login-url'); ?>
Author: Otto Perdomo
Author URI: https://github.com/OttoPerdomo/
*/

// Aquí agregaremos el código para nuestro plugin
// Registramos el menú del plugin
add_action('admin_menu', 'ad_manager_add_menu');

function ad_manager_add_menu() {
    add_menu_page(
        'Administrador de Publicidad',
        'Publicidad',
        'manage_options',
        'ad-manager',
        'ad_manager_menu_callback',
        'dashicons-megaphone'
    );
}

// Callback de la página de menú
function ad_manager_menu_callback() {
    ?>
    <div class="wrap">
        <h1>Administrador de Publicidad</h1>
        <p>Bienvenido al Administrador de Publicidad. Aquí puedes gestionar la publicidad de tu sitio.</p>
        <!-- Agrega aquí el contenido de tu página de administración de publicidad -->
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
