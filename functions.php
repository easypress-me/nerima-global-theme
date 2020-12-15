<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// add_action('wp_dashboard_setup', 'referer_ranking_dashboard_widgets', 999999);
  
function referer_ranking_dashboard_widgets() {
	global $wp_meta_boxes;
 
	wp_add_dashboard_widget('referer_ranking', '추천왕', 'referer_ranking_template');
}
 
function referer_ranking_template() {
	global $wpdb;

	for ($i=0; $i < 3; $i++) :
		//11월
		$results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix ."msps_log WHERE message like '%초대 포인트 지급%' and date like '".date('Y-m',strtotime('-'.$i.' Month'))."%' and message like '%님]%' limit 0,10000");
		$users = array();
		foreach ($results as $key => $value) {
			$users[$value->user_id] += 1;
		}
		arsort($users);
		$j = 0;
		if($i != 0)
			echo '<hr>';
		echo '<strong>'.date('Y년 m',strtotime('-'.$i.' Month')).'월 추천왕</strong><br>';
		foreach ($users as $user_id => $count) {
			if($j++ >= 5)
				break; 

			$user = get_userdata( $user_id );
			?>
			<?php echo $j; ?>위 <a href="/wp-admin/user-edit.php?user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $user->first_name; ?>(<?php echo $user->user_login; ?>)</a>님 : <?php echo $count; ?>회 추천<br>
			<?php
		}
	endfor;
}


add_shortcode( 'test_showing', 'test_showing' );
function test_showing() {

    foreach ( WC()->cart->get_cart() as $key => $value ) {
    	echo '<pre>';
    	print_r($value['data']);
    	echo '</pre>';
    }
}

/**
 * Enqueue child scripts
 */
add_action( 'wp_enqueue_scripts', 'amely_child_enqueue_scripts' );
if ( ! function_exists( 'amely_child_enqueue_scripts' ) ) {

	function amely_child_enqueue_scripts() {
		wp_enqueue_style( 'amely-main-style', trailingslashit( get_template_directory_uri() ) . '/style.css' );
		wp_enqueue_style( 'amely-child-style', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css' );
		wp_enqueue_script( 'amely-child-script',
			trailingslashit( get_stylesheet_directory_uri() ) . 'script.js',
			array( 'jquery' ),
			null,
			true );
		wp_enqueue_script( 'amely-child-common',
			trailingslashit( get_stylesheet_directory_uri() ) . '/js/common.js',
			array( 'jquery' ),
			null,
			true );

		wp_enqueue_style( 'font-awesome-5','https://use.fontawesome.com/releases/v5.8.2/css/all.css');
	}

}
add_filter( 'woocommerce_order_details_after_order_table_items', 'two_car_order_sheet', 10, 3 );
function two_car_order_sheet($order, $first_sheet) {
	$order_id = $order->ID;
	if($first_sheet) {
		$output .= '<tr><td colspan=2><h5>배송조회</h5>'.$first_sheet.'</td></tr>';

	}
	if ( $two_sheet = get_field('2차송장번호',$order_id) ) {
		$company = get_field('2차택배사', $order_id);
		if($company == '우체국') {
			$link = 'http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1='.$two_sheet;
		} else if($company == '대한통운') {
			$link = 'https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no='.$two_sheet;
		}
		$output .= '<tr><td colspan=2>';
		$output .= sprintf( '2차 송장 : %s ( <a target="_blank" href="%s">%s</a> )', $company, $link, $two_sheet );
		$output .= ' <a target="_blank" href="'.$link.'" class="btn btn-default"><i class="fas fa-truck"></i> 배송조회</a>';
		$output .= '</td></tr>';
	}

	echo $output;
}
function woocommerce_product_category( $args = array() ) {
    $woocommerce_category_id = get_queried_object_id();
  $args = array(
      'parent' => $woocommerce_category_id
  );
  $terms = get_terms( 'product_cat', $args );
  if ( $terms ) {
      echo '<ul class="woocommerce-categories">';
      foreach ( $terms as $term ) {
          echo '<li class="woocommerce-product-category-page">';
          echo '<a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">';
          echo $term->name;
          echo '</a>';
          echo '</li>';
      }
      echo '</ul>';
  }
}

function ep_product_submenu() {
	global $post;

	if(is_product_category()){
	    // The WP_Term object for the current product category
	    $term = get_queried_object();

	    // Get the current term name for the product category page
	    $term_name = $term->name;

		echo '<div class="product-sub-menu-wrapper">';
		echo '	<div class="container">';
		echo '		<ul class="product-sub-menu">';

		$args = array(
		    'taxonomy'   => "product_cat",
		    // 'orderby'    => $orderby,
		    // 'order'      => $order,
		    'hide_empty' => false,
		    'parent' => 67,
		    // 'include'    => $ids
		);
		$product_categories = get_terms($args);

		foreach ( $product_categories as $item ) {
			$active = '';
			if($term_name == $item->name)
				$active = ' current-item';
			echo '<li class="menu-item'.$active.'"><a href="'.get_term_link($item).'">'.$item->name.'</a></li>';
		}
		echo '		</ul>';
		echo '	</div>';
		echo '</div>';
	}

}
add_action( 'ep_header_after', 'ep_product_submenu', 100 );


function MobileCheck() { 
   //Check Mobile
    $mAgent = array("iPhone","iPod","Android","Blackberry", 
        "Opera Mini", "Windows ce", "Nokia", "sony" );
    $chkMobile = 0;
    for($i=0; $i<sizeof($mAgent); $i++){
        if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
            $chkMobile = 1;
            break;
        }
    }

    return $chkMobile;
}

