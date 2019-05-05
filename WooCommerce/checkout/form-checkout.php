<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

//do_action( 'woocommerce_before_checkout_form', $checkout );
woocommerce_checkout_login_form();


// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'puca' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url()); ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">
    <div class="express-one-page-checkout-main">
	<div class="grid-col-1 grid-col-checkout">
	
	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php //do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div id="customer_address_details">
			<?php do_action( 'woocommerce_checkout_billing' ); ?>
			<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			
		</div>

		<?php //do_action( 'woocommerce_checkout_after_customer_details' ); ?>

		
	<?php endif; ?>
	</div>
	
	<div class="grid-col-2 grid-col-checkout">
	    <?php if(WC()->cart->needs_shipping())
		{
			?>
	    <div class="express-one-page-order-shipping">
				
			<h3><?php esc_html_e( 'Shipping method', 'puca' ); ?></h3>
			<?php wc_cart_totals_shipping_html(); ?>
		</div>
		<?php
		}	
       	?>
		
		<div id="express-one-page-additional-fields" class="woocommerce-additional-fields">
		<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php _e( 'Additional information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
		</div>
		
		<div  id="express-one-page-order-payment" class="order-payment">
			<h3 id="order_payment_heading"><?php esc_html_e( 'Payment method', 'puca' ); ?></h3>
			<?php //do_action( 'woocommerce_checkout_after_order_review' ); ?>
			<?php include 'select-payment.php';?>
		</div>
	</div>
	<div class="grid-col-3 grid-col-checkout">
	    <div id="express-one-page-order-review" class="order-review">
			<h3><?php esc_html_e( 'Confirm your order', 'puca' ); ?></h3>
			<p class="subtitle">
			<?php
				$url = wc_get_cart_url();

				printf( wp_kses( __('Confirm the last time to your order, You can change it <a href="%1$s">here</a>', 'puca' ), array( 'a' => array('href' => array()) ) ), $url);
			
			?></p>
			<?php //do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				<?php  //woocommerce_checkout_payment(); ?>
			</div>
		</div>
		
	</div>
</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
