<?php
function custom_binnacle_post()
{
    $page_redirect  = home_url('/registro-de-bitacora/');

    // Verificar nonce
    if (!isset($_POST['custom_binnacle_nonce']) || !wp_verify_nonce($_POST['custom_binnacle_nonce'], 'custom_binnacle')) {
        wp_redirect($page_redirect . '?cedula_found=failed');
    }

    $all_data = array();
    $user_binnacle = sanitize_text_field($_POST['user_binnacle']);
    $is_inv = sanitize_text_field($_POST['is_guest']);
    $user_inv = sanitize_text_field($_POST['invitado']);
    $observacion = sanitize_text_field($_POST['observacion']);

    // buscar usuario con el acf cedula_de_ciudadania igual a $user_binnacle
    $user_query = new WP_User_Query(array(
        'meta_key' => 'cedula_de_ciudadania',
        'meta_value' => $user_binnacle
    ));

    if (empty($user_query->get_results())) {
        wp_redirect($page_redirect . '?cedula_found=failed');
        exit;
    } else {
        $user_bitacora = $user_query->get_results()[0];
        $user_bitacora = $user_bitacora->data;

        $user_id = $user_bitacora->ID;
        $basic = search_data_basic_user($user_id);
        $group = search_data_group_user($user_id);
        $team = search_data_team_user($user_id);
        $acf_data = search_data_acf_user($user_id);
        $events = search_events_by_user($user_id, $group, $team);


        $current_date = strtotime(date('Y-m-d'));
        $fecha_vencimiento = strtotime(str_replace('/', '-', $acf_data["fecha_vencimiento"]));

        $vencimiento = false;
        if ($fecha_vencimiento < $current_date) {
            $vencimiento = true;
        }

        $all_data["basic"] = $basic;
        $all_data["group"] = $group;
        $all_data["team"] = $team;
        $all_data["other_data"] = $acf_data;
        $all_data["events"] = $events;

        $ans = false;
        if (array_search($basic["role"], ["socio", "particular", "visitante"]) !== false) {
            $ans = register_binnade($user_id, null, $observacion);
        } elseif (array_search($basic["role"], ["invitado"]) !== false && $is_inv && $user_inv) {
            $ans = register_binnade($user_id, $user_inv, $observacion);
        }
        if ($ans) {
            // Codificar los datos en la URL
            $encoded_data = urlencode(base64_encode(serialize($all_data)));

            // Redirigir de vuelta a la página que hizo la solicitud con los datos en la URL
            $page_redirect = add_query_arg(array('cedula_found' => $vencimiento ? 'found_defeated' : 'found', 'all_data' => $encoded_data), $page_redirect);
            wp_redirect($page_redirect);
            exit;
        } else {
            wp_redirect($page_redirect . '?cedula_found=found_fail');
            exit;
        }
    }
}

add_action('admin_post_nopriv_custom_binnacle_post', 'custom_binnacle_post');
add_action('admin_post_custom_binnacle_post', 'custom_binnacle_post');

function register_binnade($user_id, $user_inv = null, $observacion)
{
    $current_date = date('d/m/Y');

    // Verifica si existe un post con el título especificado
    $post_existente = get_page_by_title($current_date, OBJECT, 'bitacora');

    // Si el post no existe, crea uno nuevo
    if (empty($post_existente)) {
        $nuevo_post = array(
            'post_title' => $current_date,
            'post_status' => 'publish',
            'post_type' => 'bitacora'
        );
        $post_id = wp_insert_post($nuevo_post);
    } else {
        $post_id = $post_existente->ID;
    }
    $ans = register_user_binnade($user_id, $post_id, $user_inv, $observacion);
    return $ans;
}

function register_user_binnade($user_id, $post_id, $user_inv, $observacion)
{
    $listado_de_usuarios = get_field('listado_de_usuarios', $post_id);
    $hour_min = date('H:i');

    $new_user = array(
        'acf_fc_layout' => 'usuario',
        'es_invitado' => false,
        'usuario' => $user_id,
        'hora_de_ingreso' => $hour_min,
        'observacion' => $observacion,
    );

    if (!is_null($user_inv)) {
        $new_user['es_invitado'] = true;
        $new_user['usuario_que_lo_invita'] = $user_inv;
    }

    if (empty($listado_de_usuarios)) {
        $listado_de_usuarios = array();
        array_push($listado_de_usuarios, $new_user);
        update_field('listado_de_usuarios', $listado_de_usuarios, $post_id);
        return true;
    } else {
        array_push($listado_de_usuarios, $new_user);
        update_field('listado_de_usuarios', $listado_de_usuarios, $post_id);
        return true;
    }

    return false;
}

// Agregar un nonce en el formulario
function custom_binnacle_form()
{
    wp_nonce_field('custom_binnacle', 'custom_binnacle_nonce');
}

add_action('custom_binnacle_form', 'custom_binnacle_form');

function search_events_by_user($user_id, $group, $team)
{
    $current_date = date('Ymd');
    $args = array(
        'post_type' => 'eventos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'fecha_del_evento',
                'value' => $current_date,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);

    $events = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $event = array();
            $query->the_post();
            $event_id = get_the_id();
            $list_activity = get_field("listado_de_actividades", $event_id);
            $format_activities = list_activities($list_activity, $user_id, $group, $team);

            if (!empty($format_activities)) {
                $event_name = get_the_title();
                $tipo_de_organizador = get_field("tipo_de_organizador", $event_id);
                $organizacion = get_information_event($tipo_de_organizador['value'], $event_id);

                $event["id"] = $event_id;
                $event["name"] = $event_name;
                $event["organization"] = $organizacion;
                $event["activity"] = $format_activities;
                array_push($events, $event);
            }
        }
    }
    wp_reset_postdata();

    return $events;
}

