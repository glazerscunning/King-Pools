<?php

class Project_List_Table extends WP_List_Table{

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        //usort( $data, array( &$this, 'sort_data' ) );

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
            'project_id'      => 'ID',
            'project_status'  => 'Status',
            'customer_name'   => 'Customer Name',
            'project_type'    => 'Type',
            'project_phase'   => 'Phase',
            //'notification'    => 'Notification',
            'updated_at'      => 'Updated At',
            'project_start_date'      => 'Started'
        );

        return $columns;
    }
    
    public function get_hidden_columns()
    {
        return array();
    }
    
    public function get_sortable_columns()
    {
        return array('customer_name' => array('customer_name', false), 'project_type' => array('project_type', false), 'project_status' => array('project_status', false));
    }
    
    private function table_data()
    {
        $data = array();
        global $wpdb;
        
        if ($_REQUEST['list'] == 'construction-remodel'){
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM ' . $wpdb->prefix . 'king_projects projects
                                            JOIN ' . $wpdb->prefix . 'king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "construction-remodel"
                                          ');
            
        } else if ($_REQUEST['list'] == 'cleaning'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM ' . $wpdb->prefix . 'king_projects projects
                                            JOIN ' . $wpdb->prefix . 'king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "cleaning"
                                          ');                
        } else if ($_REQUEST['list'] == 'service-repair'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM ' . $wpdb->prefix . 'king_projects projects
                                            JOIN ' . $wpdb->prefix . 'king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            WHERE projects.project_type = "service-repair"
                                          ');                
        } else if ($_REQUEST['list'] == 'outstanding'){
            
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM ' . $wpdb->prefix . 'king_projects projects
                                            JOIN ' . $wpdb->prefix . 'king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            ORDER BY project_updatedat ASC
                                          ');                
        } else {
        
            $results = $wpdb->get_results('SELECT projects.* , customers.customer_firstname, customers.customer_lastname, phases.phase_name, project_types.project_type_name
                                            FROM ' . $wpdb->prefix . 'king_projects projects
                                            JOIN ' . $wpdb->prefix . 'king_customers customers
                                            ON projects.customer_id = customers.customer_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_phases phases
                                            ON projects.phase_id = phases.phase_id
                                            LEFT JOIN ' . $wpdb->prefix . 'king_project_types project_types
                                            ON projects.project_type = project_types.project_type
                                            ORDER BY project_updatedat ASC
                                          ');
        }
        
        foreach ($results as $row ){
        
            $data[] = array(
                        'project_id'        => $row->project_id,
                        'project_status'    => $row->project_status,
                        'customer_name'     => $row->customer_firstname . " " . $row->customer_lastname,
                        'project_phase'     => $row->phase_name,
                        'project_type'      => $row->project_type_name,
                        'project_amount'    => $row->project_amount,
                        'notification'      => "",
                        'updated_at'        => format_date($row->project_updatedat, 'updateDate'),
                        'project_start_date' => format_date($row->project_start_date, 'startDate')
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
            case 'project_id';
            case 'project_status':
            case 'customer_name':
            case 'project_phase':
            case 'project_type':
            case 'notification':
            case 'updated_at':
            case 'project_start_date':
            
            return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    public function column_project_id($item){
        
        //Build row actions
        $actions = array(
//            'assets'    => sprintf('<a href="?page=%s&action=%s&id=%s">Media</a>','project-assets','assets',$item['project_id']),
            'view'      => sprintf('<a href="?page=%s&action=%s&id=%s">View Details</a>',$_REQUEST['page'],'view',$item['project_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['project_id']),
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', $item['project_id'], $this->row_actions($actions));
    }
    
    public function column_notification($item){
        
        //Build row actions
        $actions = array(
            'send'      => sprintf('<a href="?page=%s&action=%s&id=%s">Send</a>',$_REQUEST['page'],'send',$item['project_id']),
            'resend'      => sprintf('<a href="?page=%s&action=%s&id=%s">Resend</a>',$_REQUEST['page'],'resend',$item['project_id']),
        );
        
        //Return the title contents
        return sprintf('%1$s%2$s', "Manage", $this->row_actions($actions));
    }    

    
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['project_id']                //The value of the checkbox should be the record's id
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
        $orderby = 'updated_at';
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