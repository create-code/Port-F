<?php


/* ### ENQUEUE CSS AND JS ### */

/* css */

function hm_theme_css() {

    wp_register_style( 'hm_normalize', get_template_directory_uri() . '/normalize.css', 0 );
    wp_register_style( 'hm_theme', get_template_directory_uri() . '/style.css', array( 'hm_normalize' ) );

    wp_enqueue_style( 'hm_normalize' );
    wp_enqueue_style( 'hm_theme' );

} 

/* js */

function hm_theme_js() {

    hm_theme_css();

    wp_register_script( 'hm_dependencies', get_template_directory_uri() . '/js/dependencies-global.min.js', 0, false );
    wp_register_script( 'hm_theme', get_template_directory_uri() . '/js/site-global.js', array( 'jquery','hm_dependencies' ), 0, false );
    wp_register_script( 'hm_loader', get_template_directory_uri() . '/js/loader.js', array( 'hm_theme' ), 0, false );
    
    wp_enqueue_script( 'hm_dependencies' );
    wp_enqueue_script( 'hm_theme' );
    wp_enqueue_script( 'hm_loader' );

} 

add_action( 'wp_enqueue_scripts', 'hm_theme_js', 1 ); /* prints both CSS and JS */


/* ### ADD HTML TO HEADER ### */

function hm_theme_head() { ?>
<!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="ie7.css" />
<![endif]-->
<?php 
}

add_action( 'wp_head', 'hm_theme_head', 1 );


/* ### ADD HTML TO FOOTER ### */

function hm_theme_footer() { ?>
<!-- W3TC-include-js-head -->
<?php 
}

add_action( 'wp_footer', 'hm_theme_footer', 1 );


/* ### CLEAN UP THE HEADER ### */

remove_action( 'wp_head', 'rsd_link' ); // remove Really Simple Discovery Entry
remove_action( 'wp_head', 'wlwmanifest_link' );  // remove Windows Live Writer Link 
remove_action( 'wp_head', 'wp_generator' );  // remove Version number


/* ### THEME SUPPORT ### */

add_theme_support( 'post-thumbnails' );
add_theme_support( 'menus' );


/* ### CUSTOM TAXONOMY ### */

register_taxonomy( 
	'project_types', 
	array( 'projects' ), 
	array( 
        'hierarchical' => true,
    	'labels' => array( 
            'name' => _( 'Project Types' ), 
            'singular_name' => _( 'Project Type' )
    	 ),
    'show_ui' => true,
    'show_admin_column' => true,
    'rewrite' => array( 
        'slug' => 'project_type' 
    )
    ) 
);


/* ### CUSTOM POST TYPES ### */

register_post_type( 
	'projects', 
	array( 'labels' => array( 'name' => _( 'Projects' ), 
							  'singular_name' => _( 'Project' ),
							  'add_new_item' => _( 'Add new Project' ),
							  'edit_item' => _( 'Edit Project' ),
							  'new_item' => _( 'Add Project' ),
							  'view_item' => _( 'View Project' ),
        					  'search_items' => _( 'Search Project' )
					        ),
    	  'capability_type' => 'post',
    	  'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
    	  'public' => true,
    	  'menu_position' => 5,
          // http://melchoyce.github.io/dashicons/
          'menu_icon' => 'dashicons-smiley',
    	  'rewrite' => array( 'slug' => 'projects' ),
    	  'has_archive' => 'projects',
          'taxonomies' => array( 'project_types' )
));


/* ### HIDE ADMIN BAR ### */

add_filter( 'show_admin_bar', '__return_false' );


/* ### MENUS ### */

register_nav_menu( 'head_primary', _( 'Primary Header Navigation' ) );
register_nav_menu( 'footer_primary', _( 'Primary Footer Navigation' ) );


/* ### CUSTOM WIDGET AREA */

register_sidebar( array(
	'name'=> _( 'Footer Widgets' ),
	'id' => 'footer_widgets'
	) );	



/* ### CUSTOM IMAGE SIZES ### */

add_image_size( '300x200', 300, 200, true );


/* ### DEFINE JPEG QUALITY ### */

function hm_jpeg_quality() {
    return 100;
}

add_filter( 'jpeg_quality', 'hm_jpeg_quality' );


/* ### REMOVE ADMIN MENUS ### */

function hm_remove_admin_menus () {
    
    // remove_menu_page( 'edit.php' );                  /* Posts */
    // remove_menu_page( 'edit.php?post_type=page' );   /* Pages */
    // remove_menu_page( 'upload.php' );                /* Media */
    remove_menu_page( 'edit-comments.php' );            /* Comments */
    // remove_menu_page( 'tools.php' );                 /* Tools */

}

add_action( 'admin_menu', 'hm_remove_admin_menus' );


/* ### REMOVE DASHBOARD WIDGETS ### */

function hm_remove_admin_dashboard_widgets() {
	
    global $wp_meta_boxes;

	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
//	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
}

add_action( 'wp_dashboard_setup', 'hm_remove_admin_dashboard_widgets' );


/* ### CONFIGURE TINY MCE ### */

function hm_customize_tinyMCE( $init ) {

    /* disable rich text pasting */
    $init['paste_text_sticky'] = true;
    $init['paste_text_sticky_default'] = true;

    /* configre format select entries */ 
    $init['theme_advanced_blockformats'] = 'h3,h4,p';

    return $init;
    
}

