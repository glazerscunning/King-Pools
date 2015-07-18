<?php
/*
{
    Module: photocrati-nextgen_pro_proofing
}
 */

define('NGG_PRO_PROOFING', 'photocrati-nextgen_pro_proofing');
define('NGG_PRO_PROOFING_TRIGGER', 'photocrati-proofing');

class M_NextGen_Pro_Proofing extends C_Base_Module
{
    function define($context=FALSE)
    {
        parent::define(
            NGG_PRO_PROOFING,
            'NextGEN Pro Proofing',
            'Provides rating capabilities',
            '0.5',
            'http://www.photocrati.com',
            'Photocrati Media',
            'http://www.photocrati.com',
            $context
        );

        include_once('class.nextgen_pro_proofing_installer.php');
        C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Pro_Proofing_Installer');
    }

    function initialize()
    {
        parent::initialize();

        if (!is_admin())
        {
            // adds lightbox JS overrides
            $lightbox_controller = C_NextGen_Pro_Lightbox_Controller::get_instance();
            $lightbox_controller->add_component('photocrati-nextgen_pro_proofing', 'C_NextGen_Pro_Proofing_Lightbox');

            $triggers = C_Displayed_Gallery_Trigger_Manager::get_instance();
            $triggers->add(NGG_PRO_PROOFING_TRIGGER, 'C_NextGen_Pro_Proofing_Trigger');

            C_NextGen_Shortcode_Manager::add('ngg_pro_proofing', array(&$this, 'render_proofed_images'));
        }
    }

    function _register_adapters()
    {
        $this->get_registry()->add_adapter('I_Component_Factory', 'A_NextGen_Pro_Proofing_Factory');

        if (M_Attach_To_Post::is_atp_url() || is_admin())
        {
            // add additional proofing options to these display types' settings
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Form', NGG_BASIC_THUMBNAILS);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Form', NGG_PRO_THUMBNAIL_GRID);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Form', NGG_PRO_BLOG_GALLERY);
            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Form', NGG_PRO_FILM);

            $this->get_registry()->add_adapter('I_Form', 'A_NextGen_Pro_Proofing_Settings_Form', 'ngg-proofing');
        }

