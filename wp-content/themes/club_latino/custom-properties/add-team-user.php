<?php

// Agregar una taxonomía personalizada para usuarios
function registrar_taxonomia_equipos() {
    $labels = array(
        'name'              => _x( 'Equipos', 'taxonomy general name' ),
        'singular_name'     => _x( 'Equipo', 'taxonomy singular name' ),
        'search_items'      => __( 'Buscar Equipos' ),
        'all_items'         => __( 'Todos los Equipos' ),
        'parent_item'       => __( 'Equipo Padre' ),
        'parent_item_colon' => __( 'Equipo Padre:' ),
        'edit_item'         => __( 'Editar Equipo' ),
        'update_item'       => __( 'Actualizar Equipo' ),
        'add_new_item'      => __( 'Agregar Nuevo Equipo' ),
        'new_item_name'     => __( 'Nuevo Nombre de Equipo' ),
        'menu_name'         => __( 'Equipos' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'equipo' ),
    );

    register_taxonomy( 'equipo', array('user'), $args );
}

add_action( 'init', 'registrar_taxonomia_equipos', 0 );


 /**
 * Admin page for the 'teams' taxonomy
 */
function cb_add_teams_taxonomy_admin_page() {
  $tax = get_taxonomy( 'equipo' );
  add_users_page(
    esc_attr( $tax->labels->menu_name ),
    esc_attr( $tax->labels->menu_name ),
    $tax->cap->manage_terms,
    'edit-tags.php?taxonomy=' . $tax->name
  );
}
add_action( 'admin_menu', 'cb_add_teams_taxonomy_admin_page' );

/**
 * Unsets the 'posts' column and adds a 'users' column on the manage teams admin page.
 */
function cb_manage_teams_user_column( $columns ) {

  unset( $columns['posts'] );
  $columns['users'] = __( 'Users' );
  return $columns;
}
add_filter( 'manage_edit-teams_columns', 'cb_manage_teams_user_column' );

/**
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function cb_manage_teams_column( $display, $column, $term_id ) {

  if ( 'users' === $column ) {
    $term = get_term( $term_id, 'equipo' );
    echo $term->count;
  }
}
add_filter( 'manage_teams_custom_column', 'cb_manage_teams_column', 10, 3 );


/**
 * @param object $user The user object currently being edited.
 */
function cb_edit_user_team_section( $user ) {
  global $pagenow;

  $tax = get_taxonomy( 'equipo' );

  /* Make sure the user can assign terms of the departments taxonomy before proceeding. */
  if ( !current_user_can( $tax->cap->assign_terms ) )
    return;

  /* Get the terms of the 'equipo' taxonomy. */
  $terms = get_terms( 'equipo', array( 'hide_empty' => false ) ); ?>
  <div id="section-team">
    <h3><?php _e( 'Equipos' ); ?></h3>
    <table class="form-table">

      <tr>
        <th><label for="team"><?php _e( 'Selecionar Equipo' ); ?></label></th>

        <td><?php

        /* If there are any departments terms, loop through them and display checkboxes. */
        if ( !empty( $terms ) ) {
            echo cb_custom_team_form_field('equipos', $terms, $user->ID, 'multi-select');
        }

        /* If there are no departments terms, display a message. */
        else {
          _e( 'There are no departments available.' );
        }

        ?></td>
      </tr>

    </table>
  </div>
<?php }

add_action( 'show_user_profile', 'cb_edit_user_team_section' );
add_action( 'edit_user_profile', 'cb_edit_user_team_section' );
add_action( 'user_new_form', 'cb_edit_user_team_section' );

/**
 * return field as dropdown or checkbox, by default checkbox if no field type given
 * @param: name = taxonomy, options = terms avaliable, userId = user id to get linked terms
 */
