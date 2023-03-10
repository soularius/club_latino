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
	<?php if (is_active_sidebar('footer-widget-area')) : ?>
		<div class="footer-widget-area">
			<?php dynamic_sidebar('footer-widget-area'); ?>
		</div>
	<?php endif; ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>