<?php   

include("inc/_pricing_jquery.js");

if($_POST['king_hidden'] == 'Y') {  

    $array_of_options = array(
        'play_small_price' => $_POST['play_small_price'],
        'play_medium_price' => $_POST['play_medium_price'],
        'play_large_price' => $_POST['play_large_price'],
        'diving_small_price' => $_POST['diving_small_price'],
        'diving_medium_price' => $_POST['diving_medium_price'],
        'diving_large_price' => $_POST['diving_large_price'],
        'spa_level_price' => $_POST['spa_level_price'],
        'spa_medium_price' => $_POST['spa_medium_price'],
        'spa_large_price' => $_POST['spa_large_price'],        
        'decking_small_price' => $_POST['decking_small_price'],
        'decking_medium_price' => $_POST['decking_medium_price'],
        'decking_large_price' => $_POST['decking_large_price'],
        'tanledge_stepdown_price' => $_POST['tanledge_stepdown_price'],
        'tanledge_beachentry_price' => $_POST['tanledge_beachentry_price'],             
        'rockwfall_small_price' => $_POST['rockwfall_small_price'],
        'rockwfall_medium_price' => $_POST['rockwfall_medium_price'],
        'rockwfall_large_price' => $_POST['rockwfall_large_price'],
        'grottocave_standard_price' => $_POST['grottocave_standard_price'],
        'grottocave_large_price' => $_POST['grottocave_large_price'],
        'grottocave_xlarge_price' => $_POST['grottocave_xlarge_price'],
        'rockwfall_grotto_slide_small_price' => $_POST['rockwfall_grotto_slide_small_price'],
        'rockwfall_grotto_slide_standard_price' => $_POST['rockwfall_grotto_slide_standard_price'],
        'slide_standard_price' => $_POST['slide_standard_price'],
        'slide_large_price' => $_POST['slide_large_price'],        
        'lounge_table_price' => $_POST['lounge_table_price'],
        'firepit_wood_price' => $_POST['firepit_wood_price'],        
        'firepit_gas_price' => $_POST['firepit_gas_price'],  
        'fireplace_price' => $_POST['fireplace_price'], 
        'outdoor_kitchen_price' => $_POST['outdoor_kitchen_price'], 
        'greenegg_small_price' => $_POST['greenegg_small_price'],
        'greenegg_medium_price' => $_POST['greenegg_medium_price'],
        'greenegg_large_price' => $_POST['greenegg_large_price'],  
        'gasgrill_small_price' => $_POST['gasgrill_small_price'],
        'gasgrill_medium_price' => $_POST['gasgrill_medium_price'],
        'gasgrill_large_price' => $_POST['gasgrill_large_price'],   
        'arbor_small_price' => $_POST['arbor_small_price'],
        'arbor_medium_price' => $_POST['arbor_medium_price'],
        'arbor_large_price' => $_POST['arbor_large_price'],                               
        'gazebo_small_price' => $_POST['gazebo_small_price'],
        'gazebo_medium_price' => $_POST['gazebo_medium_price'],
        'gazebo_large_price' => $_POST['gazebo_large_price'],
        'landscaping_small_price' => $_POST['landscaping_small_price'],
        'landscaping_medium_price' => $_POST['landscaping_medium_price'],
        'landscaping_large_price' => $_POST['landscaping_large_price']       
    );      

    update_option('king_pricing_options', $array_of_options );

?>  
    <div id="message" class="updated"><?php _e('Pricing updated!' ); ?></div> 
<?php
} 

    $king_pricing_options = get_option('king_pricing_options');

