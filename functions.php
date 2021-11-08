<?php

add_filter('query_vars', 'ticoQueryVars');
function ticoQueryVars($vars) {
    $vars[] = 'skyColor';
    $vars[] = 'grassColor';
    return $vars;
}

require get_theme_file_path('/includes/search-route.php');
require get_theme_file_path('/includes/like-route.php');

add_action('rest_api_init', 'tico_custom_rest');
function tico_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
    ));
}

function pageBanner($args = NULL) {
    if (!$args['title']) {
        // if no title is provided, get the post title
        $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
        if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        } 
    }
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
            <div class="page-banner__intro">
            <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>
<?php }

add_action('wp_enqueue_scripts', 'tico_files');
function tico_files() {
    // wp_enqueue_style('tico_main_styles', get_stylesheet_uri());
    
    // Google Maps script
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=yourGoogleKey', NULL, '1.0', true);
    // Main script
    wp_enqueue_script('main-tico-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    // font-family
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    // icons
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // styles
    wp_enqueue_style('tico_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('tico_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script('main-tico-js', 'ticoData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
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

    // allowed feature images
    add_theme_support('post-thumbnails');

    // create an image copy with customize size
    // nickname,width px,height px,crop or not
    add_image_size('designerLandscape', 400, 260, true);
    add_image_size('designerPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
    add_image_size('slideImage', 1900, 525, true);
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

    if (!is_admin() AND is_post_type_archive('store') AND $query->is_main_query()) {
        // to make sure that all stores will be shown at Google Map
        $query->set('posts_per_page',-1);
    }
}

add_filter('acf/fields/google_map/api', 'storeMapKey');
function storeMapKey($api) {
    $api['key'] = 'AIzaSyAtPvu9O0yTyQkJ9UFS_jRgDnZD5ifeBU8';
    return $api;
}

// Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');
function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

// Remove admin header bar for subscribers
add_action('wp_loaded', 'noSubsAdminBar');
function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

// Customize login screen
add_filter('login_headerurl', 'ourHeaderUrl');
function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

// Apply CSS files on login screen
add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS() {
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('tico_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('tico_extra_styles', get_theme_file_uri('/build/index.css'));
}

// Change title of login screen
add_filter('login_headertitle', 'ourLoginTitle');
function ourLoginTitle() {
    return get_bloginfo('name');
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);
function makeNotePrivate($data, $postarr) {
    if ($data['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 3 AND !$postarr['ID']) {
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }
    return $data;
}

add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');
function ignoreCertainFiles($exclude_filters) {
    $exclude_filters[] = 'themes/tico-theme/node_modules';
    return $exclude_filters;
}