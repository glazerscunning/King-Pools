    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label><?php _e("Customer Name: " ); ?></label>
                </th>
                <td>
                <?php
                if($_REQUEST['action'] == 'add'){
                ?>  
                    <select name="customer_id">
                        <option value="">Please select...</option>
                        <?php
                        foreach ($customers as $customer ){
                        ?>
                        <option value="<?=$customer->customer_id;?>"><?=$customer->customer_firstname . " " . $customer->customer_lastname;?></option>    
                        <?php
                        }
                        ?>
                    </select>
                <?php
                } else {
                ?>
                    <span class="description"><?=$result->customer_firstname . " " . $result->customer_lastname;?></span>
                <?php
                }
                ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Status: " ); ?></label>
                </th>
                <td>
                <?php
                if($_REQUEST['action'] == 'add'){
                ?>      
                    <span class="description"><?= ($_REQUEST['action'] == 'add') ? 'New' : $result->project_status;?></span>
                <?php
                } else {
                ?>
                    <select name="project_status" class="project_status">
                        <option value="New" <?= ($result->project_status == "New") ? "selected='selected'" : "";?>>New</option>
                        <option value="In Progress" <?= ($result->project_status == "In Progress") ? "selected='selected'" : "";?>>In Progress</option>
                        <option value="Complete" <?= ($result->project_status == "Complete") ? "selected='selected'" : "";?>>Complete</option>
                    </select>                
                <?php
                }
                ?>                
                </td>
            </tr> 
            <tr class="project_amount">
                <th>
                    <label><?php _e("Project Amount: " ); ?></label>
                </th>
                <td>
                    $<input type="text" id="project_amount" name="project_amount" value="<?= $result->project_amount?>"/><i>(Final project amount)</i>
                </td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Project Notes: " ); ?></label>
                </th>
                <td>
                    <textarea id="project_notes" cols="50" rows="5" name="project_notes"><?= $result->project_notes?></textarea>
                </td>
            </tr>                       
            <tr>
                <th>
                    <label><?php _e("Type: " ); ?></label>
                </th>
                <td>
                    <select name="project_type" class="project_type">
                        <option value="none">Please select...</option>
                        <option value="construction-remodel" <?= ($result->project_type == "construction-remodel") ? "selected='selected'" : "";?>>Construction/Remodel</option>
                        <option value="cleaning" <?=($result->project_type == "cleaning") ? "selected='selected'" : "";?>>Pool Cleaning</option>
                        <option value="service-repair" <?=($result->project_type == "service-repair") ? "selected='selected'" : "";?>>Service/Repair</option>
                    </select>
                </td>
            </tr>             
            <tr class="construction_phase">
                <th>
                    <label><?php _e("Construction Phase: " ); ?></label>
                </th>
                <td>
                    <select name="project_phase" class="project_phase">
                        <option value="">N/A</option>
<?php
                    foreach($phases as $phase){
?>
                        <option vnd_email_trigger="<?=$phase->phase_trigger_vendor_email?>" value="<?=$phase->phase_id?>" <?= ($result->phase_id == $phase->phase_id) ? "selected='selected'" : "";?>><?=$phase->phase_id?>) <?=$phase->phase_name?> - <?=ucfirst($phase->phase_status)?></option>  
<?php                        
                    }        
?>              
                    
                    </select>
                </td>
            </tr>
            <tr class="work_order_scheduling">
                <th>
                    <label><?php _e("Schedule Work Order: " ); ?></label>
                </th>
                <td>
                    <input type="text" id="wo_schedule_date" name="wo_schedule_date" value="<?= $result->schedule_date?>"/>                                  
                </td>
            </tr>             
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Vendor Selection: " ); ?></label>
                </th>
                <td>
                    <select name="vendor_id">
                        <option value="">N/A</option>
<?php
                    foreach($vendors as $vendor){
?>
                        <option value="<?=$vendor->vendor_id?>" <?= ($result->vendor_id == $vendor->vendor_id) ? "selected='selected'" : "";?>><?=$vendor->vendor_name?></option>  
<?php                        
                    }        
?>              
                    
                    </select>
                </td>
            </tr>                                  
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Schedule Vendor: " ); ?></label>
                </th>
                <td>
                    <input type="text" id="vnd_schedule_date" name="vnd_schedule_date" value="<?= $result->schedule_date?>"/>                                  
                </td>
            </tr>
            <tr class="vendor_phase">
                <th>
                    <label><?php _e("Send pool plan to vendor?: " ); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="attach_pool_plan" name="attach_pool_plan" value="yes"/>                                  
                </td>
            </tr>            
        </tbody>
    </table>    
    <input type="hidden" name="project_id" value="<?=$_REQUEST['id']?>"/>
    <input type="hidden" name="last_project_status" value="<?=$result->project_status;?>"/>
    <p class="submit">
<?php
if($_REQUEST['action'] == 'view'){
?>
<input id="update_project_button" class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Project', 'king_trdom' ) ?>" />

<?php
} else {
?>
<input id="add_project_button" class="button button-primary" type="submit" name="Submit" value="<?php _e('Add Project', 'king_trdom' ) ?>" /> 
<?php
}
?>           
    </p>