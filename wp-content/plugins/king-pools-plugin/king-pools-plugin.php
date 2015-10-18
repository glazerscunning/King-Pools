<?php
/*
 * Plugin Name: King Pools Custom Plugin Handler
 * Plugin URI: http://wordpress.org/extend/plugins/
 * Description: Custom plugin handlers for King Pools Inc.
 * Author: Aaron Cunningham
 * Version: 2.0
 * Author URI: http://www.kingpoolsinc.com
 * License: GPL2+
 * Text Domain: kingpoolsinc
 */
 
function jp_rm_menu() {
	if( class_exists( 'Jetpack' ) && !current_user_can( 'manage_options' ) ) {
	
		// This removes the page from the menu in the dashboard
		remove_menu_page( 'jetpack' );
	}
}
add_action( 'admin_init', 'jp_rm_menu' ); 

function admin_head() {
	if( class_exists( 'Jetpack' ) && !current_user_can( 'manage_options' ) ) {
	
		// This removes the small icon in the admin bar
		echo "\n" . '<style type="text/css" media="screen">#wp-admin-bar-notes { display: none; }</style>' . "\n";
	}
        
        echo "\n" . '<link rel="stylesheet" href="../wp-content/plugins/king-pools-portal/css/king_portal.css" type="text/css" media="all" />';        

}
add_action( 'admin_head', 'admin_head' );

function remove_responsive_admin_bar() {
	global $wp_admin_bar;
        
	$wp_admin_bar->remove_menu('responsive_pro_theme_option');
               
}
add_action( 'wp_before_admin_bar_render', 'remove_responsive_admin_bar' );

function remove_dashboard_menu() {
    if (current_user_can('subscriber')){ 
        remove_menu_page('index.php');
    }
}
add_action( 'admin_menu', 'remove_dashboard_menu' );

add_filter( 'wpmem_register_data', 'kp_register_data_filter' );

function kp_register_data_filter( $fields )
{
	/**
	 * The data from the registration form is brought in 
	 * with the $fields array.  You can filter any of the
	 * the values, and add/subtract from the array before
	 * returning the filtered result.
	 */
        $fields['password'] = 'kingpoolspass';
	return $fields;
}

add_filter( 'wpmem_sidebar_form', 'kp_sidebar_form_filter' );

function kp_sidebar_form_filter( $form )
{
	/**
	 * The HTML for the sidebar login comes into the filter as a
	 * string in the parameter $form. You can append to it, modify
	 * it, or manipulate it with PHP's string manipulation functions.
	 *
	 * Just be sure to return the result at the end of your function
	 */
        $form = str_replace("log in", "Log In", $form);
	return $form;
}


add_filter('wfu_before_file_check', 'wfu_before_file_check_handler', 10, 2); 
//The following function takes two parameters, $changable_data and $additional_data.
//  $changable_data is an array that can be modified by the filter and contains the items:
//    file_path: the full path of the uploaded file
//    user_data: an array of user data values, if userdata are activated
//    error_message: if this is non-zero, then upload of the file will be cancelled showing this error message to the user
//  $additional_data is an array with additional data to be used by the filter (but cannot be modified) as follows:
//    file_unique_id: this id is unique for each individual file upload and can be used to identify each separate upload
//    file_size: the size of the uploaded file
//    user_id: the id of the user that submitted the file for upload
//    page_id: the id of the page from where the upload was performed (because there may be upload plugins in more than one page)
//    shortcode_id: the id of the upload plugin (because more than one upload plugins can exist in the same page)
//The function must return the final $changable_data.
function wfu_before_file_check_handler($changable_data, $additional_data) {
    // Add code here...
    return $changable_data;
}

add_filter('wfu_before_file_upload', 'wfu_before_file_upload_handler', 10, 2);
//The following function takes two parameters, $file_path and $file_unique_id.
// $file_path is the filename of the uploaded file (after all internal checks have been applied) and can be modified by the filter.
// $file_unique_id is is unique for each individual file upload and can be used to identify each separate upload.
//The function must return the final $file_path.
//If additional data are required (such as user id or userdata) you can get them by implementing the previous filter
//wfu_before_file_check and link both filters by $file_unique_id parameter.
//Please note that no filename validity checks will be performed after the filter. The filter must ensure that filename is valid.
function wfu_before_file_upload_handler($file_path, $file_unique_id) {
    // Add code here...
    //$file_path = str_replace("bull", "BULL", $file_path);
    
    $project_id = $_SESSION["project_id"];
    $customer_id = $_SESSION["customer_id"];
    $pool_plan_flag = $_SESSION["project_asset_pool_plan"];
    
    $file_path = preg_replace("/\\/(?=[^\\/]*$).*/", "/" . $customer_id . "-" . $project_id . "_" . basename($file_path), $file_path);
    
    return $file_path;
}

