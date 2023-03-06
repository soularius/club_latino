<?php

add_action('save_post', 'last_update_event');

function last_update_event($post_id)
{
    if (get_post_type($post_id) == "eventos" && get_field("enviar_email_a_invitados", $post_id)) {
        $eventos = get_all_activitys_($post_id);
        $lists = map_user_by_activity($eventos);
        $list_activity_user = map_unique_activity_user($lists);
        send_mail($post_id, $list_activity_user);
        update_field("enviar_email_a_invitados", false, $post_id);
    }
}

function get_all_activitys_($event_id)
{
    $list_activity = get_field("listado_de_actividades", $event_id);
    $tipo_de_organizador = get_field("tipo_de_organizador", $event_id);
    $organizacion = get_information_event_cpt($tipo_de_organizador['value'], $event_id);
    return list_activities_by_avents_($list_activity);
}

function list_activities_by_avents_($list_activity)
{
    $activities = [];
    foreach ($list_activity as $key => $activity) {
        $activity_temp = [];
        $activity_temp["id"] = $activity->ID;
        $activity_temp["title"] = $activity->post_title;
        $activity_temp["description"] = $activity->post_content;
        $activity_temp["guid"] = $activity->guid;
        $activity_temp["link"] = get_permalink($activity->ID);
        $activity_temp["hora_inicio"] = get_field("hora_inicio", $activity->ID);
        $activity_temp["hora_fin"] = get_field("hora_fin", $activity->ID);
        $activity_temp["actividades"] = get_information_activities_cpt($activity->ID);

        array_push($activities, $activity_temp);
    }
    return $activities;
}

function get_information_event_cpt($organization, $event_id)
{
    $org_event = [];
    switch ($organization) {
        case 'grupo':
            $temp = get_field($organization, $event_id);
            $org_event["id"] = $temp->term_id;
            $org_event["name"] = $temp->name;
            $org_event["type"] = $organization;
            break;
        case 'equipo':
            $temp = get_field($organization, $event_id);
            $org_event["id"] = $temp->term_id;
            $org_event["name"] = $temp->name;
            $org_event["type"] = $organization;
            break;
        case 'usuario':
            $temp = get_field($organization, $event_id);
            $org_event["id"] = $temp->ID;
            $org_event["name"] = $temp['user_firstname'] . " " . $temp['user_lastname'];
            $org_event["type"] = $organization;
            break;
        case 'club':
            $org_event["id"] = "N/A";
            $org_event["name"] = "Club";
            $org_event["type"] = $organization;
            break;
    }
    return $org_event;
}

function get_information_activities_cpt($activity_id)
{
    $ans = false;
    $select_activity = [];
    $select_activity["es_abierta_a_todo_publico"] = get_field("es_abierta_a_todo_publico", $activity_id);
    if (!$select_activity["es_abierta_a_todo_publico"]) {
        $select_activity["tiene_limite_de_usuarios"] = get_field("tiene_limite_de_usuarios", $activity_id);
        $select_activity["es_grupal"] = get_field("es_grupal", $activity_id);
        $select_activity["es_en_equipo"] = get_field("es_en_equipo", $activity_id);
        $select_activity["cantidad_maxima_de_usuarios"] = get_field("cantidad_maxima_de_usuarios", $activity_id);
        $select_activity["email_usuarios"] = get_activity_user_cpt($activity_id, $select_activity);
    }
    return $select_activity;
}

function get_activity_user_cpt($activity_id, $select_activity)
{
    $users = array();

    if ($select_activity["es_grupal"]) {
        $grupos = get_field("grupos_que_competiran", $activity_id);
        $users = term_data("grupo", $grupos);
    }

    if ($select_activity["es_en_equipo"]) {
        $equipos = get_field("equipos_que_competiran", $activity_id);
        $users = term_data("equipo", $equipos);
    }

    if (!$select_activity["es_grupal"] && !$select_activity["es_en_equipo"]) {
        $usuarios = get_field("lista_de_usuarios", $activity_id);
        $data_user = array_column($usuarios, "usuario");
        $u["users"] = array_column($data_user, "user_email");
        $u["type"] = null;
        array_push($users, $u);
    }

    return $users;
}

function term_data($taxonomy, $terms)
{
    $users = [];
    foreach ($terms as $key => $term) {
        $us = [];
        $u = get_all_user($taxonomy, $term);
        $us["type"] = $term;
        $us["users"] = $u;
        array_push($users, $us);
    }
    return $users;
}

