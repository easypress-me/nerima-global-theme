<?php

$pageID = Amely_Helper::get_the_ID();

$container_classes          = array( '' );
$container_classes[]        = 'container ' . amely_get_option( 'header_width' );
$offcanvas_position         = amely_get_option( 'offcanvas_position' );
$header_right_column_layout = amely_get_option( 'header_right_column_layout' );

if ( $header_right_column_layout == 'only-mini-cart' ) {
	$header_right_column_layout = 'base';
}

?>
<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
	<div class="row">
		<div class="left-col">
			<?php
			if ( $offcanvas_position == 'left' ) {
				echo Amely_Templates::header_offcanvas_btn();
			} ?>
		</div>
		<div class="site-logo">
			<a href="/" rel="home" class="site-logo-img"></a>
		</div>
		<?php echo Amely_Templates::header_block_site_menu(); ?>
		
		<div class="right-col header-tools layout-<?php echo esc_attr( $header_right_column_layout ); ?>">
			<?php //echo Amely_Templates::header_block_search(); ?>
			<?php echo Amely_Templates::header_block_header_login(); ?>
			<?php //echo Amely_Templates::header_block_wishlist(); ?><?php echo do_shortcode('[prisna-google-website-translator]'); ?>
			<div class="profile-link">
				
				<?php if(is_user_logged_in()) : ?>
					<a href="/my-account/">MY</a>
				<?php else : ?>
					<a href="/login/">Login</a>
				<?php endif; ?>
			</div>
			<?php echo Amely_Templates::header_block_cart(); ?>
			<?php echo Amely_Templates::header_block_mobile_btn(); ?>
			<?php
			if ( $offcanvas_position == 'right' ) {
				echo Amely_Templates::header_offcanvas_btn();
			}
			?>
		</div>
	</div>
</div>