?> 
<div class="wrap">  
    <?php    echo "<h2>" . __( 'Pool Estimate Pricing Configuration', 'king_trdom' ) . "</h2>"; ?>  
      
    <hr>  
    <form name="king_pricing_form" class="king_pricing_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">    
        <?php    echo "<h3>" . __( 'Play Pools', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="play_small_price" value="<?=$king_pricing_options['play_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="play_medium_price" value="<?=$king_pricing_options['play_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="play_large_price" value="<?=$king_pricing_options['play_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>
        <hr>
        <?php    echo "<h3>" . __( 'Diving Pools', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="diving_small_price" value="<?=$king_pricing_options['diving_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="diving_medium_price" value="<?=$king_pricing_options['diving_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="diving_large_price" value="<?=$king_pricing_options['diving_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'Spa', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Level: " ); ?></label>
                    </th>
                    <td><input type="text" name="spa_level_price" value="<?=$king_pricing_options['spa_level_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Raised Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="spa_medium_price" value="<?=$king_pricing_options['spa_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Raised Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="spa_large_price" value="<?=$king_pricing_options['spa_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>  
        <?php    echo "<h3>" . __( 'Decking/Flat Work', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="decking_small_price" value="<?=$king_pricing_options['decking_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="decking_medium_price" value="<?=$king_pricing_options['decking_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="decking_large_price" value="<?=$king_pricing_options['decking_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>             
        <?php    echo "<h3>" . __( 'Tanning Ledge', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Step Down: " ); ?></label>
                    </th>
                    <td><input type="text" name="tanledge_stepdown_price" value="<?=$king_pricing_options['tanledge_stepdown_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Beach Entry: " ); ?></label>
                    </th>
                    <td><input type="text" name="tanledge_beachentry_price" value="<?=$king_pricing_options['tanledge_beachentry_price']; ?>" size="20"></td>
                </tr>         
            </tbody>
        </table>        
        <hr>          
        <?php    echo "<h3>" . __( 'Waterfall - Rock', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="rockwfall_small_price" value="<?=$king_pricing_options['rockwfall_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="rockwfall_medium_price" value="<?=$king_pricing_options['rockwfall_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="rockwfall_large_price" value="<?=$king_pricing_options['rockwfall_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>  
        <?php    echo "<h3>" . __( 'Grotto Cave Rock Waterfall', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Standard: " ); ?></label>
                    </th>
                    <td><input type="text" name="grottocave_standard_price" value="<?=$king_pricing_options['grottocave_standard_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="grottocave_large_price" value="<?=$king_pricing_options['grottocave_large_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("X-Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="grottocave_xlarge_price" value="<?=$king_pricing_options['grottocave_xlarge_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>  
        <?php    echo "<h3>" . __( 'Rock Waterfall with Grotto Cave and Hand Made Tile Slide', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="rockwfall_grotto_slide_small_price" value="<?=$king_pricing_options['rockwfall_grotto_slide_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Standard: " ); ?></label>
                    </th>
                    <td><input type="text" name="rockwfall_grotto_slide_standard_price" value="<?=$king_pricing_options['rockwfall_grotto_slide_standard_price']; ?>" size="20"></td>
                </tr>           
            </tbody>
        </table>        
        <hr>  
        <?php    echo "<h3>" . __( 'Slides', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Standard Plaster: " ); ?></label>
                    </th>
                    <td><input type="text" name="slide_standard_price" value="<?=$king_pricing_options['slide_standard_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large Plastic: " ); ?></label>
                    </th>
                    <td><input type="text" name="slide_large_price" value="<?=$king_pricing_options['slide_large_price']; ?>" size="20"></td>
                </tr>           
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'In Pool Lounge Table', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Stone Lounge Table and Bench: " ); ?></label>
                    </th>
                    <td><input type="text" name="lounge_table_price" value="<?=$king_pricing_options['lounge_table_price']; ?>" size="20"></td>
                </tr>         
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'Fire Pit', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Wood Burning: " ); ?></label>
                    </th>
                    <td><input type="text" name="firepit_wood_price" value="<?=$king_pricing_options['firepit_wood_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Gas Ring & Church Key: " ); ?></label>
                    </th>
                    <td><input type="text" name="firepit_gas_price" value="<?=$king_pricing_options['firepit_gas_price']; ?>" size="20"></td>
                </tr>           
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'Fire Place', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Stone Fire Place: " ); ?></label>
                    </th>
                    <td><input type="text" name="fireplace_price" value="<?=$king_pricing_options['fireplace_price']; ?>" size="20"></td>
                </tr>         
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'Outdoor Kitchen', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Standard: " ); ?></label>
                    </th>
                    <td><input type="text" name="outdoor_kitchen_price" value="<?=$king_pricing_options['outdoor_kitchen_price']; ?>" size="20"></td>
                </tr>         
            </tbody>
        </table>        
        <hr> 
        <?php    echo "<h3>" . __( 'Big Green Egg', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="greenegg_small_price" value="<?=$king_pricing_options['greenegg_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="greenegg_medium_price" value="<?=$king_pricing_options['greenegg_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="greenegg_large_price" value="<?=$king_pricing_options['greenegg_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>  
        <?php    echo "<h3>" . __( 'Gas Stainless Steel Grill', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="gasgrill_small_price" value="<?=$king_pricing_options['gasgrill_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="gasgrill_medium_price" value="<?=$king_pricing_options['gasgrill_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="gasgrill_large_price" value="<?=$king_pricing_options['gasgrill_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>
        <?php    echo "<h3>" . __( 'Arbor/Pergola', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="arbor_small_price" value="<?=$king_pricing_options['arbor_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="arbor_medium_price" value="<?=$king_pricing_options['arbor_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="arbor_large_price" value="<?=$king_pricing_options['arbor_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>
        <?php    echo "<h3>" . __( 'Gazebo/Cabana', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="gazebo_small_price" value="<?=$king_pricing_options['gazebo_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="gazebo_medium_price" value="<?=$king_pricing_options['gazebo_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="gazebo_large_price" value="<?=$king_pricing_options['gazebo_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>
        <?php    echo "<h3>" . __( 'Landscaping', 'king_trdom' ) . "</h3>"; ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th>
                        <label><?php _e("Small: " ); ?></label>
                    </th>
                    <td><input type="text" name="landscaping_small_price" value="<?=$king_pricing_options['landscaping_small_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Medium: " ); ?></label>
                    </th>
                    <td><input type="text" name="landscaping_medium_price" value="<?=$king_pricing_options['landscaping_medium_price']; ?>" size="20"></td>
                </tr>
                <tr>
                    <th>
                        <label><?php _e("Large: " ); ?></label>
                    </th>
                    <td><input type="text" name="landscaping_large_price" value="<?=$king_pricing_options['landscaping_large_price']; ?>" size="20"></td>
                </tr>              
            </tbody>
        </table>        
        <hr>

        <input type="hidden" name="king_hidden" value="Y">
        <p class="submit">  
        <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'king_trdom' ) ?>" />  
        </p>  
    </form>
    <hr>
</div>