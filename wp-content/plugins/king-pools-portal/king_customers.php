<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Customer Management', 'king_trdom' );?>
<a class='add-new-h2' href='?page=<?=$_REQUEST['page']?>&action=add'>Add New Customer</a>
</h2>

<?php

if($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'add'){
    
    if($_REQUEST['action'] == 'edit'){
        global $wpdb; 
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_customers WHERE customer_id = ' . $_REQUEST['id']);
    }
?>
<hr>
<?php
if($_REQUEST['action'] == 'edit'){
?>
<form method="post" id="customer_edit_form" name="customer_edit_form" action="?page=<?=$_REQUEST['page']?>&action=update">
<?php
} else {
?>
<form method="post" id="customer_add_form" name="customer_add_form" action="?page=<?=$_REQUEST['page']?>&action=add_cust">
<?php
}
?>
<?php echo "<h3>" . __( 'Customer Details', 'king_trdom' ) . "</h3>"; ?>
    
<?php include("inc/_customer_form.php");?>

</form>

<script>
jQuery("#customer_add_form").validate();
</script>

<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    //TODO: Add DB statements to cleanup associations with projects, notifications etc.

    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'king_customers', array( 'customer_id' => $_REQUEST['id'] ) );
    
    echo '<div id="message" class="error">Customer has been deleted!</div>';
    
} else if($_REQUEST['action'] == 'update'){
    
    global $wpdb;
    $wpdb->update($wpdb->prefix . 'king_customers', array(
                                             'customer_firstname'  =>$_REQUEST['customer_firstname'],
                                             'customer_lastname'   =>$_REQUEST['customer_lastname'],
                                             'customer_address'    =>$_REQUEST['customer_address'],
                                             'customer_city'       =>$_REQUEST['customer_city'],
                                             'customer_state'      =>$_REQUEST['customer_state'],
                                             'customer_zip'        =>$_REQUEST['customer_zip'],
                                             'customer_phone'      =>$_REQUEST['customer_phone'],
                                             'customer_email'      =>$_REQUEST['customer_email'],
                                             'customer_status'     =>$_REQUEST['customer_status'],
                                             ), 
                                       array('customer_id'=>$_REQUEST['customer_id']));
    
    echo '<div id="message" class="updated">Customer #' . $_REQUEST['customer_id'] . ' has been updated!</div>';
    
} else if($_REQUEST['action'] == 'add_customer'){
    
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'king_customers', array(
                                             'customer_firstname'  =>$_REQUEST['customer_firstname'],
                                             'customer_lastname'   =>$_REQUEST['customer_lastname'],
                                             'customer_address'    =>$_REQUEST['customer_address'],
                                             'customer_city'       =>$_REQUEST['customer_city'],
                                             'customer_state'      =>$_REQUEST['customer_state'],
                                             'customer_zip'        =>$_REQUEST['customer_zip'],
                                             'customer_phone'      =>$_REQUEST['customer_phone'],
                                             'customer_email'      =>$_REQUEST['customer_email'],
                                             ));
    
    echo '<div id="message" class="updated">Customer has been created!</div>';
    
} else if($_REQUEST['action'] == 'createproject'){
    
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'king_projects', array('project_status'=>'New', 'customer_id'=>$_REQUEST['id']));
    
    echo '<div id="message" class="updated">A new project has been created!</div>';
    
}


include("inc/_customer_list_table.php");
$customerListTable = new Customer_List_Table();
$customerListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All</a> | 
        <a href="?page=<?=$_REQUEST['page']?>&list=active">Active</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=inactive">Inactive</a>
    </li>
</ul>

<form id="customers" method="get">
<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$customerListTable->display();?>
</form>

</div>
