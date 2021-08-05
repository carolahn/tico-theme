<?php

add_action('wp_enqueue_scripts', 'tico_files');
function tico_files() {
    // wp_enqueue_style('tico_main_styles', get_stylesheet_uri());

    // slider script
    wp_enqueue_script('main-slider', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    // font-family
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    // icons
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // styles
    wp_enqueue_style('tico_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('tico_extra_styles', get_theme_file_uri('/build/index.css'));
}

add_action('after_setup_theme', 'tico_features');
function tico_features() {
    // to create dynamic menu from wp-admin
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    
    // footer dynamic menu from wp-admin
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');

    // show page title at the browser
    add_theme_support('title-tag');
}

add_action('pre_get_posts', 'tico_adjust_queries');
function tico_adjust_queries($query) {
    // to change only patterns filter on page
    // but keeping all others post configurations, like pagination
    if (!is_admin() AND is_post_type_archive('pattern') AND $query->is_main_query()) {
        // if not is_admin() means that your not at the admin dashboard, 
        // so it will change only front-end, not the back-end
        // $query->is_main_query() to avoid changing custom queries, changes only URL based queries

        // $query->set('posts_per_page', '1');
        $query->set('meta_key','suggested_price');
        $query->set('orderby','meta_value_num');
        $query->set('order','DESC');
        // to show only advanced patterns
        // $query->set('meta_query', array(
        //     array(
        //         'key' => 'skill_level',
        //         'compare' => '=',
        //         'value' => 'advanced',
        //         'type' => 'string'
        //     )
        // ));
    }
}