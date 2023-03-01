<?php

// Agregar nuevos roles de usuario
add_role('socio', 'Socio', array('read' => true));
add_role('particular', 'Particular', array('read' => true));
add_role('visitante', 'Visitante', array('read' => true));
add_role('invitado', 'Invitado', array('read' => true));
add_role('seguridad', 'Seguridad', array('read' => true, 'edit_posts' => false));
