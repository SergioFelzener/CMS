<?php
/**
 * Describe child theme functions
 *
 * @package News Portal
 * @subpackage Trend Portal
 * 
 */

/*-------------------------------------------------------------------------------------------------------------------------------*/
if ( ! function_exists( 'trend_portal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function trend_portal_setup() {

    $trend_portal_theme_info = wp_get_theme();
    $GLOBALS['trend_portal_version'] = $trend_portal_theme_info->get( 'Version' );
}
endif;

add_action( 'after_setup_theme', 'trend_portal_setup' );

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Register Google fonts
 *
 * @return string Google fonts URL for the theme.
 * @since 1.0.0
 */
if ( ! function_exists( 'trend_portal_fonts_url' ) ) :
    function trend_portal_fonts_url() {

        $fonts_url = '';
        $font_families = array();

        /*
         * Translators: If there are characters in your language that are not supported
         * by Arimo, translate this to 'off'. Do not translate into your own language.
         */
        if ( 'off' !== _x( 'on', 'Arimo font: on or off', 'trend-portal' ) ) {
            $font_families[] = 'Arimo:300,400,400i,500,700';
        }

        if( $font_families ) {
            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }
endif;

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Managed the theme default color
 */
function trend_portal_customize_register( $wp_customize ) {
		global $wp_customize;

		$wp_customize->get_setting( 'news_portal_theme_color' )->default = '#DE2023';
        $wp_customize->get_setting( 'news_portal_site_title_color' )->default = '#DE2023';

        /**
         * footer background image
         */
        $wp_customize->add_setting( 'trend_portal_header_bg_image',
            array(
                'default' => '',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
            )
        );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
            'trend_portal_header_bg_image',
                array(
                    'label'      => esc_html__( 'Header Background', 'trend-portal' ),
                    'section'    => 'news_portal_header_option_section',
                    'priority'   => 20
                )
            )
        );

	}

add_action( 'customize_register', 'trend_portal_customize_register', 20 );

/*-------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue child theme styles and scripts
 */
add_action( 'wp_enqueue_scripts', 'trend_portal_scripts', 20 );

