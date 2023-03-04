<?php
function custom_event_post()
{
    $page_redirect  = home_url('/perfil/');
    // Verificar nonce
    if (!isset($_POST['custom_event_post']) || !wp_verify_nonce($_POST['custom_event_post'], 'custom_event_post')) {
        wp_redirect($page_redirect . '?envio=failed');
    }

    if (
        !isset($_POST['event_name']) ||
        !isset($_POST['description']) ||
        !isset($_POST['team'])
    ) {
        wp_redirect($page_redirect . '?envio=failed');
    }
    $user_id = get_current_user_id();
    $user_data = get_userdata($user_id);
    $acf_data = search_data_acf_user($user_id);

    $email = $user_data->user_email;
    $args = array(
        'home_url' => home_url(),
        'name' => $user_data->display_name,
        'mail' => $email,
        'cedula_de_ciudadania' => $acf_data["cedula_de_ciudadania"],
        'telefono' => $acf_data["telefono"],
        'event_name' => sanitize_text_field($_POST['event_name']),
        'description' => sanitize_text_field($_POST['description']),
        'team' => sanitize_text_field($_POST['team'])
    );

    get_template_part('template-mail/mail', 'create-event');
    $html_template = generate_template_mail($args);
    $status = send_mail_new_event($html_template, $email);
    if ($status) {
        wp_redirect($page_redirect . '?envio=success');
    } else {
        wp_redirect($page_redirect . '?envio=failed');
    }
}

add_action('admin_post_nopriv_custom_event_post', 'custom_event_post');
add_action('admin_post_custom_event_post', 'custom_event_post');

function send_mail_new_event($mensaje, $email)
{
    ob_start();
    $headers_mail[] = 'From: ' . get_bloginfo('name') . ' <' . $email . '>';
    $headers_mail[] = 'Content-Type: text/html; charset=UTF-8';
    $destinatario = get_field("email_eventos", 'option');
    $asunto = "Solicitud de Creacion de Nuevo Evento";
    $enviado = wp_mail($destinatario, $asunto, $mensaje, $headers_mail);
    ob_end_clean();

    return $enviado;
}
