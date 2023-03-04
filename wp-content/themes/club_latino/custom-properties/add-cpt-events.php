<?php
function registrar_eventos_post_type()
{

  // Etiquetas para el Custom Post Type
  $labels = array(
    'name' => 'Eventos',
    'singular_name' => 'Evento',
    'menu_name' => 'Eventos',
    'name_admin_bar' => 'Evento',
    'add_new' => 'Agregar Nuevo',
    'add_new_item' => 'Agregar Nuevo Evento',
    'new_item' => 'Nuevo Evento',
    'edit_item' => 'Editar Evento',
    'view_item' => 'Ver Evento',
    'all_items' => 'Todos los Eventos',
    'search_items' => 'Buscar Eventos',
    'parent_item_colon' => 'Evento Padre:',
    'not_found' => 'No se encontraron eventos.',
    'not_found_in_trash' => 'No se encontraron eventos en la papelera.'
  );

  // Otras opciones para el Custom Post Type
  $args = array(
    'labels'                => $labels,
    'public'                => true,
    'publicly_queryable'    => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'query_var'             => true,
    'rewrite'               => array('slug' => 'eventos'),
    'capability_type'       => 'post',
    'has_archive'           => true,
    'hierarchical'          => false,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-calendar',
    'supports'              => array('title', 'thumbnail', 'comments')
  );

  // Registrar el Custom Post Type
  register_post_type('eventos', $args);
}
add_action('init', 'registrar_eventos_post_type');

function ocultar_agregar_nuevo_eventos()
{
  remove_submenu_page('edit.php?post_type=eventos', 'post-new.php?post_type=eventos');
}
add_action('admin_menu', 'ocultar_agregar_nuevo_eventos');

function redirigir_cpt_eventos()
{
  if (is_singular('eventos') || is_post_type_archive('eventos')) {
    wp_redirect(home_url(), 301);
    exit;
  }
}
add_action('template_redirect', 'redirigir_cpt_eventos');

// Register custom post type actividades as child of eventos
function actividades_custom_post_type()
{
  $labels = array(
    'name'                  => 'Actividades',
    'singular_name'         => 'Actividad',
    'name_admin_bar'        => 'Actividades',
    'menu_name'             => 'Actividades',
    'add_new'               => 'Agregar Nueva',
    'add_new_item'          => 'Agregar Nueva Actividad',
    'edit_item'             => 'Editar Actividad',
    'new_item'              => 'Nueva Actividad',
    'view_item'             => 'Ver Actividad',
    'search_items'          => 'Buscar Actividades',
    'not_found'             => 'No se encontraron actividades',
    'not_found_in_trash'    => 'No se encontraron actividades en la papelera'
  );

  $args = array(
    'labels'                => $labels,
    'public'                => true,
    'has_archive'           => true,
    'menu_icon'             => 'dashicons-calendar-alt',
    'supports'              => array('title', 'editor', 'thumbnail'),
    'rewrite'               => array('slug' => 'actividades'),
    'capability_type'       => 'post',
    'hierarchical'          => true,
    'menu_position'         => 5,
    'show_in_rest'          => true,
    'query_var'             => true,
    'publicly_queryable'    => true,
    'show_ui'               => true,
    'show_in_menu'          => 'edit.php?post_type=eventos'
  );
  register_post_type('actividades', $args);
}
add_action('init', 'actividades_custom_post_type');


function crear_taxonomia_fechas()
{
  $labels = array(
    'name' => _x('Fechas', 'taxonomy general name'),
    'singular_name' => _x('Fecha', 'taxonomy singular name'),
    'search_items' => __('Buscar Fechas'),
    'all_items' => __('Todas las Fechas'),
    'parent_item' => __('Fecha Padre'),
    'parent_item_colon' => __('Fecha Padre:'),
    'edit_item' => __('Editar Fecha'),
    'update_item' => __('Actualizar Fecha'),
    'add_new_item' => __('Agregar Nueva Fecha'),
    'new_item_name' => __('Nombre Nueva Fecha'),
    'menu_name' => __('Fechas'),
  );

  $args = array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'fechas'),
  );

  register_taxonomy('fechas', 'actividades', $args);
}
add_action('init', 'crear_taxonomia_fechas');

function add_custom_script()
{
  $screen = get_current_screen();
  if ($screen->id == 'actividades') {
    wp_enqueue_script('custom-activity', get_stylesheet_directory_uri() . '/js/custom-activity.js', array('jquery', 'jquery-ui-datepicker'), '1.0', true);
  }
}
add_action('admin_enqueue_scripts', 'add_custom_script', 99);

function redirigir_cpt_actividades()
{
  if (is_singular('actividades') || is_post_type_archive('actividades')) {
    wp_redirect(home_url(), 301);
    exit;
  }
}
add_action('template_redirect', 'redirigir_cpt_actividades');
