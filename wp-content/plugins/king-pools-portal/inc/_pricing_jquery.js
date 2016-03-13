<script type="text/javascript">

jQuery(document).ready(function() {

    jQuery("#update_options").click(function(){
        var pricing_clean = true;

        jQuery("#king_pricing_form input[type='text']").each(function( index ) {
            
            if(!jQuery.isNumeric(jQuery(this).val())){
                jQuery(this).css('border','1px solid red');
                pricing_clean = false;
            }
           
        });

        if(!pricing_clean){
            jQuery(function() {
                jQuery( "#errors_dialog" ).dialog();
            });
            return false;
        }
    });    

});

</script> 