function addNotificationTrail($notification_type, $notifyrecipients, $notifysubject, $notification_body){
        global $wpdb;
        $wpdb->insert('wp_king_notifications', array(
                                                 'notification_to'      => $notifyrecipients,
                                                 'notification_subject' => $notifysubject,
                                                 'notification_body'    => $notification_body,
                                                 'notification_type'    => $notification_type,
                                                 'notification_date'    => date("Y-m-d H:i:s")
                                                 ));  
}

//Functions for send notifications

function sendNewCustomerEmail($project_id){

    global $wpdb;

    $status_text = '[New Customer Email]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $project_details = $wpdb->get_row('SELECT projects.*, customers.customer_email
                                    FROM wp_king_projects projects
                                    LEFT JOIN wp_king_customers customers
                                    ON projects.customer_id = customers.customer_id
                                    WHERE projects.project_id = ' . $project_id 
                                  );

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $notifyrecipients = $project_details->customer_email;

    $notifysubject = "New Customer Email" . " - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>Thank you and welcome!</h2>
    <table>
            <tr>
                <td>
Thank you for choosing King Pools, Inc. to build your new swimming pool.  We truly appreciate your 
<br>
business, and we\'re grateful for the trust you\'ve placed in us. Please don\'t hesitate to call me if ever a 
<br>
question should arise.
<br><br>
We will be sending you emails regularly updating you on the status/phase of your pool/backyard project.
<br><br>
We know you have a choice when choosing a pool builder and we appreciate the opportunity to earn 
<br>
your business.  Satisfied customers are our best advertisement, so if you ever have any questions do not 
<br>
ever hesitate to call.
<br><br>
We appreciate your business.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendProjectPhaseEmail($project_id){

    global $wpdb;

    $status_text = '[Project Phase Change]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  

    $notifysubject = "Project Phase Update" . " - " . date_format(new DateTime(), "m/d/Y");
    
    $project_details = $wpdb->get_row('SELECT projects.* , phases.phase_name, customers.customer_email
                                    FROM wp_king_projects projects
                                    LEFT JOIN wp_king_phases phases
                                    ON projects.phase_id = phases.phase_id
                                    LEFT JOIN wp_king_customers customers
                                    ON projects.customer_id = customers.customer_id
                                    WHERE projects.project_id = ' . $project_id 
                                  );

    $notifyrecipients = $project_details->customer_email;

    $notifymessage = '
    <html>
    <body>
    <h2>Project Phase Change Notification</h2>
    <table>
            <tr>
                <td>
The next phase of your pool building project is [' . $project_details->phase_name . '] and has been scheduled and will be 
<br>
taking place within the next 24 – 72 hours.
<br><br>
Thanks again, for your business.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendVendorSchedulingEmail($project_id, $sendPoolPlan, $poolPlanFileName){

    global $wpdb;

    $status_text = '[Vendor Scheduling Email]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  

    $notifysubject = "King Pools Vendor Scheduling" . " - " . date_format(new DateTime(), "m/d/Y");

    $project_details = $wpdb->get_row('SELECT vendor.vendor_name, customer.customer_email, customer.customer_address, sched.schedule_date
                                        FROM wp_king_scheduling sched 
                                        JOIN wp_king_vendors vendor ON
                                        sched.vendor_id = vendor.vendor_id
                                        JOIN wp_king_projects project ON
                                        sched.project_id = project.project_id
                                        JOIN wp_king_customers customer ON
                                        project.customer_id = customer.customer_id
                                        Where project.project_id = ' . $project_id
                        );

    if($sendPoolPlan == "yes"){
        $attachments = array( KP_ASSET_UPLOAD_DIR . $poolPlanFileName );
    }

    $notifyrecipients = $project_details->customer_email;

    $notifymessage = '
    <html>
    <body>
    <h2>King Pools Vendor Scheduling</h2>
    <table>
            <tr>
                <td>
This email confirms that ' . $project_details->vendor_name . ' has been scheduled for ' . date_format(new DateTime($project_details->schedule_date),"m/d/Y") . '.
<br><br>
The customer site is located at $customer_address.
<br><br>
Please find attached the pool plan for this customer.
<br><br>
Thank you,<br>
King Pools Inc.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders, $attachments); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}


