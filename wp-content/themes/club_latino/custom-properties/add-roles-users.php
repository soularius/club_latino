<?php

// Agregar nuevos roles de usuario
add_role( 'socio', 'Socio', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
add_role( 'particular', 'Particular', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
add_role( 'visitante', 'Visitante', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
add_role( 'invitado', 'Invitado', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );
add_role( 'seguridad', 'Seguridad', array( 'read' => true, 'edit_posts' => false, 'delete_posts' => false ) );