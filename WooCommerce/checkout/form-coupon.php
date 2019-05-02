<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

if ( empty( WC()->cart->applied_coupons ) ) {
	$info_message = '<a href="#" class="showcoupon">' . esc_html__( 'Coupon apply', 'puca' ) . '<i class="icons icon-arrow-down"></i></a>';
	wc_print_notice( $info_message, 'notice' );
}
?>

<form class="checkout_coupon expresss-one-page-coupen" method="post" style="display:none" >

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'puca' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="coupon_button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'puca' ); ?>" />
	</p>

	<div class="clear"></div>
</form>
