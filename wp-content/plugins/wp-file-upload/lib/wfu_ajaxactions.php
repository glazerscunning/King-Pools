<?php


function wfu_ajax_action_send_email_notification() {
	$user = wp_get_current_user();
	if ( 0 == $user->ID ) $is_admin = false;
	else $is_admin = current_user_can('manage_options');

	$arr = wfu_get_params_fields_from_index($_POST['params_index']);
	//check referer using server sessions to avoid CSRF attacks
	$sid = $arr['shortcode_id'];
	if ( $_SESSION["wfu_token_".$sid] != $_POST['session_token'] ) die();
	if ( $user->user_login != $arr['user_login'] ) die();

	$params_str = get_option('wfu_params_'.$arr['unique_id']);
	$params = wfu_decode_array_from_string($params_str);

	/* initialize return array */
	$params_output_array["version"] = "full";
	$params_output_array["general"]['shortcode_id'] = $params["uploadid"];
	$params_output_array["general"]['unique_id'] = ( isset($_POST['uniqueuploadid_'.$sid]) ? sanitize_text_field($_POST['uniqueuploadid_'.$sid]) : "" );
	$params_output_array["general"]['state'] = 0;
	$params_output_array["general"]['files_count'] = 0;
	$params_output_array["general"]['update_wpfilebase'] = "";
	$params_output_array["general"]['redirect_link'] = "";
	$params_output_array["general"]['upload_finish_time'] = "";
	$params_output_array["general"]['message'] = "";
	$params_output_array["general"]['message_type'] = "";
	$params_output_array["general"]['admin_messages']['wpfilebase'] = "";
	$params_output_array["general"]['admin_messages']['notify'] = "";
	$params_output_array["general"]['admin_messages']['redirect'] = "";
	$params_output_array["general"]['admin_messages']['other'] = "";
	$params_output_array["general"]['errors']['wpfilebase'] = "";
	$params_output_array["general"]['errors']['notify'] = "";
	$params_output_array["general"]['errors']['redirect'] = "";
	$params_output_array["general"]['color'] = "black";
	$params_output_array["general"]['bgcolor'] = "#F5F5F5";
	$params_output_array["general"]['borcolor'] = "#D3D3D3";
	$params_output_array["general"]['notify_only_filename_list'] = "";
	$params_output_array["general"]['notify_target_path_list'] = "";
	$params_output_array["general"]['notify_attachment_list'] = "";
	$params_output_array["general"]['fail_message'] = WFU_ERROR_UNKNOWN;

	// prepare user data 
	$userdata_fields = $params["userdata_fields"]; 
	foreach ( $userdata_fields as $userdata_key => $userdata_field ) 
		$userdata_fields[$userdata_key]["value"] = ( isset($_POST['userdata_'.$userdata_key]) ? wfu_plugin_decode_string($_POST['userdata_'.$userdata_key]) : "" );

	$send_error = wfu_send_notification_email($user, $_POST['only_filename_list'], $_POST['target_path_list'], $_POST['attachment_list'], $userdata_fields, $params);

	/* suppress any errors if user is not admin */
	if ( !$is_admin ) $send_error = "";

	if ( $send_error != "" ) {
		$params_output_array["general"]['admin_messages']['notify'] = $send_error;
		$params_output_array["general"]['errors']['notify'] = "error";
	}

	/* construct safe output */
	$sout = "0;".WFU_DEFAULTMESSAGECOLORS.";0";

	die("wfu_fileupload_success::".$sout.":".wfu_encode_array_to_string($params_output_array)); 
}

