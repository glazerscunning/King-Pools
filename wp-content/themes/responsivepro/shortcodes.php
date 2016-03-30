<?php
add_shortcode('getRequestValue', function($atts){
    
    return $_REQUEST[$atts['parameter']];
    
});

add_shortcode('getEstimate', function($atts){

    $king_pricing_options = get_option('king_pricing_options');

    $estimate_type = $atts['estimate_type'];
    
    $pool_type = strtolower(($estimate_type == 'notification') ? $atts['pool_type'] : ($_REQUEST[$atts['pool_type']]));
    $pool_size_activity = strtolower(($estimate_type == 'notification') ? $atts['pool_size_activity'] : ($_REQUEST[$atts['pool_size_activity']]));
    $pool_size_diving = strtolower(($estimate_type == 'notification') ? $atts['pool_size_diving'] : ($_REQUEST[$atts['pool_size_diving']]));
    $spa_option = strtolower(($estimate_type == 'notification') ? $atts['spa_option'] : ($_REQUEST[$atts['spa_option']]));
    $spa_size = strtolower(($estimate_type == 'notification') ? $atts['spa_size'] : ($_REQUEST[$atts['spa_size']]));
    $decking_option = strtolower(($estimate_type == 'notification') ? $atts['decking_option'] : ($_REQUEST[$atts['decking_option']]));
    $tanning_ledge_option = strtolower(($estimate_type == 'notification') ? $atts['tanning_ledge_option'] : ($_REQUEST[$atts['tanning_ledge_option']]));
    $rock_waterfall_option = strtolower(($estimate_type == 'notification') ? $atts['rock_waterfall_option'] : ($_REQUEST[$atts['rock_waterfall_option']]));
    $grotto_cave_option = strtolower(($estimate_type == 'notification') ? $atts['grotto_cave_option'] : ($_REQUEST[$atts['grotto_cave_option']]));
    $rock_grotto_slide_option = strtolower(($estimate_type == 'notification') ? $atts['rock_grotto_slide_option'] : ($_REQUEST[$atts['rock_grotto_slide_option']]));
    $slide_option = strtolower(($estimate_type == 'notification') ? $atts['slide_option'] : ($_REQUEST[$atts['slide_option']]));
    $lounge_table_option = strtolower(($estimate_type == 'notification') ? $atts['lounge_table_option'] : ($_REQUEST[$atts['lounge_table_option']]));
    $firepit_option = strtolower(($estimate_type == 'notification') ? $atts['firepit_option'] : ($_REQUEST[$atts['firepit_option']]));
    $fireplace_option = strtolower(($estimate_type == 'notification') ? $atts['fireplace_option'] : ($_REQUEST[$atts['fireplace_option']]));
    $outdoor_kitchen_option = strtolower(($estimate_type == 'notification') ? $atts['outdoor_kitchen_option'] : ($_REQUEST[$atts['outdoor_kitchen_option']]));
    $big_green_egg_option = strtolower(($estimate_type == 'notification') ? $atts['big_green_egg_option'] : ($_REQUEST[$atts['big_green_egg_option']]));
    $gas_grill_option = strtolower(($estimate_type == 'notification') ? $atts['gas_grill_option'] : ($_REQUEST[$atts['gas_grill_option']]));
    $arbor_pergola_option = strtolower(($estimate_type == 'notification') ? $atts['arbor_pergola_option'] : ($_REQUEST[$atts['arbor_pergola_option']]));
    $gazebo_cabana_option = strtolower(($estimate_type == 'notification') ? $atts['gazebo_cabana_option'] : ($_REQUEST[$atts['gazebo_cabana_option']]));
    $landscaping_option = strtolower(($estimate_type == 'notification') ? $atts['landscaping_option'] : ($_REQUEST[$atts['landscaping_option']]));

    $entry_id = $_REQUEST['entry_id'];
    
    $pool_estimate = 0;
    $options_estimate = 0;
    $total_estimate = 0;
    

    if($pool_type == 'diving-pool'){
        if($pool_size_diving == 'diving-small'){
            $pool_estimate += intval($king_pricing_options['diving_small_price']);
        }else if($pool_size_diving == 'diving-medium'){
            $pool_estimate += intval($king_pricing_options['diving_medium_price']);
        }else if($pool_size_diving == 'diving-large'){
            $pool_estimate += intval($king_pricing_options['diving_large_price']);
        }
    } else if($pool_type == 'activity-pool'){
        if($pool_size_activity == 'activity-small'){
            $pool_estimate += intval($king_pricing_options['play_small_price']);
        }else if($pool_size_activity == 'activity-medium'){
            $pool_estimate += intval($king_pricing_options['play_medium_price']);
        }else if($pool_size_activity == 'activity-large'){
            $pool_estimate += intval($king_pricing_options['play_large_price']);
        }
    }
    
    if($spa_option == 'yes'){
        if($spa_size == 'level'){
            $pool_estimate += intval($king_pricing_options['spa_level_price']);
        }else if($spa_size == 'raised-medium'){
            $pool_estimate += intval($king_pricing_options['spa_medium_price']);
        }else if($spa_size == 'raised-large'){
            $pool_estimate += intval($king_pricing_options['spa_large_price']);
        }
    }
    
    if($decking_option == 'small'){
        $pool_estimate += intval($king_pricing_options['decking_small_price']);
    }else if($decking_option == 'medium'){
        $pool_estimate += intval($king_pricing_options['decking_medium_price']);
    }else if($decking_option == 'large'){
        $pool_estimate += intval($king_pricing_options['decking_large_price']);
    }
    
    if($rock_waterfall_option == 'small'){
        $options_estimate += intval($king_pricing_options['rockwfall_small_price']);
    }else if($rock_waterfall_option == 'medium'){
        $options_estimate += intval($king_pricing_options['rockwfall_medium_price']);
    }else if($rock_waterfall_option == 'large'){
        $options_estimate += intval($king_pricing_options['rockwfall_large_price']);
    }
    
    if($tanning_ledge_option == 'step-down'){
        $options_estimate += intval($king_pricing_options['tanledge_stepdown_price']);
    }else if($tanning_ledge_option == 'beach-entry'){
        $options_estimate += intval($king_pricing_options['tanledge_beachentry_price']);
    }

    if($grotto_cave_option == 'standard'){
        $options_estimate += intval($king_pricing_options['grottocave_standard_price']);
    }else if($grotto_cave_option == 'large'){
        $options_estimate += intval($king_pricing_options['grottocave_large_price']);
    }else if($grotto_cave_option == 'xlarge'){
        $options_estimate += intval($king_pricing_options['grottocave_xlarge_price']);
    }

    if($rock_grotto_slide_option == 'small'){
        $options_estimate += intval($king_pricing_options['rockwfall_grotto_slide_small_price']);
    }else if($rock_grotto_slide_option == 'standard'){
        $options_estimate += intval($king_pricing_options['rockwfall_grotto_slide_standard_price']);
    }

    if($slide_option == 'standard'){
        $options_estimate += intval($king_pricing_options['slide_standard_price']);
    }else if($slide_option == 'large'){
        $options_estimate += intval($king_pricing_options['slide_large_price']);
    }

    if($lounge_table_option == 'stone-lounge'){
        $options_estimate += intval($king_pricing_options['lounge_table_price']);
    }

    if($firepit_option == 'wood'){
        $options_estimate += intval($king_pricing_options['firepit_wood_price']);
    }else if($firepit_option == 'gas'){
        $options_estimate += intval($king_pricing_options['firepit_gas_price']);
    }

    if($fireplace_option == 'fire-place'){
        $options_estimate += intval($king_pricing_options['fireplace_price']);
    }

    if($outdoor_kitchen_option == 'outdoor-kitchen'){
        $options_estimate += intval($king_pricing_options['outdoor_kitchen_price']);
    }

    if($big_green_egg_option == 'small'){
        $options_estimate += intval($king_pricing_options['greenegg_small_price']);
    }elseif ($big_green_egg_option == 'medium') {
        $options_estimate += intval($king_pricing_options['greenegg_medium_price']);
    }elseif ($big_green_egg_option == 'large') {
        $options_estimate += intval($king_pricing_options['greenegg_large_price']);
    }

    if($gas_grill_option == 'small'){
        $options_estimate += intval($king_pricing_options['gasgrill_small_price']);
    }elseif ($gas_grill_option == 'medium') {
        $options_estimate += intval($king_pricing_options['gasgrill_medium_price']);
    }elseif ($gas_grill_option == 'large') {
        $options_estimate += intval($king_pricing_options['gasgrill_large_price']);
    }

    if($arbor_pergola_option == 'small'){
        $options_estimate += intval($king_pricing_options['arbor_small_price']);
    }elseif ($arbor_pergola_option == 'medium') {
        $options_estimate += intval($king_pricing_options['arbor_medium_price']);
    }elseif ($arbor_pergola_option == 'large') {
        $options_estimate += intval($king_pricing_options['arbor_large_price']);
    }

    if($gazebo_cabana_option == 'small'){
        $options_estimate += intval($king_pricing_options['gazebo_small_price']);
    }elseif ($gazebo_cabana_option == 'medium') {
        $options_estimate += intval($king_pricing_options['gazebo_medium_price']);
    }elseif ($gazebo_cabana_option == 'large') {
        $options_estimate += intval($king_pricing_options['gazebo_large_price']);
    }

    if($landscaping_option == 'small'){
        $options_estimate += intval($king_pricing_options['landscaping_small_price']);
    }elseif ($landscaping_option == 'medium') {
        $options_estimate += intval($king_pricing_options['landscaping_medium_price']);
    }elseif ($landscaping_option == 'large') {
        $options_estimate += intval($king_pricing_options['landscaping_large_price']);
    }

    $total_estimate = $pool_estimate + $options_estimate;
    
    if($estimate_type == 'form'){
        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'king_estimates',
                      array('lead_id' => $entry_id,
                            'value'   => $total_estimate,
                            'status'  => 'unconverted'),
                      array('%d','%s','%s')
                     );
    }
    
    $htmlOutput = "<br><br>";
    $htmlOutput .= "Pool - $" . $pool_estimate . "<br>";
    $htmlOutput .= "Options - $" . $options_estimate . "<br>";
    $htmlOutput .= "Total - $" . $total_estimate;

    return $htmlOutput;
    
});

?>