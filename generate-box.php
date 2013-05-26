<?php
	/*
	Plugin Name: Generate Box
    Plugin URI: http://generatebox.com/
    Description: Add more functionality to Generate Child Theme and Genesis framework from StudioPress. 
    Version: 0.3
    Author: Hesham Zebida
    Author URI: http://zebida.com
    Last Version update : 26 May 2013
    */
	
    $generatebox_plugin_url = trailingslashit ( WP_PLUGIN_URL . '/' . dirname ( plugin_basename ( __FILE__ ) ) );
	$generatebox_plugin_version = '0.3';
	$shortname = "generatebox";
			
	// set up plugin actions
    //add_action( 'admin_init', 'generatebox_requires_wordpress_version' );				// check WP version 3.0+
	register_activation_hook( __FILE__, 'generatebox_activation_check' );				// genesis activation check
	add_action( 'admin_init', 'generatebox_admin_init' );								// to register admin styles and scripts
	add_action( 'genesis_init', 'generatebox_add_my_stylesheet', 15 );
	
	//requires
	require_once ('include/admin.php');												// load admin page
	require_once ('include/functions.php');											// load functions
	require_once ('include/box.php');												// load generate box

		
	// ------------------------------------------------------------------------
	// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               
	// ------------------------------------------------------------------------
	function generatebox_requires_wordpress_version() {
		global $wp_version;
		$plugin = plugin_basename( __FILE__ );
		$plugin_data = get_plugin_data( __FILE__, false );

		if ( version_compare($wp_version, "3.0", "<" ) ) {
			if( is_plugin_active($plugin) ) {
				deactivate_plugins( $plugin );
				wp_die( "'".$plugin_data['Name']."' requires WordPress 3.0 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
			}
		}
	}

	/**
 	* This function runs on plugin activation. It checks to make sure the required
 	* minimum Genesis version is installed. If not, it deactivates itself.
  	*/
	function generatebox_activation_check() {

			$latest = '1.7.1';

			$theme_info = get_theme_data( TEMPLATEPATH . '/style.css' );

			if ( 'genesis' != basename( TEMPLATEPATH ) ) {
	        deactivate_plugins( plugin_basename( __FILE__ ) ); /** Deactivate ourself */
			wp_die( sprintf( __( 'Sorry, you can\'t activate unless you have installed <a href="%s">Genesis</a>', 'apl' ), 'http://generatebox.com/recommends/wp-studiopress-theme/' ) );
			}

			if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) ); /** Deactivate ourself */
				wp_die( sprintf( __( 'Sorry, you cannot activate without <a href="%s">Genesis %s</a> or greater', 'apl' ), 'http://generatebox.com/recommends/wp-studiopress-theme/', $latest ) );
			}

	}


	// add admin init
	function generatebox_admin_init() {
		global $generatebox_plugin_url;
		$file_dir = get_bloginfo('template_directory');
		// *** add scripts here to admin page if required
		wp_enqueue_style ('generatebox-style', $generatebox_plugin_url."/style/admin_style.css");
	}
	/**
	*load our css
	* to the head
	*/
	function generatebox_add_my_stylesheet() {
        
		// set globals
		global $generatebox_plugin_url;
		
		// set generate box color
		if ( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ) ) {
			
			$generatebox_style = genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD );
		}
        else
        {
        	// set color to red by default
			$generatebox_style = 'red';
        
		}
		
		$generateStyleUrl = plugins_url('style/'. $generatebox_style .'.css', __FILE__); // Respects SSL, Style.css is relative to the current file
        $generateStyleFile = $generatebox_plugin_url. '/style/' . $generatebox_style . '.css';
      
		//if ( file_exists($myStyleFile) ) {

            	wp_register_style('generateStyleSheets', $generateStyleUrl);
            	wp_enqueue_style( 'generateStyleSheets');
        //}
    }
?>