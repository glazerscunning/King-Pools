<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

?>

<div class="wrap">    
<h2>
<?= __( 'King Pools Vendor Management', 'king_trdom' );?>
<a class='add-new-h2' href='?page=<?=$_REQUEST['page']?>&action=add'>Add New Vendor</a>
</h2>

<?php

if($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'add'){
    
    if($_REQUEST['action'] == 'edit'){
        global $wpdb; 
        $result = $wpdb->get_row('SELECT * FROM wp_king_vendors WHERE vendor_id = ' . $_REQUEST['id']);
    }
?>
<hr>
<?php
if($_REQUEST['action'] == 'edit'){
?>
<form method="post" id="vendor_edit_form" name="vendor_edit_form" action="?page=<?=$_REQUEST['page']?>&action=update">
<?php
} else {
?>
<form method="post" id="vendor_edit_form" name="vendor_add_form" action="?page=<?=$_REQUEST['page']?>&action=add_vendor">
<?php
}
?>
<?php echo "<h3>" . __( 'Vendor Details', 'king_trdom' ) . "</h3>"; ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label><?php _e("Name: " ); ?></label>
                </th>
                <td><input class="textbox" type="text" name="vendor_name" value="<?=$result->vendor_name?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Address: " ); ?></label>
                </th>
                <td><input type="text" name="vendor_address" value="<?=$result->vendor_address?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("City: " ); ?></label>
                </th>
                <td><input type="text" name="vendor_city" value="<?=$result->vendor_city?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("State: " ); ?></label>
                </th>
                <td>
                    <select name="vendor_state">
                            <option value="AL" <?= ($result->vendor_state == "AL") ? "selected='selected'" : "";?>>Alabama</option>
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
                            <option value="TX" <?= ($result->vendor_state == "TX") ? "selected='selected'" : "";?>>Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA" <?= ($result->vendor_state == "VA") ? "selected='selected'" : "";?>>Virginia</option>
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
                <td><input type="text" name="vendor_zip" value="<?=$result->vendor_zip?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Phone: " ); ?></label>
                </th>
                <td><input type="text" name="vendor_phone" value="<?=$result->vendor_phone?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Fax: " ); ?></label>
                </th>
                <td><input type="text" name="vendor_fax" value="<?=$result->vendor_fax?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Email: " ); ?></label>
                </th>
                <td><input type="text" name="vendor_email" value="<?=$result->vendor_email?>" size="25"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Type: " ); ?></label>
                </th>
                <td>
                    <select name="vendor_type">
                        <option value="">Select...</option>
                        <option value="excavation" <?= ($result->vendor_type == "excavation") ? "selected='selected'" : "";?>>Excavation</option>
                        <option value="steel" <?=($result->vendor_type == "steel") ? "selected='selected'" : "";?>>Steel</option>
                        <option value="plumbing" <?=($result->vendor_type == "plumbing") ? "selected='selected'" : "";?>>Plumbing</option>
                        <option value="concrete" <?=($result->vendor_type == "concrete") ? "selected='selected'" : "";?>>Concrete</option>
                    </select>                    
                </td>
            </tr>               
        </tbody>
    </table>    
    <input type="hidden" name="vendor_id" value="<?=$_REQUEST['id']?>"/>
    <p class="submit">
<?php
if($_REQUEST['action'] == 'edit'){
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Vendor', 'king_trdom' ) ?>" /> 
<?php
} else {
?>
<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Add Vendor', 'king_trdom' ) ?>" /> 
<?php
}
?>          
    </p>
</form>

<hr>

<?php
} else if($_REQUEST['action'] == 'delete'){
    
    echo "<div id='message' class='error'>Vendor '" . $_REQUEST['name'] . "' has been deleted!</div>";
    
} else if($_REQUEST['action'] == 'update'){
    global $wpdb;
    $wpdb->update('wp_king_vendors', array(
                                             'vendor_name'       =>$_REQUEST['vendor_name'],
                                             'vendor_address'    =>$_REQUEST['vendor_address'],
                                             'vendor_city'       =>$_REQUEST['vendor_city'],
                                             'vendor_state'      =>$_REQUEST['vendor_state'],
                                             'vendor_zip'        =>$_REQUEST['vendor_zip'],
                                             'vendor_phone'      =>$_REQUEST['vendor_phone'],
                                             'vendor_fax'        =>$_REQUEST['vendor_fax'],
                                             'vendor_email'      =>$_REQUEST['vendor_email'],
                                             'vendor_type'       =>$_REQUEST['vendor_type'],
                                             ), 
                                       array('vendor_id'=>$_REQUEST['vendor_id']));
    
    echo '<div id="message" class="updated">Vendor has been updated!</div>';
} else if($_REQUEST['action'] == 'add_vendor'){
    global $wpdb;
    $wpdb->insert('wp_king_vendors', array(
                                             'vendor_name'       =>$_REQUEST['vendor_name'],
                                             'vendor_address'    =>$_REQUEST['vendor_address'],
                                             'vendor_city'       =>$_REQUEST['vendor_city'],
                                             'vendor_state'      =>$_REQUEST['vendor_state'],
                                             'vendor_zip'        =>$_REQUEST['vendor_zip'],
                                             'vendor_phone'      =>$_REQUEST['vendor_phone'],
                                             'vendor_fax'        =>$_REQUEST['vendor_fax'],
                                             'vendor_email'      =>$_REQUEST['vendor_email'],
                                             'vendor_type'       =>$_REQUEST['vendor_type'],
                                             ));
    
    echo '<div id="message" class="updated">Vendor has been created!</div>';
}

$vendorListTable = new Vendor_List_Table();
$vendorListTable->prepare_items();

?>

<ul class="subsubsub">
    <li class="all">
        <a href="?page=<?=$_REQUEST['page']?>&list=all">All <span class="count">(<?=$vendorListTable->num_rows();?>)</span></a> |
    </li>
</ul>

<form id="vendors" method="get">
<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
<?=$vendorListTable->display();?>
</form>

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
        $results = $wpdb->get_results('SELECT * FROM wp_king_vendors');
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

</div>