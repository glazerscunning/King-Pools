<?php

class Estimate_List_Table extends WP_List_Table{

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
    
    public function num_rows(){
        global $wpdb;
        $num_rows = $wpdb->num_rows;
        return $num_rows;
    }    
    
    public function get_columns()
    {
        $columns = array(
            //'cb'        => '<input type="checkbox" />',
            'lead_id'   => 'ID',
            'status'    => 'Status',
            'email'     => 'Email',
            'name'      => 'Name',
            'estimate'  => 'Estimate',
            'date_created'      => 'Date Created'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('date_created' => array('date_created', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        $query = '';
        $fullName = '';
        
        if($_REQUEST['list'] == 'all' || empty($_REQUEST['list'])){
            
            $query = "SELECT lead.date_created, lead.id, est.status, detail.value as email, meta.meta_key, meta.meta_value, IFNULL( est.value,  '' ) AS estimate
                      FROM " . $wpdb->prefix . "rg_lead lead 
                      JOIN " . $wpdb->prefix . "rg_lead_detail detail ON lead.id = detail.lead_id
                      LEFT JOIN " . $wpdb->prefix . "king_estimates est ON detail.lead_id = est.lead_id
                      JOIN " . $wpdb->prefix . "users users ON detail.value = users.user_email
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      JOIN " . $wpdb->prefix . "rg_form form ON form.id = lead.form_id
                      WHERE form.title like 'Pool Estimator Appointment Request%'
                      AND detail.field_number = 28
                      AND meta.meta_key
                      IN ('first_name',  'last_name')
                      GROUP BY lead.id, est.status, meta.meta_key, meta.meta_value
                      ORDER BY lead.id DESC";
            
        } else if($_REQUEST['list'] == 'converted'){
            
            $query = "SELECT lead.date_created, lead.id, est.status, detail.value as email, meta.meta_key, meta.meta_value, IFNULL( est.value,  '' ) AS estimate
                      FROM " . $wpdb->prefix . "rg_lead lead 
                      JOIN " . $wpdb->prefix . "rg_lead_detail detail ON lead.id = detail.lead_id
                      LEFT JOIN " . $wpdb->prefix . "king_estimates est ON detail.lead_id = est.lead_id
                      JOIN " . $wpdb->prefix . "users users ON detail.value = users.user_email
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      JOIN " . $wpdb->prefix . "rg_form form ON form.id = lead.form_id
                      WHERE form.title = 'Pool Estimator Appointment Request'
                      AND detail.field_number = 28
                      AND est.status = 'converted'
                      AND meta.meta_key
                      IN ('first_name',  'last_name')
                      GROUP BY lead.id, est.status, meta.meta_key, meta.meta_value
                      ORDER BY lead.id DESC";
                      
        } else if($_REQUEST['list'] == 'unconverted'){
            
            $query = "SELECT lead.date_created, lead.id, est.status, detail.value as email, meta.meta_key, meta.meta_value, IFNULL( est.value,  '' ) AS estimate
                      FROM " . $wpdb->prefix . "rg_lead lead 
                      JOIN " . $wpdb->prefix . "rg_lead_detail detail ON lead.id = detail.lead_id
                      LEFT JOIN " . $wpdb->prefix . "king_estimates est ON detail.lead_id = est.lead_id
                      JOIN " . $wpdb->prefix . "users users ON detail.value = users.user_email
                      JOIN " . $wpdb->prefix . "usermeta meta ON users.ID = meta.user_id
                      JOIN " . $wpdb->prefix . "rg_form form ON form.id = lead.form_id
                      WHERE form.title = 'Pool Estimator Appointment Request'
                      AND detail.field_number = 28
                      AND est.status = 'unconverted'
                      AND meta.meta_key
                      IN ('first_name',  'last_name')
                      GROUP BY lead.id, est.status, meta.meta_key, meta.meta_value
                      ORDER BY lead.id DESC";
                      
        }
        
        $results = $wpdb->get_results($query);
    
        //$estimate = array();
        //
        //foreach($results as $row){
        //    $lead_id = $row->lead_id;
        //    
        //    switch($row->field_number){
        //        case 28: $estimate['email'] = $row->value;
        //        case  1: $estimate['pool_type'] = $row->value;
        //        case  5: $estimate['activity_pool_size'] = $row->value;
        //        case 32: $estimate['diving_pool_size'] = $row->value;
        //        case 10: $estimate['spa_interest'] = $row->value;
        //        case 22: $estimate['spa_size'] = $row->value;
        //        case  9: $estimate['decking'] = $row->value;
        //        case 12: $estimate['project_budget'] = $row->value;
        //        case 27: $estimate['home_survey'] = $row->value;
        //        case 29: $estimate['financing'] = $row->value;
        //        
        //    }
        //    
        //}
        //print_r($estimate);
        
        
        foreach ($results as $row ){
    
            if($row->meta_key == 'first_name'){
                $fullName = $row->meta_value;
            }elseif($row->meta_key == 'last_name'){
                $fullName = $fullName . ' ' . $row->meta_value;
                $data[] = $this->fill_data($row,$fullName);
            }

        }   
        
        return $data;
    }

    public function fill_data($row, $fullName){
        $data = array(
                    'lead_id'      => $row->id,
                    'status'       => ucfirst($row->status),
                    'email'        => $row->email,
                    'name'         => ucfirst($fullName),
                    'estimate'     => $row->estimate,
                    'date_created' => $row->date_created
                    );
        
        return $data;
    }
    
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'lead_id':
            case 'status':
            case 'email':
            case 'name':
            case 'estimate':
            case 'date_created':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_lead_id($item){
    
        $pool_estimate_id = get_option('pool_estimate_id');
        //Build row actions
        if(strtolower($item['status']) == 'unconverted'){
            $actions = array(
                'edit'    => sprintf('<a href="?page=gf_entries&view=entry&id=' . $pool_estimate_id . '&lid=%s&filter=&paged=1&pos=1&field_id=&operator=">Edit</a>',$item['lead_id']),
                'convert' => sprintf('<a href="?page=%s&action=%s&id=%s&list=unconverted">Convert to Customer</a>',$_REQUEST['page'],'convert',$item['lead_id']),
            );
        }else{
            $actions = array(
                'edit'    => sprintf('<a href="?page=gf_entries&view=entry&id=' . $pool_estimate_id . '&lid=%s&filter=&paged=1&pos=1&field_id=&operator=">Edit</a>',$item['lead_id'])
            );            
        }
        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['lead_id'], $this->row_actions($actions));
    }    
    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['lead_id']                //The value of the checkbox should be the record's id
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
        $orderby = 'lead_id';
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