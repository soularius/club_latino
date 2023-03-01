<?php
function custom_register_user_post()
{
    $page_redirect  = home_url('/registrar/');

    // Verificar nonce
    if (!isset($_POST['custom_register_user_nonce']) || !wp_verify_nonce($_POST['custom_register_user_nonce'], 'custom_register_user')) {
        wp_redirect($page_redirect . '?registro=failed');
    }

    if (
        !isset($_POST['username']) ||
        !isset($_POST['email']) ||
        !isset($_POST['password']) ||
        !isset($_POST['cedula_de_ciudadania']) ||
        !isset($_POST['rol'])
    ) {
        wp_redirect($page_redirect . '?registro=failed');
    }

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $cedula_de_ciudadania = sanitize_text_field($_POST['cedula_de_ciudadania']);
    $email = sanitize_text_field($_POST['email']);
    $rol = sanitize_text_field($_POST['rol']);
    $first_name_lower = strtolower($first_name);
    $last_name_lower = strtolower($last_name);
    $comp_lower = $first_name_lower . $last_name_lower;
    $username =  str_replace(" ", "", $comp_lower);
    $password = wp_generate_password(10, true, true);
    $errors = new WP_Error();
    $error_arr = [];

    if (empty($username) || empty($password) || empty($email)) {
        $errors->add('field', 'Por favor, rellena todos los campos.');
    }

    if (username_exists($username)) {
        $username = $username . bin2hex(random_bytes(4));
    }

    if (email_exists($email)) {
        $errors->add('email', 'Este correo electrónico ya está registrado.');
        array_push($error_arr, 'Este correo electrónico ya está registrado.');
    }

    if (!is_email($email)) {
        $errors->add('email_invalid', 'El correo electrónico no es válido.');
        array_push($error_arr, 'El correo electrónico no es válido.');
    }

    if (strlen($password) < 6) {
        $errors->add('password', 'La contraseña debe tener al menos 6 caracteres.');
        array_push($error_arr, 'Error interno.');
    }

    $errors = apply_filters('registration_errors', $errors, $username, $email, $password);

    if ($errors->get_error_code()) {
        $encoded_data = urlencode(base64_encode(serialize($error_arr)));
        $page_redirect = add_query_arg(array('registro' =>  'failed_data', 'errors' => $encoded_data), $page_redirect);
        wp_redirect($page_redirect);
        exit;
    }

    $user_id = wp_insert_user(array(
        'user_login' => $username,
        'user_pass' => $password,
        'user_email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . " " . $last_name,
        'role' => $rol
    ));
    update_field('cedula_de_ciudadania', $cedula_de_ciudadania, "user_" . $user_id);

    wp_redirect($page_redirect . '?registro=success');
    exit;
}

add_action('admin_post_nopriv_custom_register_user_post', 'custom_register_user_post');
add_action('admin_post_custom_register_user_post', 'custom_register_user_post');


// Agregar un nonce en el formulario
function custom_register_user_form()
{
    wp_nonce_field('custom_register_user', 'custom_register_user_nonce');
}

add_action('custom_register_user_form', 'custom_register_user_form');