function ep_direct_checkout() {
	?>
	<a href="#" class="add_to_cart_redirect button">Buy</a>
	<?php
}
add_action('woocommerce_after_add_to_cart_button', 'ep_direct_checkout');

function ep_footer_script() {
	?>

	<script type="text/javascript">

		var direct_checkout = 0;
		jQuery('.add_to_cart_redirect').click(function(event) {
		    jQuery(".single_add_to_cart_button").trigger("click"); 
		    direct_checkout = 1;
		});

		jQuery('.single_add_to_cart_button').click(function(event) {
			direct_checkout = 0;
		});
	    jQuery(document.body).on("added_to_cart", function( data ) {
	    	jQuery('.header-minicart').addClass('minicart-open');
	    	if(direct_checkout) {
	    		location.href="/checkout/";
	    	}
	    });

	</script>

	<?php 
}
add_action('wp_footer', 'ep_footer_script');

add_shortcode( 'home-content', 'ep_home_template' );
function ep_home_template() {
	?>

<section id="home-category">
	<div class="inner">
		<div class="section-title">
			<h2>Category List</h2>
		</div>

		<div class="category-wrapper">
			<div class="row">
			<?php 
			$args = array(
			    'taxonomy'   => "product_cat",
			    // 'orderby'    => $orderby,
			    // 'order'      => $order,
			    'hide_empty' => false,
			    'parent' => 67,
			    // 'include'    => $ids
			);
			$product_categories = get_terms($args);

			foreach ( $product_categories as $item ) :
				$active = '';
				$thumb_id = get_term_meta( $item->term_id, 'thumbnail_id', true );
                $thumb = wp_get_attachment_image_src( $thumb_id, $size );

                $thumb_image = $thumb[0];
				$icon_image = get_term_meta( $item->term_id, 'amely_product_cat_thumbnail_masonry', true );
				?>
				<div class="col-md-6">
					<div class="cate-item" style="background-image:url(<?php echo $thumb_image; ?>);">
						<a href="<?php echo get_term_link($item) ?>">
							<?php echo $item->name; ?>	
						</a>
					</div>
				</div>
			<?php
			endforeach;
			?>
			</div>
		</div>
	</div>
</section>
<section id="home-shop">
	<div class="inner">
		<div class="section-title">
			<h2>Best Seller</h2>
		</div>
		<div class="row">
			<?php 
			$products = get_posts(
					array(
						'post_type' => 'product', 
						'posts_per_page' => 8, 
					    'meta_key' => 'total_sales',
					    'orderby' => 'meta_value_num',
					    'order' => 'DESC',
				        'tax_query' => array(
				            array(
				                'taxonomy' => 'product_cat',
				                'field' => 'ID',
				                'terms' => array(41, 15, 44,68),
				                'operator' => 'NOT IN',
				            )
				         )
					)
				);
			foreach ($products as $product_item) :
				$product = wc_get_product( $product_item->ID );
				$terms = get_the_terms( $product_item->ID, 'product_cat' );
				foreach ($terms as $product_cat)
					break;
			?>
			<div class="col-md-3 col-xs-6">
				<div class="product-item" onclick="location.href='<?php echo get_permalink( $product_item->ID ); ?>'">
					<div class="product-image" style="background-image:url(<?php echo get_the_post_thumbnail_url( $product_item, $size = 'large' ); ?>);"></div>
					<div class="product-info">
						<span><?php echo $product_cat->name; ?></span>
						<h4><?php echo $product_item->post_title; ?></h4>
						<strong class="price"><?php echo number_format($product->get_price()).'원'; ?></strong>
						<a href="#" class="add_to_cart"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add_cart.png" width="23px" height="auto"></a>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section id="home-blog">
	<div class="inner">
		<div class="section-title">
			<h2>blog</h2>
		</div>
		<div class="blog-slider">
			<?php 
			$blogs = get_posts( array('post_type' => 'post', 'posts_per_page' => 20 ));
			foreach($blogs as $blog_item) :
			?>
			<div class="blog-item" onclick="location.href='<?php echo get_permalink( $blog_item ); ?>'">
				<div class="blog-image" style="background-image:url(<?php echo get_the_post_thumbnail_url( $blog_item, 'large' ); ?>)"></div>
				<div class="blog-info">
					<?php
					foreach(get_the_category($blog_item->ID) as $cd) {
						break;
					}
					?>
					<div class="cate"><span><?php echo $cd->name;  ?></span></div>
					<h4><?php echo $blog_item->post_title; ?></h4>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<section id="home-review" style="display:none;">
	<div class="inner">
		<div class="section-title">
			<h2>Review</h2>
		</div>
		<div class="review-slider">
			<?php 
			$reviews = get_posts( array('post_type' => 'rainboard_post', 'posts_per_page' => 20, 'meta_query' => array(
					array(
							'key' => 'rainboard_origin',
							'value' => 2428
					)
				) ));
			foreach($reviews as $review_item) :
				if(!has_post_thumbnail($review_item))
					continue;
			?>
			<div class="review-item" onclick="location.href='<?php echo get_permalink( $review_item ); ?>'">
				<div class="review-item-wrapper">
					<div class="review-image" style="background-image:url(<?php echo get_the_post_thumbnail_url( $review_item, 'large' ); ?>)"></div>
					<div class="review-info">
						<h4><?php echo $review_item->post_title; ?></h4>
						<span class="date"><?php echo get_post_time( 'Y-m-d',false,$review_item ); ?></span>
						<span class="author">
							<?php 
							$user_info = get_userdata($review_item->post_author);
							$user_nicename = $user_info->display_name;
							echo mb_substr( $user_nicename, 0, mb_strlen( $user_nicename, 'UTF-8' )-2, 'UTF-8' )."**";
								?>
						</span>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<script type="text/javascript">
	jQuery(function($){
		$('.review-slider').slick({
			<?php if(!MobileCheck()) : ?>
		  slidesToShow: 4,
		  slidesToScroll: 4,
		  dots: true
		  <?php else :?>
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  <?php endif; ?>
		});

		$('.blog-slider').slick({
			<?php if(!MobileCheck()) : ?>
		  slidesToShow: 4,
		  slidesToScroll: 4,
		  dots: true
		  <?php else :?>
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  <?php endif; ?>
		});

	});
</script>
	<?php
}
add_shortcode( 'footer-notice', 'ep_footer_notice' );
function ep_footer_notice() {
	?>
	<h4 class="widget-title">공지사항 <a href="/notice/">더보기</a></h4>
	<?php
	echo do_shortcode( '[rainboard-widget id="1" rows="3"]', true );
}
add_shortcode( 'footer-qna', 'ep_footer_qna' );
function ep_footer_qna() {
	?>
	<h4 class="widget-title">Q&A <a href="/qa/">더보기</a></h4>
	<?php
	echo do_shortcode( '[rainboard-widget id="5" rows="3"]', true );
}
function wc_dropdown_variation_attribute_options2( $args = array() ) { 
    $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array( 
        'options' => false,  
        'attribute' => false,  
        'product' => false,  
        'selected' => false,  
        'name' => '',  
        'id' => '',  
        'class' => '',  
        'show_option_none' => __( 'Choose an option', 'woocommerce' ),  
 ) ); 
 
    $options = $args['options']; 
    $product = $args['product']; 
    $attribute = $args['attribute']; 
    $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute ); 
    $id = $args['id'] ? $args['id'] : sanitize_title( $attribute ); 
    $class = $args['class']; 
    $show_option_none = $args['show_option_none'] ? true : false; 
    $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options. 
 
    if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) { 
        $attributes = $product->get_variation_attributes(); 
        $options = $attributes[ $attribute ]; 
    } 
 
    $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">'; 
    $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>'; 
 
    if ( ! empty( $options ) ) { 
        if ( $product && taxonomy_exists( $attribute ) ) { 
            // Get terms if this is a taxonomy - ordered. We need the names too. 
            $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) ); 
 
            foreach ( $terms as $term ) { 
                if ( in_array( $term->slug, $options ) ) { 
                    $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>'; 
                } 
            } 
        } else { 


			$product = new WC_Product_Variable($product->get_id());
			$variations = $product->get_available_variations();
			$var_data = [];
			foreach ($variations as $variation) {
				// $option = $variation['title'];
				foreach($variation['attributes'] as $option)
					break;
				$regular_price = $variation['display_regular_price'];
				$display_price = $variation['display_price'];
				$percent = '';
				if($display_price != $regular_price){
					$percent = (($regular_price - $display_price ) / $regular_price) * 100;
					$percent = '('.intval($percent).'% Discount)';	
				}
			
                $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false ); 
                $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) .$percent .'</option>'; 
            } 
        } 
    } 
 
    $html .= '</select>'; 
 
    echo apply_filters( 'woocommerce_dropdown_variation_attribute_options_html', $html, $args ); 
}  