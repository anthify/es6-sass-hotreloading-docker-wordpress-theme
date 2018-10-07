<?php

    // ----------------------------------------------------------------------------
    // ---------- Translations can be filed in the /languages/ directory ----------
    // ----------------------------------------------------------------------------
    load_theme_textdomain( 'html5reset', TEMPLATEPATH . '/languages' );

    $locale = get_locale();
    $locale_file = TEMPLATEPATH . "/languages/$locale.php";
    if ( is_readable($locale_file) )
        require_once($locale_file);


    // --------------------------------------
    // ---------- Remove admin bar ----------
    // --------------------------------------
    show_admin_bar(false);

    // ------------------------------------------------
    // ---------- Add post thumbnail support ----------
    // ------------------------------------------------
    add_theme_support('post-thumbnails');
    add_image_size('large-thumbnail', 246, 140);
    
    // ----------------------------------------------------------------------------
    // ---------- Remove width and height attributes from post thumbnail ----------
    // ----------------------------------------------------------------------------
    function clean_img_width_height($string){
        return preg_replace('/\<(.*?)(width="(.*?)")(.*?)(height="(.*?)")(.*?)\>/i', '<$1$4$7>',$string);
    }

    // ----------------------------------------------
    // ---------- Add page excerpt support ----------
    // ----------------------------------------------
    add_post_type_support('page', 'excerpt');


    // ---------------------------------
    // ---------- Load CSS ----------
    // ---------------------------------
    function theme_styles() {
        wp_enqueue_style(
            'theme-styles', 
            get_stylesheet_uri(), 
            array(),
            false,
            'all'
        );
    }
    add_action( 'wp_enqueue_scripts', 'theme_styles' );
        
    // ---------------------------------
    // ---------- Load JS ----------
    // ---------------------------------

    function add_javascript() {
        wp_enqueue_script( 'script', get_template_directory_uri() . '/scripts/main.min.js', false, false, true);
    }

    add_action('wp_enqueue_scripts', 'add_javascript');


    // -----------------------------------------
    // ---------- Clean up the <head> ----------
    // -----------------------------------------
    function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    // ----------------------------------------------------
    // ---------- Enable custom navigation menus ----------
    // ----------------------------------------------------
    if (function_exists('register_nav_menus')) {
        register_nav_menus(array(
            'main_nav' => 'Main Navigation Menu'
        )); 
    }
    
    // ----------------------------------------------------------------------------------
    // ---------- Create the home (homepage) option in custom navigation menus ----------
    // ----------------------------------------------------------------------------------
    function home_page_menu_item($args) {
        $args['show_home'] = true;
        return $args;
    }
    add_filter('wp_page_menu_args', 'home_page_menu_item');
    
    // ------------------------------------------------
    // ---------- Enable Post Format support ----------
    // ------------------------------------------------
    add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video')); // Add 3.1 post format theme support.

    // ------------------------------------------------
    // ---------- Add class to post excerpts ----------
    // ------------------------------------------------
    function add_excerpt_class( $excerpt ) {
        $excerpt = str_replace( "<p", "<p class=\"excerpt\"", $excerpt );
        return $excerpt;
    }
    add_filter( "the_excerpt", "add_excerpt_class" );

    // ------------------------------------------------------------
    // ---------- Wrap all post images in figure element ----------
    // ------------------------------------------------------------
    function wrap_my_div($html, $id, $caption, $title, $align, $url, $size, $alt){
        return '<figure class="figure">'.$html.'</figure>';
    }
    add_filter('image_send_to_editor', 'wrap_my_div', 10, 8);
           
?>