function wfu_ajax_action_callback() {
	if ( !isset($_REQUEST['session_token']) ) die();
	$session_token = sanitize_text_field( $_REQUEST["session_token"] );
	if ( $session_token == "" ) die();

/* This section is executed when forceclassic is enabled or when redirection to the classic uploader was performed */
	if ( isset($_REQUEST['sid']) && isset($_REQUEST['start_time']) ) {
		//this request came from classic non-HTML5 uploader
		$sid = sanitize_text_field( $_REQUEST["sid"] );
		if ( $sid == "" ) die();
		$start_time = sanitize_text_field( $_REQUEST["start_time"] );
		
		$_SESSION['wfu_check_refresh_'.$sid] = 'form button pressed';
		$_SESSION['wfu_start_time_'.$sid] = $start_time;

		die("wfu_response_success:");
	}
	
/* This section is executed when normal HTML5 upload is performed */
	if ( !isset($_REQUEST['params_index']) ) die();
	
	$params_index = sanitize_text_field( $_REQUEST["params_index"] );
	
	if ( $params_index == "" ) die();
	
	$user = wp_get_current_user();
	$arr = wfu_get_params_fields_from_index($params_index);
	$sid = $arr['shortcode_id'];
	//check referrer using server sessions to avoid CSRF attacks
	if ( $_SESSION["wfu_token_".$sid] != $session_token ) {
		echo "Session failed!<br/><br/>Session Data:<br/>";
		print_r(wfu_sanitize($_SESSION));
		echo "<br/><br/>Post Data:<br/>";
		print_r(wfu_sanitize($_POST));
		die('force_errorabort_code');
	}

	if ( $user->user_login != $arr['user_login'] ) {
		echo "User failed!<br/><br/>User Data:<br/>";
		print_r(wfu_sanitize($user));
		echo "<br/><br/>Post Data:<br/>";
		print_r(wfu_sanitize($_POST));
		echo "<br/><br/>Params Data:<br/>";
		print_r(wfu_sanitize($arr));
		die('force_errorabort_code');
	}

	//if force_connection_close is set, then the first pass to this callback script is for closing the previous connection
	if ( isset($_POST["force_connection_close"]) && $_POST["force_connection_close"] === "1" ) {
		header("Connection: Close");
		die("success");
	}
	
	//get the unique id of the upload
	$unique_id = ( isset($_POST['uniqueuploadid_'.$sid]) ? sanitize_text_field($_POST['uniqueuploadid_'.$sid]) : "" );
	if ( strlen($unique_id) != 10 ) die('force_errorabort_code');
	
	//if upload has finished then perform post upload actions
	if ( isset($_POST["upload_finished"]) && $_POST["upload_finished"] === "1" ) {
		die("success");
	}
	
	$params_str = get_option('wfu_params_'.$arr['unique_id']);
	$params = wfu_decode_array_from_string($params_str);


	//if this is the first pass of an upload attempt then perform pre-upload actions
	if ( !isset($_SESSION['wfu_upload_first_pass_'.$unique_id]) || $_SESSION['wfu_upload_first_pass_'.$unique_id] != 'true' ) {
		$_SESSION['wfu_upload_first_pass_'.$unique_id] = 'true';
	}

	if ( !isset($_POST["subdir_sel_index"]) ) die();
	$subdir_sel_index = sanitize_text_field( $_POST["subdir_sel_index"] );
	$params['subdir_selection_index'] = $subdir_sel_index;
	$_SESSION['wfu_check_refresh_'.$params["uploadid"]] = 'do not process';

	$wfu_process_file_array = wfu_process_files($params, 'ajax');
	// extract safe_output from wfu_process_file_array and pass it as separate part of the response text
	$safe_output = $wfu_process_file_array["general"]['safe_output'];
	unset($wfu_process_file_array["general"]['safe_output']);
	// get javascript code that has been defined in wfu_after_file_upload action
	$js_script = wfu_plugin_encode_string($wfu_process_file_array["general"]['js_script']);
	unset($wfu_process_file_array["general"]['js_script']);

	die("wfu_fileupload_success:".$js_script.":".$safe_output.":".wfu_encode_array_to_string($wfu_process_file_array)); 
}

function wfu_ajax_action_save_shortcode() {
	if ( !current_user_can( 'manage_options' ) ) die();
	if ( !isset($_POST['shortcode']) || !isset($_POST['shortcode_original']) || !isset($_POST['post_id']) || !isset($_POST['post_hash']) || !isset($_POST['shortcode_position']) || !isset($_POST['shortcode_tag']) ) die();

	//sanitize parameters
	$shortcode = wfu_sanitize_code($_POST['shortcode']);
	$shortcode_original = wfu_sanitize_code($_POST['shortcode_original']);
	$post_id = wfu_sanitize_int($_POST['post_id']);
	$post_hash = wfu_sanitize_code($_POST['post_hash']);
	$shortcode_position = wfu_sanitize_int($_POST['shortcode_position']);
	$shortcode_tag = wfu_sanitize_tag($_POST['shortcode_tag']);
	
	if ( $_POST['post_id'] == "" ) {
		die();
	}
	else {
		$data['post_id'] = $post_id;
		$data['post_hash'] = $post_hash;
		$data['shortcode'] = wfu_plugin_decode_string($shortcode_original);
		$data['position'] = $shortcode_position;
		if ( !wfu_check_edit_shortcode($data) ) die("wfu_save_shortcode:fail:post_modified");
		else {
			$new_shortcode = "[".$shortcode_tag." ".wfu_plugin_decode_string($shortcode)."]";
			if ( wfu_replace_shortcode($data, $new_shortcode) ) {
				$post = get_post($post_id);
				$hash = hash('md5', $post->post_content);
				die("wfu_save_shortcode:success:".$hash);
			}
			else die("wfu_save_shortcode:fail:post_update_failed");
		}
	}
}

