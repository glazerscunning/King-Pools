<script type="text/javascript">

jQuery(document).ready(function() {
    jQuery('#vnd_schedule_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });''
    jQuery('#wo_schedule_date').datepicker({
        dateFormat : 'yy-mm-dd'
    });

    if(jQuery("select.project_status option:selected").val() != "Complete"){
        jQuery('.project_amount').hide();
    }else{
        jQuery('#update_project_button').hide();
    }

    jQuery('.work_order_scheduling').hide();
    jQuery('.vendor_phase').hide();
    jQuery('.construction_phase').hide();

    if(jQuery("select.project_type option:selected").val() == "cleaning" || jQuery("select.project_type option:selected").val() == "service-repair"){
        if(jQuery("select.project_status option:selected").val() != "Complete"){
            jQuery('.work_order_scheduling').show();
        }
    }else if(jQuery("select.project_type option:selected").val() == "construction-remodel"){
        jQuery('.construction_phase').show();
    }

    if(jQuery("select.project_phase option:selected").attr("vnd_email_trigger") > 0){
        jQuery('.vendor_phase').show();
    }

<?php
if(empty($result->vendor_name)){
?>    
    jQuery('.vendor_phase').hide();
<?php 
}
?>  
    jQuery(".project_phase").change(function() {
        if(jQuery("select.project_phase option:selected").attr("vnd_email_trigger") > 0){
            jQuery('.vendor_phase').fadeIn();
        }else{
            jQuery('.vendor_phase').fadeOut();
        }
    });

    jQuery(".project_type").change(function() {
        if(jQuery("select.project_type option:selected").val() == "construction-remodel"){
            jQuery('.work_order_scheduling').hide();
            jQuery('.construction_phase').fadeIn();
        }

        if(jQuery("select.project_type option:selected").val() == "cleaning" || jQuery("select.project_type option:selected").val() == "service-repair"){
            jQuery('.vendor_phase').hide();
            jQuery('.construction_phase').hide();
            jQuery('.work_order_scheduling').fadeIn();
        }else if(jQuery("select.project_type option:selected").val() == "none"){
            jQuery('.work_order_scheduling').hide();
            jQuery('.construction_phase').hide();
            jQuery('.vendor_phase').hide();
        }

    });  

    jQuery(".project_status").change(function() {
        if(jQuery("select.project_status option:selected").val() == "In Progress" && jQuery(".project_type").val() == "construction-remodel"){
            jQuery( "<div>This status will trigger the new customer email.</div>" ).dialog({
                  modal: true,
                  buttons: {
                    Ok: function() {
                      jQuery( this ).dialog( "close" );
                    }
                  }
            });
        }else if(jQuery("select.project_status option:selected").val() == "Complete"){
            jQuery('.project_amount').fadeIn();
        }else{
            jQuery('.project_amount').fadeOut();
        }
    });        

    jQuery("#update_project_button").click(function(){
        if(jQuery("select.project_status option:selected").val() == "Complete" && jQuery("#project_amount").val() == 0){
           jQuery("#project_amount").css('border','1px solid red'); 
           return false;
        }

        if(jQuery("select.project_type option:selected").val() == "cleaning" || jQuery("select.project_type option:selected").val() == "service-repair"){
            if(jQuery("#wo_schedule_date").val() == ''){
                jQuery("#wo_schedule_date").css('border','1px solid red');
                return false;
            }
        }
    });

});

</script> 