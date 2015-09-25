<?php

class Vendor_List_Table extends WP_List_Table{

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
            'vendor_id'       => 'ID',
            'vendor_name'     => 'Name',
            'vendor_address'  => 'Address',
            'vendor_city'     => 'City',
            'vendor_state'    => 'State',
            'vendor_zip'      => 'Zip',
            'vendor_phone'    => 'Phone',
            'vendor_fax'      => 'Fax',
            'vendor_email'    => 'Email',
            'vendor_type'     => 'Type'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('vendor_name' => array('vendor_name', false), 'vendor_address' => array('vendor_address', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb; 
        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'king_vendors');
        foreach ($results as $row ){
        
            $data[] = array(
                        'vendor_id'          => $row->vendor_id,
                        'vendor_name'        => $row->vendor_name,
                        'vendor_address'     => $row->vendor_address,
                        'vendor_city'        => $row->vendor_city,
                        'vendor_state'       => $row->vendor_state,
                        'vendor_zip'         => $row->vendor_zip,
                        'vendor_phone'       => $row->vendor_phone,
                        'vendor_fax'         => $row->vendor_fax,
                        'vendor_email'       => $row->vendor_email,
                        'vendor_type'       => $row->vendor_type
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
            case 'vendor_id';
            case 'vendor_name':
            case 'vendor_address':
            case 'vendor_city':
            case 'vendor_state':
            case 'vendor_zip':
            case 'vendor_phone':
            case 'vendor_fax':
            case 'vendor_email':
            case 'vendor_type':
            
            return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_vendor_id($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['vendor_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s&name=%s">Delete</a>',$_REQUEST['page'],'delete',$item['vendor_id'],urlencode($item['vendor_name'])),
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['vendor_id'], $this->row_actions($actions));
    }
    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['vendor_id']                //The value of the checkbox should be the record's id
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
        $orderby = 'vendor_name';
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