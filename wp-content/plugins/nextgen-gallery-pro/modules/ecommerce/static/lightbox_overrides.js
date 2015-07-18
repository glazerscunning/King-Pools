jQuery(function($){
    $('#galleria').on('npl.ready', function(e, data){
        var methods = data.methods;
        var self = data.galleria_theme;
        methods.sidebars.cart = {
            init: function() {},
            render: function(id) {
                // impose overlay
                self.$('sidebar-overlay').velocity({opacity: 1});
                self.$('sidebar-container').velocity({opacity: 0});
                self.$('sidebar-container').empty();
                var app = new Ngg_Pro_Cart.Views.Add_To_Cart({
                    image_id: id,
                    container: self.$('sidebar-container')
                });
                app.on('rendered', this.show_licensing_terms);
                app.on('ready', function(){
                    app.render();
                });
            },

            show_licensing_terms: function(){
                var request = {
                    action: 'get_digital_download_settings',
                    image_id: this.image_id
                };
                var header = this.$el.find('#ngg_digital_downloads_header');
                $.post(parent.photocrati_ajax.url, request, function(response){
                    if (typeof(response) != 'object') response = JSON.parse(response);
                    header.html(response.header);
                    self.$('sidebar-overlay').velocity({opacity: 0});
                    self.$('sidebar-container').velocity({opacity: 1});
                });
            },

            get_type: function() {
                return 'cart';
            },
            events: {
                bind: function() {
                    self.bind('npl.init', this.npl_init);
                    self.bind('npl.init.keys', this.npl_init_keys);
                    self.bind('image', this.image);
                },
                _image_ran_once: false,
                    image: function() {
                    if (methods.sidebars.cart.events.is_ecommerce_enabled()) {
                        if (!methods.sidebars.cart._image_ran_once) {
                            // possibly display the cart sidebar at startup
                            // display_comments may attempt to load at the same time--skip if it is on
                            if ((top.nplModalRouted.sidebar && top.nplModalRouted.sidebar == methods.sidebars.cart.get_type())
                            ||  (Galleria.get_npl_setting('display_cart', false) && !Galleria.get_npl_setting('display_comments', false))) {
                                methods.sidebar.open(methods.sidebars.cart.get_type());
                            }
                        } else if (top.nplModalRouted.sidebar && top.nplModalRouted.sidebar == methods.sidebars.cart.get_type()) {
                            // updates the sidebar
                            methods.sidebar.render(methods.sidebars.cart.get_type());
                        }
                        methods.sidebars.cart._image_ran_once = true;
                    }
                },

                is_ecommerce_enabled: function(){
                    var retval = false;

                    if (typeof(Galleria_Instance.displayed_gallery) != 'undefined') {
                        if (typeof(Galleria_Instance.displayed_gallery.display_settings['is_ecommerce_enabled']) != 'undefined') {
                            retval = Galleria_Instance.displayed_gallery.display_settings['is_ecommerce_enabled'];
                        }
                        if (typeof(Galleria_Instance.displayed_gallery.display_settings['original_settings']) != 'undefined') {
                            if (typeof(Galleria_Instance.displayed_gallery.display_settings['original_settings']['is_ecommerce_enabled']) != 'undefined') {
                                retval = Galleria_Instance.displayed_gallery.display_settings['original_settings']['is_ecommerce_enabled'];
                            }
                        }
                    }

                    return parseInt(retval);
                },

                npl_init: function() {
                    var is_ecommerce_enabled = methods.sidebars.cart.events.is_ecommerce_enabled;
                    if (is_ecommerce_enabled()) {
                        // Add cart toolbar button
                        var cart_button = $('<i/>')
                            .addClass('nextgen-cart fa fa-shopping-cart')
                            .attr({'title': 'Toggle cart sidebar'})
                            .click(function(event) {
                                methods.sidebar.toggle(methods.sidebars.cart.get_type());
                                event.preventDefault();
                            });
                        methods.thumbnails.register_button(cart_button);
                    }
                },
                npl_init_keys: function(event) {
                    var input_types = methods.galleria.get_keybinding_exclude_list();
                    self.attachKeyboard({
                        // 'e' for shopping cart
                        69: function() {
                            var is_ecommerce_enabled = methods.sidebars.cart.events.is_ecommerce_enabled;
                            if (!$(document.activeElement).is(input_types) && is_ecommerce_enabled()) {
                                methods.sidebar.toggle(methods.sidebars.cart.get_type());
                            }
                        }
                    });
                }
            }
        };

        methods.sidebars.cart.events.bind();
    });
});