(function($) {
    window.Galleria_Instance = {
        gallery_selector: '#galleria',
        ajax_image_fetch_completed: false,
        images: [],

        create: function(id) {
            this.id = id;
            if (id != '!') {
                this.displayed_gallery = this.find_gallery_on_parent(id);
            }
            this.configure_galleria();
            Galleria.run(this.gallery_selector, {
                responsive: true,
                debug: false
            });
        },

        find_gallery_on_parent: function(id){
            var retval = null;

            // Find the gallery based on it's ID
            if (typeof(top.galleries['gallery_'+id]) != 'undefined') {
                retval = top.galleries['gallery_'+id];
            }

            // Find the gallery using it's transient
            else {
                jQuery.each(top.galleries, function(){
                    if (this.transient_id == id) retval = this;
                });
            }

            return retval;
        },

        configure_galleria: function() {
            // Adjust our default settings from the host and then load our theme
            if (this.id == '!') {
                window.lightbox_settings.enable_routing = 0;
                window.lightbox_settings.enable_comments = 0;
            }

            if (!window.lightbox_settings.enable_routing) {
                window.lightbox_settings.enable_comments = 0;
                window.lightbox_settings.enable_sharing = 0;
            }

            window.lightbox_settings.transition_speed = window.lightbox_settings.transition_speed * 1000;
            window.lightbox_settings.slideshow_speed  = window.lightbox_settings.slideshow_speed * 1000;

            Galleria.loadTheme(window.lightbox_settings.theme);
            Galleria.configure({
                dataSource: this.fetch_images()
            });
        },

        /**
         * Some displays come from inside an iframe and are only accessible to us via the triggers. For them we
         * can only fetch the images-to-load via an ajax request.
         *
         * @returns [images]
         */
        fetch_images: function() {
            // Firefox has an issue with later accessing this.images for unknown reasons; if this.images is cached
            // we use it but Firefox should cause a second fetch_images_from_parent() call on each startup.
            if (this.images.length != 0) {
                return this.images;
            }

            var transient_id = top.nplModalRouted.get_transient(this.id);
            var images = this.fetch_images_from_parent(transient_id);
            if (images.length <= 0) {
                images = this.fetch_images_from_parent(this.id);
            }

            if (images.length <= 0 && this.id != '!') {
                images = this.fetch_images_from_ajax();
            }

            this.images = images;
            return images;
        },

        fetch_images_from_ajax: function() {
            var images = [];

            $.ajax({
                async: false,
                url: window.lightbox_settings.load_images_url,
                dataType: 'json',
                success: function(data, status, jqXHR) {
                    $.each(data, function(ndx, newimage) {
                        images.push(newimage);
                    });
                }
            });

            this.ajax_image_fetch_completed = true;
            return images;
        },

        fetch_images_from_parent: function(id) {
            var self = this;
            var selector = top.nplModalRouted.selector;
            var images = [];

            top.jQuery(selector).each(function(index, element) {
                var anchor = $(this);

                if (anchor.hasClass('ngg-trigger')) {
                    return true; // exclude NextGEN trigger icons
                }

                if (id != '!' && id != anchor.data('nplmodal-gallery-id') && id != this.id) {
                    return true; // exclude images from other galleries
                }

                if (self.id == '!' && anchor.data('nplmodal-gallery-id')) {
                    return true; // when viewing non-nextgen images; exclude nextgen-images
                }

                var image = $(this).find('img').first();

                // when in doubt we id images by their href
                var gallery_image = {};
                gallery_image.image    = (anchor.data('fullsize') == undefined) ? anchor.attr('href') : anchor.data('fullsize');
                gallery_image.image_id = (anchor.data('image-id') == undefined) ? gallery_image.image : anchor.data('image-id');

                // optional attributes
                if (anchor.data('thumb') != undefined) gallery_image.thumb = anchor.data('thumb');
                else if (anchor.data('thumbnail') != 'undefined') gallery_image.thumb = anchor.data('thumbnail');

                if (anchor.data('title') != undefined) {
                    gallery_image.title = anchor.data('title');
                } else if (typeof image.attr('title') != 'undefined') {
                    gallery_image.title = image.attr('title');
                } else if (typeof anchor.siblings('.wp-caption-text').html() != 'undefined') {
                    gallery_image.title = anchor.siblings('.wp-caption-text').html();
                }

                if (anchor.data('description') != undefined) {
                    gallery_image.description = anchor.data('description');
                } else {
                    gallery_image.description = image.attr('alt');
                }

                images.push(gallery_image);
            });

            return images;
        }
    };
})(jQuery);
