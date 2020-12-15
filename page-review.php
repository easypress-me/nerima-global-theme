<?php
/* Template Name: Review */

get_header(); ?>

<?php

global $amely_has_blog;
$amely_has_blog = true;

// Variables.
$page_wrap_class       = $content_class = '';
$remove_bottom_spacing = $remove_top_spacing = '';

// Sidebar config.
$page_sidebar_config = amely_get_option( 'page_sidebar_config' );

if ( $page_sidebar_config == 'left' ) {
	$page_wrap_class = 'has-sidebar-left row';
	$content_class   = 'col-xs-12 col-md-8 col-lg-9';
} else if ( $page_sidebar_config == 'right' ) {
	$page_wrap_class = 'has-sidebar-right row';
	$content_class   = 'col-xs-12 col-md-8 col-lg-9';
} else {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}

$sidebar = Amely_Helper::get_active_sidebar();

if ( ! $sidebar ) {
	$page_wrap_class = 'has-no-sidebars row';
	$content_class   = 'col-xs-12';
}
?>

	<div class="container woocommerce single-product review-page">
		<div class="inner-page-wrap <?php echo esc_attr( $page_wrap_class ); ?>">
			<div id="main" class="product site-content <?php echo esc_attr( $content_class ); ?>" role="main">
				<h1>소중한 후기를 공유해주세요</h1>
				
				<div id="review_form_wrapper">
					<div id="review_form">
						<?php
						$commenter = wp_get_current_commenter();

						$comment_form = array(
							'title_reply'         => '',
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'amely' ),
							'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'   => '</span>',
							'comment_notes_after' => '',
							'fields'              => '',
							// array(
							// 	'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required placeholder="' . esc_html__( 'Name *',
							// 			'amely' ) . '" /></p>',
							// 	'email'  => '<p class="comment-form-email">' . '<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required placeholder="' . esc_html__( 'Email *',
							// 			'amely' ) . '" /></p>',
							// )
							'label_submit'        => esc_html__( '등록하기', 'amely' ),
							'logged_in_as'        => '',
							'comment_field'       => '',
						);

						// if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						// 	$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.',
						// 			'amely' ),
						// 			esc_url( $account_page_url ) ) . '</p>';
						// }

						if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( '별점을 남겨주세요',
									'amely' ) . '</label><select name="rating" id="rating" aria-required="true" required>
									<option value="">' . esc_html__( 'Rate&hellip;', 'amely' ) . '</option>
									<option value="5">' . esc_html__( 'Perfect', 'amely' ) . '</option>
									<option value="4">' . esc_html__( 'Good', 'amely' ) . '</option>
									<option value="3">' . esc_html__( 'Average', 'amely' ) . '</option>
									<option value="2">' . esc_html__( 'Not that bad', 'amely' ) . '</option>
									<option value="1">' . esc_html__( 'Very poor', 'amely' ) . '</option>
								</select></div>';
						}

						$comment_form['comment_field'] .= '<p class="comment-form-author">' . '<input id="author" name="author" type="text" class="input-text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required placeholder="' . esc_html__( '이름*',
										'amely' ) . '" /></p>';
						$comment_form['comment_field'] .= '<p class="comment-form-email">' . '<input id="email" name="email" type="email" class="input-text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required placeholder="' . esc_html__( '이메일*',
										'amely' ) . '" /></p>';
						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment"></label><textarea id="comment" class="input-text" name="comment" cols="35" rows="5" aria-required="true" required placeholder="' . esc_html__( '소중한 후기를 남겨주시면 앞으로의 제품 개선에 반영 할 수 있도록 노력하겠습니다.*',
								'amely' ) . '"></textarea></p>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ), $_GET['product_id'] );
						?>
					</div>
				</div>

			</div>
		</div>
	</div>
<?php
get_footer();