function wfu_ajax_action_check_page_contents() {
	if ( !current_user_can( 'manage_options' ) ) die();
	if ( !isset($_POST['post_id']) || !isset($_POST['post_hash']) ) die();
	if ( $_POST['post_id'] == "" ) die();

	$data['post_id'] = $_POST['post_id'];
	$data['post_hash'] = $_POST['post_hash'];
	if ( wfu_check_edit_shortcode($data) ) die("wfu_check_page_contents:current:");
	else die("wfu_check_page_contents:obsolete:");
}

function wfu_ajax_action_edit_shortcode() {
	if ( !current_user_can( 'manage_options' ) ) die();
	if ( !isset($_POST['upload_id']) || !isset($_POST['post_id']) || !isset($_POST['post_hash']) || !isset($_POST['shortcode_tag']) ) die();
	
	//sanitize parameters
	$upload_id = sanitize_text_field($_POST['upload_id']);
	$post_id = wfu_sanitize_int($_POST['post_id']);
	$post_hash = wfu_sanitize_code($_POST['post_hash']);
	$shortcode_tag = wfu_sanitize_tag($_POST['shortcode_tag']);

	$data['post_id'] = $post_id;
	$data['post_hash'] = $post_hash;
	if ( wfu_check_edit_shortcode($data) ) {
		$post = get_post($data['post_id']);
		//get default value for uploadid
		$defs = wfu_attribute_definitions();
		$default = "";
		foreach ( $defs as $key => $def ) {
			if ( $def['attribute'] == 'uploadid' ) {
				$default = $def['value'];
				break;
			}
		}
		//get page shortcodes
		$wfu_shortcodes = wfu_get_content_shortcodes($post, $shortcode_tag);
		//find the shortcodes' uploadid and the correct one
		$validkey = -1;
		foreach ( $wfu_shortcodes as $key => $data ) {
			$shortcode = trim(substr($data['shortcode'], strlen('['.$shortcode_tag), -1));
			$shortcode_attrs = wfu_shortcode_string_to_array($shortcode);
			if ( array_key_exists('uploadid', $shortcode_attrs) ) $uploadid = $shortcode_attrs['uploadid'];
			else $uploadid = $default;
			if ( $uploadid == $upload_id ) {
				$validkey = $key;
				break;
			}
		}
		if ( $validkey == -1 ) die();
		$data_enc = wfu_safe_store_shortcode_data(wfu_encode_array_to_string($wfu_shortcodes[$validkey]));
		$url = site_url().'/wp-admin/options-general.php?page=wordpress_file_upload&tag='.$shortcode_tag.'&action=edit_shortcode&data='.$data_enc;
		die("wfu_edit_shortcode:success:".wfu_plugin_encode_string($url));
	}
	else die("wfu_edit_shortcode:check_page_obsolete:".WFU_ERROR_PAGE_OBSOLETE);
}

function wfu_ajax_action_read_subfolders() {
	if ( !isset($_POST['folder1']) || !isset($_POST['folder2']) ) die();
	$temp_params = array( 'uploadpath' => wfu_plugin_decode_string($_POST['folder1']), 'accessmethod' => 'normal', 'ftpinfo' => '', 'useftpdomain' => 'false' );
	$path = wfu_upload_plugin_full_path($temp_params);

	if ( !is_dir($path) ) die("wfu_read_subfolders:error:Parent folder is not valid! Cannot retrieve subfolder list.");

	$path2 = wfu_plugin_decode_string($_POST['folder2']);
	$dirlist = "";
	if ( $handle = opendir($path) ) {
		$blacklist = array('.', '..');
		while ( false !== ($file = readdir($handle)) )
			if ( !in_array($file, $blacklist) ) {
				$filepath = $path.$file;
				if ( is_dir($filepath) ) {
					if ( $file == $path2 ) $file = '[['.$file.']]';
					$dirlist .= ( $dirlist == "" ? "" : "," ).$file;
				}
			}
		closedir($handle);
	}
	if ( $path2 != "" ) {
		$dirlist2 = $path2;
		$path .= $path2."/";
		if ( is_dir($path) ) {
			if ( $handle = opendir($path) ) {
				$blacklist = array('.', '..');
				while ( false !== ($file = readdir($handle)) )
					if ( !in_array($file, $blacklist) ) {
						$filepath = $path.$file;
						if ( is_dir($filepath) )
							$dirlist2 .= ",*".$file;
					}
				closedir($handle);
			}
		}
		$dirlist = str_replace('[['.$path2.']]', $dirlist2, $dirlist);
	}

	die("wfu_read_subfolders:success:".wfu_plugin_encode_string($dirlist));
}

