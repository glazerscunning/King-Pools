<?php   
if($_POST['king_hidden'] == 'Y') {  
    $company_name = $_POST['company_name'];  
    update_option('company_name', $company_name);
    
    $company_address = $_POST['company_address'];  
    update_option('company_address', $company_address);
    
    $company_city = $_POST['company_city'];  
    update_option('company_city', $company_city);
    
    $company_state = $_POST['company_state'];  
    update_option('company_state', $company_state);
    
    $company_zip = $_POST['company_zip'];  
    update_option('company_zip', $company_zip);

    $project_stale_threshold = $_POST['project_stale_threshold'];  
    update_option('project_stale_threshold', $project_stale_threshold);
    
    $outstanding_project_threshold = $_POST['outstanding_project_threshold'];  
    update_option('outstanding_project_threshold', $outstanding_project_threshold);    
    
    $project_frequency = $_POST['project_frequency'];  
    update_option('project_frequency', $project_frequency);
    
    $vendor_frequency = $_POST['vendor_frequency'];  
    update_option('vendor_frequency', $vendor_frequency);      
        
    $back_office_email = $_POST['back_office_email'];  
    update_option('back_office_email', $back_office_email);

    $back_office_subject = $_POST['back_office_subject'];  
    update_option('back_office_subject', $back_office_subject);     

    $customer_project_subject = $_POST['customer_project_subject'];  
    update_option('customer_project_subject', $customer_project_subject);                 
?>  
    <div id="message" class="updated"><?php _e('Settings updated!' ); ?></div> 
<?php
} 
$company_name = get_option('company_name');
$company_address = get_option('company_address');
$company_city = get_option('company_city');
$company_state = get_option('company_state');
$company_zip = get_option('company_zip');
$project_frequency = get_option('project_frequency');
$vendor_frequency = get_option('vendor_frequency');
$project_stale_threshold = get_option('project_stale_threshold');
$outstanding_project_threshold = get_option('outstanding_project_threshold');
$back_office_email = get_option('back_office_email');
$back_office_subject = get_option('back_office_subject');
$customer_project_subject = get_option('customer_project_subject');
?> 
<div class="wrap">  
    <?php    echo "<h2>" . __( 'King Pools Management Portal Options', 'king_trdom' ) . "</h2>"; ?>  
      
    <hr>  
    <form name="king_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">    
        <?php    echo "<h3>" . __( 'Company Settings', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Name: " ); ?></label>
                    </th>
                    <td><input type="text" name="company_name" value="<?=$company_name; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Address: " ); ?></label>
                    </th>
                    <td><input type="text" name="company_address" value="<?=$company_address; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("City: " ); ?></label>
                    </th>
                    <td><input type="text" name="company_city" value="<?=$company_city; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("State: " ); ?></label>
                    </th>
                    <td><input type="text" name="company_state" value="<?=$company_state; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Zip: " ); ?></label>
                    </th>
                    <td><input type="text" name="company_zip" value="<?=$company_zip; ?>" size="20"></td>
                </tr>                
            </tbody>
        </table>
        <hr>
        <?php    echo "<h3>" . __( 'Back Office Notification Settings', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Email: " ); ?></label>
                    </th>
                    <td><input type="text" name="back_office_email" value="<?=$back_office_email; ?>" size="30"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Subject: " ); ?></label>
                    </th>
                    <td><input type="text" name="back_office_subject" value="<?=$back_office_subject; ?>" size="30"><i>(Subject of email + " - mm/dd/yyyy")</i></td>
                </tr>                               
            </tbody>
        </table>
        <?php    echo "<h3>" . __( 'Customer Project Notification Settings', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Subject: " ); ?></label>
                    </th>
                    <td><input type="text" name="customer_project_subject" value="<?=$customer_project_subject; ?>" size="30"><i>(Subject of email + " - mm/dd/yyyy")</i></td>
                </tr>                               
            </tbody>
        </table>        
        <?php    echo "<h3>" . __( 'General Settings', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>        
                <tr>
                    <th>
                        <label><?php _e("Project Stale Threshold: " ); ?></label>
                    </th>
                    <td><input type="text" name="project_stale_threshold" value="<?=$project_stale_threshold; ?>" size="20"><i>(Number of days old a project is before it will highlight as red)</i></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Outstanding Project Threshold: " ); ?></label>
                    </th>
                    <td><input type="text" name="outstanding_project_threshold" value="<?=$outstanding_project_threshold; ?>" size="20"><i>(Number of days old a project is to show in 'Outstanding' view)</i></td>
                </tr>                
                <tr>
                    <th>
                        <label><?php _e("Project Frequency: " ); ?></label>
                    </th>
                    <td><input type="text" name="project_frequency" value="<?=$project_frequency; ?>" size="20"><i>(Number of days in between project update notifications)</i></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Vendor Frequency: " ); ?></label>
                    </th>
                    <td><input type="text" name="vendor_frequency" value="<?=$vendor_frequency; ?>" size="20"><i>(Number of days in between vendor update notifications)</i></td>
                </tr>                  
            </tbody>
        </table>        
        
        
        <input type="hidden" name="king_hidden" value="Y">
        <p class="submit">  
        <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'king_trdom' ) ?>" />  
        </p>  
    </form>
    <hr>
</div>