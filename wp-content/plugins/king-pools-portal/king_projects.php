<?php

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
                                   FROM wp_king_projects projects
                                   JOIN wp_king_customers customers
                                   ON projects.customer_id = customers.customer_id
                                   LEFT JOIN wp_king_phases phases
                                   ON projects.phase_id = phases.phase_id
                                   LEFT JOIN wp_king_scheduling scheduling 
                                   ON scheduling.project_id = projects.project_id                                   
                                   LEFT JOIN wp_king_vendors vendors
                                   ON vendors.vendor_id = scheduling.vendor_id
                                   WHERE projects.project_id = ' . $_REQUEST['id']
                                );
        $_SESSION["customer_id"] = $result->customer_id;

        $phases = $wpdb->get_results('SELECT * 
                                        FROM wp_king_phases
                                        ORDER BY phase_id'
                                    );
        
        $vendors = $wpdb->get_results('SELECT * 
                                        FROM wp_king_vendors
                                        ORDER BY vendor_name'
                                    );

    } else if ($_REQUEST['action'] == 'add'){
        $customers = $wpdb->get_results('SELECT customers.customer_id, customers.customer_firstname, customers.customer_lastname
                                        FROM wp_king_customers customers
                                        WHERE customers.customer_status = "active"
                                        ORDER BY customers.customer_firstname, customers.customer_lastname'
                                       );   
    }
?>
<hr>  
    
<?php
if($_REQUEST['action'] == 'view'){
?>
<div style="width:500px">
<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#schedule_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });

<?php
if(empty($result->vendor_name)){
?>    
    jQuery('.vendor_phase').hide();
<?php 
}
?>    
    jQuery(".phase_target").change(function() {
        if(jQuery("select.phase_target option:selected").attr("email_trigger") > 0){
            jQuery('.vendor_phase').fadeIn();
        }else{
            jQuery('.vendor_phase').fadeOut();
        }
    });

});

</script>   
<h3>Project Assets</h3>
<?php

$_SESSION["project_id"] = $_REQUEST['id'];
echo do_shortcode('[wordpress_file_upload uploadpath="uploads/king_portal" createpath="true" showtargetfolder="true" adminmessages="true" placements="title/filename+selectbutton/uploadbutton+progressbar/message/userdata" uploadtitle="" widths="progressbar:200px"]');

echo "<hr>";

$dirlist = getFileList(KP_ASSET_UPLOAD_DIR);

// output file list in HTML TABLE format
echo "<table id='project_assets' border=\"1\">\n";
echo "<thead>\n";
echo "<tr><th>Delete</th><th>Name</th><th>Size</th><th>Last Modified</th></tr>\n";
echo "</thead>\n";
echo "<tbody>\n";

