<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools User Management', 'king_trdom' );?>
</h2>

<?php
global $wpdb;

$allCount = get_users('meta_key=traffic_source');
$fanCount = get_users('meta_key=traffic_source&meta_value=105.3 The Fan Radio');

$query = "SELECT
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' ) as AllCount, 
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = '105.3 The Fan Radio' ) as FanCount, 
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Print Advertisement' ) as PrintCount,
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Word of Mouth' ) as WordMouthCount,
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Customer Referral' ) as ReferralCount,
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Yard Signs on Customers Property' ) as SignsCount,
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Other' ) as OtherCount, 
            (select count(*) FROM wp_users users JOIN wp_usermeta meta ON users.ID = meta.user_id WHERE meta_key = 'traffic_source' AND meta_value = 'Customer Referral' ) as ReferralCount
         ";

$counts = $wpdb->get_results($query, ARRAY_A);

//print_r($counts);

$counts = $counts[0];

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All (<?=$counts['AllCount'];?>)</a>|
        <a href="?page=<?=$_REQUEST['page']?>&list=fan_radio">105.3 The Fan Radio (<?=$counts['FanCount']?>)</a>|
        <a href="?page=<?=$_REQUEST['page']?>&list=print_advert">Print Advertisement (<?=$counts['PrintCount']?>)</a>|       
        <a href="?page=<?=$_REQUEST['page']?>&list=word_of_mouth">Word of Mouth (<?=$counts['WordMouthCount']?>)</a>|
        <a href="?page=<?=$_REQUEST['page']?>&list=referral">Customer Referral (<?=$counts['ReferralCount']?>)</a>|
        <a href="?page=<?=$_REQUEST['page']?>&list=yard_signs">Yard Signs on Customers Property (<?=$counts['SignsCount']?>)</a>|                    
        <a href="?page=<?=$_REQUEST['page']?>&list=other">Other (<?=$counts['OtherCount']?>)</a>
    </li>
</ul>

<?php

include("inc/_user_list_table.php");
$userListTable = new User_List_Table();
$userListTable->prepare_items();
$userListTable->display();

?>
</div>
