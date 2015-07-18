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

<div class="wrap">    
<h2>
<?= __( 'King Pools Notifications Management', 'king_trdom' );?>
</h2>


</div>

<div class="wrap"> 

<h3>Notifications</h3>

<hr>

<?php
$notificationsListTable = new Notifications_List_Table();
$notificationsListTable->prepare_items();

?>

<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$notificationsListTable->display();?>
</div>
<?php

class Notifications_List_Table extends WP_List_Table{

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $data;
    }
    
    public function get_columns()
    {
        $columns = array(
            'notification_id'      => 'ID',
            'notification_to'      => 'Sent To',
            'notification_subject' => 'Subject',
            'notification_type'    => 'Type',
            'notification_date'    => 'Date/Time'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array('notification_to' => array('notification_to', false), 'notification_type' => array('notification_type', false), 'notification_date' => array('notification_date', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        
        $results = $wpdb->get_results('SELECT *
                                        FROM wp_king_notifications
                                        ORDER BY notification_date DESC
                                      ');
        
        foreach ($results as $row ){
        
            $data[] = array(
                        'notification_id'      => $row->notification_id,
                        'notification_to'      => $row->notification_to,
                        'notification_subject' => $row->notification_subject,
                        'notification_type'    => $row->notification_type,
                        'notification_date'    => $row->notification_date
                        );
        
        }
        
        return $data;
    }
    
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'notification_id';
            case 'notification_to':
            case 'notification_subject':
            case 'notification_type':
            case 'notification_date':
            
            return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'notification_date';
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

