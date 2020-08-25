<?php
    /*
     * Plugin Name:       Blockspare
     * Plugin URI:        https://profiles.wordpress.org/blockspare
     * Description:       Beautiful Page Building Blocks for WordPress
     * Version:           1.2.2
     * Author:            Blockspare
     * Author URI:        https://blockspare.com
     * Text Domain:       blockspare
     * License:           GPL-2.0+
     * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
     */
    
    defined('ABSPATH') or die('No script kiddies please!');  // prevent direct access
    
    if (!class_exists('Blockspare')) :
        
        class Blockspare
        {
            
            
            /**
             * Plugin version.
             *
             * @var string
             */
            const VERSION = '1.2.2';
            
            /**
             * Instance of this class.
             *
             * @var object
             */
            protected static $instance = null;
            
            
            /**
             * Initialize the plugin.
             */
            public function __construct()
            {
                
                /**
                 * Define global constants
                 **/
                defined('BLOCKSPARE_BASE_FILE') or define('BLOCKSPARE_BASE_FILE', __FILE__);
                defined('BLOCKSPARE_BASE_DIR') or define('BLOCKSPARE_BASE_DIR', dirname(BLOCKSPARE_BASE_FILE));
                defined('BLOCKSPARE_PLUGIN_URL') or define('BLOCKSPARE_PLUGIN_URL', plugin_dir_url(__FILE__));
                defined('BLOCKSPARE_PLUGIN_DIR') or define('BLOCKSPARE_PLUGIN_DIR', plugin_dir_path(__FILE__));

                defined( 'BLOCKSPARE_SHOW_PRO_NOTICES' ) || define( 'BLOCKSPARE_SHOW_PRO_NOTICES', true );
                defined( 'BLOCKSPARE_VERSION' ) || define( 'BLOCKSPARE_VERSION', '1.2.5' );

                /**
                 * Freemius.
                 */
                require_once(BLOCKSPARE_PLUGIN_DIR.'/freemius.php');

                /**
                 * Plugin init and welcome.
                 */
                include_once 'src/init.php';
                include_once 'src/welcome.php';
                include_once 'src/fonts.php';

            } // end of contructor
            
            /**
             * Return an instance of this class.
             *
             * @return object A single instance of this class.
             */
            public static function get_instance()
            {
                
                // If the single instance hasn't been set, set it now.
                if (null == self::$instance) {
                    self::$instance = new self;
                }
                return self::$instance;
            }
            
            
        }// end of the class
        
        add_action('plugins_loaded', array('Blockspare', 'get_instance'), 0);
    
    endif;
