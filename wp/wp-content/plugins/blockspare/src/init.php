<?php
    
    //Exit if directly acess
    defined('ABSPATH') or die('No script kiddies please!');
    
    
    function blockspare_blocks_block_assets()
    {
        
        $min = (SCRIPT_DEBUG == true) ? '' : '.min';
        wp_enqueue_style(
            'blockspare-blocks-fontawesome-front',
            plugins_url('src/assets/fontawesome/css/all.css', dirname(__FILE__)),
            array(),
            filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.css')
        );
        // Load the compiled styles.
        wp_enqueue_style(
            'blockspare-frontend-block-style-css',
            plugins_url('dist/blocks.style.build.css', dirname(__FILE__)),
            array()
        );


        wp_enqueue_style('slick', BLOCKSPARE_PLUGIN_URL . 'src/assets/slick/css/slick.css', array(), '', 'all');
        wp_enqueue_script('slick-js', BLOCKSPARE_PLUGIN_URL . 'src/assets/slick/js/slick.js', array('jquery'), '', true);
        wp_register_script('countup', BLOCKSPARE_PLUGIN_URL . 'src/assets/js/countup/jquery.counterup' . $min . '.js', array('waypoint'), true);
        
        wp_enqueue_script('waypoint', BLOCKSPARE_PLUGIN_URL . 'src/assets/js/countup/waypoints.min.js', array('jquery'), '', true);
        wp_enqueue_script('jquery-masonry');
        
        wp_enqueue_script('blockspare-script', BLOCKSPARE_PLUGIN_URL . 'src/assets/js/frontend.js', array('jquery', 'waypoint', 'countup'), '', true);
        
    }
    
    add_action('init', 'blockspare_blocks_block_assets');
    
    if (!function_exists('blockspare_create_block')) {
        
        
        function blockspare_create_block()
        {
    
            $min = (SCRIPT_DEBUG == true) ? '' : '.min';
            // Register our block script with WordPress
            wp_enqueue_script(
                'blockspare-blocks-block-js',
                BLOCKSPARE_PLUGIN_URL . 'dist/blocks.build.js',
                array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor')
            );
    
    
            // Register our block's editor-specific CSS
            if (is_admin()) :
                wp_enqueue_style(
                    'blockspare-block-edit-style',
                    BLOCKSPARE_PLUGIN_URL . 'dist/blocks.editor.build.css',
                    array('wp-edit-blocks')
                );
            endif;
    
            wp_enqueue_style(
                'blockspare-blocks-fontawesome',
                plugins_url('src/assets/fontawesome/css/all.css', dirname(__FILE__)),
                array(),
                filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.css')
            );
    
            
            $blockspare_priview_img_url = BLOCKSPARE_PLUGIN_URL . 'dist/images/blockspare-placeholder-img.jpg';
            $blockspare_food_img_url = BLOCKSPARE_PLUGIN_URL . 'src/assets/blockspare-placeholder-img-square.jpg';
    
    
            wp_localize_script(
                'blockspare-blocks-block-js',
                'blockspare_globals',
                array(
                    'srcUrl' => untrailingslashit(plugins_url('/', BLOCKSPARE_BASE_DIR . '/dist/')),
                    'rest_url' => esc_url(rest_url()),
                    'img' => $blockspare_priview_img_url,
                    'menu_img_url' => $blockspare_food_img_url
                )
            );
        }
        
        add_action('enqueue_block_editor_assets', 'blockspare_create_block');
        
    }
    
    
    
    
    function blockspare_blocks_loader()
    {
        
        //Load Gutenberg Block php Files
        include(BLOCKSPARE_PLUGIN_DIR . '/src/blocks/social-sharing/index.php');
        include(BLOCKSPARE_PLUGIN_DIR . '/src/blocks/latest-posts-grid/index.php');
        include(BLOCKSPARE_PLUGIN_DIR . '/src/blocks/latest-posts-list/index.php');
        
        
    }
    
    add_action('plugins_loaded', 'blockspare_blocks_loader');
    
    
    add_action('admin_enqueue_scripts', 'blockspare_enqueue_admin_style');
    
    function blockspare_enqueue_admin_style()
    {
        wp_enqueue_style('slick', BLOCKSPARE_PLUGIN_URL . 'src/assets/slick/css/slick.css', array(), '', 'all');
    }
    
    
   