foreach($dirlist as $file) {
 
    if(preg_match("/^". $_SESSION['customer_id'] . "-" . $_SESSION['project_id'] . ".*/", basename($file['name']))){    
        echo "<tr>\n";
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

    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label><?php _e("Customer Name: " ); ?></label>
                </th>
                <td>
                <?php
                if($_REQUEST['action'] == 'add'){
                ?>  
                    <select name="customer_id">
                        <option value="">Please select...</option>
                        <?php
                        foreach ($customers as $customer ){
                        ?>
                        <option value="<?=$customer->customer_id;?>"><?=$customer->customer_firstname . " " . $customer->customer_lastname;?></option>    
                        <?php
                        }
                        ?>
                    </select>
                <?php
                } else {
                ?>
                    <span class="description"><?=$result->customer_firstname . " " . $result->customer_lastname;?></span>
                <?php
                }
                ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Status: " ); ?></label>
                </th>
                <td>
                <?php
                if($_REQUEST['action'] == 'add'){
                ?>      
                    <span class="description"><?= ($_REQUEST['action'] == 'add') ? 'New' : $result->project_status;?></span>
                <?php
                } else {
                ?>
                    <select name="project_status">
                        <option value="New" <?= ($result->project_status == "New") ? "selected='selected'" : "";?>>New</option>
                        <option value="In Progress" <?= ($result->project_status == "In Progress") ? "selected='selected'" : "";?>>In Progress</option>
                        <option value="Complete" <?= ($result->project_status == "Complete") ? "selected='selected'" : "";?>>Complete</option>
                    </select>                
                <?php
                }
                ?>                
                </td>
            </tr> 
            <tr>
                <th>
                    <label><?php _e("Project Amount: " ); ?></label>
                </th>
                <td>
                    $<input type="text" name="project_amount" value="<?= $result->project_amount?>"/><i>(Final project amount)</i>
                </td>
            </tr>                       
            <tr>
                <th>
                    <label><?php _e("Type: " ); ?></label>
                </th>
                <td>
                    <select name="project_type">
                        <option value="">Please select...</option>
                        <option value="construction-remodel" <?= ($result->project_type == "construction-remodel") ? "selected='selected'" : "";?>>Construction/Remodel</option>
                        <option value="cleaning" <?=($result->project_type == "cleaning") ? "selected='selected'" : "";?>>Pool Cleaning</option>
                        <option value="service-repair" <?=($result->project_type == "service-repair") ? "selected='selected'" : "";?>>Service/Repair</option>
                    </select>
                </td>
            </tr>             
            <tr>
                <th>
                    <label><?php _e("Construction Phase: " ); ?></label>
                </th>
                <td>
                    <select name="project_phase" class="phase_target">
                        <option value="">N/A</option>
<?php
                    foreach($phases as $phase){
?>
                        <option email_trigger="<?=$phase->phase_trigger_vendor_email?>" value="<?=$phase->phase_id?>" <?= ($result->phase_id == $phase->phase_id) ? "selected='selected'" : "";?>><?=$phase->phase_id?>) <?=$phase->phase_name?></option>  
<?php                        
                    }        
?>              
                    
                    </select>
                </td>
            </tr>
            <tr class="vendor_phase">
                <td colspan=2 style="color:blue">
                    <i>This phase will notify the vendor of the confirmed schedule.</i>
                </td>
            </tr> 
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Vendor Selection: " ); ?></label>
                </th>
                <td>
                    <select name="vendor_id">
                        <option value="">N/A</option>
<?php
                    foreach($vendors as $vendor){
?>
                        <option value="<?=$vendor->vendor_id?>" <?= ($result->vendor_id == $vendor->vendor_id) ? "selected='selected'" : "";?>><?=$vendor->vendor_name?></option>  
<?php                        
                    }        
?>              
                    
                    </select>
                </td>
            </tr>                       
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Scheduling: " ); ?></label>
                </th>
                <td>
                    <input type="text" id="schedule_date" name="schedule_date" value="<?= $result->schedule_date?>"/>                                  
                </td>
            </tr>
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Send pool plan to vendor?: " ); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="attach_plan" name="attach_plan" value="yes"/>                                  
                </td>
            </tr>            
        </tbody>
    </table>    
    <input type="hidden" name="project_id" value="<?=$_REQUEST['id']?>"/>
    <input type="hidden" name="last_project_status" value="<?=$result->project_status;?>"/>
    <p class="submit">
<?php
if($_REQUEST['action'] == 'view'){
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Project', 'king_trdom' ) ?>" />

<?php
} else {
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Add Project', 'king_trdom' ) ?>" /> 
<?php
}
?>           
    </p>
</form>

<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    global $wpdb;
    $wpdb->delete( 'wp_king_projects', array( 'project_id' => $_REQUEST['id'] ) );
    
    //Add code to remove files from uploads directory based on regex
    //Example: 2-3_financing.png
    //[1-100]-{$_REQUEST['id']}.*

    echo "<div id='message' class='error'>Project #" . $_REQUEST['id'] . " has been deleted!</div>";
 
} else if($_REQUEST['action'] == 'delete_asset'){

    unlink(KP_ASSET_UPLOAD_DIR . $_REQUEST['file_name']);
    echo "<div id='message' class='updated'>Project asset '" . urldecode($_REQUEST['file_name']) . "' has been deleted for customer '" . urldecode($_REQUEST['customer']) . "'.</div>";

} else if($_REQUEST['action'] == 'update'){

    global $wpdb;
 
    $wpdb->update('wp_king_projects', array(
                                             'project_type'   => $_REQUEST['project_type'],
                                             'phase_id'       => $_REQUEST['project_phase'],
                                             'project_status' => $_REQUEST['project_status'],
                                             'project_amount' => $_REQUEST['project_amount'],
                                             'project_updatedat' => date("Y-m-d H:i:s")
                                             ), 
                                       array('project_id'=>$_REQUEST['project_id']));

    $result = $wpdb->get_row('SELECT projects.project_id, projects.phase_id, phases.phase_type, vendors.vendor_id, vendors.vendor_type, vendors.vendor_email, phases.phase_trigger_customer_email, phases.phase_trigger_vendor_email
                               FROM wp_king_projects projects
                               JOIN wp_king_phases phases
                               ON projects.phase_id = phases.phase_id
                               LEFT JOIN wp_king_vendors vendors
                               ON phases.vendor_id = vendors.vendor_id
                               WHERE project_id = ' . $_REQUEST['project_id']
                            );

    if($_REQUEST['last_project_status'] == 'New' && $_REQUEST['project_status'] == 'In Progress'){
        startProject($_REQUEST['project_id']);
        echo '<div id="message" class="updated">' . sendNewCustomerEmail($_REQUEST['project_id']) . '</div>';
    }

    if($result->phase_trigger_customer_email > 0){
        echo '<div id="message" class="updated">' . sendProjectPhaseEmail($_REQUEST['project_id']) . '</div>';
    }

    if($result->phase_trigger_vendor_email > 0){

        $wpdb->insert('wp_king_scheduling', array(
                                                 //'vendor_id'   => $result->vendor_id,
                                                 'vendor_id'   => $_REQUEST['vendor_id'],
                                                 'project_id'   => $_REQUEST['project_id'],
                                                 'schedule_date'   => $_REQUEST['schedule_date'],
                                                 'last_updated' => date("Y-m-d H:i:s"),
                                                 'phase_id'     => $result->phase_id
                                                 ));

        //Send email to vendor with $result->vendor_email

        echo '<div id="message" class="updated">Vendor has been scheduled for ' . $_REQUEST['schedule_date'] . '</div>';
    }

    echo '<div id="message" class="updated">Project has been updated!</div>';
    
} else if($_REQUEST['action'] == 'add_project'){
    global $wpdb;
    $wpdb->insert('wp_king_projects', array(
                                             'project_status'  => 'New',
                                             'customer_id'     => $_REQUEST['customer_id'],
                                             'project_type'    => $_REQUEST['project_type'],
                                             'phase_id'        => $_REQUEST['project_phase'],
                                             'project_updatedat' => date("Y-m-d H:i:s")
                                             ));
    
    echo '<div id="message" class="updated">Project has been created!</div>';
    
} else if($_REQUEST['action'] == 'complete_project'){
    global $wpdb;
    $wpdb->update('wp_king_projects', array(
                                             'project_status'  => 'Complete',
                                             'phase_id'       => $_REQUEST['project_phase'],
                                             'project_status' => $_REQUEST['project_status'],
                                             'project_amount' => $_REQUEST['project_amount'],
                                             'project_updatedat' => date("Y-m-d H:i:s")
                                             ), 
                                       array('project_id'=>$_REQUEST['project_id']));

    echo '<div id="message" class="updated">' . sendProjectFinishedEmail($_REQUEST['project_id']) . '</div>';
    echo '<div id="message" class="updated">Project has been completed!</div>';
}

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