function trend_portal_scripts() {
    
    global $trend_portal_version;
    
    wp_enqueue_style( 'trend-portal-google-font', trend_portal_fonts_url(), array(), null );
    
    wp_dequeue_style( 'news-portal-style' );
    
    wp_dequeue_style( 'news-portal-responsive-style' );
    
	wp_enqueue_style( 'news-portal-parent-style', get_template_directory_uri() . '/style.css', array(), esc_attr( $trend_portal_version ) );
    
    wp_enqueue_style( 'news-portal-parent-responsive', get_template_directory_uri() . '/assets/css/np-responsive.css', array(), esc_attr( $trend_portal_version ) );
    
    wp_enqueue_style( 'trend-portal-style', get_stylesheet_uri(), array(), esc_attr( $trend_portal_version ) );
    
    $get_categories = get_categories( array( 'hide_empty' => 1 ) );
    
    $trend_portal_theme_color = get_theme_mod( 'news_portal_theme_color', '#DE2023' );
    
    $trend_portal_theme_hover_color = news_portal_hover_color( $trend_portal_theme_color, '-50' );
    
    $news_portal_site_title_option = get_theme_mod( 'news_portal_site_title_option', 'true' );        
    $news_portal_site_title_color = get_theme_mod( 'news_portal_site_title_color', '#DE2023' );
    
    $output_css = '';
    
    foreach( $get_categories as $category ){

        $cat_color = get_theme_mod( 'news_portal_category_color_'.strtolower( $category->name ), '#DE2023' );

        $cat_hover_color = news_portal_hover_color( $cat_color, '-50' );
        $cat_id = $category->term_id;
        
        if( !empty( $cat_color ) ) {
            $output_css .= ".category-button.np-cat-". esc_attr( $cat_id ) ." a { background: ". esc_attr( $cat_color ) ."}\n";

            $output_css .= ".category-button.np-cat-". esc_attr( $cat_id ) ." a:hover { background: ". esc_attr( $cat_hover_color ) ."}\n";

            $output_css .= ".np-block-title .np-cat-". esc_attr( $cat_id ) ." { color: ". esc_attr( $cat_color ) ."}\n";
            
            $output_css .= ".np-block-title .np-cat-". esc_attr( $cat_id ) .":after { background: ". esc_attr( $cat_color ) ." !important}\n";
        }
    }
    
    if ( $news_portal_site_title_option == 'false' ) {
        $output_css .=".site-title, .site-description {
                    position: absolute;
                    clip: rect(1px, 1px, 1px, 1px);
                }\n";
    } else {
        $output_css .=".site-title a, .site-description {
                    color:". esc_attr( $news_portal_site_title_color ) .";
                }\n";
    }
    
        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.navigation .nav-links a:hover,.bttn:hover,button,input[type='button']:hover,input[type='reset']:hover,input[type='submit']:hover,.widget_search .search-submit,.edit-link .post-edit-link,.reply .comment-reply-link,.np-top-header-wrap,.np-header-menu-wrapper,#site-navigation ul.sub-menu, #site-navigation ul.children,.np-header-menu-wrapper::before, .np-header-menu-wrapper::after,.np-header-search-wrapper .search-form-main .search-submit,.news_portal_slider .lSAction > a:hover,.news_portal_default_tabbed ul.widget-tabs li,.np-full-width-title-nav-wrap .carousel-nav-action .carousel-controls:hover,.news_portal_social_media .social-link a,.np-archive-more .np-button:hover,.error404 .page-title,#np-scrollup,.news_portal_featured_slider .slider-posts .lSAction > a:hover,div.wpforms-container-full .wpforms-form input[type='submit'], div.wpforms-container-full .wpforms-form button[type='submit'],div.wpforms-container-full .wpforms-form .wpforms-page-button,div.wpforms-container-full .wpforms-form input[type='submit']:hover, div.wpforms-container-full .wpforms-form button[type='submit']:hover,div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,.np-block-title .np-title:after,.np-block-title:after, .widget-title::after, .page-header .page-title::after, .np-related-title::after { background: ". esc_attr( $trend_portal_theme_color ) ."}\n";

        $output_css .= ".home .np-home-icon a, .np-home-icon a:hover,#site-navigation ul li:hover > a, #site-navigation ul li.current-menu-item > a,#site-navigation ul li.current_page_item > a,#site-navigation ul li.current-menu-ancestor > a,.news_portal_default_tabbed ul.widget-tabs li.ui-tabs-active, .news_portal_default_tabbed ul.widget-tabs li:hover { background: ". esc_attr( $trend_portal_theme_hover_color ) ."}\n";

        $output_css .= ".np-header-menu-block-wrap::before, .np-header-menu-block-wrap::after { border-right-color: ". esc_attr( $trend_portal_theme_color ) ."}\n";

        $output_css .= "a,a:hover,a:focus,a:active,.widget a:hover,.widget a:hover::before,.widget li:hover::before,.entry-footer a:hover,.comment-author .fn .url:hover,#cancel-comment-reply-link,#cancel-comment-reply-link:before,.logged-in-as a,.np-slide-content-wrap .post-title a:hover,#top-footer .widget a:hover,#top-footer .widget a:hover:before,#top-footer .widget li:hover:before,.news_portal_featured_posts .np-single-post .np-post-content .np-post-title a:hover,.news_portal_fullwidth_posts .np-single-post .np-post-title a:hover,.news_portal_block_posts .layout3 .np-primary-block-wrap .np-single-post .np-post-title a:hover,.news_portal_featured_posts .layout2 .np-single-post-wrap .np-post-content .np-post-title a:hover,.np-block-title,.widget-title,.page-header .page-title,.np-related-title,.np-post-meta span:hover,.np-post-meta span a:hover,.news_portal_featured_posts .layout2 .np-single-post-wrap .np-post-content .np-post-meta span:hover,.news_portal_featured_posts .layout2 .np-single-post-wrap .np-post-content .np-post-meta span a:hover,.np-post-title.small-size a:hover,#footer-navigation ul li a:hover,.entry-title a:hover,.entry-meta span a:hover,.entry-meta span:hover,.np-post-meta span:hover, .np-post-meta span a:hover, .news_portal_featured_posts .np-single-post-wrap .np-post-content .np-post-meta span:hover, .news_portal_featured_posts .np-single-post-wrap .np-post-content .np-post-meta span a:hover,.news_portal_featured_slider .featured-posts .np-single-post .np-post-content .np-post-title a:hover { color: ". esc_attr( $trend_portal_theme_color ) ."}\n";

        $output_css .= ".navigation .nav-links a,.bttn,button,input[type='button'],input[type='reset'],input[type='submit'],.widget_search .search-submit,.np-archive-more .np-button:hover { border-color: ". esc_attr( $trend_portal_theme_color ) ."}\n";

        $output_css .= ".comment-list .comment-body,.np-header-search-wrapper .search-form-main { border-top-color: ". esc_attr( $trend_portal_theme_color ) ."}\n";
        
        $output_css .= ".np-header-search-wrapper .search-form-main:before { border-bottom-color: ". esc_attr( $trend_portal_theme_color ) ."}\n";

        $output_css .= "@media (max-width: 768px) { #site-navigation,.main-small-navigation li.current-menu-item > .sub-toggle i { background: ". esc_attr( $trend_portal_theme_color ) ." !important } }\n";
        
        $output_css .= "div.wpforms-container-full .wpforms-form button[type='submit'] { background: ". esc_attr( $trend_portal_theme_color ) ." !important }\n";
        
    $refine_output_css = news_portal_css_strip_whitespace( $output_css );

    wp_add_inline_style( 'trend-portal-style', $refine_output_css );
    
}

/**
 * Managed the header bg image
 *
 */
function trend_portal_header_start() {
    $trend_portal_header_bg_image = get_theme_mod( 'trend_portal_header_bg_image', '' );
    $header_style = '';
    $header_class = '';
    if( !empty( $trend_portal_header_bg_image ) ) {
        $header_style = 'style="background-image:url('. esc_url( $trend_portal_header_bg_image ) .')"';
        $header_class = 'has-bg-image';
    }

    echo '<div class="header-background-image '. esc_attr( $header_class ) .'" role="banner" '. $header_style .'>';
}

remove_action( 'news_portal_header_section', 'news_portal_header_section_start', 5 );
add_action( 'news_portal_header_section', 'trend_portal_header_start', 6 );
