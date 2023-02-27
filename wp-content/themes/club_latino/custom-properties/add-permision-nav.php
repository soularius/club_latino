<?php

function add_menu_item_roles_field($item_id, $item, $depth, $args)
{
    $all_roles = get_editable_roles();
    $allowed_roles = array_keys($all_roles); // Roles permitidos
    $current_roles = get_post_meta($item_id, '_allowed_roles', true); // Roles permitidos actuales

    echo '<p class="description description-wide" style="display: flex;flex-wrap: wrap;">';
    echo '<label for="menu-item-allowed-roles-' . $item_id . '" style="width: 100%; margin-bottom: 15px; margin-top: 15px;"><strong>' . esc_html__('Roles permitidos', 'text-domain') . '</strong></label><br />';

    foreach ($allowed_roles as $role) {
        $checked = in_array($role, $current_roles) ? 'checked="checked"' : '';
        echo '<label style="width: 50%;"><input type="checkbox" id="menu-item-allowed-roles-' . $item_id . '-' . esc_attr($role) . '" name="menu-item-allowed-roles[' . $item_id . '][]" value="' . esc_attr($role) . '" ' . $checked . ' /> ' . esc_html__(ucfirst($role), 'text-domain') . '</label><br />';
    }

    echo '</p>';
}

add_action('wp_nav_menu_item_custom_fields', 'add_menu_item_roles_field', 10, 4);


function save_menu_item_roles_field($menu_id, $menu_item_db_id, $menu_item_args)
{
    if (isset($_POST['menu-item-allowed-roles'][$menu_item_db_id])) {
        $allowed_roles = $_POST['menu-item-allowed-roles'][$menu_item_db_id];
        update_post_meta($menu_item_db_id, '_allowed_roles', $allowed_roles);
    } else {
        delete_post_meta($menu_item_db_id, '_allowed_roles');
    }
}

add_action('wp_update_nav_menu_item', 'save_menu_item_roles_field', 10, 3);

function my_nav_menu_items_filter($items, $args)
{
    if ($args->menu == "menu-head") {
        $menu = get_term_by('slug', $args->menu, 'nav_menu');
        if ($menu) {
            $current_user = wp_get_current_user();
            $current_roles = $current_user->roles;
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            $menu_items_filtered = array_filter($menu_items, function ($item) use ($current_roles) {
                $allowed_roles = get_post_meta($item->ID, '_allowed_roles', true);

                if (empty($current_roles)) {
                    return in_array('all', $allowed_roles);
                } else {
                    return in_array('all', $allowed_roles) || count(array_intersect($current_roles, $allowed_roles)) > 0;
                }
            });

            foreach ($menu_items_filtered as $item) {
                $allowed_roles = get_post_meta($item->ID, '_allowed_roles', true);
                if (empty($allowed_roles)) {
                    update_post_meta($item->ID, '_allowed_roles', array('all')); // Si no hay roles permitidos guardados, se agrega "all" para permitir que lo vea cualquier usuario
                }
            }

            $output = ''; // Creamos una cadena de texto vacía
            $current_page_id = get_queried_object_id(); // Obtenemos el ID de la página actual

            foreach ($menu_items_filtered as $item) {
                $active_class = '';
                if ($item->object_id == $current_page_id) { // Si el ID del elemento del menú es igual al ID de la página actual, agregamos la clase "active"
                    $active_class = ' current_page_item active';
                }
                $output .= '<li id="menu-item-' . $item->ID . '" class="menu-item ' . implode(' ', $item->classes) . ' nav-item' . $active_class . '">'; // Concatenamos la apertura del <li> y sus clases, incluyendo la clase "active" si corresponde
                $output .= '<a itemprop="url" href="' . $item->url . '" class="nav-link"><span itemprop="name">' . $item->title . '</span></a>'; // Concatenamos el contenido del <a>
                $output .= '</li>'; // Concatenamos el cierre del <li>
            }
            return $output;
        }
    }

    return $items;
}

add_filter('wp_nav_menu_items', 'my_nav_menu_items_filter', 10, 2);

function redirect_permision()
{
    $user_id = get_current_user_id();
    if (!user_can($user_id, 'seguridad') && !user_can($user_id, 'manage_options') && !is_admin()) {
        ob_start();
        $url = home_url();
        wp_redirect($url);
        ob_end_flush();
        exit;
    }
}
