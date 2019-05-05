<?php
/**
Plugin Name: Woocommerce one page checkout and layouts
Description: This plugin is designed to Combine Cart and Checkout process which gives users a faster checkout experience, with less interruption.
Author: coolcoders
Version: 1.1
License: GPL v2

Text Domain: cclw
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'CCLW_VERSION', '1.0' );
define('CCLW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('CCLW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    add_action( 'plugins_loaded','cclw_verify_woocommerce_installed');
      function cclw_verify_woocommerce_installed() {
        if ( ! class_exists( 'WooCommerce' )) {
            add_action( 'admin_notices','cclw_show_woocommerce_error_message');
        }
		
    }
     function cclw_show_woocommerce_error_message() {
        if ( current_user_can( 'activate_plugins' ) ) {
            $url = 'plugin-install.php?s=woocommerce&tab=search&type=term';
            $title = __( 'WooCommerce', 'woocommerce' );
            echo '<div class="error"><p>' . sprintf( esc_html( __( 'To begin using "%s" , please install the latest version of %s%s%s and add some product.', 'cclw' ) ), 'Custom Checkout Layouts WooCommerce', '<a href="' . esc_url( admin_url( $url ) ) . '" title="' . esc_attr( $title ) . '">', 'WooCommerce', '</a>' ) . '</p></div>';
        }
    }


	
	add_action( 'wp_enqueue_scripts', 'cclw_register_plugin_styles' );

	/**
	 * Register style sheet.
	 */
	function cclw_register_plugin_styles() {
		wp_register_style( 'custom-checkout-css', CCLW_PLUGIN_URL .'css/custom-checkout.css', array(), CCLW_VERSION );
		wp_enqueue_style( 'custom-checkout-css' );
	}
	
	/*menu setting page*/
	function cclw_register_express_checkout_menu_page(){
	
	add_submenu_page( 'woocommerce', 'Custom Checkout Layouts', 'Checkout Layouts', 'manage_options', 'custom_checkout_settings', 'cclw_express_checkout_settings'); 
	}
	add_action( 'admin_menu', 'cclw_register_express_checkout_menu_page' );

	/**
	* Display a custom menu page
	*/
	function cclw_express_checkout_settings(){
	//esc_html_e( 'Admin Page Test', 'cclw' );  
	    $filename = get_template_directory().'/woocommerce/checkout';
		$to_rename  = get_option('overide_checkout');
		
		if($to_rename != '' && $to_rename == 1)
		{
			$old_name  = $filename;
		    $new_name  = $filename.'-test';
			if  (file_exists($filename)) {
			rename( $old_name, $new_name);	
			}
		}
		else
		{
			$folder  = get_template_directory().'/woocommerce/checkout-test';
			$newfolder = get_template_directory().'/woocommerce/checkout';
			if  (file_exists($folder)) {
			rename( $folder, $newfolder);	
			}
		
		}
		
			
		?>
		 <div class="wrap">
	    <h1>Custom checkout for woocommerce settings</h1>
	    <form method="post" action="options.php">
	        <?php
	            settings_fields("section");
	            do_settings_sections("theme-options");      
	            submit_button(); 
	        ?>          
	    </form>
		</div>
		<?php
		
		
	}
	/**setting section**/
	function cclw_rename_checkout_theme()
	{
		?>
			<input type="checkbox" name="overide_checkout" value="1" <?php checked(1, get_option('overide_checkout'), true); ?> /> 
		<?php
		
	}


	function cclw_display_checkout_panel_fields()
	{
		add_settings_section("section", "All Settings", null, "theme-options");
		add_settings_field("overide_checkout", "Check to overide  theme's default checkout page", "cclw_rename_checkout_theme", "theme-options", "section");

		
		register_setting("section", "overide_checkout");
	}

   add_action("admin_init", "cclw_display_checkout_panel_fields");
	/*overide woocommerce  template folder*/
	add_filter( 'woocommerce_locate_template', 'cclw_adon_plugin_template', 1, 3 );
   function cclw_adon_plugin_template( $template, $template_name, $template_path ) {
     global $woocommerce;
     $_template = $template;
     if ( ! $template_path ) 
        $template_path = $woocommerce->template_url;
 
     $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/woocommerce/';
 
   
    $template = locate_template(
    array(
      $template_path . $template_name,
      $template_name
    )
   );
 
   if( ! $template && file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;
 
   if ( ! $template )
    $template = $_template;

   return $template;
  }
  
   /*set product quantity*/ 
		add_filter ('woocommerce_checkout_cart_item_quantity','cclw_add_quantity', 10, 2 );
	   
		 function cclw_add_quantity( $cart_item, $cart_item_key ) {
				   $product_quantity= '';
				   return $product_quantity;
				}
				
		/**add some footer js*/		
		add_action( 'wp_footer','cclw_add_js');
     	
        function cclw_add_js(){
    	     
            if (  is_checkout() ) {
				
            ?>
                <script type="text/javascript">
					
                    <?php  $admin_url = get_admin_url(); ?>
					jQuery("form.checkout #order_review").on("change", "input.qty", function(){
                       
                        var data = {
                    		action: 'cclw_update_order_review',
                    		security: wc_checkout_params.update_order_review_nonce,
                    		post_data: jQuery( 'form.checkout' ).serialize()
                    	};
						
                    	jQuery.post( '<?php echo $admin_url; ?>' + 'admin-ajax.php', data, function( response )
                		{
                            jQuery( 'body' ).trigger( 'update_checkout' );
						});
                    });
                </script>
             <?php  
             }
        }
        add_action( 'init','cclw_load_ajax' );
        function cclw_load_ajax() {
        
            if ( !is_user_logged_in() ){
                add_action( 'wp_ajax_nopriv_cclw_update_order_review','cclw_update_order_review');
            } else{
                add_action( 'wp_ajax_cclw_update_order_review','cclw_update_order_review');
            }
        
        }
        
        function cclw_update_order_review() {
             
            $values = array();
            parse_str($_POST['post_data'], $values);
            $cart = $values['cart'];
            foreach ( $cart as $cart_key => $cart_value ){
                WC()->cart->set_quantity( $cart_key, $cart_value['qty'], false );
                WC()->cart->calculate_totals();
                woocommerce_cart_totals();
            }
            exit;
        }
		function cclw_myplugin_deactivate() {
			update_option( 'overide_checkout', 0 );
			$filename = get_template_directory().'/woocommerce/checkout-test';
			$new_name  =  get_template_directory().'/woocommerce/checkout';
					if  (file_exists($filename)) {
					rename( $filename, $new_name);	
					}
		}

		register_deactivation_hook( __FILE__, 'cclw_myplugin_deactivate' );

