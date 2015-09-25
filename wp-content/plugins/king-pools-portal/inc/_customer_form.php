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