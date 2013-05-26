<?php

/**

 * CT Settings Theme Settings

 *

 * @package      CT Settings

 * @author       Thomas Griffin <http://thomasgriffinmedia.com/>

 * @copyright    Copyright (c) 2011, Thomas Griffin

 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License

 *

 */


/* Define our constants
------------------------------------------------------------ */
define( 'generatebox_SETTINGS_FIELD', 'ct-settings' );


/* Setup default options
------------------------------------------------------------ */


function generatebox_default_theme_options() {

	$options = array(
		'generatebox_color_scheme' 			=> 'blue',
		'generatebox_title' 				=> '',
		'generatebox_img' 					=> '',
		'generatebox_text'					=> '',
		
		'generatebox_form' 					=> '',
		
		'generatebox_front_enable'			=> 0,
		'generatebox_home_enable'			=> 0,
		'generatebox_single_enable'			=> 0,
		'generatebox_everywhere_enable'		=> 0
	);

	return apply_filters( 'generatebox_default_theme_options', $options );
}


/* Sanitize any inputs
------------------------------------------------------------ */
add_action( 'genesis_settings_sanitizer_init', 'generatebox_sanitize_inputs' );


function generatebox_sanitize_inputs() {

    genesis_add_option_filter( 'one_zero', generatebox_SETTINGS_FIELD, array( 

			'generatebox_front_enable',
			'generatebox_home_enable',
			'generatebox_single_enable',
			'generatebox_everywhere_enable'

		) );

	genesis_add_option_filter( 'no_html', GENESIS_SEO_SETTINGS_FIELD,

		array(

			'generatebox_color_scheme',
			'generatebox_title',
			'generatebox_img',
			'generatebox_text',
			
		) );
}


/* Register our settings and add the options to the database
------------------------------------------------------------ */


add_action( 'admin_init', 'generatebox_register_settings' );

function generatebox_register_settings() {

	register_setting( generatebox_SETTINGS_FIELD, generatebox_SETTINGS_FIELD );

	add_option( generatebox_SETTINGS_FIELD, generatebox_default_theme_options() );

	

	if ( genesis_get_option( 'reset', generatebox_SETTINGS_FIELD ) ) {

		update_option( generatebox_SETTINGS_FIELD, generatebox_default_theme_options() );

		genesis_admin_redirect( generatebox_SETTINGS_FIELD, array( 'reset' => 'true' ) );

		exit;

	}

}


/* Admin notices for when options are saved/reset
------------------------------------------------------------ */


add_action( 'admin_notices', 'generatebox_theme_settings_notice' );


function generatebox_theme_settings_notice() {

	if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != generatebox_SETTINGS_FIELD )

		return;



	if ( isset( $_REQUEST['reset'] ) && 'true' == $_REQUEST['reset'] )

		echo '<div id="message" class="updated"><p><strong>' . __( 'Settings reset.', 'genesis' ) . '</strong></p></div>';

	elseif ( isset( $_REQUEST['settings-updated'] ) && 'true' == $_REQUEST['settings-updated'] )

		echo '<div id="message" class="updated"><p><strong>' . __( 'Settings saved.', 'genesis' ) . '</strong></p></div>';

}



/* Register our theme options page
------------------------------------------------------------ */

add_action( 'admin_menu', 'generatebox_theme_options' );


function generatebox_theme_options() {

	global $_generatebox_settings_pagehook;

	$_generatebox_settings_pagehook = add_submenu_page( 'genesis', 'Generate Box Settings', 'Generate Box', 'edit_theme_options', generatebox_SETTINGS_FIELD, 'generatebox_theme_options_page' );
	
	add_action( 'load-'.$_generatebox_settings_pagehook, 'generatebox_settings_scripts' );
	add_action( 'load-'.$_generatebox_settings_pagehook, 'generatebox_settings_boxes' );
}


/* Setup our scripts
------------------------------------------------------------ */


function generatebox_settings_scripts() {	

	global $_generatebox_settings_pagehook;

	wp_enqueue_script( 'common' );

	wp_enqueue_script( 'wp-lists' );

	wp_enqueue_script( 'postbox' );

}


/* Setup our metaboxes
------------------------------------------------------------ */


function generatebox_settings_boxes() {

	global $_generatebox_settings_pagehook;

	add_meta_box( 'generatebox-general-box', __( 'Generate Box Settings', 'genesis' ), 'generatebox_metabox', $_generatebox_settings_pagehook, 'main' );
	
	add_meta_box( 'generatebox-email-box', __( 'Email Service Settings', 'genesis' ), 'generatebox_email_metabox', $_generatebox_settings_pagehook, 'main' );

	add_meta_box( 'generatebox-display-box', __( 'Display Settings', 'genesis' ), 'generatebox_display_metabox', $_generatebox_settings_pagehook, 'main' );
}


/* Add our custom post metabox for social sharing
------------------------------------------------------------ */

