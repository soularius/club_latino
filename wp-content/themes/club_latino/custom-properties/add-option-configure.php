<?php

if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page(array(
        'page_title'    => 'Configuración general',
        'menu_title'    => 'Configuración general',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
    
    /* acf_add_options_sub_page(array(
        'page_title'    => 'Configuración de usuarios',
        'menu_title'    => 'Configuración de usuarios',
        'parent_slug'   => 'users.php',
    )); */
    
    /* acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    )); */
    
}