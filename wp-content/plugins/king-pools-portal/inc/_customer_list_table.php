<?php

class Customer_List_Table extends WP_List_Table{

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
        //$this->process_bulk_action();
        $this->items = $data;
    }
    
    public function get_columns()
    {
        $columns = array(
            //'cb'        => '<input type="checkbox" />',
            'customer_id'       => 'ID',
            'customer_firstname'  => 'First Name',
            'customer_lastname'  => 'Last Name',
            'customer_address'  => 'Address',
            'customer_city'     => 'City',
            'customer_state'    => 'State',
            'customer_zip'      => 'Zip',
            'customer_phone'   => 'Phone',
            'customer_email'    => 'Email'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('customer_lastname' => array('customer_lastname', false), 'customer_address' => array('customer_address', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        
        if ($_REQUEST['list'] == 'active'){
            
            $results = $wpdb->get_results('SELECT customers.*
                                           FROM ' . $wpdb->prefix . 'king_customers customers
                                           WHERE customers.customer_status = "active"');
                                           
        } else if ($_REQUEST['list'] == 'inactive'){

            $results = $wpdb->get_results('SELECT customers.*
                                           FROM ' . $wpdb->prefix . 'king_customers customers
                                           WHERE customers.customer_status = "inactive"');
            
        } else {
            
            $results = $wpdb->get_results('SELECT customers.*
                                           FROM ' . $wpdb->prefix . 'king_customers customers');
            
        }
        
        foreach ($results as $row ){
        
            $data[] = array(
                        'customer_id'          => $row->customer_id,
                        'customer_firstname'   => $row->customer_firstname,
                        'customer_lastname'    => $row->customer_lastname,
                        'customer_address'     => $row->customer_address,
                        'customer_city'        => $row->customer_city,
                        'customer_state'       => $row->customer_state,
                        'customer_zip'         => $row->customer_zip,
                        'customer_phone'       => $row->customer_phone,
                        'customer_email'       => $row->customer_email,
                        'customer_status'      => $row->customer_status
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
            case 'customer_id';
            case 'customer_firstname':
            case 'customer_lastname':
            case 'customer_address':
            case 'customer_city':
            case 'customer_state':
            case 'customer_zip':
            case 'customer_phone':
            case 'customer_email':
            case 'customer_status';    
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_customer_id($item){
        
        //Build row actions

            $actions = array(
                'edit'          => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['customer_id']),
                'createproject' => sprintf('<a href="?page=%s&action=%s&id=%s">Create Project</a>',$_REQUEST['page'],'createproject',$item['customer_id']),
            );

        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['customer_id'], $this->row_actions($actions));
    }
    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['customer_id']                //The value of the checkbox should be the record's id
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
        $orderby = 'customer_lastname';
        $order = 'asc';

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