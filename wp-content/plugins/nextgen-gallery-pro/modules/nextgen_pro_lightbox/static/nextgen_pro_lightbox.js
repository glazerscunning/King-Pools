(function($) {
    var is_open  = false;
    var pre_open_callbacks = [];
    var options  = {};
    var defaults = {
        overlay_opacity: 1,   // background opacity setting when active
        padding: 0,           // pixels, in equal amounts from each border
        speed: 'slow',        // see jQuery docs for setting
        timeout: 60,          // measured in seconds
        timeout_message: 'Waited too long', // error to display after the timeout setting
        error_message: 'Error loading',
        error_timeout: 3      // how long to display errors before closing
    };

    // used to track connection errors
    var iframe_loading = false;

    // active elements offset is stored in this so we can restore the browser scroll position on closing
    var scroll_top = 0;

    var methods = {
        init: function(parameters) {
            // We create two overlays because iOS has horrible position:fixed support. The first overlay is absolutely
            // positioned at the top of the page; the second uses a fixed position for a cleaner experience without iOS
            var overlay = $("<div id='npl_overlay'></div>");
            var overlay2 = $("<div id='npl_overlay2'></div>");
            var wrapper = $("<div id='npl_wrapper'></div>");
            var spinner = $("<div id='npl_spinner_container' class='npl-loading-spinner'><i id='npl_spinner' class='fa fa-spin fa-spinner hidden'></i></div>");
            var message = $("<div id='npl_message_container' class='hidden'><p id='npl_message'></p></div>");
            var btn_close = $("<div id='npl_button_close' class='hidden'>[ x ]</div>");
            var content = $("<iframe src='' id='npl_content' frameborder='0' marginheight='0' marginwidth='0' scrolling='no' allowfullscreen='yes' webkitallowfullscreen='yes' mozallowfullscreen='yes'></iframe>");

            overlay.css({background: window.nplModalSettings.background_color});
            overlay2.css({background: window.nplModalSettings.background_color});
            spinner.css({color: window.nplModalSettings.icon_color});

            $('body').append(overlay);
            $('body').append(overlay2);
            $('body').append(wrapper);
            wrapper.append(spinner);
            wrapper.append(message);
            wrapper.append(btn_close);
            wrapper.append(content);

            methods.configure(parameters);
            methods.resize_modal();
            methods.bind_images();
            methods.set_events();
            methods.ios.init();

            return this.each(function() {
            });
        },

        configure: function(parameters) {
            options = $.extend(defaults, parameters);
        },

        run_pre_open_lightbox_callbacks: function(link, params){
            for (var i=0; i<pre_open_callbacks.length; i++) {
                var callback = pre_open_callbacks[i];
                params = callback(link, params);
            }
            return params;
        },

        add_pre_open_callback: function(callback){
            pre_open_callbacks.push(callback);
        },

        bind_images: function() {
            // in order to handle ajax-pagination events this method is called every time the 'refreshed' signal
            // is emitted. For Galleria to process images we store the selector in nplModalRouted
            var selector = nextgen_lightbox_filter_selector($, $(".nextgen_pro_lightbox"));

            // Modify the selector to exclude any Photocrati Lightboxes
            var new_selector = [];
            for (var index=0; index < selector.length; index++) {
                var el = selector[index];
                if (!$(el).hasClass('photocrati_lightbox_always') && !$(el).hasClass('decoy')) {
                    new_selector.push(el);
                }
            }

            window.nplModalRouted.selector = selector = $(new_selector);

            selector.on('click', function (event) {
                // pass these by
                if ($.inArray($(this).attr('target'), ['_blank', '_parent', '_top']) > -1) {
                    return;
                }

                // NextGEN Basic Thumbnails has an option to link to an imagebrowser display; this disables the effect
                // code (we have no gallery-id) but we may be asked to open it anyway if lightboxes are set to apply
                // to all images. Check for and do nothing in that scenario:
                if ($(this).data('src')
                &&  $(this).data('src').indexOf(nplModalSettings.router_slug + '/image') != -1
                &&  !$(this).data('nplmodal-gallery-id')) {
                    return;
                }

                event.stopPropagation();
                event.preventDefault();

                if (event.handled !== true) {
                    event.handled = true;

                    // cache the current scroll position
                    scroll_top = $(document).scrollTop();

                    // Define parameters for opening the Pro Lightbox
                    var params = {
                        show_sidebar: '',
                        gallery_id: '!',
                        gallery_transient: '!',
                        image_id: '!',
                        slug: null,
                        revert_image_id: '!',
                        open_the_lightbox: true
                    };

                    // Determine if we should show the comment sidebar
                    if ($(this).data('nplmodal-show-comments')) params.show_sidebar = '/comments';

                    // Determine the gallery id
                    if ($(this).data('nplmodal-gallery-id')) params.gallery_id = $(this).data('nplmodal-gallery-id');

                    // Determine the transient id
                    params.gallery_transient = window.nplModalRouted.get_transient(params.gallery_id);

                    // Determine the image id
                    if ($(this).data('nplmodal-image-id'))      params.image_id = $(this).data('nplmodal-image-id');
                    else if ($(this).data('image-id'))          params.image_id = $(this).data('image-id');
                    else if (params.gallery_transient == '!')   params.image_id = $(this).attr('href');

                    // Determine the slug
                    if (params.gallery_transient != '!') {
                        params.slug = window.nplModalRouted.get_slug(params.gallery_transient);
                        if (params.slug != 'undefined' && params.slug != null) {
                            if (params.slug.toString().indexOf('widget-ngg-images-') !== -1) {
                                params.revert_image_id = params.image_id;
                                params.image_id = '!';
                            }
                        }
                    }

                    if (jQuery.browser.mobile) {
                        methods.enter_fullscreen();
                    }

                    // Run any registered callbacks for modifying lightbox params
                    params = methods.run_pre_open_lightbox_callbacks(this, params);

                    // Are we to still open the lightbox?
                    if (params.open_the_lightbox) {

                        // open the pro-lightbox manually
                        if (params.gallery_transient == '!' || window.nplModalSettings.enable_routing == '0') {
                            // set this so we can tell Galleria which image to display first
                            window.nplModalRouted.image_id = params.image_id;
                            methods.open_modal(window.nplModalSettings.gallery_url.replace('{gallery_id}', params.gallery_transient));
                        } else {
                            // open the pro-lightbox through our backbone.js router
                            window.nplModalRouted.front_page_pushstate(params.gallery_transient, params.image_id);
                            params.gallery_id = window.nplModalRouted.get_id_from_transient(params.gallery_transient);
                            window.nplModalRouted.navigate(
                                window.nplModalRouted.router_slug + '/' + params.slug + '/' + params.image_id + params.show_sidebar,
                                {trigger: true, replace: false}
                            );
                            // some displays (random widgets) may need to disable routing but still pass
                            // an image-id to display on startup
                            if (params.revert_image_id != '!') {
                                window.nplModalRouted.image_id = params.revert_image_id;
                            }
                        }
                    }
                }
            });
        },

        // establishes bindings of events to actions
        set_events: function() {
            $(window).on('refreshed', methods.bind_images);

            $(document).ready(function() {
                // Provide a hook for third-parties to add their own methods
                $(window).trigger('override_nplModal_methods', methods);
            });

            // some display types (pro-slideshow for example) require their trigger buttons image-id
            // attribute to be updated as they display. also because our galleries may be in an iframe
            $('body').on('nplmodal.update_image_id', function(event, entities, image_id) {
                entities.each(function() {
                    $(this).data('nplmodal-image-id', image_id);
                });
            });

            // keep the display "responsive" by adjusting its dimensions when the browser resizes
            $(window).on('resize orientationchange fullscreenchange mozfullscreenchange webkitfullscreenchange', function (event) {
                if (methods.is_open()) {
                    window.scrollTo(0,0);
                    methods.resize_modal();
                }
            });

            // open_modal() only starts the process, content is revealed by iframe_loaded when the request is finished
            $('#npl_content').on('load', function (event) {
                methods.iframe_loaded();
            });

            // we really want to prevent scrolling
            $(window).bind('mousewheel DOMMouseScroll touchmove', methods.prevent_scrolling);
            $(window).bind('keydown', methods.handle_keyboard_input);
            $(document).bind('touchmove', methods.prevent_scrolling);

            // handle exit clicks/touch events
            $('#npl_overlay, #npl_overlay2, #npl_button_close').on('touchstart click', function(event) {
                event.stopPropagation();
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    methods.close_modal();
                }
            });
        },

        // displays #npl_content once it has loaded; this also hides the loading spinner
        iframe_loaded: function() {
            // Firefox and IE will fire this event on the first page load; even when src=''
            if ($('#npl_content').attr('src') == '') {
                return;
            }

            // disable our watchdog timer so it doesn't kill anything
            iframe_loading = false;

            // shutdown if #galleria doesn't exist -- the server or net connection have failed us
            if ($('#npl_content').contents().find('#galleria').length == 0) {
                methods.error(options.error_message);
                return;
            }

            methods.resize_modal();
            $('#npl_spinner, #npl_button_close').addClass('hidden');
            $('#npl_content').velocity({opacity: 1}, {duration: options.speed});

            if (window.nplModalRouted) {
                // requires tabindex be set on the #galleria element; Galleria won't have key support without this
                $('#npl_content').contents().find('#galleria').focus()
            }
        },

        // ran at options.timeout seconds after the iframe src value is set
        check_iframe_loaded: function() {
            if (iframe_loading == true) {
                methods.error(options.timeout_message);
            }
        },

        // keeps the modal window exactly in the center of the browser window
        resize_modal: function() {
            var height = $(window).height();
            var width = $(window).width();
            $('#npl_wrapper').css({
                'width': width - (options.padding * 4) + 'px',
                'height': height + 'px',
                'top': (options.padding * 2),
                'left': (options.padding * 2)
            });
        },

        // opens the overlay and wrapper; sets the iframe src to the url provided
        open_modal: function(url) {
            is_open = true;

            // disables browser scrollbar display
            $('html, body').toggleClass('nextgen_pro_lightbox_open');

            // immediately show the overlay; if done after scrolling the page will appear to flash
            $('#npl_overlay, #npl_overlay2')
                .css({display: 'block', opacity: 0})
                .velocity(
                {opacity: 1},
                {duration: options.overlay_opacity,
                    complete: function () {
                        window.scrollTo(0, 0);
                        methods.ios.open();
                    }
                });


            $('#npl_wrapper')
                .css({display: 'block', opacity: 0})
                .velocity({opacity: 1}, {duration: options.speed});

            $('#npl_spinner, #npl_button_close').removeClass('hidden');

            $('#npl_content').attr('src', url);

            iframe_loading = true;
            setTimeout(methods.check_iframe_loaded, (options.timeout * 1000));
        },

        error: function(msg) {
            var p = $('#npl_message');
            p.text(msg);

            // we've ceased loading and will be closing soon anyway
            $('#npl_spinner, #npl_button_close').addClass('hidden');
            $('#npl_content')
                .velocity({opacity: 0}, {duration: options.speed})
                .attr('src', '');

            // the reveal!
            $('#npl_message_container').removeClass('hidden');

            // leave the warning up for options.warning_timeout seconds
            setTimeout(function() {
                $('#npl_message_container').addClass('hidden');
                methods.close_modal();
                p.text('');
            }, (options.error_timeout * 1000));
        },

        // handles iOS specific hacks:
        // * When rotaning from portrait to landscape safari increases the user zoom level beyond the default.
        //   To handle this we update the viewport setting to disable zooming when open_modal is run; and restore
        //   it to the original value when calling close_modal()
        ios: {
            meta: null,
            original: null, // original viewport setting; it's restored at closing
            // these functions should only apply to iOS
            is_ios: function() {
                return (/(iPhone|iPad|iPod)/.test(navigator.userAgent));
            },
            init: function() {
                var doc = window.document;
                if (!doc.querySelector) { return; } // this isn't available on pre 3.2 safari
                this.meta     = doc.querySelector("meta[name=viewport]");
                this.original = this.meta && this.meta.getAttribute("content");
            },
            open: function() {
                if (!this.is_ios()) { return; }
                if (this.meta) {
                    this.meta.setAttribute("content", this.original + ', width=device-width, height=device-height, minimum-scale=1, maximum-scale=1, user-scalable=no');
                }
                methods.resize_modal();
            },
            close: function() {
                if (!this.is_ios()) { return; }
                if (this.meta) {
                    this.meta.setAttribute("content", this.original);
                }
            }
        },

        // hide our content and close up
        close_modal: function() {
            methods.exit_fullscreen();

            // for use with Galleria it is important that npl_content never have display:none set
            $("#npl_wrapper, #npL_content, #npl_overlay, #npl_overlay2").velocity({opacity: 0}, {duration: options.speed});
            $('#npl_spinner, #npl_button_close').addClass('hidden');
            $("#npl_wrapper, #npl_overlay, #npl_overlay2").css({display: 'none'});

            // enables displaying browser scrollbars
            $('html, body').toggleClass('nextgen_pro_lightbox_open');

            methods.ios.close();

            // reset our modified url to our original state
            if (window.nplModalRouted && window.nplModalSettings.enable_routing != '0') {
                window.nplModalRouted.navigate('', {trigger: false, replace: true});

                // We can't always return to the initial url as Chrome on Android has issues
                if (nplModalSettings.is_front_page == "1" && history.pushState) {
                    history.pushState({}, document.title, window.nplModalRouted.initial_url);
                }
            }

            $(document).scrollTop(scroll_top);

            is_open = false;

            // Opera <= 12 (17 is fine) will end execution here, so this statement must come last
            $('#npl_content').attr('src', '');
        },

        // make a request to enter fullscreen mode.
        //
        // NOTE: this can only be done in response to a user action; just calling enter_fullscreen() programatically
        // will not work. Firefox & IE will produce errors, but Chrome (presently, 2013-04) silently fails
        enter_fullscreen: function() {
            // do not use a jquery selector, it will not work
            element = document.getElementById('npl_wrapper');

            if (element.requestFullScreen) {
                element.requestFullScreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullScreen) {
                element.webkitRequestFullScreen();
            }
        },

        exit_fullscreen: function() {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
        },

        toggle_fullscreen: function() {
            if (document.fullScreen || document.mozfullScreen || document.webkitIsFullScreen) {
                methods.exit_fullscreen();
            } else {
                methods.enter_fullscreen();
            }
        },

        // prevent the mouse wheel from sending events to the parent page
        prevent_scrolling: function(event) {
            if (methods.is_open()) {
                event.preventDefault();
            }
        },

        // try to prevent the user from scrolling in the parent page
        handle_keyboard_input: function(event) {
            if (methods.is_open()) {
                // escape key closes the modal
                if (event.which == 27) {
                    methods.close_modal();
                }
            }
        },

        // self-descriptory
        is_open: function() {
            return is_open;
        }
    };

    $.fn.nplModal = function(method) {
        if (!nplModalSettings) {
            $.error('nplModalSettings variable is undefined or null');
        }

        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.nplModal');
        }
    };
})(jQuery);

