<?php
/**
* Plugin Name: Range Slider for Contact Form 7
* Description: This plugin allows create Range Slider for Contact Form 7 plugin.
* Version: 1.0.1
* Copyright: 2020
* Text Domain: range-slider-for-contact-form-7
* Domain Path: /languages 
*/

if (!defined('ABSPATH')) {
    die('-1');
}
if (!defined('OCCF7RS_PLUGIN_NAME')) {
    define('OCCF7CRS_PLUGIN_NAME', 'Range Slider for Contact Form 7');
}
if (!defined('OCCF7RS_PLUGIN_VERSION')) {
    define('OCCF7RS_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCCF7RS_PLUGIN_FILE')) {
    define('OCCF7RS_PLUGIN_FILE', __FILE__);
}
if (!defined('OCCF7RS_PLUGIN_DIR')) {
    define('OCCF7RS_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCCF7RS_BASE_NAME')) {
    define('OCCF7RS_BASE_NAME', plugin_basename(OCCF7RS_PLUGIN_FILE));
}
if (!defined('OCCF7RS_DOMAIN')) {
    define('OCCF7CRS_DOMAIN', 'range-slider-for-contact-form-7');
}
if (!class_exists('OCCF7RS')) {
  class OCCF7RS {
    protected static $OCCF7RS_instance;
   	//Load all includes files
  	function includes() {
      include_once('admin/rangeslider.php');
    }

  	function init() {
      add_action( 'admin_init', array($this, 'OCCF7RS_load_plugin'), 11 );
      add_action( 'admin_enqueue_scripts', array($this, 'OCCF7RS_load_admin'));
      add_action( 'wp_enqueue_scripts',  array($this, 'OCCF7RS_load_script_style'));
      add_filter( 'plugin_row_meta', array( $this, 'OCCF7RS_plugin_row_meta' ), 10, 2 );
    }

  	function OCCF7RS_load_plugin() {
      if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
        add_action( 'admin_notices', array($this,'OCCF7RS_install_error') );
      }
    }

  	function OCCF7RS_install_error() {
      deactivate_plugins( plugin_basename( __FILE__ ) );
        ?>
        <div class="error">
          <p>
            <?php _e( 'cf7 calculator plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.', OCCF7RS_DOMAIN ); ?>
          </p>
        </div>
        <?php
  	}

    function OCCF7RS_plugin_row_meta( $links, $file ) {
      if ( OCCF7RS_BASE_NAME === $file ) {
        $row_meta = array(
          'rating'    =>  '<a href="https://oceanwebguru.com/range-slider-for-contact-form-7/" target="_blank">Documentation</a> | <a href="https://oceanwebguru.com/contact-us/" target="_blank">Support</a> | <a href="https://wordpress.org/support/plugin/range-slider-for-contact-form-7/reviews/?filter=5" target="_blank"><img src="'.OCCF7RS_PLUGIN_DIR.'/includes/images/star.png" class="OCCF7RS_rating_div"></a>',
        );
        return array_merge( $links, $row_meta );
      }
      return (array) $links;
    }

    //Add CSS on Backend
    function OCCF7RS_load_admin() {
      wp_enqueue_style( 'OCCF7RS-style-css', OCCF7RS_PLUGIN_DIR . '/includes/css/admin_style.css', false, '2.0.0' );
    }

    //Add JS and CSS on Frontend
    function OCCF7RS_load_script_style() {
    	wp_enqueue_style( 'OCCF7RS-style-css', OCCF7RS_PLUGIN_DIR . '/includes/css/style.css', false, '2.0.0' );
    	wp_enqueue_script( 'jquery-ui' );
    	wp_enqueue_script( 'OCCF7RS-jquery-ui-js', OCCF7RS_PLUGIN_DIR .'/includes/js/jquery-ui.min.js', false, '2.0.0' );
      wp_enqueue_script( 'OCCF7RS-jquery-ui-touch-punch-js', OCCF7RS_PLUGIN_DIR .'/includes/js/jquery.ui.touch-punch.min.js', false, '2.0.0' );
    	wp_enqueue_style( 'OCCF7RS-jquery-ui-css', OCCF7RS_PLUGIN_DIR . '/includes/js/jquery-ui.min.css', false, '2.0.0' );
    	wp_enqueue_style( 'OCCF7RS-jquery-ui-slider-pips-css', OCCF7RS_PLUGIN_DIR .'/includes/js/jquery-ui-slider-pips.css', false, '2.0.0' ); 
    	wp_enqueue_script( 'OCCF7RS-jquery-ui-slider-pips-js', OCCF7RS_PLUGIN_DIR .'/includes/js/jquery-ui-slider-pips.js', false, '2.0.0' );
      wp_enqueue_script( 'OCCF7RS-front-js', OCCF7RS_PLUGIN_DIR . '/includes/js/front.js', array('jquery'), '2.0.0' );
    }
    
    //Plugin Rating
    public static function do_activation() {
      set_transient('occfrs-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function OCCF7RS_instance() {
      if (!isset(self::$OCCF7RS_instance)) {
        self::$OCCF7RS_instance = new self();
        self::$OCCF7RS_instance->init();
        self::$OCCF7RS_instance->includes();
      }
      return self::$OCCF7RS_instance;
    }

  }
  
  add_action('plugins_loaded', array('OCCF7RS', 'OCCF7RS_instance'));
  register_activation_hook(OCCF7RS_PLUGIN_FILE, array('OCCF7RS', 'do_activation'));
}


add_action( 'plugins_loaded', 'OCCF7RS_load_textdomain' );
function OCCF7RS_load_textdomain() {
    load_plugin_textdomain( 'woo-product-and-custom-post-type-dropdown-cf7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
function OCCF7RS_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'woo-product-and-custom-post-type-dropdown-cf7' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
        $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
        $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'OCCF7RS_load_my_own_textdomain', 10, 2 );