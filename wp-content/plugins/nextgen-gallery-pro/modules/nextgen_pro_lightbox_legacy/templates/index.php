<!DOCTYPE html>
<html>
    <head>
        <meta name='viewport' content='width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
        <style type='text/css'>
            html, body, #galleria {
                height: 100%;
                width: 100%;
                margin: 0px;
                padding: 0px;
                max-height: 100%;
                max-width: 100%;
            }
        </style>
        <?php
            wp_print_styles();
            wp_print_scripts();
        ?>
    </head>
    <body>
        <!-- component markup, such as backbone/underscore/mustache templates -->
        <?php echo $component_markup ?>

        <!-- tabindex is necessary for some browsers to focus #galleria after loading -->
        <div id='galleria' tabindex='1'></div>
        <script type='text/javascript'>
            (function($) {
                window.lightbox_settings = <?php echo json_encode($lightbox_settings); ?>;
                window.Galleria_Instance.create('<?php echo $displayed_gallery_id; ?>');
                $(window).bind('beforeunload', function() {
                    if (top.nplModalRouted) {
                        top.nplModalRouted.close_modal();
                    }
                });
            })(jQuery);
        </script>
    </body>
</html>
