<?php

function crear_post_type_bitacora()
{
    $labels = array(
        'name'               => 'Bitácora',
        'singular_name'      => 'Bitácora',
        'menu_name'          => 'Bitácora',
        'name_admin_bar'     => 'Bitácora',
        'add_new'            => 'Agregar nueva entrada',
        'add_new_item'       => 'Agregar nueva entrada a la bitácora',
        'new_item'           => 'Nueva entrada',
        'edit_item'          => 'Editar entrada',
        'view_item'          => 'Ver entrada',
        'all_items'          => 'Todas las entradas',
        'search_items'       => 'Buscar entradas',
        'parent_item_colon'  => 'Entradas padre:',
        'not_found'          => 'No se encontraron entradas.',
        'not_found_in_trash' => 'No se encontraron entradas en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'bitacora'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'author'),
        'menu_icon'          => 'dashicons-welcome-write-blog'
    );

    register_post_type('bitacora', $args);
}

add_action('init', 'crear_post_type_bitacora');
