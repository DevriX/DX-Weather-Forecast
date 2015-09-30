<?php

class WC_Plugin_Settings {
	
	private $wc_setting;
	/**
	 * Construct me
	 */
	public function __construct() {
		$this->wc_setting = get_option( 'wc_setting', '' );
		
		// register the checkbox
		add_action('admin_init', array( $this, 'register_settings' ) );
	}
		
	/**
	 * Setup the settings
	 * 
	 * Add a single checkbox setting for Active/Inactive and a text field 
	 * just for the sake of our demo
	 * 
	 */
	public function register_settings() {
		register_setting( 'wc_setting', 'wc_setting', array( $this, 'wc_validate_settings' ) );
		
		add_settings_section(
			'wc_settings_section',         // ID used to identify this section and with which to register options
			__( "Google maps API key", 'weater-cityes-plugin' ),                  // Title to be displayed on the administration page
			array($this, 'wc_settings_callback'), // Callback used to render the description of the section
			'weater-cityes'                           // Page on which to add this section of options
		);
		
		add_settings_field(
			'wc_api_key',                      // ID used to identify the field throughout the theme
			__( "WC API key: ", 'weater-cityes-plugin' ),                           // The label to the left of the option interface element
			array( $this, 'wc_api_key_callback' ),   // The name of the function responsible for rendering the option interface
			'weater-cityes',                          // The page on which this option will be displayed
			'wc_settings_section'         // The name of the section to which this field belongs
		);
                add_settings_field(
			'dx_opt_in',                      // ID used to identify the field throughout the theme
			__( "Display Kelvins: ", 'weater-cityes-plugin' ),                           // The label to the left of the option interface element
			array( $this, 'wc_opt_in_callback' ),   // The name of the function responsible for rendering the option interface
			'weater-cityes',                          // The page on which this option will be displayed
			'wc_settings_section'         // The name of the section to which this field belongs
		);
	}
	
	public function wc_settings_callback() {
		echo _e( "Enter API key", 'dxbase' );
	}
	
	public function wc_api_key_callback() {
		$api_key = (isset($this->wc_setting['wc_api_key']))?$this->wc_setting['wc_api_key']:'';	
                ?>
                    <input type="text" id="wc_api_key" name="wc_setting[wc_api_key]" size="40" value="<?php echo $api_key?>"  />
                <?php
	}	
	public function wc_opt_in_callback() {
            $enabled = false;
            $out = ''; 
            $val = false;

            // check if checkbox is checked
            if(! empty( $this->wc_setting ) && isset ( $this->wc_setting['wc_opt_in'] ) ) {
                    $val = true;
            }

            if($val) {
                    $out = '<input type="checkbox" id="wc_opt_in" name="wc_setting[wc_opt_in]" CHECKED  />';
            } else {
                    $out = '<input type="checkbox" id="wc_opt_in" name="wc_setting[wc_opt_in]" />';
            }

            echo $out;
        }
	/**
	 * Validate Settings
	 * 
	 * Filter the submitted data as per your request and return the array
	 * 
	 * @param array $input
	 */
	public function wc_validate_settings( $input ) {
		
		return $input;
	}
}
