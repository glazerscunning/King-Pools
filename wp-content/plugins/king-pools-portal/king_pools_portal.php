<?php
/*
Plugin Name: King Pools Management Portal
Description: The is a complete Customer, Vendor and Back Office management portal for King Pools Inc.
Version: 2.0
Author: Aaron Cunningham
*/

DEFINE("KP_ASSET_UPLOAD_DIR", WP_CONTENT_DIR . "/uploads/king_portal/");
DEFINE("WPFILEUPLOAD_DIR", '/'.PLUGINDIR .'/wp-file-upload/');

function king_admin() {  
    include('king_admin.php');  
}

function king_vendors() {  
    include('king_vendors.php');  
}

function king_customers() {  
    include('king_customers.php');  
}

function king_projects() {  
    include('king_projects.php');  
}

function king_work_orders() {  
    include('king_work_orders.php');  
}

function king_estimates() {  
    include('king_estimates.php');  
}

function king_notifications() {  
    include('king_notify.php');  
}

function king_admin_actions() {   	
    add_menu_page("King Management Portal", "King Portal", 1, "king-management-portal", "king_admin");
    add_submenu_page("king-management-portal", "Estimate Management", "Manage Estimates", 1, "estimate-management", "king_estimates");
    add_submenu_page("king-management-portal", "Customer Management", "Manage Customers", 1, "customer-management", "king_customers");
    add_submenu_page("king-management-portal", "Project Management", "Manage Projects", 1, "project-management", "king_projects");
    add_submenu_page("king-management-portal", "Vendor Management", "Manage Vendors", 1, "vendor-management", "king_vendors");
    add_submenu_page("king-management-portal", "Notification Management", "Manage Notifications", 1, "notification-management", "king_notifications");
}

add_action('admin_menu', 'king_admin_actions');

//WP-File-Upload plugin includes

wp_enqueue_style('wordpress-file-upload-style', WPFILEUPLOAD_DIR.'css/wordpress_file_upload_style.css',false,'1.0','all');
wp_enqueue_style('wordpress-file-upload-style-safe', WPFILEUPLOAD_DIR.'css/wordpress_file_upload_style_safe.css',false,'1.0','all');
wp_enqueue_script('json_class', WPFILEUPLOAD_DIR.'js/json2.js');
wp_enqueue_script('wordpress_file_upload_script', WPFILEUPLOAD_DIR.'js/wordpress_file_upload_functions.js');
wp_enqueue_script('jquery_validate','http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

function getFileList($dir){
  // array to hold return value
  $retval = array();

  // add trailing slash if missing
  if(substr($dir, -1) != "/") $dir .= "/";

  // open pointer to directory and read list of files
  $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
  while(false !== ($entry = $d->read())) {
    // skip hidden files
    if($entry[0] == ".") continue;
    if(is_dir("$dir$entry")) {
      $retval[] = array(
        "name" => "$dir$entry/",
        "type" => filetype("$dir$entry"),
        "size" => 0,
        "lastmod" => filemtime("$dir$entry")
      );
    } elseif(is_readable("$dir$entry")) {
      $retval[] = array(
        "name" => "$dir$entry",
        "type" => mime_content_type("$dir$entry"),
        "size" => filesize("$dir$entry"),
        "lastmod" => filemtime("$dir$entry")
      );
    }
  }
  $d->close();

  return $retval;
}

function updateProject($project_id){
    global $wpdb;
    
    $wpdb->update('wp_king_projects', array(
                                             'project_updatedat' => date("Y-m-d H:i:s")
                                             ), 
                                       array('project_id'=>$project_id));    
}

function startProject($project_id){
    global $wpdb;
    
    $wpdb->update('wp_king_projects', array(
                                             'project_start_date' => date("Y-m-d H:i:s")
                                             ), 
                                       array('project_id'=>$project_id));    
}

function format_date($updated_at, $update_type){

  $interval = date_diff(new DateTime($updated_at), date_create("now"));
  
  if(($update_type == 'updateDate') && ($interval->format('%a') > get_option('project_stale_threshold'))){
    return "<span style='color:red'>" . $interval->format('%a days ago') . "</span>";
  }else{
    if($update_type == 'startDate' && ($updated_at == 0)){
      return "<span style='color:red'>NOT STARTED</span>"; 
    }
    return $interval->format('%a days ago');
  }

}



?>
