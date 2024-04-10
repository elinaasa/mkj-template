<?php
/*
Plugin Name: Like Button
Description: Adds a like button to posts
Version: 1.1
Author: John Doe
*/

// Create table
function create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'likes';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id mediumint(9) NOT NULL,
        user_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY post_user_unique (post_id, user_id)  -- Ensure each user can like a post only once
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

register_activation_hook( __FILE__, 'create_table' );

// Add like button
function like_button() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'likes';

    $post_id = get_the_ID();
    $user_id = get_current_user_id();

    $is_liked = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND user_id = %d", $post_id, $user_id ) );

    $likes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE post_id = %d", $post_id ) );

    $output = '<form id="like-form" method="post" action="'. admin_url( 'admin-post.php' ) .'">';
    $output .= '<input type="hidden" name="action" value="'. ( $is_liked ? 'remove_like' : 'add_like' ) .'">';
    $output .= '<input type="hidden" name="post_id" value="' . $post_id . '">';
    $output .= '<button id="like-button">' . ( $is_liked ? '<ion-icon name="heart-dislike"></ion-icon>' : '<ion-icon name="heart"></ion-icon>' ) . '</button>';
    $output .= '<span id="like-count">' . $likes . '</span>';
    $output .= '</form>';

    return $output;
}

add_shortcode( 'like_button', 'like_button' );

// Add or remove like from database
function handle_like() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'likes';

    $post_id = $_POST['post_id'];
    $user_id = get_current_user_id();

    if ( isset( $_POST['action'] ) && $_POST['action'] === 'add_like' ) {
        $data = array(
            'post_id' => $post_id,
            'user_id' => $user_id
        );

        $format = array( '%d', '%d' );

        $success = $wpdb->insert( $table_name, $data, $format );

        if ( $success ) {
            echo 'Liked';
        } else {
            echo 'Error liking';
        }
    } elseif ( isset( $_POST['action'] ) && $_POST['action'] === 'remove_like' ) {
        $where = array(
            'post_id' => $post_id,
            'user_id' => $user_id
        );

        $wpdb->delete( $table_name, $where );

        echo 'Unliked';
    }

    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit;
}

add_action( 'admin_post_add_like', 'handle_like' );
add_action( 'admin_post_remove_like', 'handle_like' );

// Enqueue Ionicons
function my_theme_load_ionicons_font() {
    // Load Ionicons font from CDN
    wp_enqueue_script( 'my-theme-ionicons', 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js', array(), '7.1.0', true );
}

add_action( 'wp_enqueue_scripts', 'my_theme_load_ionicons_font' );