        if (!is_admin())
        {
            $this->get_registry()->add_adapter('I_Display_Type_Controller', 'A_NextGen_Pro_Proofing_Trigger_Resources');
            $this->get_registry()->add_adapter('I_Ajax_Controller', 'A_NextGen_Pro_Proofing_Ajax');
            $this->get_registry()->add_adapter('I_MVC_View', 'A_NextGen_Pro_Proofing_Trigger_Element');
        }
    }

    function _register_utilities()
    {
    }

    function _register_hooks()
    {
        add_action('init', array($this, 'wp_init'));
        add_action('admin_init', array(&$this, 'register_forms'));
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_filter('the_posts', array($this, 'serve_proofing_page'));

        if (!empty($_GET['post_type']) && $_GET['post_type'] == 'nextgen_proof')
            add_action('admin_head', array($this, 'hide_add_new_button'));

        // add additional columns to display the customer name
        if (M_Attach_To_Post::is_atp_url() || is_admin()
        &&  strpos($_SERVER['SCRIPT_NAME'], '/wp-admin/edit.php') !== FALSE
        &&  isset($_REQUEST['post_type'])
        &&  $_REQUEST['post_type'] == 'nextgen_proof')
        {
            add_filter('manage_nextgen_proof_posts_columns', array(&$this, 'proofing_columns'));
            add_action('manage_nextgen_proof_posts_custom_column', array(&$this, 'output_proofing_column'), 10, 2);
            add_filter('manage_edit-nextgen_proof_sortable_columns', array(&$this, 'proofing_columns'));
        }
    }

    function proofing_columns($columns)
    {
        // move the date column
        $tmp = $columns['date'];
        unset($columns['date']);
        $columns['proofing_customer'] = __('Customer', 'nggallery');
        $columns['date'] = $tmp;
        return $columns;
    }

    function output_proofing_column($column_name, $post_id)
    {
        global $post;
        $mapper = C_NextGen_Pro_Proofing_Mapper::get_instance();
        $entity = $mapper->unserialize($post->post_content);
        switch ($column_name) {
            case 'proofing_customer':
                echo esc_html($entity['customer_name']);
                break;
        }
    }

    /**
     * Shortcode handler for [ngg_pr_proofing]
     *
     * @return mixed
     */
    function render_proofed_images()
    {
        $controller = C_NextGen_Pro_Proofing_Controller::get_instance();
        return $controller->index_action();
    }

    /**
     * Applying capabilities => [create_posts=>FALSE] doesn't seem to always remove the 'add new' button
     * so some extra CSS is added to be certain.
     */
    function hide_add_new_button()
    {
        echo '<style type="text/css">.add-new-h2 { display:none; }</style>';
    }

    /**
     * Registers the settings form
     */
    function register_forms()
    {
        $forms = C_Form_Manager::get_instance();
        $forms->add_form(NGG_PRO_ECOMMERCE_OPTIONS_PAGE, 'ngg-proofing');
    }

    function wp_init()
    {
        $labels = array(
            'name'               => __('Proofs',        'nggallery'),
            'singular_name'      => __('Proof',         'nggallery'),
            'add_new_item'       => __('Add New Proof', 'nggallery'),
            'edit_item'          => __('Edit Proof',    'nggallery'),
            'new_item'           => __('New Proof',     'nggallery'),
            'view_item'          => __('View Proof',    'nggallery'),
            'search_items'       => __('Search Proof',  'nggallery'),
            'not_found'          => __('Nothing found', 'nggallery'),
            'not_found_in_trash' => __('Nothing found in Trash', 'nggallery')
        );

        register_post_type(
            'nextgen_proof',
            array(
                'labels'       => $labels,
                'public'       => FALSE,
                'has_archive'  => FALSE,
                'hierarchical' => FALSE,
                'show_ui'      => TRUE,
                'supports'     => array('title'),
                'show_in_menu' =>  FALSE,
                'map_meta_cap' =>  TRUE,
                'capabilities' =>  array(
                    'create_posts' => FALSE,
                    'edit_post'    => 'edit_post',
                    'edit_posts'   => 'edit_posts'
                )
            )
        );
    }

    /**
     * Registers our "Edit Proof" content box
     */
    function add_meta_box()
    {
        add_meta_box(
            'nextgen_proof_metabox',
            __('Proofed Images', 'nggallery'),
            array($this, 'nextgen_proof_metabox'),
            'nextgen_proof',
            'normal'
        );
    }

    /**
     * Renders the "Edit Proof" main content area
     *
     * @param null $post
     */
    function nextgen_proof_metabox($post = null)
    {
        if ($post != null)
        {
            $settings = C_NextGen_Settings::get_instance();
            $image_mapper = C_Image_Mapper::get_instance();
            $values = $image_mapper->unserialize($post->post_content);
            $proofed_gallery = $values['proofed_gallery'];
            $image_list = isset($proofed_gallery['image_list']) ? $proofed_gallery['image_list'] : null;

            $confirmation_param = array('proof' => $values['hash']);
            if (!empty($settings->proofing_page_confirmation))
            {
                $confirmation_url = self::get_page_url($settings->proofing_page_confirmation, $confirmation_param);
            }
            else {
                $confirmation_url = self::add_to_querystring(site_url('/?ngg_pro_proofing_page=1'), $confirmation_param);
            }

            if ($image_list != null)
            {
                $storage = C_Gallery_Storage::get_instance();

                echo '<h4>';
                echo '<a target="_blank" href="' . $confirmation_url . '">' . __('User confirmation', 'nggallery') . '</a>';
                echo ' | ';
                echo '<a target="_blank" href="' . $values['referer'] . '">' . __('Gallery source url', 'nggallery') . '</a>';
                echo '</h4>';
                echo '<table style="width: 98%;">';
                echo '<tr><th style="width: 150px; text-align:left;">Thumbnail</th><th style="text-align:left;">Title</th><th style="width: 40%; text-align:left;">Filename</th></tr>';

                foreach ($image_list as $image_id) {
                    $image = $image_mapper->find($image_id);
                    if (!$image) continue;

                    echo '<tr>';
                    echo '<td>' . $storage->get_image_html($image, 'thumb') . '</td>';
                    echo '<td>' . esc_html($image->alttext) . '</td>';
                    echo '<td>' . esc_html($image->filename) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            }
        }
    }

    /**
     * Provides the proofing confirmation page for sites that haven't yet created one
     *
     * @param $posts
     * @return array
     */
    function serve_proofing_page($posts)
    {
        if (isset($_REQUEST['ngg_pro_proofing_page'])) {
            $post = new stdClass;
            $post->name = 'ngg_pro_proofing_page';
            $post->post_title = __('Proofed Images', 'nggallery');
            $post->post_parent = 0;
            $post->post_content = "[ngg_pro_proofing]";
            $post->post_type = 'page';
            $posts = array($post);
        }
        remove_filter('the_posts', array(&$this, 'serve_proofing_page'));
        return $posts;
    }

    /**
     * Stolen from class.nextgen_pro_checkout.php to avoid dependency
     *
     * @TODO Move and the ecommerce functions somewhere reusable
     * @param $page_id
     * @param array $params
     *
     * @return string
     */
    static function get_page_url($page_id, $params = array())
    {
        $link = get_page_link($page_id);
        if ($params)
            $link = self::_add_to_querystring($link, $params);
        return $link;
    }

    /**
     * Stolen from class.nextgen_pro_checkout.php to avoid dependency
     *
     * @TODO Move and the ecommerce functions somewhere reusable
     * @param $url
     * @param array $params
     *
     * @return string
     */
    static function _add_to_querystring($url, $params = array())
    {
        if ($params)
        {
            $qs = array();
            foreach ($params as $key => $value) {
                $qs[] = urlencode($key) .'='. urlencode($value);
            }
            $url .= ((strpos($url, '?') === FALSE ? '?' : '&')) . implode('&', $qs);
        }
        return $url;
    }

    function get_type_list()
    {
        return array(
            'C_NextGen_Pro_Proofing_Controller'        => 'class.nextgen_pro_proofing_controller.php',
            'C_NextGen_Pro_Proofing'                   => 'class.nextgen_pro_proofing.php',
            'A_NextGen_Pro_Proofing_Factory'           => 'adapter.nextgen_pro_proofing_factory.php',
            'C_NextGen_Pro_Proofing_Mapper'            => 'class.nextgen_pro_proofing_mapper.php',
            'C_NextGen_Pro_Proofing_Lightbox'          => 'class.nextgen_pro_proofing_lightbox.php',
            'A_NextGen_Pro_Proofing_Trigger_Resources' => 'adapter.nextgen_pro_proofing_trigger_resources.php',
            'A_NextGen_Pro_Proofing_Trigger_Element'   => 'adapter.nextgen_pro_proofing_trigger_element.php',
            'A_NextGen_Pro_Proofing_Settings_Form'     => 'adapter.nextgen_pro_proofing_settings_form.php',
            'A_NextGen_Pro_Proofing_Ajax'              => 'adapter.nextgen_pro_proofing_ajax.php',
            'A_NextGen_Pro_Proofing_Form'              => 'adapter.nextgen_pro_proofing_form.php',
            'C_NextGen_Pro_Proofing_Trigger'           => 'class.nextgen_pro_proofing_trigger.php',
            'M_Nextgen_Pro_Proofing'                   => 'module.nextgen_pro_proofing.php'
        );
    }
}

new M_NextGen_Pro_Proofing;