jQuery(document).ready(function($) {

    var nplModalRoutes = Backbone.Router.extend({
        initialize: function() {
            var self = this;

            // the url is restored to this location when the lightbox closes
            this.initial_url = window.location.toString().split('#')[0];

            // cach for galleria to inspect
            this.gallery_id  = null;
            this.image_id    = null;
            this.sidebar     = null;
            this.selector    = null;

            // for client windows to reference
            this.ajax_url = photocrati_ajax.url;

            // users can configure the slug on which the backbone-router takes effect
            this.router_slug = nplModalSettings.router_slug;
            this.route(this.router_slug + "/:gallery_id/:image_id", "gallery_and_image");
            this.route(this.router_slug + "/:gallery_id/:image_id/:sidebar", "gallery_and_image");
            this.route(this.router_slug, 'close_modal');
            this.route('', 'close_modal');

            // Galleria theme will listen for this event to determine fullscreen state changes
            $(window).on('fullscreenchange mozfullscreenchange webkitfullscreenchange', function (event) {
                self.trigger('nplModalRouted_fullscreen_change');
            });
        },

        // to prevent slug conflicts inject the wordpress url prefix when we're dealing with the wordpress front-page
        front_page_pushstate: function(transient_id, image_id) {
            if (nplModalSettings.is_front_page != "1" || transient_id == undefined) { return false; }
            if ('undefined' == typeof window.galleries) { return false; }

            var url  = '';
            var slug = transient_id;

            $.each(galleries, function(index, gallery) {
                if (gallery.transient_id == transient_id) {
                    url = gallery.wordpress_page_root;
                    if (gallery.slug) {
                        slug = gallery.slug;
                    }
                }
            });
            url += '#' + Backbone.history.getFragment(this.router_slug + '/' + slug + '/' + image_id);

            // redirect those browsers that don't support history.pushState
            if (history.pushState) {
                history.pushState({}, document.title, url);
                return true;
            } else {
                window.location = url;
                return false;
            }
        },

        // returns the slug string by inspecting galleries by their transient id and gallery ID
        get_slug: function (transient_id) {
            var slug = transient_id;
            if ('undefined' == typeof window.galleries) { return slug; }

            $.each(galleries, function(index, gallery) {
                if (gallery.slug && gallery.transient_id == transient_id) {
                    // a slug was provided, so use it first
                    slug = gallery.slug;
                } else if (!gallery.slug && gallery.ID != gallery.transient_id && gallery.transient_id == transient_id) {
                    // attempt to look for an ATP ID before falling back to the transient-id
                    slug = gallery.ID;
                }
            });

            return slug;
        },

        get_id_from_transient: function (transient_id) {
            var id = transient_id;
            if ('undefined' == typeof window.galleries) { return id; }

            $.each(galleries, function(index, gallery) {
                if (gallery.transient_id == transient_id) {
                    id = gallery.ID;
                }
            });
            return id;
        },

        // returns the transient id by inspecting galleries by their slug and gallery ID
        get_transient: function (slug) {
            var id = slug;
            if ('undefined' == typeof window.galleries) { return id; }

            $.each(galleries, function(index, gallery) {
                if (gallery.slug && gallery.slug == slug) {
                    id = gallery.transient_id;
                } else if (gallery.ID == slug) {
                    id = gallery.transient_id;
                }
            });

            return id;
        },

        toggle_fullscreen: function() {
            if ($(document).nplModal('is_open')) {
                $(document).nplModal('toggle_fullscreen');
            }
        },

        close_modal: function() {
            if ($(document).nplModal('is_open')) {
                // backbone's .on() registers callbacks rather than allowing proper event listening
                // so we must unbind what has been registered, lest the same callback be registered
                // multiple times for the same event
                this.unbind();

                $(document).nplModal('close_modal');
            }
        },

        is_open: function() {
            return ($(document).nplModal('is_open'));
        },

        gallery_and_image: function(slug, image_id, sidebar) {
            // determine the ID from our slug. if nothing comes back, assume we're already looking at the ID
            var transient_id = this.get_transient(slug);
            if (!transient_id) {
                transient_id = slug;
            }

            if (sidebar == '1') {
                sidebar = 'comments';
            }

            // the galleria theme handles url updates between image ids, so if the modal window is already open
            // and is already looking at the same gallery we don't need to do anything here
            if ($(document).nplModal('is_open') && slug == this.gallery_id) {
                this.image_id = image_id;
                this.sidebar  = sidebar;
                return;
            }

            if ($(document).nplModal('is_open')) {
                $(document).nplModal('close_modal');
            }

            // cache these; Galleria will read them to determine which image to load first
            this.gallery_id = slug;
            this.image_id = image_id;
            this.sidebar = sidebar;

            // determine the url to the lightbox endpoint
            var url = nplModalSettings.gallery_url.replace('{gallery_id}', transient_id);

            $(document).nplModal('open_modal', url);
        }
    });

    if (window.top == window.self) {
        var nplModalOptions = {};
        window.nplModalRouted = new nplModalRoutes();
        $(document).nplModal(nplModalOptions);
        Backbone.history.start({
            pushState: false
        });
    }
});
