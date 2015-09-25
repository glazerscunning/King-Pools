    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label><?php _e("Name: " ); ?></label>
                </th>
                <td><input required class="textbox" type="text" name="vendor_name" value="<?=$result->vendor_name?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("Address: " ); ?></label>
                </th>
                <td><input required type="text" name="vendor_address" value="<?=$result->vendor_address?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("City: " ); ?></label>
                </th>
                <td><input required type="text" name="vendor_city" value="<?=$result->vendor_city?>" size="20"></td>
            </tr>
            <tr>
                <th>
                    <label><?php _e("State: " ); ?></label>
                </th>
                <td>
                    <select required name="vendor_state">
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