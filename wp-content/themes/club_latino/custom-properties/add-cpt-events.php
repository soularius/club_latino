<?php
function registrar_eventos_post_type() {

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
    'rewrite'               => array( 'slug' => 'eventos' ),
    'capability_type'       => 'post',
    'has_archive'           => true,
    'hierarchical'          => false,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-calendar',
    'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' )
  );

  // Registrar el Custom Post Type
  register_post_type( 'eventos', $args );

}
add_action( 'init', 'registrar_eventos_post_type' );
