<?php 
/* Template name: cafe24 마이그레이션 */

wp_head(); 

global $wpdb;

echo '<pre>';

// 사용자 마이그레이션
// $fp = fopen("/var/www/html/old-site/nerima_users_191109.csv","r"); 
// while( $row = fgetcsv($fp) ) {
// 	if(!isset($first)){
// 		$first = 1;
// 		continue;
// 	}

// 	$user_id = $row[0];
// 	$email_address = $row[4];
// 	$password = '0000';
// 	$user_name = $row[1];
// 	$regdate = $row[8];
// 	$mileage = intval($row[7]);

// 	if(get_user_by('email', $email_address)) 
// 		continue;

// 	$userdata = array(
// 	    'user_login'  =>  $user_id,
// 	    'user_email'    =>  $email_address,
// 	    'user_pass'   =>  $password,
// 	    'first_name' => $user_name,
// 	    'user_registered' => date('Y-m-d H:i:s', strtotime($regdate))
// 	);
// 	if($u_id = wp_insert_user( $userdata )){
// 		update_user_meta( $u_id, 'old_site_user', 1 );
// 		print_r($userdata);
// 		if(isset($mileage)) {
// 			$user = new MSPS_User( $u_id );
// 			$user->earn_point($mileage);
// 			echo '포인트:'.$user->get_point();
// 		}

// 	}

// 	// break;
// }

//리뷰 마이그레이션
$fp = fopen("/var/www/html/old-site/nerima_qna_191109.csv","r"); 
while( $row = fgetcsv($fp) ) {
	/*
		* 리뷰 2428
		* 공지사항 2427
		* 입금확인 2429
		* Q&A 2448
		* 자주묻는질문 5625
	*/
	if(!isset($first)){
		$first = 1;
		continue;
	}
	$board_id = 2448;
	$post_title = $row[1];
	$post_content = $row[2];
	$user_name = $row[3];
	$user_id = $row[4];
	$post_date = $row[5];
	// echo $row[4];

	$post_date = date('Y-m-d H:i:s', strtotime($post_date));
	$user_login = $user_id;
	$user_id = 1;
	if($user = get_user_by('login', $user_login)) {
		$user_id = $user->ID;
	} else {
		$guest_name = $user_name;
		$guest_password = 'gksqja15987!';
	}


	$notice_flag    = 0;
	$secret_flag    = 1;//비밀글여부
	$parent_id      = 0;
	$section_val      = 'all';

	$args = array(
			'post_title'     => $post_title,
			'post_content'   => $post_content,
			'post_author'	 => $user_id,
			'post_date'		 => $post_date,
			'post_type'      => RAINBOARD::$POST_TYPE,
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
			'guid'			=> 'rainboard'
	);

	do_action( 'rainboard_savepost_before', $post_title ,  $post_content , $parent_id , $board_id);

	$post_id = wp_insert_post($args);

	//글번호 등록
	$last_id = $wpdb->get_results("SELECT ID FROM $wpdb->posts as p, $wpdb->postmeta as m
				WHERE
				p.id = m.post_id
				and m.meta_key='rainboard_origin'
				and m.meta_value = $board_id
				ORDER BY ID DESC LIMIT 0 , 1");

	add_post_meta( $post_id, 'rainboard_origin', $board_id);
	add_post_meta( $post_id, 'rainboard_view_count', 0 );
	add_post_meta( $post_id, 'rainboard_post_notice_flag', $notice_flag );
	add_post_meta( $post_id, 'rainboard_secret_flag', $secret_flag );
	add_post_meta( $post_id, 'rainboard_section_val', $section_val );

	if(isset($guest_name)) { // 비회원
		add_post_meta( $post_id, 'rainboard_guest_name', $guest_name);
		add_post_meta( $post_id, 'rainboard_guest_password', $guest_password);
	}
	print_r($args['post_title']);

} 
echo '</pre>';


?>