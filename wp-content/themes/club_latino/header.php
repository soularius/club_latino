<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package club_latino
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site site-cl">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'club_latino'); ?></a>

		<header id="masthead" class="site-header">
			<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
				<div class="container-fluid">
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<a class="navbar-brand" href="#">Club Latino</a>
					<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
						<?php
						$current_user = wp_get_current_user();
						$current_roles = $current_user->roles;
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu'           => 'menu-head',
								'menu_class'	 => 'navbar-nav me-auto mb-2 mb-lg-0',
								'container'      => false,
								'fallback_cb'    => '__return_false',
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'          => 2,
								'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
								'walker'         => new WP_Bootstrap_Navwalker()
							)
						);
						?>
					</div>
					<div id="dropdown-profile" class="dropdown">
						<button class="btn btn-secondary dropdown-toggle btn-user-info" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<span class="material-icons material-icons-round">face</span>
						</button>
						<ul class="dropdown-menu">
							<?php
							if (!is_user_logged_in()) {
								$page_url_login = '';
								$page_slug_login = 'iniciar-sesion'; // Reemplazar con el slug de la página deseada
								$page = get_page_by_path($page_slug_login);
								if ($page) {
									$page_url_login = get_permalink($page->ID);
								}
							?>
								<li><a class="dropdown-item font-primary" href="<?= $page_url_login ?>">Iniciar Sesión</a></li>
							<?php
							} else {
							?>
								<li><a class="dropdown-item font-primary" href="<?= get_url_by_slug("perfil") ?>">Mi Perfil</a></li>
								<li><a class="dropdown-item font-primary" href="<?php echo wp_logout_url(home_url()); ?>">Cerrar sesión</a></li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</nav>


		</header><!-- #masthead -->