<?php

class Project_List_Table extends WP_List_Table{

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        //usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        //$this->process_bulk_action();
        $this->items = $data;
    }
    
    public function get_columns()
    {
        $columns = array(
            //'cb'        => '<input type="checkbox" />',
            'project_id'      => 'ID',
            'project_status'  => 'Status',
            'customer_name'   => 'Customer Name',
            'project_type'    => 'Type',
            'project_phase'   => 'Phase',
            //'notification'    => 'Notification',
            'updated_at'      => 'Updated At',
            'project_start_date'      => 'Started'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('customer_name' => array('customer_name', false), 'project_type' => array('project_type', false), 'project_status' => array('project_status', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        
        if ($_REQUEST['list'] == 'construction-remodel'){
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM wp_king_projects projects
                                            JOIN wp_king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN wp_king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN wp_king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "construction-remodel"
                                          ');
            
        } else if ($_REQUEST['list'] == 'cleaning'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM wp_king_projects projects
                                            JOIN wp_king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN wp_king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN wp_king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "cleaning"
                                          ');                
        } else if ($_REQUEST['list'] == 'service-repair'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM wp_king_projects projects
                                            JOIN wp_king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN wp_king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN wp_king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "service-repair"
                                          ');                
        } else if ($_REQUEST['list'] == 'outstanding'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM wp_king_projects projects
                                            JOIN wp_king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN wp_king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN wp_king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            ORDER BY project_updatedat ASC
                                          ');                
        } else {
        
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM wp_king_projects projects
                                            JOIN wp_king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN wp_king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN wp_king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            ORDER BY project_updatedat ASC
                                          ');
        }
        
        foreach ($results as $row ){
        
            $data[] = array(
                        'project_id'        => $row->project_id,
                        'project_status'    => $row->project_status,
                        'customer_name'     => $row->customer_firstname . " " . $row->customer_lastname,
                        'project_phase'     => $row->phase_name,
                        'project_type'      => $row->project_type_name,
                        'project_amount'    => $row->project_amount,
                        'notification'      => "",
                        'updated_at'        => format_date($row->project_updatedat, 'updateDate'),
                        'project_start_date' => format_date($row->project_start_date, 'startDate')
                        );
        
        }
        
        return $data;
    }
    
    
    public function num_rows(){
        global $wpdb;
        $num_rows = $wpdb->num_rows;
        return $num_rows;
    }
    
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'project_id';
            case 'project_status':
            case 'customer_name':
            case 'project_phase':
            case 'project_type':
            case 'notification':
            case 'updated_at':
            case 'project_start_date':
            
            return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_project_id($item){
        
        //Build row actions
        $actions = array(
//            'assets'    => sprintf('<a href="?page=%s&action=%s&id=%s">Media</a>','project-assets','assets',$item['project_id']),
            'view'      => sprintf('<a href="?page=%s&action=%s&id=%s">View Details</a>',$_REQUEST['page'],'view',$item['project_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['project_id']),
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['project_id'], $this->row_actions($actions));
    }
    
    public function column_notification($item){
        
        //Build row actions
        $actions = array(
            'send'      => sprintf('<a href="?page=%s&action=%s&id=%s">Send</a>',$_REQUEST['page'],'send',$item['project_id']),
            'resend'      => sprintf('<a href="?page=%s&action=%s&id=%s">Resend</a>',$_REQUEST['page'],'resend',$item['project_id']),
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', "Manage", $this->row_actions($actions));
    }    

    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['project_id']                //The value of the checkbox should be the record's id
        );
    }
/*    
    public function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
    private function process_bulk_action() {
        
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
*/   

    
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'updated_at';
        $order = 'desc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }

        $result = strnatcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }    
}
?>

</div>