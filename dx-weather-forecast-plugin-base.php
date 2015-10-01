<?php
/**
 * Plugin Name: DX Weather Forecast
 * Description: A plugin get weater for cityes
 * Author: Ivan Mudrik
 * Author URI: http://ivan-mudrik.kl.com.ua/
 * Version: 1.0
 * Text Domain: DX-Weather-Forecast
 * License: GPL2
 */


define( 'WFP_VERSION', '1.0' );
define( 'WFP_PATH', dirname( __FILE__ ) );
define( 'WFP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'WFP_FOLDER', basename( WFP_PATH ) );
define( 'WFP_URL', plugins_url() . '/' . WFP_FOLDER );
define( 'WFP_URL_INCLUDES', WFP_URL . '/inc' );


/**
 * 
 * The plugin base class - the root of all WP goods!
 * 
 * @author meatman
 *
 */
class DX_Weather_Forecast_Plugin_Base {
        public $WF_Plugin;
        /**
	 * Assign everything as a call from within the constructor
	 */
	public function __construct() {
            // add script and style calls the WP way 
            // it's a bit confusing as styles are called with a scripts hook
            // @blamenacin - http://make.wordpress.org/core/2011/12/12/use-wp_enqueue_scripts-not-wp_print_styles-to-enqueue-scripts-and-styles-for-the-frontend/
            add_action( 'wp_enqueue_scripts', array( $this, 'wf_add_CSS' ) );
            // add scripts and styles only available in admin
            add_action( 'admin_enqueue_scripts', array( $this, 'wf_add_admin_CSS' ) );
            // register admin pages for the plugin
            add_action( 'admin_menu', array( $this, 'wf_admin_pages_callback' ) );
            // Register activation and deactivation hooks
            register_activation_hook( __FILE__, 'dx_on_activate_callback' );
            register_deactivation_hook( __FILE__, 'dx_on_deactivate_callback' );
            // Translation-ready
            add_action( 'plugins_loaded', array( $this, 'wf_add_textdomain' ) );
            // Add earlier execution as it needs to occur before admin page display
            add_action( 'admin_init', array( $this, 'wf_register_settings' ), 5 );
            // Add a sample shortcode
            add_action( 'init', array( $this, 'wf_city_shortcode' ) );
            //find_city handle
            add_action( 'init', array( $this, 'init_find_city' ) );	
	}		
	/**
	 * Add CSS styles
	 *  Can be used to style front end 
	 */
	public function wf_add_CSS() {
            wp_register_style( 'samplestyle', plugins_url( '/css/samplestyle.css', __FILE__ ), array(), '1.0', 'screen' );
            wp_enqueue_style( 'samplestyle' );
	}	
	/**
	 * Add admin CSS styles - available only on admin
	 */
	public function wf_add_admin_CSS( $hook ) {
            wp_register_style( 'samplestyle-admin', plugins_url( '/css/samplestyle-admin.css', __FILE__ ), array(), '1.0', 'screen' );
            wp_enqueue_style( 'samplestyle-admin' );
            if( 'toplevel_page_dx-plugin-base' === $hook ) {
                    wp_register_style('dx_help_page',  plugins_url( '/help-page.css', __FILE__ ) );
                    wp_enqueue_style('dx_help_page');
            }
	}	
	/**
	 * Callback for registering option page
	 */
	public function wf_admin_pages_callback() {
            add_options_page( __( "Weather Forecast settings", 'DX-Weather-Forecast' ), __( "Weather forecast", 'DX-Weather-Forecast' ), 'edit_themes', 'weater-forecast', array($this, 'wf_option_page') );
	}	
	/**
	 * The content of the base option page
	 */
        public function wf_option_page() {
            include_once( WFP_PATH_INCLUDES . '/base-page-template.php' );
        }	
	/**
	 * Initialize the Settings class
	 * 
	 * Register a settings section with a field for a secure WordPress admin option creation.
	 * 
	 */
	public function wf_register_settings() {
		require_once( WFP_PATH . '/dx-weather-forecast-settings.class.php' );
		new DX_Weather_Forecast_Settings();
	}
        /*
         * Initial method on user search of city
         */
	public function init_find_city() {
            $find_city = filter_input( INPUT_POST, 'find_city', FILTER_SANITIZE_SPECIAL_CHARS );
            if( isset( $find_city ) ){
                $find_city_nonce = filter_input( INPUT_POST, 'find_city_nonce', FILTER_SANITIZE_SPECIAL_CHARS );
                if( wp_verify_nonce( $find_city_nonce, 'find_city' ) ){
                    $result = $this->get_coord( $find_city );
                    if( isset( $result->results ) ) {
                        $this->WF_Plugin = $result;
                    }
                }
            }
        }
        /*
         * get ccordinates using google map api 
         */
        public function get_coord( $search ) {
            $responce = wp_remote_get( 'http://maps.google.com/maps/api/geocode/json?address='.urlencode( $search ) .'&sensor=false' );
            if ( !is_wp_error( $responce ) ){                
                $body = json_decode( $responce['body'] );
                return $body;
            } else {
                return __( 'Error ocured!', 'DX-Weather-Forecast');
            }
        }
	/**
	 * First parameter is the shortcode name, would be used like: [wf_shortcode]	 
	 */
	public function wf_city_shortcode() {
		add_shortcode( 'wf_shortcode', array( $this, 'wf_shortcode_body' ) );
	}	
	/**
	 * Returns the content of the sample shortcode, like [wf_shortcode]
	 * @param array $attr arguments passed to array, like [wf_shortcode city="London" coord="0,0"]
	 */
	public function wf_shortcode_body( $attr, $content = null ) {
            $display_kelvins = get_option( 'wf_setting' );
            switch( true ) {
                case ( isset( $attr['city'] ) ):
                    $weather = $this->get_city( 'q='.$attr['city'] );
                    if( isset( $weather->name ) ) {
                        include WFP_PATH_INCLUDES . '/weather-template.php';
                        return ob_get_clean();
                    } else {
                        return $weather;
                    }
                    break;
                case ( isset( $attr['coord'] ) ):
                    list( $lat, $lon ) = explode( ',', $attr['coord'] );
                    $weather = $this->get_city( 'lat='.$lat.'&lon='.$lon );
                    if( isset( $weather->name ) ) {
                        include WFP_PATH_INCLUDES . '/weather-template.php';
                        return ob_get_clean();
                    } else {
                        return $weather;
                    }
                    break;
            }
	}
        /*
         * Get weater using  openweathermap.org api
         * Weater result are cashed with 30m intervar (weather doesn't change in it interval so mutch)
         */
	public function get_city( $param ) {
            $wf_all_cityes = get_option( 'wf_all_cityes' );            
            if(
                !isset( $wf_all_cityes[$param] ) ||  
                (
                    isset( $wf_all_cityes[$param] ) &&
                    (   
                        time() >= $wf_all_cityes[$param]['time']
                    )
                )
            ){
                $responce = wp_remote_get( 'http://api.openweathermap.org/data/2.5/weather?'.$param );
                if ( !is_wp_error( $responce ) ){                
                    $body = json_decode( $responce['body'] );
                    $wf_all_cityes[$param] = array(
                        'body'  => $body,
                        'time'  => mktime( date('H'), date('i')+30, date('s'), date("m"), date("d"), date("Y") )
                    );
                    update_option( 'wf_all_cityes', $wf_all_cityes );
                    return $body;
                } else {
                    return __( 'Error ocured!', 'DX-Weather-Forecast' );
                }
            } else {
                return $wf_all_cityes[$param]['body'];
            }
        }
	/**
	 * Add textdomain for plugin
	 */
	public function wf_add_textdomain() {
            load_plugin_textdomain( 'DX-Weather-Forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
}

/**
 * Register activation hook
 */
function dx_on_activate_callback() {
	// do something on activation
}
/**
 * Register deactivation hook
 */
function dx_on_deactivate_callback() {
	// do something when deactivated
}
// Initialize everything
$dx_weather_forecast_plugin_base = new DX_Weather_Forecast_Plugin_Base();
