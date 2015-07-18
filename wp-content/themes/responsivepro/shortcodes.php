<?php
add_shortcode('getRequestValue', function($atts){
    
    return $_REQUEST[$atts['parameter']];
    
});

add_shortcode('getEstimate', function($atts){
    
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
            $pool_estimate += 34600;
        }else if($pool_size_diving == 'diving-medium'){
            $pool_estimate += 37100;
        }else if($pool_size_diving == 'diving-large'){
            $pool_estimate += 42900;
        }
    } else if($pool_type == 'activity-pool'){
        if($pool_size_activity == 'activity-small'){
            $pool_estimate += 22900;
        }else if($pool_size_activity == 'activity-medium'){
            $pool_estimate += 26600;
        }else if($pool_size_activity == 'activity-large'){
            $pool_estimate += 31400;
        }
    }
    
    if($spa_option == 'yes'){
        if($spa_size == 'level'){
            $pool_estimate += 7600;
        }else if($spa_size == 'raised-medium'){
            $pool_estimate += 8200;
        }else if($spa_size == 'raised-large'){
            $pool_estimate += 9200;
        }
    }
    
    if($decking_option == 'small'){
        $pool_estimate += 2700;
    }else if($decking_option == 'medium'){
        $pool_estimate += 4100;
    }else if($decking_option == 'large'){
        $pool_estimate += 5900;
    }
    
    if($rock_waterfall_option == 'small'){
        $options_estimate += 1200;
    }else if($rock_waterfall_option == 'medium'){
        $options_estimate += 3000;
    }else if($rock_waterfall_option == 'large'){
        $options_estimate += 6000;
    }
    
    if($tanning_ledge_option == 'step-down'){
        $options_estimate += 100;
    }else if($tanning_ledge_option == 'beach-entry'){
        $options_estimate += 600;
    }

    if($grotto_cave_option == 'standard'){
        $options_estimate += 3700;
    }else if($grotto_cave_option == 'large'){
        $options_estimate += 5000;
    }else if($grotto_cave_option == 'xlarge'){
        $options_estimate += 7500;
    }

    if($rock_grotto_slide_option == 'small'){
        $options_estimate += 6800;
    }else if($rock_grotto_slide_option == 'standard'){
        $options_estimate += 10200;
    }

    if($slide_option == 'standard'){
        $options_estimate += 2800;
    }else if($slide_option == 'large'){
        $options_estimate += 3800;
    }

    if($lounge_table_option == 'stone-lounge'){
        $options_estimate += 650;
    }

    if($firepit_option == 'wood'){
        $options_estimate += 1400;
    }else if($firepit_option == 'gas'){
        $options_estimate += 1850;
    }

    if($fireplace_option == 'fire-place'){
        $options_estimate += 7950;
    }

    if($outdoor_kitchen_option == 'outdoor-kitchen'){
        $options_estimate += 3300;
    }

    if($big_green_egg_option == 'small'){
        $options_estimate += 600;
    }elseif ($big_green_egg_option == 'medium') {
        $options_estimate += 750;
    }elseif ($big_green_egg_option == 'large') {
        $options_estimate += 975;
    }

    if($gas_grill_option == 'small'){
        $options_estimate += 1200;
    }elseif ($gas_grill_option == 'medium') {
        $options_estimate += 1650;
    }elseif ($gas_grill_option == 'large') {
        $options_estimate += 2100;
    }

    if($arbor_pergola_option == 'small'){
        $options_estimate += 4100;
    }elseif ($arbor_pergola_option == 'medium') {
        $options_estimate += 5600;
    }elseif ($arbor_pergola_option == 'large') {
        $options_estimate += 7200;
    }

    if($gazebo_cabana_option == 'small'){
        $options_estimate += 4800;
    }elseif ($gazebo_cabana_option == 'medium') {
        $options_estimate += 7500;
    }elseif ($gazebo_cabana_option == 'large') {
        $options_estimate += 13000;
    }

    if($landscaping_option == 'small'){
        $options_estimate += 2700;
    }elseif ($landscaping_option == 'medium') {
        $options_estimate += 3800;
    }elseif ($landscaping_option == 'large') {
        $options_estimate += 5400;
    }
    
    
    $total_estimate = $pool_estimate + $options_estimate;
    
    if($estimate_type == 'form'){
        global $wpdb;
        $wpdb->insert('wp_king_estimates',
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