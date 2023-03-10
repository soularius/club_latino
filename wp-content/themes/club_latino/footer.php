<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package club_latino
 */

?>

<footer id="colophon" class="site-footer">

<?php /* get_sidebar( 'footer' ); */ ?>

<div class="site-info">
	<?php /* do_action( 'twentyfourteen_credits' ); */ ?>
	<?php
	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '', '<span role="separator" aria-hidden="true"></span>' );
	}
	?>
	<div >
	
	</div>
</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
