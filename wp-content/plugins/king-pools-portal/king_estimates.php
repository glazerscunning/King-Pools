<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Estimate Management', 'king_trdom' );?>
</h2>

<?php

if($_REQUEST['action'] == 'delete'){
    
    echo '<div id="message" class="error">Estimate has been deleted!</div>';
    
} else if($_REQUEST['action'] == 'convert'){
    global $wpdb;
 
    $wpdb->update( 
                    $wpdb->prefix . 'king_estimates', 
                    array( 
                            'status'  => 'converted'
                    ), 
                    array( 'lead_id' => $_REQUEST['id'] ), 
                    array( 
                            '%s'
                    ), 
                    array( '%s' ) 
    );   
    
    $query = 'SELECT meta.meta_key, meta.meta_value, users.user_email
                FROM ' . $wpdb->prefix . 'users users
                JOIN ' . $wpdb->prefix . 'usermeta meta
                ON users.ID = meta.user_id
                JOIN ' . $wpdb->prefix . 'rg_lead_detail lead_detail
                ON lead_detail.value = users.user_email
                WHERE meta.meta_key
                IN ("first_name",  "last_name",  "addr1",  "city",  "thestate",  "zip",  "phone1")
                AND lead_detail.lead_id = ' . $_REQUEST['id'];   
                             
    $results = $wpdb->get_results($query);
    foreach ($results as $row ){
        
        $email = $row->user_email;
        
        switch($row->meta_key){
            case "first_name": $firstName = $row->meta_value;
            case "last_name" : $lastName = $row->meta_value;
            case "addr1"     : $addr1 = $row->meta_value;
            case "city"      : $city = $row->meta_value;
            case "thestate"  : $state = $row->meta_value;
            case "zip"       : $zip = $row->meta_value;
            case "phone1"    : $phone1 = $row->meta_value;    
        }
        
    }
    
    $wpdb->insert( 
                    $wpdb->prefix . 'king_customers', 
                    array( 
                            'customer_firstname'  => $firstName,
                            'customer_lastname'   => $lastName,                            
                            'customer_address'    => $addr1,
                            'customer_city'       => $city,
                            'customer_state'      => $state,
                            'customer_zip'        => $zip,
                            'customer_phone'      => $phone1,
                            'customer_email'      => $email
                    ), 
                    array( '%s','%s','%s','%s','%s','%s','%s' ) 
    );
                
    
    echo '<div id="message" class="updated">Estimate has been converted to a customer. Please click <a href="?page=customer-management">here</a> to see this customer.</div>';
       
}


include("inc/_estimate_list_table.php");
$estimateListTable = new Estimate_List_Table();
$estimateListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=converted">Converted</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=unconverted">Unconverted</a>        
    </li>
</ul>
<?=$estimateListTable->display();?>
</div>