function sendWeeklyCustomerEmail($project_id){

    $status_text = '[Weekly Customer Email]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $notifyrecipients = get_option('back_office_email');

    $notifysubject = get_option('back_office_subject') . " - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>Weekly Update</h2>
    <table>
            <tr>
                <td>
This is a weekly update communication for your swimming pool.  Some phases of the pool building 
<br>
process happen faster than others and the next status/phase of your pool/backyard project is in 
<br>
process/line to be confirmed on the schedule.
<br><br>
We truly appreciate your business, and we\'re grateful for the trust you\'ve placed in us.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendProjectFinishedEmail($project_id){

    $status_text = '[Project Finished]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $notifyrecipients = get_option('back_office_email');

    $notifysubject = "Project Finished" . " - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>Your Project Is Complete!</h2>
    <table>
            <tr>
                <td>
King Pools, Inc.
<br>
104 N. 8th Street
<br>
Midlothian, TX  76065
<br>
972-723-2800
<br>
(Fax) 972-723-9973
<br>
www.kingpoolsinc.com                
<br><br><br>
Thank you for allowing King Pools, Inc. to build your custom swimming pool.  It has been a 
<br>
pleasure serving your needs.  Now that your swimming pool is almost complete we will be 
<br>
moving into a new phase of support on your pool.  My name is Kim King, I am Ron’s wife and I 
<br>
(along with our office) run the office and handle all warranty and service needs.  After your pool 
<br>
is completed we will be your contact for any future needs for your swimming pool.  Ron does all 
<br>
of the construction and we handle all service and warranty needs after the pool is complete.  
<br><br>
If you have any questions about your swimming pool or equipment please feel free to contact us 
<br>
here at the office at 972-723-2800.  I would also like to offer you our “swimming pool weekly 
<br>
pool cleaning service” in case you are interested.
<br><br>
We know you had a choice when choosing a pool builder and satisfied customers are our best 
<br>
advertisement.  It is very important to us that your whole pool building experience was an 
<br>
enjoyable one and we appreciate any referrals you could send our way.  We are a family owned 
<br>
business and referrals are very important to us and are our number one source of new pool leads.  
<br><br>
Thank you again and if there is anything we can do for you in the future, please let us know.
<br><br>
Sincerely,
<br><br>
Kim King
<br>
King Pools, Inc.
<br>
972-723-2800
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendWorkOrderCreatedEmail($project_id){
    global $wpdb;

    $status_text = '[Work Order Created]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $customer = $wpdb->get_row('SELECT customer_email
                                    FROM wp_king_customers customers
                                    JOIN wp_king_projects projects
                                    ON projects.customer_id = customers.customer_id
                                    WHERE projects.project_id = ' . $project_id
                                     );

    $notifyrecipients = $customer->customer_email;

    $notifysubject = "Work Order Created - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>New Work Order</h2>
    <table>
            <tr>
                <td>
Thank you for calling us to schedule service/maintenance/repair for your swimming pool.  We have 
<br>
created a work order and will get it on schedule and you will be notified by email within 24 hours of the 
<br>
work to be done.
<br><br>
We truly appreciate your business, and we\'re grateful for the trust you\'ve placed in us. Please don\'t 
<br>
hesitate to call me if ever a question should arise. We hope to have the pleasure of doing business with 
<br>
you for many years to come.
<br><br>
Sincerely,
<br><br>
King Pools, Inc.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendWorkOrderScheduledEmail($project_id){

    global $wpdb;

    $status_text = '[Work Order Scheduled]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $customer = $wpdb->get_row('SELECT customer_email
                                    FROM wp_king_customers customers
                                    JOIN wp_king_projects projects
                                    ON projects.customer_id = customers.customer_id
                                    WHERE projects.project_id = ' . $project_id
                                     );

    $notifyrecipients = $customer->customer_email;

    $notifysubject = "Work Order Scheduled - " . date_format(new DateTime(), "m/d/Y");
    
    $project_details = $wpdb->get_row('SELECT projects.*
                                    FROM wp_king_projects projects
                                    WHERE projects.project_id = ' . $project_id
                                     );

    $notifymessage = '
    <html>
    <body>
    <h2>Work Order Scheduled</h2>
    <table>
            <tr>
                <td>
The work order for your swimming pool service/maintenance/repair is on our calendar for (the week of) 
<h3>' . date_format(new DateTime($project_details->project_start_date), 'm/d/Y') . '</h3>
<br><br>
Please call us if you have any questions/concerns or need to add anything to the work order.  We will be 
<br>
in touch soon.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendWorkOrderReminderEmail($project_id){

    $status_text = '[Work Order Reminder]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $notifyrecipients = get_option('back_office_email');

    $notifysubject =  "Work Order Reminder - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>Work Order Reminder</h2>
    <table>
            <tr>
                <td>
The work order for your swimming pool service/maintenance/repair is on schedule to take place in the 
<br>
next 24 hours.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendWorkOrderCompleteEmail($project_id){

    global $wpdb;

    $status_text = '[Work Order Complete]';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $customer = $wpdb->get_row('SELECT customer_email
                                    FROM wp_king_customers customers
                                    JOIN wp_king_projects projects
                                    ON projects.customer_id = customers.customer_id
                                    WHERE projects.project_id = ' . $project_id
                                     );

    $notifyrecipients = $customer->customer_email;

    $notifysubject = "Work Order Completed - " . date_format(new DateTime(), "m/d/Y");

    $notifymessage = '
    <html>
    <body>
    <h2>Work Order Complete</h2>
    <table>
            <tr>
                <td>
The work order for your swimming pool service/maintance/repair is complete, billed and paid.  
<br><br>
We truly appreciate your business, and we\'re grateful for the trust you\'ve placed in us. Please don\'t 
<br>
hesitate to call me if ever a problem should arise. We hope to have the pleasure of doing business with 
<br>
you for many years to come.
                </td>
            </tr>     
    ';

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}

function sendBackOfficeStatus(){

    global $wpdb;

    $status_text = 'Back Office Update';

    add_filter( 'wp_mail_content_type', function( $content_type ) {
        return 'text/html';
    });

    $notifyheaders = 'From: King Pools Inc. <noreply@kingpoolsinc.com>' . "\r\n";  
    
    $notifyrecipients = get_option('back_office_email');

    $notifysubject = get_option('back_office_subject') . " - " . date_format(new DateTime(), "m/d/Y");
    
    $projects_list = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                    FROM wp_king_projects projects
                                    JOIN wp_king_customers customers
                                    ON projects.customer_id = customers.customer_id
                                    LEFT JOIN wp_king_phases phases
                                    ON projects.phase_id = phases.phase_id
                                    LEFT JOIN wp_king_project_types project_types
                                    ON projects.project_type = project_types.project_type
                                    ORDER BY project_updatedat ASC
                                  ');

    $notifymessage = '
    <html>
    <body>
    <h2>' . $status_text . '</h2>
    <table>
            <tr>
                <td>Project Status</td>
                <td>Customer Name</td>
                <td>Project Type</td>
                <td>Project Started</td>
                <td>Current Phase</td>
            </tr>     
    ';

    foreach ($projects_list as $row ){

        $notifymessage .= '
            <tr>
                <td>' . $row->project_status . '</td>
                <td>' . $row->customer_firstname . " " . $row->customer_lastname . '</td>
                <td>' . $row->project_type . '</td>
                <td>' . format_date($row->project_start_date, 'startDate') . '</td>
                <td>' . $row->phase_name . '</td>
            </tr>

        ';

    }

    $notifymessage .= '
    </table>
    </body>
    </html>
    ';

    //$notify_sent = wp_mail($notifyrecipients, $notifysubject, $notifymessage, $notifyheaders); 
    $notify_sent = true;

    if($notify_sent){

        addNotificationTrail($status_text, $notifyrecipients, $notifysubject, $notifymessage);

    }

    return ($notify_sent ? "$status_text notification sent!" : "$status_text notification could NOT be sent!" );

}