/** generatebox_metabox general options function
*****************************/
function generatebox_metabox() { ?>

	<p>

    	Select Color Scheme: 

        <select name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_color_scheme]" id="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_color_scheme]">

        	 <option value="red" <?php selected('red', esc_attr( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ))); ?>><?php _e("Red", 'genesis'); ?></option>

           <option value="blue" <?php selected('blue', esc_attr( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ))); ?>><?php _e("Blue", 'genesis'); ?></option>

           <option value="green" <?php selected('green', esc_attr( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ))); ?>><?php _e("Green", 'genesis'); ?></option>

           <option value="orange" <?php selected('orange', esc_attr( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ))); ?>><?php _e("Orange", 'genesis'); ?></option>

			

		</select>


           <?php echo 'You are using the <b>' . esc_attr( genesis_get_option( 'generatebox_color_scheme', generatebox_SETTINGS_FIELD ) ) . '</b> sceheme'; ?>

    </p> 	
    
    <p>Title:<br />

		<input type="text" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_title]"

		value="<?php echo esc_attr( genesis_get_option( 'generatebox_title', generatebox_SETTINGS_FIELD ) ); ?>" size="50" />

	</p>

    <p>Badge image URL:<br />

		<input type="text" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_img]"

		value="<?php echo esc_attr( genesis_get_option( 'generatebox_img', generatebox_SETTINGS_FIELD ) ); ?>" size="50" />

	</p>

	<p>Text:<br />

                        <textarea

                        rows="4"

                        cols="60"

                        name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_text]"

                        ><?php echo esc_attr( genesis_get_option( 'generatebox_text', generatebox_SETTINGS_FIELD ) ); ?></textarea>

	</p>

<?php }



/* email services settings
******************************/
function generatebox_email_metabox() { ?>
    
    <p>Form HTML code:<br />

                        <textarea

                        rows="4"

                        cols="60"

                        name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_form]"

                        ><?php echo genesis_get_option( 'generatebox_form', generatebox_SETTINGS_FIELD ); ?></textarea>

	</p>

<?php
}


/* display settings
******************************/
function generatebox_display_metabox() { ?>
	
    <p><?php _e( 'Enable and display Generate Box.', 'genesis' ); ?></p>
    
    <p>

    	<input type="checkbox" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_front_enable]" id="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_front_enable]" value="1" <?php checked( 1, genesis_get_option( 'generatebox_front_enable', generatebox_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_front_enable]"><?php _e( 'Enable Generate Box on front page?', 'genesis' ); ?></label>

    </p>

    <p>

    	<input type="checkbox" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_home_enable]" id="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_home_enable]" value="1" <?php checked( 1, genesis_get_option( 'generatebox_home_enable', generatebox_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_home_enable]"><?php _e( 'Enable Generate Box on home page?', 'genesis' ); ?></label>

    </p>
    
    <p>

    	<input type="checkbox" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_single_enable]" id="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_single_enable]" value="1" <?php checked( 1, genesis_get_option( 'generatebox_single_enable', generatebox_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_single_enable]"><?php _e( 'Enable Generate Box on signle pages?', 'genesis' ); ?></label>

    </p>
    
    <p>

    	<input type="checkbox" name="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_everywhere_enable]" id="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_everywhere_enable]" value="1" <?php checked( 1, genesis_get_option( 'generatebox_everywhere_enable', generatebox_SETTINGS_FIELD ) ); ?> /> <label for="<?php echo generatebox_SETTINGS_FIELD; ?>[generatebox_everywhere_enable]"><?php _e( 'Enable Generate Box everywhere?', 'genesis' ); ?></label>

    </p>

<?php
}


/* Set the screen layout to one column
------------------------------------------------------------ */

add_filter( 'screen_layout_columns', 'generatebox_settings_layout_columns', 10, 2 );


function generatebox_settings_layout_columns( $columns, $screen ) {

	global $_generatebox_settings_pagehook;

	if ( $screen == $_generatebox_settings_pagehook ) {

		$columns[$_generatebox_settings_pagehook] = 1;

	}

	return $columns;

}


/* Build our theme options page
------------------------------------------------------------ */

function generatebox_theme_options_page() {

	global $_generatebox_settings_pagehook, $screen_layout_columns;

	$width = "width: 99%;";

	$hide2 = $hide3 = " display: none;";

	?>	

	

	<div id="generatebox" class="wrap genesis-metaboxes">

		<form method="post" action="options.php">

		

			<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

			<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

			<?php settings_fields( generatebox_SETTINGS_FIELD ); ?>

			

			<?php screen_icon( 'options-general' ); ?>

			

			<h2><?php _e( 'Generate Box Settings', 'genesis' ); ?></h2>
            
            <p class="top-buttons"><input type="submit" class="button button-primary" value="<?php _e( 'Save Settings', 'genesis' ) ?>" /></p>

			<div class="metabox-holder">

				<div class="postbox-container" style="<?php echo $width; ?>">

					<?php do_meta_boxes( $_generatebox_settings_pagehook, 'main', null ); ?>

				</div>

			</div>


		</form>

	</div>

	<script type="text/javascript">

		//<![CDATA[

		jQuery(document).ready( function($) {

			// close postboxes that should be closed

			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');

			// postboxes setup

			postboxes.add_postbox_toggles('<?php echo $_generatebox_settings_pagehook; ?>');

		});

		//]]>

	</script>



<?php }