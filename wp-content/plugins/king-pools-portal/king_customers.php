<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Customer Management', 'king_trdom' );?>
<a class='add-new-h2' href='?page=<?=$_REQUEST['page']?>&action=add'>Add New Customer</a>
</h2>

<?php

if($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'add'){
    
    if($_REQUEST['action'] == 'edit'){
        global $wpdb; 
        $result = $wpdb->get_row('SELECT * FROM wp_king_customers WHERE customer_id = ' . $_REQUEST['id']);
    }
?>
<hr>
<?php
if($_REQUEST['action'] == 'edit'){
?>
<form method="post" id="customer_edit_form" name="customer_edit_form" action="?page=<?=$_REQUEST['page']?>&action=update">
<?php
} else {
?>
<form method="post" id="customer_add_form" name="customer_add_form" action="?page=<?=$_REQUEST['page']?>&action=add_cust">
<?php
}
?>
<?php echo "<h3>" . __( 'Customer Details', 'king_trdom' ) . "</h3>"; ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label><?php _e("First Name: " ); ?></label>
                </th>
                <td><input required class="textbox" type="text" name="customer_firstname" value="<?=$result->customer_firstname?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Last Name: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_lastname" value="<?=$result->customer_lastname?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Address: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_address" value="<?=$result->customer_address?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("City: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_city" value="<?=$result->customer_city?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("State: " ); ?></label>
                </th>
                <td>
                    <select name="customer_state">
                            <option value="AL" <?= ($result->customer_state == "AL") ? "selected='selected'" : "";?>>Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX" <?= ($result->customer_state == "TX") ? "selected='selected'" : "";?>>Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA" <?= ($result->customer_state == "VA") ? "selected='selected'" : "";?>>Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                    </select>                
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Zip: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_zip" value="<?=$result->customer_zip?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Phone: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_phone" value="<?=$result->customer_phone?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Email: " ); ?></label>
                </th>
                <td><input required type="text" name="customer_email" value="<?=$result->customer_email?>" size="25"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Status: " ); ?></label>
                </th>
                <td>
                    <select name="customer_status">
                        <option value="active" <?= ($result->customer_status == "active") ? "selected='selected'" : "";?>>Active</option>
                        <option value="inactive" <?= ($result->customer_status == "inactive") ? "selected='selected'" : "";?>>Inactive</option>
                    </select>
                </td>
            </tr>             
        </tbody>
    </table>    
    <input type="hidden" name="customer_id" value="<?=$_REQUEST['id']?>"/>
    <p class="submit">
<?php
if($_REQUEST['action'] == 'edit'){
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Customer', 'king_trdom' ) ?>" /> 
<?php
} else {
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Add Customer', 'king_trdom' ) ?>" /> 
<?php
}
?>        
     
    </p>
</form>

<script>
jQuery("#customer_add_form").validate({
  rules: {
    customer_email: {
      required: true,
      email: true
    }
  }
});
</script>

<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    echo '<div id="message" class="error">Customer has been deleted!</div>';
    
} else if($_REQUEST['action'] == 'update'){
    
    global $wpdb;
    $wpdb->update('wp_king_customers', array(
                                             'customer_firstname'  =>$_REQUEST['customer_firstname'],
                                             'customer_lastname'   =>$_REQUEST['customer_lastname'],
                                             'customer_address'    =>$_REQUEST['customer_address'],
                                             'customer_city'       =>$_REQUEST['customer_city'],
                                             'customer_state'      =>$_REQUEST['customer_state'],
                                             'customer_zip'        =>$_REQUEST['customer_zip'],
                                             'customer_phone'      =>$_REQUEST['customer_phone'],
                                             'customer_email'      =>$_REQUEST['customer_email'],
                                             'customer_status'     =>$_REQUEST['customer_status'],
                                             ), 
                                       array('customer_id'=>$_REQUEST['customer_id']));
    
    echo '<div id="message" class="updated">Customer #' . $_REQUEST['customer_id'] . ' has been updated!</div>';
    
} else if($_REQUEST['action'] == 'add_customer'){
    
    global $wpdb;
    $wpdb->insert('wp_king_customers', array(
                                             'customer_firstname'  =>$_REQUEST['customer_firstname'],
                                             'customer_lastname'   =>$_REQUEST['customer_lastname'],
                                             'customer_address'    =>$_REQUEST['customer_address'],
                                             'customer_city'       =>$_REQUEST['customer_city'],
                                             'customer_state'      =>$_REQUEST['customer_state'],
                                             'customer_zip'        =>$_REQUEST['customer_zip'],
                                             'customer_phone'      =>$_REQUEST['customer_phone'],
                                             'customer_email'      =>$_REQUEST['customer_email'],
                                             ));
    
    echo '<div id="message" class="updated">Customer has been created!</div>';
    
} else if($_REQUEST['action'] == 'createproject'){
    
    global $wpdb;
    $wpdb->insert('wp_king_projects', array('project_status'=>'New', 'customer_id'=>$_REQUEST['id']));
    
    echo '<div id="message" class="updated">A new project has been created!</div>';
    
}

$customerListTable = new Customer_List_Table();
$customerListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All</a> | 
        <a href="?page=<?=$_REQUEST['page']?>&list=active">Active</a> |
        <a href="?page=<?=$_REQUEST['page']?>&list=inactive">Inactive</a>
    </li>
</ul>

<form id="customers" method="get">
<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$customerListTable->display();?>
</form>

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
                                           FROM wp_king_customers customers
                                           WHERE customers.customer_status = "active"');
                                           
        } else if ($_REQUEST['list'] == 'inactive'){

            $results = $wpdb->get_results('SELECT customers.*
                                           FROM wp_king_customers customers
                                           WHERE customers.customer_status = "inactive"');
            
        } else {
            
            $results = $wpdb->get_results('SELECT customers.*
                                           FROM wp_king_customers customers');
            
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

</div>
