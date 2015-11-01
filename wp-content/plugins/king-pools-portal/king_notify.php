<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


if($_REQUEST['auth_type'] == 'notification'){

    if($_REQUEST['auth_hash'] != 'c42a7dad500f02fd63db33f5e5df8884'){

        wp_die("<span style='color:red'>Hash key is incorrect!</span>");    
    
    } else if(!isset($_REQUEST['project_id'])){ 
    
        wp_die("<span style='color:red'>Required parameters have not been set.</span>");
    
    }

    if(isset($_REQUEST['function_name']) && function_exists($_REQUEST['function_name'])){
        call_user_func($_REQUEST['function_name'], $_REQUEST['project_id']);
    }else{
        wp_die("<span style='color:red'>Function does not exist!</span>");
    }    

}
?>

<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery(".notification_message").hide();

    jQuery(".show_email_link").click(function(){
        jQuery("#notification_message_" + jQuery(this).attr('notification_id')).dialog({
            width:500
        });
    });

});
</script>

<div class="wrap">    
<h2>
<?= __( 'King Pools Notifications Management', 'king_trdom' );?>
</h2>


</div>

<div class="wrap"> 

<h3>Notifications</h3>

<hr>

<?php
include("inc/_notify_list_table.php");
$notificationsListTable = new Notifications_List_Table();
$notificationsListTable->prepare_items();

?>

<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$notificationsListTable->display();?>
</div>
