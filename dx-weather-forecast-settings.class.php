<?php

class DX_Weather_Forecast_Settings {	
	private $wf_setting;
	/**
	 * Construct me
	 */
	public function __construct() {
            $this->wf_setting = get_option( 'wf_setting', '' );
            // register the checkbox
            add_action('admin_init', array( $this, 'register_settings' ) );
	}		
	/**
	 * Setup the settings
	 * Add a single checkbox setting for Active/Inactive and a text field 
	 */
	public function register_settings() {
            register_setting( 'wf_setting', 'wf_setting', array( $this, 'wf_validate_settings' ) );
            add_settings_section(
                'wf_settings_section',         // ID used to identify this section and with which to register options
                __( "Google maps API key", 'DX-Weather-Forecast' ),                  // Title to be displayed on the administration page
                array($this, 'wf_settings_callback'), // Callback used to render the description of the section
                'weater-forecast'                           // Page on which to add this section of options
            );
            add_settings_field(
                'wf_api_key',                      // ID used to identify the field throughout the theme
                __( "WF API key: ", 'DX-Weather-Forecast' ),                           // The label to the left of the option interface element
                array( $this, 'wf_api_key_callback' ),   // The name of the function responsible for rendering the option interface
                'weater-forecast',                          // The page on which this option will be displayed
                'wf_settings_section'         // The name of the section to which this field belongs
            );
            add_settings_field(
                'wf_opt_in',                      // ID used to identify the field throughout the theme
                __( "Display Kelvins: ", 'DX-Weather-Forecast' ),                           // The label to the left of the option interface element
                array( $this, 'wf_opt_in_callback' ),   // The name of the function responsible for rendering the option interface
                'weater-forecast',                          // The page on which this option will be displayed
                'wf_settings_section'         // The name of the section to which this field belongs
            );
	}
        /*
         *  Displayed text of section in options page        
         */
	public function wf_settings_callback() {
            echo __( "Enter API key", 'DX-Weather-Forecast' );
	}
	/*
         * Callback to display the input for google maps api  
         */
	public function wf_api_key_callback() {
            $api_key = ( isset( $this->wf_setting['wf_api_key'] ) )?$this->wf_setting['wf_api_key']:'';	
            ?>
                <input type="text" id="wf_api_key" name="wf_setting[wf_api_key]" size="40" value="<?php echo $api_key?>"  />
            <?php
	}	
        /*
         * Callback to show checkbox for optional display kelvins tempratures   
         */
	public function wf_opt_in_callback() {
            $enabled = false;
            $out = ''; 
            $val = false;
            // check if checkbox is checked
            if(! empty( $this->wf_setting ) && isset ( $this->wf_setting['wf_opt_in'] ) ) {
                $val = true;
            }
            if( $val ) {
                ?>
                    <input type="checkbox" id="wf_opt_in" name="wf_setting[wf_opt_in]" CHECKED  />
                <?php
            } else {
                ?>
                    <input type="checkbox" id="wf_opt_in" name="wf_setting[wf_opt_in]" />
                <?php
            }
        }
	/**
	 * Validate Settings
	 * Filter the submitted data as per your request and return the array
	 * @param array $input
	 */
	public function wf_validate_settings( $input ) {		
            return $input;
	}
}