function wfu_ajax_action_download_file_invoker() {
	$file_code = (isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : ''));
	$nonce = (isset($_POST['nonce']) ? $_POST['nonce'] : (isset($_GET['nonce']) ? $_GET['nonce'] : ''));
	if ( $file_code == '' || $nonce == '' ) die();

	//security check to avoid CSRF attacks
	if ( !wp_verify_nonce($nonce, 'wfu_download_file_invoker') ) die();
	
	//check if user is allowed to download files
	if ( !current_user_can( 'manage_options' ) ) {
		die();
	}
	
//	$filepath = wfu_plugin_decode_string($file_code);
	$file_code = wfu_sanitize_code($file_code);
	$filepath = wfu_get_filepath_from_safe($file_code);
	if ( $filepath === false ) die();
	$filepath = wfu_path_rel2abs(wfu_flatten_path($filepath));

	//check if user is allowed to perform this action on this file
	if ( !wfu_current_user_owes_file($filepath) ) die();
	
	//generate download unique id to monitor this download
	$download_id = wfu_create_random_string(16);
	//store download status of this download
	$_SESSION['wfu_download_status_'.$download_id] = 'starting';
	//generate download ticket which expires in 30sec and store it in session
	//it will be used as security measure for the downloader script, which runs outside Wordpress environment
	$_SESSION['wfu_download_ticket_'.$download_id] = time() + 30;
	//generate download monitor ticket which expires in 30sec and store it in session
	//it will be used as security measure for the monitor script that will check download status
	$_SESSION['wfu_download_monitor_ticket_'.$download_id] = time() + 30;
	
	//this routine returns a dynamically created iframe element, that will call the actual download script;
	//the actual download script runs outside Wordpress environment in order to ensure that no php warnings
	//or echo from other plugins is generated, that could scramble the downloaded file;
	//a ticket, similar to nonces, is passed to the download script to check that it is not a CSRF attack; moreover,the ticket is destroyed
	//by the time it is consumed by the download script, so it cannot be used again
	$response = '<iframe src="'.WFU_DOWNLOADER_URL.'?file='.$file_code.'&ticket='.$download_id.'" style="display: none;"></iframe>';

	die('wfu_ajax_action_download_file_invoker:wfu_download_id;'.$download_id.':'.$response);
}

function wfu_ajax_action_download_file_monitor() {
	$file_code = (isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : ''));
	$id = (isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : ''));
	if ( $file_enc == '' || $id == '' ) die();
	
	//ensure that this is not a CSRF attack by checking validity of a security ticket
	if ( !isset($_SESSION['wfu_download_monitor_ticket_'.$id]) || time() > $_SESSION['wfu_download_monitor_ticket_'.$id] ) die();
	//destroy monitor ticket so it cannot be used again
	unset($_SESSION['wfu_download_monitor_ticket_'.$id]);
	
	//initiate loop of 30secs to check the download status of the file;
	//the download status is controlled by the actual download script;
	//if the file finishes within the 30secs of the loop, then this routine logs the action and notifies
	//the client side about the download status of the file, otherwise an instruction
	//to the client side to repeat this routine and wait for another 30secs is dispatched
	$end_time = time() + 30;
	$upload_ended = false;
	while ( time() < $end_time ) {
		$upload_ended = ( isset($_SESSION['wfu_download_status_'.$id]) ? ( $_SESSION['wfu_download_status_'.$id] == 'downloaded' || $_SESSION['wfu_download_status_'.$id] == 'failed' ? true : false ) : false );
		if ( $upload_ended ) break;
		usleep(100);
	}
	
	if ( $upload_ended ) {
		$user = wp_get_current_user();
//		$filepath = wfu_plugin_decode_string($file_code);
		$filepath = wfu_get_filepath_from_safe($file_code);
		if ( $filepath === false ) die();
		$filepath = wfu_path_rel2abs(wfu_flatten_path($filepath));
		wfu_log_action('download', $filepath, $user->ID, '', 0, '', null);
		die('wfu_ajax_action_download_file_monitor:'.$_SESSION['wfu_download_status_'.$id].':');
	}
	else {
		//regenerate monitor ticket
		$_SESSION['wfu_download_monitor_ticket_'.$id] = time() + 30;
		die('wfu_ajax_action_download_file_monitor:repeat:'.$id);
	}
}


function wfu_ajax_action_notify_wpfilebase() {
	$params_index = (isset($_POST['params_index']) ? $_POST['params_index'] : (isset($_GET['params_index']) ? $_GET['params_index'] : ''));
	$session_token = (isset($_POST['session_token']) ? $_POST['session_token'] : (isset($_GET['session_token']) ? $_GET['session_token'] : ''));
	if ( $params_index == '' || $session_token == '' ) die();

	$arr = wfu_get_params_fields_from_index($params_index);
	//check referer using server sessions to avoid CSRF attacks
	if ( $_SESSION["wfu_token_".$arr['shortcode_id']] != $session_token ) die();

	do_action('wpfilebase_sync');

	die();
}

?>
