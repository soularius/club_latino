<?php

// Agregar una taxonomía personalizada para usuarios
function registrar_taxonomia_grupos() {
    $labels = array(
        'name'              => _x( 'Grupos', 'taxonomy general name' ),
        'singular_name'     => _x( 'Grupo', 'taxonomy singular name' ),
        'search_items'      => __( 'Buscar Grupos' ),
        'all_items'         => __( 'Todos los Grupos' ),
        'parent_item'       => __( 'Grupo Padre' ),
        'parent_item_colon' => __( 'Grupo Padre:' ),
        'edit_item'         => __( 'Editar Grupo' ),
        'update_item'       => __( 'Actualizar Grupo' ),
        'add_new_item'      => __( 'Agregar Nuevo Grupo' ),
        'new_item_name'     => __( 'Nuevo Nombre de Grupo' ),
        'menu_name'         => __( 'Grupos' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'grupo' ),
    );

    register_taxonomy( 'grupo', array('user'), $args );
}

add_action( 'init', 'registrar_taxonomia_grupos', 0 );


 /**
 * Admin page for the 'groups' taxonomy
 */
function cb_add_groups_taxonomy_admin_page() {
  $tax = get_taxonomy( 'grupo' );
  add_users_page(
    esc_attr( $tax->labels->menu_name ),
    esc_attr( $tax->labels->menu_name ),
    $tax->cap->manage_terms,
    'edit-tags.php?taxonomy=' . $tax->name
  );
}
add_action( 'admin_menu', 'cb_add_groups_taxonomy_admin_page' );

/**
 * Unsets the 'posts' column and adds a 'users' column on the manage groups admin page.
 */
function cb_manage_groups_user_column( $columns ) {

  unset( $columns['posts'] );
  $columns['users'] = __( 'Users' );
  return $columns;
}
add_filter( 'manage_edit-groups_columns', 'cb_manage_groups_user_column' );

/**
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function cb_manage_groups_column( $display, $column, $term_id ) {

  if ( 'users' === $column ) {
    $term = get_term( $term_id, 'grupo' );
    echo $term->count;
  }
}
add_filter( 'manage_groups_custom_column', 'cb_manage_groups_column', 10, 3 );


/**
 * @param object $user The user object currently being edited.
 */
function cb_edit_user_group_section( $user ) {
  global $pagenow;

  $tax = get_taxonomy( 'grupo' );

  /* Make sure the user can assign terms of the departments taxonomy before proceeding. */
  if ( !current_user_can( $tax->cap->assign_terms ) )
    return;

  /* Get the terms of the 'grupo' taxonomy. */
  $terms = get_terms( 'grupo', array( 'hide_empty' => false ) ); ?>
  <div id="section-group">
    <h3><?php _e( 'Grupos' ); ?></h3>

    <table class="form-table">

      <tr>
        <th><label for="group"><?php _e( 'Selecionar Grupo' ); ?></label></th>

        <td><?php

        /* If there are any departments terms, loop through them and display checkboxes. */
        if ( !empty( $terms ) ) {
            echo cb_custom_form_field('grupos', $terms, $user->ID, 'dropdown');
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

add_action( 'show_user_profile', 'cb_edit_user_group_section' );
add_action( 'edit_user_profile', 'cb_edit_user_group_section' );
add_action( 'user_new_form', 'cb_edit_user_group_section' );

/**
 * return field as dropdown or checkbox, by default checkbox if no field type given
 * @param: name = taxonomy, options = terms avaliable, userId = user id to get linked terms
 */
function cb_custom_form_field( $name, $options, $userId, $type = 'checkbox') {
  global $pagenow;

  switch ($type) {
    case 'checkbox':
      foreach ( $options as $term ) : 
      ?>
        <label for="departments-<?php echo esc_attr( $term->slug ); ?>">
          <input type="checkbox" name="groups[]" 
            id="groups-<?php echo esc_attr( $term->slug ); ?>" 
            value="<?php echo $term->slug; ?>" 
            <?php if ( $pagenow !== 'user-new.php' ) 
            checked( true, is_object_in_term( $userId, 'grupo', $term->slug ) ); ?>
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
      $usrTerms = get_the_terms( $userId, 'grupo');
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
function cb_save_user_groups_terms( $user_id ) {

  $tax = get_taxonomy( 'grupo' );
  /* Make sure the current user can edit the user and assign terms before proceeding. */
  if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) ) {
    return false;
  }

  $term = $_POST['grupos'];
  $terms = is_array($term) ? $term : (int) $term; // fix for checkbox and select input field

  /* Sets the terms (we're just using a single term) for the user. */
  wp_set_object_terms( $user_id, $terms, 'grupo', false);

  clean_object_term_cache( $user_id, 'grupo' );
}

add_action( 'personal_options_update', 'cb_save_user_groups_terms' );
add_action( 'edit_user_profile_update', 'cb_save_user_groups_terms' );
add_action( 'user_register', 'cb_save_user_groups_terms' );

/**
 * Update parent file name to fix the selected menu issue
 */
function cb_change_parent_file($parent_file)
{
    global $submenu_file;

    if(isset($_GET['taxonomy']) &&
        $_GET['taxonomy'] == 'grupo' &&
        $submenu_file == 'edit-tags.php?taxonomy=grupo'
    )
    $parent_file = 'users.php';

    return $parent_file;
}
add_filter('parent_file', 'cb_change_parent_file');