<?php
/*
Plugin Name: ProfessionalDirectory
Plugin URI: http://arielsouza.com.br/professionaldirectory
Description: A plugin to manage a directory of professional services and listings.
Version: 1.0
Author: Ariel Souza
Author URI: arielsouza.com.br
License: GPLv2 or later
Text Domain: professionaldirectory
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Enqueue public styles and scripts
function professionaldirectory_enqueue_scripts() {
    wp_enqueue_style('professionaldirectory-style', plugins_url('/public/css/style.css', __FILE__));
    wp_enqueue_script('professionaldirectory-script', plugins_url('/public/js/script.js', __FILE__), array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'professionaldirectory_enqueue_scripts');

// Enqueue admin styles and scripts
function professionaldirectory_enqueue_admin_scripts() {
    wp_enqueue_style('professionaldirectory-admin-style', plugins_url('/admin/css/admin-style.css', __FILE__));
    wp_enqueue_script('professionaldirectory-admin-script', plugins_url('/admin/js/admin-script.js', __FILE__), array('jquery'), '', true);
}

if ( is_admin() ) {
    add_action('admin_enqueue_scripts', 'professionaldirectory_enqueue_admin_scripts');
}
