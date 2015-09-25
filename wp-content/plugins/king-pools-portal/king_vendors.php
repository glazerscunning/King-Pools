<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Vendor Management', 'king_trdom' );?>
<a class='add-new-h2' href='?page=<?=$_REQUEST['page']?>&action=add'>Add New Vendor</a>
</h2>

<script>
jQuery("#vendor_edit_form").validate();
</script>

<?php

if($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'add'){
    
    if($_REQUEST['action'] == 'edit'){
        global $wpdb; 
        $result = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'king_vendors WHERE vendor_id = ' . $_REQUEST['id']);
    }
?>
<hr>
<?php
if($_REQUEST['action'] == 'edit'){
?>
<form method="post" id="vendor_edit_form" name="vendor_edit_form" action="?page=<?=$_REQUEST['page']?>&action=update">
<?php
} else {
?>
<form method="post" id="vendor_add_form" name="vendor_add_form" action="?page=<?=$_REQUEST['page']?>&action=add_vendor">
<?php
}
?>
<?php echo "<h3>" . __( 'Vendor Details', 'king_trdom' ) . "</h3>"; ?>

<?php include("inc/_vendor_form.php");?>

</form>

<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    echo "<div id='message' class='error'>Vendor '" . $_REQUEST['name'] . "' has been deleted!</div>";
    
} else if($_REQUEST['action'] == 'update'){
    global $wpdb;
    $wpdb->update($wpdb->prefix . 'king_vendors', array(
                                             'vendor_name'       =>$_REQUEST['vendor_name'],
                                             'vendor_address'    =>$_REQUEST['vendor_address'],
                                             'vendor_city'       =>$_REQUEST['vendor_city'],
                                             'vendor_state'      =>$_REQUEST['vendor_state'],
                                             'vendor_zip'        =>$_REQUEST['vendor_zip'],
                                             'vendor_phone'      =>$_REQUEST['vendor_phone'],
                                             'vendor_fax'        =>$_REQUEST['vendor_fax'],
                                             'vendor_email'      =>$_REQUEST['vendor_email'],
                                             'vendor_type'       =>$_REQUEST['vendor_type'],
                                             ), 
                                       array('vendor_id'=>$_REQUEST['vendor_id']));
    
    echo '<div id="message" class="updated">Vendor has been updated!</div>';
} else if($_REQUEST['action'] == 'add_vendor'){
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'king_vendors', array(
                                             'vendor_name'       =>$_REQUEST['vendor_name'],
                                             'vendor_address'    =>$_REQUEST['vendor_address'],
                                             'vendor_city'       =>$_REQUEST['vendor_city'],
                                             'vendor_state'      =>$_REQUEST['vendor_state'],
                                             'vendor_zip'        =>$_REQUEST['vendor_zip'],
                                             'vendor_phone'      =>$_REQUEST['vendor_phone'],
                                             'vendor_fax'        =>$_REQUEST['vendor_fax'],
                                             'vendor_email'      =>$_REQUEST['vendor_email'],
                                             'vendor_type'       =>$_REQUEST['vendor_type'],
                                             ));
    
    echo '<div id="message" class="updated">Vendor has been created!</div>';
}

include("inc/_vendor_list_table.php");
$vendorListTable = new Vendor_List_Table();
$vendorListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All <span class="count">(<?=$vendorListTable->num_rows();?>)</span></a> |
    </li>
</ul>

<form id="vendors" method="get">
<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$vendorListTable->display();?>
</form>

</div>