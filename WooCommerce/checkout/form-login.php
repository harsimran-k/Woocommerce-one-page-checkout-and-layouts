<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;


if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

$info_message = ' <a href="#" class="showlogin">'.apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'puca' ) ).'<span>' . esc_html__( 'Login to system', 'puca' ) . '</span><i class="icons icon-arrow-down"></i></a>';
//wc_print_notice( $info_message, 'notice' );

/*  woocommerce_login_form(
	array(
		'message'  => esc_html__( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'puca' ),
		'redirect' => wc_get_page_permalink( 'checkout' ),
		'hidden'   => true,
	)
);  */
?>
<div class="registered_users">
       <h3 style="font-size: 14px;"><?php esc_html_e( 'HAVE AN ACCOUNT ? LOGIN HERE', 'woocommerce' ); ?></h3>
		<form class="woocommerce-form woocommerce-form-login login onepage-checkout" method="post">
                  
                    <div id="onestepcheckout-login-table">
					<div class="login_popup field">
                        <div class="input-box">
                           <input type="text" placeholder="<?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        </div>
                        <div class="input-box">
                           <input placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
                        </div>
					</div>
 
                        <div class="input-box input-button">
                           <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-Button button" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
                        </div>
							<?php do_action( 'woocommerce_login_form_end' ); ?>
                     </div>
                </form>
				<p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
			</p>
				</div>