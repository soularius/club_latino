<?php

/**
 * club_latino functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package club_latino
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

get_template_part('lib/class', 'wp-bootstrap-navwalker');
get_template_part('custom-requests/https', 'register');
get_template_part('custom-requests/https', 'binnacle');
get_template_part('custom-properties/add', 'config-login');
get_template_part('custom-properties/add', 'permision-nav');
get_template_part('custom-properties/add', 'roles-users');
get_template_part('custom-properties/add', 'option-configure');
get_template_part('custom-properties/add', 'group-user');
get_template_part('custom-properties/add', 'team-user');
get_template_part('custom-properties/add', 'cpt-events');
get_template_part('custom-properties/add', 'cpt-binnacle');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function club_latino_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on club_latino, use a find and replace
		* to change 'club_latino' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('club_latino', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'club_latino'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'club_latino_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'club_latino_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function club_latino_content_width()
{
	$GLOBALS['content_width'] = apply_filters('club_latino_content_width', 640);
}
add_action('after_setup_theme', 'club_latino_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function club_latino_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'club_latino'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'club_latino'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'club_latino_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function club_latino_scripts()
{
	wp_enqueue_style('club_latino-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_enqueue_style('all_fonts', get_template_directory_uri() . '/css/fonts.css', array(), _S_VERSION);
	wp_enqueue_style('vars', get_template_directory_uri() . '/css/vars.css', array(), _S_VERSION);
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), _S_VERSION);
	wp_enqueue_style('nav_bar', get_template_directory_uri() . '/css/nav-bar.css', array(), _S_VERSION);
	wp_enqueue_style('general', get_template_directory_uri() . '/css/general.css', array(), _S_VERSION);

	if (is_page_template('template-full-screen.php')) {
		wp_enqueue_style('template_full_screen', get_template_directory_uri() . '/css/template-full-screen.css', array(), _S_VERSION);
	}

	if (is_page('inicio')) {
		wp_enqueue_style('page_init', get_template_directory_uri() . '/css/page-inicio.css', array(), _S_VERSION);
	}

	if (is_page('iniciar-sesion')) {
		wp_enqueue_style('page_init_sesion', get_template_directory_uri() . '/css/page-inicio-sesion.css', array(), _S_VERSION);
	}

	if (is_page('registro-de-bitacora')) {
		wp_enqueue_style('page_bitacora', get_template_directory_uri() . '/css/page-bitacora.css', array(), _S_VERSION);
	}

	if (is_page('registrar')) {
		wp_enqueue_style('page_registrar', get_template_directory_uri() . '/css/page-registrar.css', array(), _S_VERSION);
	}

	wp_style_add_data('club_latino-style', 'rtl', 'replace');

	wp_enqueue_script('club_latino-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_style('select2', get_template_directory_uri() . '/css/select2.min.css', array(), _S_VERSION);
	wp_enqueue_script('select2', get_template_directory_uri() . '/js/select2.min.js', array(), _S_VERSION, true);
}
add_action('wp_enqueue_scripts', 'club_latino_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}
