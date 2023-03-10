<?php
function footer_widget_area()
{
    register_sidebar(array(
        'name' => __('Footer', 'club_latino'),
        'id' => 'footer-widget-area',
        'description' => __('A custom widget area for the footer of my theme', 'club_latino'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'footer_widget_area');
