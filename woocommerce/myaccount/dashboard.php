
<div class="row">
	<div class="mypage-orders col-md-12">
		<?php
		$my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
			'order-number'  => __( 'Order', 'woocommerce' ),
			'order-date'    => __( 'Date', 'woocommerce' ),
			'order-status'  => __( 'Status', 'woocommerce' ),
			'order-total'   => __( 'Total', 'woocommerce' ),
			'order-actions' => '&nbsp;',
		) );

		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
			'numberposts' => $order_count,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() ),
		) ) );

		if ( $customer_orders ) : ?>
			<table class="shop_table shop_table_responsive my_account_orders">

				<thead>
					<tr>
						<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
							<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ( $customer_orders as $customer_order ) :
						$order      = wc_get_order( $customer_order );
						$item_count = $order->get_item_count();
						?>
						<tr class="order">
							<?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
								<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( 'order-number' === $column_id ) : ?>
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
											<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php
										/* translators: 1: formatted order total 2: total order items */
										printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
										?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );
			
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) {
												echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
											}
										}
										?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
		<h3 class="no-orders">No orders yet</h3>
		<a href="/shop/" class="shop-now">Shop now</a>
		<?php endif; ?>
	</div>

	</div>

	<!-- <div class="mypage-my-info col-md-5"> <?php $current_user = wp_get_current_user(); ?>
		<h5><?php 
			if($current_user->first_name) {
				echo $current_user->first_name; 
			}else {
				echo $current_user->billing_first_name; 
			}?>	
		</h5>
		<p class="email"><?php echo $current_user->user_email; ?></p>

		<?php if($current_user->billing_address_1) : ?>
			<p class="address"><?php echo $current_user->billing_address_1; ?><br><?php echo $current_user->billing_address_2; ?></p>
			<p class="phone"><?php echo $current_user->billing_phone_kr; ?></p>
			<a href="/my-account/edit-address/" class="shop-now">주소보기</a>
		<?php else: ?>
			<p>아직 등록된 주소가 없습니다.</p>
			<a href="/my-account/edit-address/" class="shop-now">주소 등록하기</a>
		<?php endif; ?>

		<?php echo do_shortcode( '[show_referer_code]' ); ?>
	</div> -->
</div>