function get_all_user($taxonomy, $term)
{
    $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $term
            )
        )
    );
    $usuarios = get_users($args);
    $data_user = array_column($usuarios, "data");
    $data_user_email = array_column($data_user, "user_email");
    return $data_user_email;
}

function map_user_by_activity($args)
{
    $list = [];
    foreach ($args as $item) {
        if (!$item["actividades"]["es_abierta_a_todo_publico"]) {
            $emails = $item["actividades"]["email_usuarios"];
            foreach ($emails as $type) {
                $type_ = $type["type"]->name;
                $tax_ = $type["type"]->taxonomy;
                if (isset($type["users"])) {
                    foreach ($type["users"] as $email) {
                        $e = array_column($list, "email");
                        $key = array_search($email, $e);
                        $l["email"] = $email;
                        $l["activity"] = [];
                        foreach ($args as $act) {
                            $d = [];
                            $d["title"] = $act["title"];
                            $d["description"] = $act["description"];
                            $d["hora_inicio"] = $act["hora_inicio"];
                            $d["hora_fin"] = $act["hora_fin"];
                            $d["link"] = home_url('/actividad/?id=' . $act["id"]);
                            $d["type"] = $type_;
                            $d["tax"] = $tax_;
                            array_push($l["activity"], $d);
                        }
                        if ($key !== false) {
                            $list[$key]["activity"] = array_merge($list[$key]["activity"], $l["activity"]);
                        } else {
                            array_push($list, $l);
                        }
                    }
                }
            }
        }
    }
    return $list;
}

function map_unique_activity_user($lists)
{
    foreach ($lists as $key => $list) {
        $lists[$key]["activity"] = array_map("unserialize", array_unique(array_map("serialize", $list["activity"])));
    }
    return $lists;
}

function send_mail($id_event, $lists)
{
    $title = get_the_title($id_event);
    $data = get_field("fecha_del_evento", $id_event);
    $newDate = date("d/m/Y", strtotime($data));
    $hora_inicio = get_field("hora_inicio", $id_event);
    $hora_fin = get_field("hora_fin", $id_event);
    $link = home_url('/evento/?id=' . $id_event);
    get_template_part('template-mail/mail', 'event-user');

    foreach ($lists as $list) {
        $arg = [];
        $email = $list["email"];
        $user = get_user_by('email', $email);
        $name = $user->data->display_name;
        $activity = $list["activity"];
        $html_table = table_html($activity);
        $arg["home_url"] = home_url();
        $arg["name"] = $name;
        $arg["title"] = $title;
        $arg["data"] = $newDate;
        $arg["hora_inicio"] = $hora_inicio;
        $arg["hora_fin"] = $hora_fin;
        $arg["link"] = $link;
        $arg["html_table"] = $html_table;
        $html_template = generate_template_mail($arg);
        send_mail_user_event($html_template, $email, $title);
    }
}

function table_html($activities)
{
    $html = "<table>";
    $html .= "<tr>";
    $html .= "<th>Actividad</th>";
    $html .= "<th>Tipo</th>";
    $html .= "<th>Nombre Tipo</th>";
    $html .= "<th>Descripción</th>";
    $html .= "<th>Hora Inicio</th>";
    $html .= "<th>Hora Fin</th>";
    $html .= "<th>Ver</th>";
    $html .= "</tr>";
    foreach ($activities as $key => $activity) {

        $html .= "<tr>";
        $html .= "<td>" . $activity["title"] . "</td>";
        $html .= "<td>" . ((is_null($activity["tax"])) ? "CLUB" : strtoupper($activity["tax"])) . "</td>";
        $html .= "<td>" . ((is_null($activity["type"])) ? "Para Todo el publico" : $activity["type"]) . "</td>";
        $html .= "<td>" . $activity["description"] . "</td>";
        $html .= "<td>" . $activity["hora_inicio"] . "</td>";
        $html .= "<td>" . $activity["hora_fin"] . "</td>";
        $html .= "<td><a href='" . $activity["link"] . "'>Ir al detalle</a></td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

function send_mail_user_event($mensaje, $email, $title)
{
    ob_start();
    $destinatario = get_field("email_contacto", 'option');
    $headers_mail[] = 'From: ' . get_bloginfo('name') . ' <' . $destinatario . '>';
    $headers_mail[] = 'Content-Type: text/html; charset=UTF-8';
    $asunto = "Invitación a evento: " . $title;
    $enviado = wp_mail($email, $asunto, $mensaje, $headers_mail);
    ob_end_clean();

    return $enviado;
}