function cb_custom_team_form_field( $name, $options, $userId, $type = 'checkbox') {
  global $pagenow;

  switch ($type) {
    case 'checkbox':
      foreach ( $options as $term ) : 
      ?>
        <label for="departments-<?php echo esc_attr( $term->slug ); ?>">
          <input type="checkbox" name="teams[]" 
            id="teams-<?php echo esc_attr( $term->slug ); ?>" 
            value="<?php echo $term->slug; ?>" 
            <?php if ( $pagenow !== 'user-new.php' ) 
            checked( true, is_object_in_term( $userId, 'equipo', $term->slug ) ); ?>
          >
          <?php echo $term->name; ?>
        </label><br/>
      <?php
      endforeach;
      break;
    case 'dropdown':
      $selectTerms = [];
      foreach ( $options as $term ) {
        $selectTerms[$term->term_id] = $term->name;
      }
  
      // get all terms linked with the user
      $usrTerms = get_the_terms( $userId, 'equipo');
      $usrTermsArr = [];
      if(!empty($usrTerms)) {
        foreach ( $usrTerms as $term ) {
          $usrTermsArr[] = (int) $term->term_id;
        }
      }
      // Dropdown
      echo "<select name='{$name}' class='select2'>";
      echo "<option value=''>-Seleccionar-</option>";
      foreach( $selectTerms as $options_value => $options_label ) {
        $selected = ( in_array($options_value, array_values($usrTermsArr)) ) ? " selected='selected'" : "";
        echo "<option value='{$options_value}' {$selected}>{$options_label}</option>";
      }
      echo "</select>";
      break;

    case 'multi-select':
      $selectTerms = [];
      foreach ( $options as $term ) {
        $selectTerms[$term->term_id] = $term->name;
      }

      // get all terms linked with the user
      $usrTerms = get_the_terms( $userId, 'equipo');
      $usrTermsArr = [];
      if(!empty($usrTerms)) {
        foreach ( $usrTerms as $term ) {
          $usrTermsArr[] = (int) $term->term_id;
        }
      }

      // Multi-select dropdown with Select2
      echo "<select name='{$name}[]' multiple='multiple' class='select2'>";
      foreach( $selectTerms as $options_value => $options_label ) {
        $selected = ( in_array($options_value, array_values($usrTermsArr)) ) ? " selected='selected'" : "";
        echo "<option value='{$options_value}' {$selected}>{$options_label}</option>";
      }
      echo "</select>";

      // Script for Select2 initialization
      echo "<script type='text/javascript'>
              jQuery(document).ready(function() {
                jQuery('.select2').select2({
                  placeholder: '-Seleccionar-',
                  allowClear: true,
                  width: '100%'
                });
              });
            </script>";
      break;

  }
}

/**
 * @param int $user_id The ID of the user to save the terms for.
 */
function cb_save_user_teams_terms( $user_id ) {

  $tax = get_taxonomy( 'equipo' );
  /* Make sure the current user can edit the user and assign terms before proceeding. */
  if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) ) {
    return false;
  }

  $term = $_POST['equipos'];
  
  $terms = is_array($term) ? array_unique($term) : (int) $term; // fix for checkbox and select input field

  if(is_array($terms)) {
    $terms_slug = array();
    foreach ( $terms as $term_id ) {
      $term = get_term_by( 'id', $term_id, 'equipo' );
      if ( $term ) {
        $terms_slug[] = $term->slug;
      }
    }
    $terms = $terms_slug;
  }

  // Delete existing term relationships
  wp_delete_object_term_relationships( $user_id, 'equipo' );
  
  /* Sets the terms (we're just using a single term) for the user. */
  $a = wp_set_object_terms( $user_id, $terms, 'equipo', false);

  clean_object_term_cache( $user_id, 'equipo' );
}

add_action( 'personal_options_update', 'cb_save_user_teams_terms' );
add_action( 'edit_user_profile_update', 'cb_save_user_teams_terms' );
add_action( 'user_register', 'cb_save_user_teams_terms' );

/**
 * Update parent file name to fix the selected menu issue
 */
function cb_change_parent_file_user($parent_file)
{
    global $submenu_file;

    if(isset($_GET['taxonomy']) &&
        $_GET['taxonomy'] == 'equipo' &&
        $submenu_file == 'edit-tags.php?taxonomy=equipo'
    )
    $parent_file = 'users.php';

    return $parent_file;
}
add_filter('parent_file', 'cb_change_parent_file_user');