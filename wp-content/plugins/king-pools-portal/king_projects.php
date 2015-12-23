<?php

$_SESSION["project_id"] = $_REQUEST['id'];

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Project Management', 'king_trdom' );?>
<a class='add-new-h2' href='?page=<?=$_REQUEST['page']?>&action=add'>Add New Project</a>
</h2>
<?php

if($_REQUEST['action'] == 'view' || $_REQUEST['action'] == 'add'){
    
    global $wpdb;
    
    if($_REQUEST['action'] == 'view'){
        $result = $wpdb->get_row('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, vendors.vendor_name, vendors.vendor_id, phases.phase_name, phases.phase_type, scheduling.schedule_date
                                   FROM ' . $wpdb->prefix . 'king_projects projects
                                   JOIN ' . $wpdb->prefix . 'king_customers customers
                                   ON projects.customer_id = customers.customer_id
                                   LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                   ON projects.phase_id = phases.phase_id
                                   LEFT JOIN ' . $wpdb->prefix . 'king_scheduling scheduling 
                                   ON scheduling.project_id = projects.project_id                                   
                                   LEFT JOIN ' . $wpdb->prefix . 'king_vendors vendors
                                   ON vendors.vendor_id = scheduling.vendor_id
                                   WHERE projects.project_id = ' . $_REQUEST['id']
                                );
        $_SESSION["customer_id"] = $result->customer_id;

    } else if ($_REQUEST['action'] == 'add'){
        $customers = $wpdb->get_results('SELECT customers.customer_id, customers.customer_firstname, customers.customer_lastname
                                        FROM ' . $wpdb->prefix . 'king_customers customers
                                        WHERE customers.customer_status = "active"
                                        ORDER BY customers.customer_firstname, customers.customer_lastname'
                                       );   
    }

    $phases = $wpdb->get_results('SELECT * 
                                    FROM ' . $wpdb->prefix . 'king_phases
                                    ORDER BY phase_id'
                                );
    
    $vendors = $wpdb->get_results('SELECT * 
                                    FROM ' . $wpdb->prefix . 'king_vendors
                                    ORDER BY vendor_name'
                                );    

    $project_phase_link = $wpdb->get_results('SELECT * 
                                    FROM ' . $wpdb->prefix . 'king_project_phase_link
                                    Where project_id = ' . $_SESSION['project_id'] . '
                                    ORDER BY phase_id'
                                );

foreach($phases as $key => $value) {

    foreach($project_phase_link as $link){

        if($link->phase_id == $phases[$key]->phase_id){
            
            if($link->status == 'complete'){
                $phases[$key]->phase_status = 'complete';
            }
        
        }else{
        
            if(empty($phases[$key]->phase_status)){
                $phases[$key]->phase_status = 'incomplete';
            }
        
        }
    
    }

}

?>
<hr>  
    
<?php
include("inc/_project_jquery.js");

if($_REQUEST['action'] == 'view'){  
?>
<div style="width:500px">
  
<h3>Project Assets</h3>
<?php

echo do_shortcode('[wordpress_file_upload uploadpath="uploads/king_portal" createpath="true" showtargetfolder="true" adminmessages="true" placements="title/filename+selectbutton/uploadbutton+progressbar/message/userdata" uploadtitle="" widths="progressbar:200px"]');

echo "<hr>";

$dirlist = getFileList(KP_ASSET_UPLOAD_DIR);

echo "<table id='project_assets' border=\"1\">\n";
echo "<thead>\n";
echo "<tr><th>Pool Plan?</th><th>Delete</th><th>Name</th><th>Size</th><th>Last Modified</th></tr>\n";
echo "</thead>\n";
echo "<tbody>\n";

foreach($dirlist as $file) {
 
    if(preg_match("/.*". $_SESSION['customer_id'] . "-" . $_SESSION['project_id'] . ".*/", basename($file['name']))){    
        echo "<tr>\n";
        echo "<td style='text-align:center;'><a href='?page=" . $_REQUEST['page'] . "&action=select_pool_plan&customer=" . urlencode($result->customer_firstname . " " . $result->customer_lastname) . "&file_name=" . urlencode(basename($file['name'])) . "'>Select</a></td>\n";
        echo "<td style='text-align:center;'><a href='?page=" . $_REQUEST['page'] . "&action=delete_asset&customer=" . urlencode($result->customer_firstname . " " . $result->customer_lastname) . "&file_name=" . urlencode(basename($file['name'])) . "'>X</a></td>\n";
        echo "<td>" . basename($file['name']) . "</td>\n";
        echo "<td>{$file['size']}</td>\n";
        echo "<td>",date('r', $file['lastmod']),"</td>\n";
        echo "</tr>\n";
    }
}
echo "</tbody>\n";
echo "</table>\n\n";

?>
<hr>
</div>

<form method="post" id="project_edit_form" name="project_edit_form" action="?page=<?=$_REQUEST['page']?>&action=update">
<?php
} else {
?>
<form method="post" id="project_edit_form" name="project_add_form" action="?page=<?=$_REQUEST['page']?>&action=add_project">
<?php
}
?>
<?php echo "<h3>" . __( 'Project Details', 'king_trdom' ) . "</h3>"; ?>

<?php include("inc/_project_form.php");?>

</form>
<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'king_projects', array( 'project_id' => $_REQUEST['id'] ) );
    
    //TODO: 
    //Add code to remove files from uploads directory based on regex
    //Example: 2-3_financing.png
    //[1-100]-{$_REQUEST['id']}.*

    echo "<div id='message' class='error'>Project #" . $_REQUEST['id'] . " has been deleted!</div>";
 
} else if($_REQUEST['action'] == 'delete_asset'){

    unlink(KP_ASSET_UPLOAD_DIR . $_REQUEST['file_name']);
    echo "<div id='message' class='updated'>Project asset '" . urldecode($_REQUEST['file_name']) . "' has been deleted for customer '" . urldecode($_REQUEST['customer']) . "'.</div>";

} else if($_REQUEST['action'] == 'select_pool_plan'){

    rename(KP_ASSET_UPLOAD_DIR . $_REQUEST['file_name'], KP_ASSET_UPLOAD_DIR . "PoolPlan_" . $_REQUEST['file_name']);
    echo "<div id='message' class='updated'>Project asset '" . urldecode($_REQUEST['file_name']) . "' has been selected as the pool plan for customer '" . urldecode($_REQUEST['customer']) . "'.</div>";

} else if($_REQUEST['action'] == 'update'){

    global $wpdb;
 
    $wpdb->update($wpdb->prefix . 'king_projects', array(
                                             'project_type'   => $_REQUEST['project_type'],
                                             'phase_id'       => $_REQUEST['project_phase'],
                                             'project_status' => $_REQUEST['project_status'],
                                             'project_amount' => $_REQUEST['project_amount'],
                                             'project_updatedat' => date("Y-m-d H:i:s"),
                                             'project_notes'  => $_REQUEST['project_notes']
                                             ), 
                                       array('project_id'=>$_REQUEST['project_id']));

    $result = $wpdb->get_row('SELECT projects.project_id, projects.phase_id, phases.phase_type, phases.phase_name, phases.phase_trigger_customer_email, phases.phase_trigger_vendor_email, sched.vendor_id, vendors.vendor_name
                               FROM ' . $wpdb->prefix . 'king_projects projects
                               JOIN ' . $wpdb->prefix . 'king_phases phases
                               ON projects.phase_id = phases.phase_id
                               LEFT JOIN ' . $wpdb->prefix . 'king_scheduling sched
                               ON projects.project_id = sched.project_id
                               AND projects.phase_id = sched.phase_id
                               LEFT JOIN ' . $wpdb->prefix . 'king_vendors vendors 
                               ON sched.vendor_id = vendors.vendor_id
                               WHERE projects.project_id = ' . $_REQUEST['project_id']
                            );

    if($_REQUEST['project_type'] == 'cleaning' || $_REQUEST['project_type'] == 'service-repair'){

        if($_REQUEST['last_project_status'] == 'New' && $_REQUEST['project_status'] == 'In Progress'){

            startProject($_REQUEST['project_id']);

            if(!empty($_REQUEST['wo_schedule_date'])){

                $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_scheduling WHERE project_id = ' . $_REQUEST['project_id']);

                if($wpdb->num_rows == 0){
                    $wpdb->insert($wpdb->prefix . 'king_scheduling', array(
                                                         'vendor_id'   => 0,
                                                         'project_id'   => $_REQUEST['project_id'],
                                                         'schedule_date'   => $_REQUEST['wo_schedule_date'],
                                                         'last_updated' => date("Y-m-d H:i:s"),
                                                         'phase_id'     => 0
                                                         ));  
                    sendWorkOrderScheduledEmail($_REQUEST['project_id'], $_REQUEST['wo_schedule_date']);
                    echo '<div id="message" class="updated">Work Order has been scheduled for the week of ' . date_format(new DateTime($_REQUEST['wo_schedule_date']), 'm-d-Y') . '</div>'; 
      
                }

            }

        }       

    }elseif ($_REQUEST['project_type'] == 'construction-remodel') {
        
        if($_REQUEST['last_project_status'] == 'New' && $_REQUEST['project_status'] == 'In Progress'){

            startProject($_REQUEST['project_id']);
            echo '<div id="message" class="updated">' . sendNewCustomerEmail($_REQUEST['project_id']) . '</div>';

        }

        if($result->phase_trigger_customer_email > 0){

            $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_notifications WHERE project_id = ' . $_REQUEST['project_id'] . ' AND phase_id = ' . $result->phase_id . ' AND notification_type = "[Project Phase Change - ' . $result->phase_id . ' {' . $result->phase_name . '}]"');  

            if($wpdb->num_rows == 0){       
                echo '<div id="message" class="updated">' . sendProjectPhaseEmail($_REQUEST['project_id'], $result->phase_id, $result->phase_name) . '</div>';
            }
        }

        if($result->phase_trigger_vendor_email > 0){

            $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_notifications WHERE project_id = ' . $_REQUEST['project_id'] . ' AND phase_id = ' . $result->phase_id . ' AND notification_type = "[Vendor Scheduling Email - ' . $result->vendor_id . ' {' . $result->vendor_name . '}]"');

            if($wpdb->num_rows == 0){

                $wpdb->insert($wpdb->prefix . 'king_scheduling', array(
                                                         'vendor_id'   => $_REQUEST['vendor_id'],
                                                         'project_id'   => $_REQUEST['project_id'],
                                                         'schedule_date'   => $_REQUEST['vnd_schedule_date'],
                                                         'last_updated' => date("Y-m-d H:i:s"),
                                                         'phase_id'     => $result->phase_id
                                                         ));

                $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_vendors WHERE vendor_id = ' . $_REQUEST['vendor_id']);
                
                $poolPlanFileName = "PoolPlan_" . $_SESSION['customer_id'] . "-" . $_REQUEST['project_id'];

                sendVendorSchedulingEmail($_REQUEST['project_id'], $result->vendor_id, $result->vendor_name, $_REQUEST['attach_pool_plan'], $poolPlanFileName);
                
                echo '<div id="message" class="updated">An email has been sent to ' . $result->vendor_email . ' to schedule services on ' . date_format(new DateTime($_REQUEST['vnd_schedule_date']), 'm/d/Y') . '</div>';

            }

        }

        echo '<div id="message" class="updated">Project has been updated!</div>';

    }

    if ($_REQUEST['last_project_status'] == 'In Progress' && $_REQUEST['project_status'] == 'Complete'){
        global $wpdb;
        $wpdb->update($wpdb->prefix . 'king_projects', array(
                                                 'project_status'  => 'Complete',
                                                 'phase_id'       => $_REQUEST['project_phase'],
                                                 'project_status' => $_REQUEST['project_status'],
                                                 'project_amount' => $_REQUEST['project_amount'],
                                                 'project_updatedat' => date("Y-m-d H:i:s")
                                                 ), 
                                           array('project_id'=>$_REQUEST['project_id']));

        if($_REQUEST['project_type'] == 'cleaning' || $_REQUEST['project_type'] == 'service-repair'){  
            echo '<div id="message" class="updated">' . sendWorkOrderCompleteEmail($_REQUEST['project_id']) . '</div>';
            echo '<div id="message" class="updated">Project has been completed!</div>';    
        }else{
            echo '<div id="message" class="updated">' . sendProjectFinishedEmail($_REQUEST['project_id']) . '</div>';
            echo '<div id="message" class="updated">Project has been completed!</div>';
        }            
    }


} else if($_REQUEST['action'] == 'add_project'){
    global $wpdb;

    $wpdb->insert($wpdb->prefix . 'king_projects', array(
                                             'project_status'  => 'New',
                                             'customer_id'     => $_REQUEST['customer_id'],
                                             'project_type'    => $_REQUEST['project_type'],
                                             'phase_id'        => $_REQUEST['project_phase'],
                                             'project_updatedat' => date("Y-m-d H:i:s")
                                             ));

    $project_id = $wpdb->insert_id;

    if($_REQUEST['project_type'] == 'cleaning' || $_REQUEST['project_type'] == 'service-repair'){  
        
        if(!empty($_REQUEST['wo_schedule_date'])){

            $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_scheduling WHERE project_id = ' . $project_id);

            if($wpdb->num_rows == 0){
                $wpdb->insert($wpdb->prefix . 'king_scheduling', array(
                                                     'vendor_id'   => 0,
                                                     'project_id'   => $project_id,
                                                     'schedule_date'   => $_REQUEST['wo_schedule_date'],
                                                     'last_updated' => date("Y-m-d H:i:s"),
                                                     'phase_id'     => 0
                                                     ));  
                sendWorkOrderScheduledEmail($project_id, $_REQUEST['wo_schedule_date']);
                echo '<div id="message" class="updated">Work Order has been scheduled for the week of ' . date_format(new DateTime($_REQUEST['wo_schedule_date']), 'm-d-Y') . '</div>'; 
  
            }

        }

    }else{
        echo '<div id="message" class="updated">Project has been created!</div>';
    }
    
}

include("inc/_project_list_table.php");
$projectListTable = new Project_List_Table();
$projectListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=construction-remodel">Pool Construction/Remodel</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=cleaning">Pool Cleaning</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=service-repair">Service & Repair</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=outstanding"><span style="color:red">OUTSTANDING</span></a>
    </li>
</ul>

<form id="projects" method="get">
<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$projectListTable->display();?>
</form>

</div>