add_filter( 'tiny_mce_before_init', 'hm_customize_tinyMCE' );


/* ## TINY MCE BUTTON SETUP ### */

function hm_tinyMCE_buttons_1( $buttons ) {
    
    return array(
        'formatselect',
        'bold', 
        'italic', 
        'bullist', 
        'link', 
        'unlink', 
        'undo', 
        'redo', 
        'removeformat'
    );

}

add_filter( 'mce_buttons', 'hm_tinyMCE_buttons_1' );


function hm_tinyMCE_buttons_2( $buttons ) {

    return array(); 
}

add_filter( 'mce_buttons_2', 'hm_tinyMCE_buttons_2' );


/* ### EDITOR STYLES ### */

function hm_tinymce_custom_css() {
    
    add_editor_style(); /* defaults to editor-style.css */

}

add_action( 'init', 'hm_tinymce_custom_css' );


/* ### CUSTOM EXCERPT ### */

function hm_custom_excerpt_length( $length ) {
    
    return 40;

}

add_filter( 'excerpt_length', 'hm_custom_excerpt_length' );


/* ### GET INC ### */

function get_inc( $type, $context, $fallback ) {

    if( $context ) {

        if( is_file( TEMPLATEPATH . '/inc/' . $type . '-' . $context . '.php' ) ) {

            get_template_part( '/inc/' . $type . '-' . $context );

        } else {

            if( $fallback ) {

                get_template_part( '/inc/' . $type . '-default' );

            }

        }

    } else {

        get_template_part( '/inc/' . $type );

    }

}


/* ### CUSTOM TEMPLATE TAGS ### */

function the_post_time() {

    echo the_time( get_option( 'date_format' ) ) . ' ' . the_time( get_option( 'time_format' ) );

}


function get_the_super_title() {

    $html = '';

    /* archive & single */

    if( is_archive () || is_single() || is_home() ) {
        if( get_post_type() == 'post' ) {
            $html = __( 'News' );
        } else { 
            $html = get_post_type_object( get_post_type() )->labels->name;
        }
    }

    /* category, tag or taxonomy archive */

    if( is_category() || is_tag() || is_tax() ) {
        $html .= ' &ndash; ' . single_cat_title( '', 0 );
    }

    /* pages */

    if( is_page() ) {
        $html .= get_the_title();
    }

    /* 404 */

    if( is_404() ) {
        $html .= __( 'Oouuups...' );
    }

    /* search */

    if( is_search() ) {
        $html .= __( 'Search results' );
    }

    return $html;

}


/* ### REWRITE SEARCH TERM ### */

function hm_rewrite_search() {

    if( is_search() && !empty( $_GET['s'] ) ) {

        wp_redirect( home_url( '/search/' ) . urlencode( get_query_var( 's' ) ) );
        
        exit();

    }   
}

add_action( 'template_redirect', 'hm_rewrite_search' );


/* ### HM POST META CONFIG ### */

$hm_post_meta_config = array(
    array(
        'id' => 'post-options', 
        'label' => 'Post Options', 
        'post_types' => array( 
            'post', 
            'events' 
        ), 
        'fields' => array( 
            array( 
                'id' => 'check', 
                'label' => 'Check', 
                'type' => 'checkbox',
                'info' => 'Short description' 
            ),
            array( 
                'id' => 'check_group', 
                'label' => 'Check Group', 
                'option_labels' => array( 
                    'Option One',
                    'Option Two', 
                    'Option Three'
                ), 
                'option_values' => array( 
                    1, 
                    2, 
                    3
                ), 
                'type' => 'checkbox',
                'info' => 'Short description'
            ), 
            array( 
                'id' => 'radio', 
                'label' => 'Radio', 
                'type' => 'radio'
            ),
            array( 
                'id' => 'radio_group', 
                'label' => 'Radio Group', 
                'option_labels' => array( 
                    'Option One', 
                    'Option Two', 
                    'Option Three'
                ), 
                'option_values' => array( 
                    1, 
                    2, 
                    3
                ), 
                'type' => 'radio'
            ), 
            array( 
                'id' => 'text', 
                'label' => 'Text', 
                'type' => 'text'
            ), 
            array( 
                'id' => 'text_2', 
                'label' => 'Text', 
                'type' => 'text',
                'info' => 'Long description Long description Long description Long description Long description Long description Long description Long description Long description Long description'
            ), 
            array( 
                'id' => 'text_3', 
                'label' => 'Text', 
                'type' => 'text' 
            ), 
            array( 
                'id' => 'text_4', 
                'label' => 'Text with super-duper long long long labl text that should wrap carefully...', 
                'type' => 'text',
                'info' => 'Long description Long description Long description Long description Long description Long description Long description Long description Long description Long description'
            ),
            array( 
                'id' => 'select', 
                'label' => 'Select', 
                'option_labels' => array( 
                    'One', 
                    'Two', 
                    'Three' 
                ), 
                'option_values' => array( 
                    1, 
                    2, 
                    3 
                ), 
                'type' => 'select',
                'info' => 'Short description' 
            )
        )
    ), 
    array(
        'id' => 'post-extras', 
        'label' => 'Post Extras', 
        'post_types' => array(
            'post' 
        ),  
        'fields' => array( 
            array( 
                'id' => 'extr-text', 
                'label' => 'Extra Text', 
                'type' => 'text' 
            ) 
        )
    )
);  
