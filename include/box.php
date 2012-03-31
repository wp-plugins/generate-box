<?php
/*
	plugin functions
*/

/** Add Generate Box after header **/
add_action( 'genesis_after_header', 'display_generate_box', 15 );

function display_generate_box() {
	
	// set blobals
	global $generatebox_plugin_url;
		
		// form starts here
		$generate_box = '<div id="generate_box"><div class="wrap">';
		$generate_box .= '<div class="widget widget_text">';

		// display box title
		if ( genesis_get_option( 'generatebox_title', generatebox_SETTINGS_FIELD ) ) {
        	$generate_box .= '<h4>'.genesis_get_option( 'generatebox_title', generatebox_SETTINGS_FIELD ).'</h4>';
        }
			
        // display box text
		if ( genesis_get_option( 'generatebox_text', generatebox_SETTINGS_FIELD ) ) {
        	$generate_box .= '<p>'.genesis_get_option( 'generatebox_text', generatebox_SETTINGS_FIELD ).'</p>';
        }
			
        // display box image
		if ( genesis_get_option( 'generatebox_img', generatebox_SETTINGS_FIELD ) ) {
			$generate_box .= '<p><img src="'.esc_attr(genesis_get_option( 'generatebox_img', generatebox_SETTINGS_FIELD )).'"
							 alt="" class="alignright" /></p>';
        }
		
		// echo our form
		if ( genesis_get_option( 'generatebox_form', generatebox_SETTINGS_FIELD ) ) {
			$generate_box .= genesis_get_option( 'generatebox_form', generatebox_SETTINGS_FIELD );
		}
		
		$generate_box .= '</div>';
		$generate_box .= '</div><!-- end .wrap --></div><!-- end #generate-box -->';
		
		// form ends here
		
		
		/* display
		*  the form
		**********/
		
		// check if enabled everywhere
		if ( genesis_get_option( 'generatebox_everywhere_enable', generatebox_SETTINGS_FIELD ) ) {
			echo $generate_box;
		}
		else
		{		// if not then, check other display options
		
			// check if enabled on front page
			if ( genesis_get_option( 'generatebox_front_enable', generatebox_SETTINGS_FIELD ) && is_front_page() ) {
				echo $generate_box;
			}
			
			// check if enabled on front page
			if ( genesis_get_option( 'generatebox_home_enable', generatebox_SETTINGS_FIELD ) && is_home() ) {
				echo $generate_box;
			}
			
			// check if enabled on single posts
			if ( genesis_get_option( 'generatebox_single_enable', generatebox_SETTINGS_FIELD ) && is_single() ) {
				echo $generate_box;
			}
		}

}

?>