function list_activities($list_activity, $user_id, $group, $team)
{
    $activities_subscribe = [];
    $activities_recomend = [];
    foreach ($list_activity as $key => $activity) {
        $es_abierta_a_todo_publico = get_field("es_abierta_a_todo_publico", $activity->ID);
        $pertence_actividad = get_information_activities($activity->ID, $user_id, $group, $team, $es_abierta_a_todo_publico);

        $activity_temp = [];
        $activity_temp["id"] = $activity->ID;
        $activity_temp["title"] = $activity->post_title;
        $activity_temp["description"] = $activity->post_content;
        $activity_temp["guid"] = $activity->guid;
        $activity_temp["link"] = get_permalink($activity->ID);
        $activity_temp["hora_inicio"] = get_field("hora_inicio", $activity->ID);
        $activity_temp["hora_fin"] = get_field("hora_fin", $activity->ID);

        if ($pertence_actividad !== false) {
            array_push($activities_subscribe, $activity_temp);
        }
        if ($es_abierta_a_todo_publico) {
            array_push($activities_recomend, $activity_temp);
        }
    }
    if (!empty($activities_subscribe) || !empty($activities_recomend)) {
        return array(
            "activities_subscribe" => $activities_subscribe,
            "activities_recomend" => $activities_recomend
        );
    } else {
        return array();
    }
}

function get_information_activities($activity_id, $user_id, $group, $team, $es_abierta_a_todo_publico)
{
    $ans = false;
    $select_activity = [];
    if (!$es_abierta_a_todo_publico) {
        $select_activity["es_abierta_a_todo_publico"] = $es_abierta_a_todo_publico;
        $select_activity["tiene_limite_de_usuarios"] = get_field("tiene_limite_de_usuarios", $activity_id);
        $select_activity["es_grupal"] = get_field("es_grupal", $activity_id);
        $select_activity["es_en_equipo"] = get_field("es_en_equipo", $activity_id);
        $select_activity["cantidad_maxima_de_usuarios"] = get_field("cantidad_maxima_de_usuarios", $activity_id);
        $ans = get_activity_user($activity_id, $user_id, $group, $team, $select_activity);
    }
    return $ans;
}

function get_activity_user($activity_id, $user_id, $group, $team, $select_activity)
{
    $ans = false;

    if ($select_activity["es_grupal"]) {
        $grupos = get_field("grupos_que_competiran", $activity_id);
        $group_id = $group["group_id"];
        $term_id = array_column($grupos, "term_id");
        $ans = array_search($group_id, $term_id);
    }

    if ($select_activity["es_en_equipo"]) {
        $equipos = get_field("equipos_que_competiran", $activity_id);
        $team_id = $team["team_id"];
        $term_id = array_column($equipos, "term_id");
        $ans = array_search($team_id, $term_id);
    }

    if (!$select_activity["es_grupal"] && !$select_activity["es_en_equipo"]) {
        $usuarios = get_field("lista_de_usuarios", $activity_id);
        $usuarios_id = array_column(array_column($usuarios, 'usuario'), 'ID');
        $ans = array_search($user_id, $usuarios_id);
    }

    return $ans;
}

function get_information_event($organization, $event_id)
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

function search_data_basic_user($user_id)
{
    $basic_data = [];
    $user_data = get_userdata($user_id);

    $basic_data['user_id'] = $user_id;
    $basic_data['first_name'] = $user_data->first_name;
    $basic_data['last_name'] = $user_data->last_name;
    $basic_data['role'] = $user_data->roles[0];

    return $basic_data;
}

function search_data_group_user($user_id)
{
    $group_data = [];
    $taxonomy = 'grupo'; // nombre de la taxonomía que quieres obtener

    $user_terms = wp_get_object_terms($user_id, $taxonomy);
    $user_term = $user_terms[0];

    $group_data["group_id"] = $user_term->term_id;
    $group_data["group_name"] = $user_term->name;
    return $group_data;
}

function search_data_team_user($user_id)
{
    $team_data = [];
    $taxonomy = 'equipo'; // nombre de la taxonomía que quieres obtener

    $user_terms = wp_get_object_terms($user_id, $taxonomy);

    foreach ($user_terms as $key => $term) {
        $team = [];
        $team["team_id"] = $term->term_id; // ID del término
        $team["team_name"] = $term->name; // nombre del término

        array_push($team_data, $team);
    }
    return $team_data;
}

function search_data_acf_user($user_id)
{
    $acf_data = [];
    $post_id = "user_" . $user_id;

    $acf_data["cedula_de_ciudadania"] = get_field('cedula_de_ciudadania', $post_id);
    $acf_data["fecha_inicio"] = get_field('fecha_inicio', $post_id);
    $acf_data["fecha_vencimiento"] = get_field('fecha_vencimiento', $post_id);
    $acf_data["costo_mensual"] = get_field('costo_mensual', $post_id);

    return $acf_data;
}
