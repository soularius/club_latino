<?php

function disable_user_registration()
{
    return false;
}
add_filter('registration_enabled', 'disable_user_registration');

function custom_login_failed()
{
    $login_page  = home_url('/iniciar-sesion/'); // Cambia "/login/" por la URL de tu página personalizada de inicio de sesión
    wp_redirect($login_page . '?login=failed');
    exit;
}
add_action('wp_login_failed', 'custom_login_failed'); // Agregar la acción de WordPress para el inicio de sesión fallido

function custom_authenticate_username_password($user, $username, $password)
{
    $login_page  = home_url('/iniciar-sesion/'); // Cambia "/login/" por la URL de tu página personalizada de inicio de sesión
    if (is_a($user, 'WP_User')) {
        return $user;
    } else {
        wp_redirect($login_page . '?login=failed');
        exit;
    }
}
add_filter('authenticate', 'custom_authenticate_username_password', 20, 3); // Agregar el filtro de WordPress para la autenticación de usuario y contraseña

function custom_login_page()
{
    $login_page  = home_url('/iniciar-sesion/'); // Cambia "/login/" por la URL de tu página personalizada de inicio de sesión
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if ($page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init', 'custom_login_page'); // Agregar la acción de WordPress para cargar la página de inicio de sesión personalizada

function remove_admin_bar()
{
    if (!current_user_can('manage_options') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'remove_admin_bar');
