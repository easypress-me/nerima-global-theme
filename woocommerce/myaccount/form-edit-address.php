<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
$current_user = wp_get_current_user();

if(!$load_address)
	echo '<script>location.href="/my-account/edit-address/billing/";</script>';

$page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); ?>
<style type="text/css">
	.dashboard-header { display: none; }
	.address-header { display: block; }
</style>
<div class="row">
	
	<div class="col-md-7 address-info">
		<?php //wc_get_template( 'myaccount/my-address.php' ); ?>

		<?php if($current_user->billing_address_1) : ?>
		<div class="row">
			<div class="col-md-7">
				
				<h3><?php echo $current_user->billing_address_1; ?></h3>
			</div>
			<div class="col-md-5">
				<p><?php 
					if($current_user->first_name) {
						echo $current_user->first_name; 
					}else {
						echo $current_user->billing_first_name; 
					}?>	
				</p>
				<p class="address"><?php echo $current_user->billing_address_1; ?><br><?php echo $current_user->billing_address_2; ?></p>
				<p class="phone"><?php echo $current_user->billing_phone_kr; ?></p>
				
			</div>
		</div>
		<?php else: ?>
			<h3>등록된 주소가 없습니다.</h3>
		<?php endif; ?>

	</div>
	<div class="col-md-5 address-form">

		<form method="post">
			<div class="woocommerce-address-fields">
				<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

				<div class="woocommerce-address-fields__field-wrapper">
					<?php
					foreach ( $address as $key => $field ) {
						woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
					}
					?>
				</div>

				<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>
				<p>
					<button type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
					<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
					<input type="hidden" name="action" value="edit_address" />
				</p>
			</div>

		</form>
	</div>
</div>



<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
