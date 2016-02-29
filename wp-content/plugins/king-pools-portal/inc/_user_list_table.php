<?php

class User_List_Table extends WP_List_Table{

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
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
    
    public function num_rows(){
        global $wpdb;
        $num_rows = $wpdb->num_rows;
        return $num_rows;
    }    
    
    public function get_columns()
    {
        $columns = array(
            //'ID'   => 'ID',
            'display_name'    => 'Display Name',
            'user_email'     => 'Email',
            'traffic_source'      => 'Traffic Source',
            'user_registered'     => 'Registered Date'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('user_registered' => array('user_registered', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        $query = '';
        
        if($_REQUEST['list'] == 'all' || empty($_REQUEST['list'])){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                     ";

        }elseif($_REQUEST['list'] == 'fan_radio'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = '105.3 The Fan Radio'
                      ";

        }elseif($_REQUEST['list'] == 'print_advert'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = 'Print Advertisement'
                      ";

        }elseif($_REQUEST['list'] == 'word_of_mouth'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = 'Word of Mouth'
                      ";

        }elseif($_REQUEST['list'] == 'yard_signs'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = 'Yard Signs on Customers Property'
                      ";

        }elseif($_REQUEST['list'] == 'referral'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = 'Customer Referral'
                      ";

        }elseif($_REQUEST['list'] == 'other'){
            
            $query = "SELECT users.ID, users.display_name, users.user_email, meta.meta_value, users.user_registered
                      FROM " . $wpdb->prefix . "users users 
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      WHERE meta_key = 'traffic_source'
                      AND meta_value = 'Other'
                      ";

        }

        
        $results = $wpdb->get_results($query);
        
        foreach ($results as $row ){
    
            $data[] = $this->fill_data($row);
    
        }   

        return $data;
    }

    public function fill_data($row){
        $data = array(
                    'ID'      => $row->ID,
                    'display_name'       => $row->display_name,
                    'user_email'        => $row->user_email,
                    'traffic_source'         => $row->meta_value,
                    'user_registered'        => $row->user_registered
                    );
        
        return $data;
    }
    
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'ID':
            case 'display_name':
            case 'user_email':
            case 'traffic_source':
            case 'user_registered':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_ID($item){
    
        $user_id = $item['ID'];
        //Build row actions
        $actions = array(
            'View'    => sprintf('<a href="?page=gf_entries&view=entry&id=1&lid=%s&filter=&paged=1&pos=1&field_id=&operator=">View</a>',$item['ID'])
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['ID'], $this->row_actions($actions));
    }     
    
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'ID';
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