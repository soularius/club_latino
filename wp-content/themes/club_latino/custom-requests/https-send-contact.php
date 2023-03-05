<?php
function custom_contact_post()
{
    $page_redirect  = home_url('/contactenos/');
    // Verificar nonce
    if (!isset($_POST['custom_contact_post']) || !wp_verify_nonce($_POST['custom_contact_post'], 'custom_contact_post')) {
        wp_redirect($page_redirect . '?envio=failed');
    }

    if (
        !isset($_POST['nombre']) ||
        !isset($_POST['email']) ||
        !isset($_POST['telefono']) ||
        !isset($_POST['comentario'])
    ) {
        wp_redirect($page_redirect . '?envio=failed');
    }
    $email = sanitize_text_field($_POST['email']);
    $args = array(
        'home_url' => home_url(),
        'nombre' => sanitize_text_field($_POST['nombre']),
        'email' => $email,
        'telefono' => sanitize_text_field($_POST['telefono']),
        'comentario' => sanitize_text_field($_POST['comentario'])
    );

    get_template_part('template-mail/mail', 'menssage-contact');
    $html_template = generate_template_mail($args);
    $status = send_mail_new_contact($html_template, $email);
    if ($status) {
        wp_redirect($page_redirect . '?envio=success');
    } else {
        wp_redirect($page_redirect . '?envio=failed');
    }
}

add_action('admin_post_nopriv_custom_contact_post', 'custom_contact_post');
add_action('admin_post_custom_contact_post', 'custom_contact_post');

function send_mail_new_contact($mensaje, $email)
{
    ob_start();
    $headers_mail[] = 'From: ' . get_bloginfo('name') . ' <' . $email . '>';
    $headers_mail[] = 'Content-Type: text/html; charset=UTF-8';
    $destinatario = get_field("email_contacto", 'option');
    $asunto = "Mensaje de formulario de contacto";
    $enviado = wp_mail($destinatario, $asunto, $mensaje, $headers_mail);
    ob_end_clean();

    return $enviado;
}
