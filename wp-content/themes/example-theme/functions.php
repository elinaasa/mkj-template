<?php

function theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-header');
    add_theme_support('custom-logo', array(
        'height' => 200,
        'width' => 200,
        'flex-height' => true,
    ));

    // Set the default Post Thumbnail size
    set_post_thumbnail_size(200, 200, true); // 200px wide by 200px high, hard crop mode

    // Add custom image sizes
    add_image_size('custom-header', 1200, 400, true); // Custom header size
    add_image_size('custom-thumbnail', 250, 250, true); // Custom thumbnail size

// Register the main menu
    register_nav_menu('main-menu', __('Main Menu'));
}

add_action('after_setup_theme', 'theme_setup');

function style_setup(): void
{
    wp_enqueue_style('main-style', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'style_setup');

// custom functions
require_once(__DIR__ . '/inc/article-function.php');
require_once(__DIR__ . '/inc/random-image.php');