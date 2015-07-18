<?php

/**
 * NextGEN Gallery 2.0.66 didn't have proper implementations of handling backup images
 */
class Mixin_Pro_Storage extends Mixin
{
    /**
     * Use the 'backup' image as the 'original' so that generated images use the backup image as their source
     *
     * @param $image
     * @param bool $check_existance
     *
     * @return mixed
     */
    function get_original_abspath($image, $check_existance=FALSE)
    {
        return $this->object->get_image_abspath($image, 'backup', $check_existance);
    }

    /**
     * Gets the absolute path where the image is stored
     * Can optionally return the path for a particular sized image
     */
    function get_image_abspath($image, $size='full', $check_existance=FALSE)
    {
        $retval = NULL;
        $fs = C_Fs::get_instance();

        // Ensure that we have a size
        if (!$size) {
            $size = 'full';
        }

        // If we have the id, get the actual image entity
        if (is_numeric($image)) {
            $image = $this->object->_image_mapper->find($image);
        }

        // Ensure we have the image entity - user could have passed in an
        // incorrect id
        if (is_object($image)) {
            if (($gallery_path = $this->object->get_gallery_abspath($image->galleryid))) {
                $folder = $prefix = $size;
                switch ($size) {

                    # Images are stored in the associated gallery folder
                    case 'full':
                    case 'original':
                    case 'image':
                        $retval = $fs->join_paths($gallery_path, $image->filename);
                        break;

                    case 'backup':
                        $retval = $fs->join_paths($gallery_path, $image->filename . '_backup');
                        if (!@file_exists($retval)) {
                            $retval = $fs->join_paths($gallery_path, $image->filename);
                        }
                        break;

                    case 'thumbnails':
                    case 'thumbnail':
                    case 'thumb':
                    case 'thumbs':
                        $size = 'thumbnail';
                        $folder = 'thumbs';
                        $prefix = 'thumbs';
                    // deliberately no break here

                    // We assume any other size of image is stored in the a
                    //subdirectory of the same name within the gallery folder
                    // gallery folder, but with the size appended to the filename
                    default:
                        $image_path = $fs->join_paths($gallery_path, $folder);

                        // NGG 2.0 stores relative filenames in the meta data of
                        // an image. It does this because it uses filenames
                        // that follow conventional WordPress naming scheme.
                        if (isset($image->meta_data) && isset($image->meta_data[$size]) && isset($image->meta_data[$size]['filename'])) {
                            $image_path = $fs->join_paths($image_path, $image->meta_data[$size]['filename']);
                        }

                        // NGG Legacy does not store relative filenames in the
                        // image entity for sizes other than the original.
                        // Although the naming scheme for filenames differs from
                        // WordPress conventions, NGG legacy does follow it's
                        // own naming schema consistently so we can guess the path
                        else {
                            $image_path = $fs->join_paths($image_path, "{$prefix}_{$image->filename}");
                        }

                        $retval = $image_path;
                        break;
                }
            }
        }

        // Check the existance of the file
        if ($retval && $check_existance) {
            if (!file_exists($retval)) $retval = NULL;
        }

        return $retval ? rtrim($retval, "/\\") : $retval;
    }

    /**
     * Backs up an image file
     * @param int|object $image
     */
    function backup_image($image)
    {
        $retval = FALSE;

        if (($image_path = $this->object->get_image_abspath($image))) {
            $retval = copy($image_path, $this->object->get_backup_abspath($image));

            // Store the dimensions of the image
            if (function_exists('getimagesize')) {
                if (!is_object($image)) $image = C_Image_Mapper::get_instance()->find($image);
                if ($image) {
                    $dimensions = getimagesize($retval);
                    $image->meta_data['backup'] = array(
                        'filename'  =>  basename($retval),
                        'width'     =>  $dimensions[0],
                        'height'    =>  $dimensions[1],
                        'generated' =>  microtime()
                    );
                }
            }
        }

        return $retval;
    }

    /**
     * Gets the absolute path of the backup of an original image
     * @param string $image
     */
    function get_backup_abspath($image)
    {
        return $this->object->get_image_abspath($image, 'backup');
    }

    function get_backup_dimensions($image)
    {
        return $this->object->get_image_dimensions($image, 'backup');
    }

    function get_backup_url($image)
    {
        return $this->object->get_image_url($image, 'backup');
    }
}

/**
 * Class Mixin_Pro_Ecomm_Storage
 *
 * NextGen Gallery's get_original_abspath() points to the fullsize image which we don't want
 */
class Mixin_Pro_Ecomm_Storage extends Mixin
{
    /**
     * Use the 'backup' image as the 'original' so that generated images use the backup image as their source
     *
     * @param $image
     * @param bool $check_existance
     *
     * @return mixed
     */
    function get_original_abspath($image, $check_existance = FALSE)
    {
        return $this->object->get_image_abspath($image, 'backup', $check_existance);
    }
}

class C_Digital_Downloads extends C_MVC_Controller
{
    static $instance = NULL;

    static function get_instance()
    {
        if (!self::$instance) {
            $klass = get_class();
            self::$instance = new $klass;
        }

        return self::$instance;
    }

    function get_i18n_strings($order)
    {
        $retval = new stdClass();
        $retval->image_header               = __('Image', 'nggallery');
        $retval->resolution_header          = __('Resolution', 'nggallery');
        $retval->item_description_header    = __('Item', 'nggallery');
        $retval->download_header            = __('Download Link', 'nggallery');
        $retval->order_info                 = sprintf(__('Digital Downloads for Order #%s', 'nggallery'), $order->ID);

        return $retval;
    }

    function index_action()
    {
        wp_enqueue_style('ngg-digital-downloads-page', $this->get_static_url('photocrati-nextgen_pro_ecommerce#digital_downloads_page.css'));
       $retval = __('Oops! This page usually displays details for image purchases, but you have not ordered any images yet. Please feel free to continue browsing. Thanks for visiting.', 'nggallery');

       if (($order = C_Order_Mapper::get_instance()->find_by_hash($this->param('order'), TRUE))) {

           // Display digital downloads for verified transactions
           if ($order->status == 'verified') {
               $retval = $this->render_download_list($order);
           }

           // Display "waiting for confirmation" message
           else {
               $retval = $this->render_partial('photocrati-nextgen_pro_ecommerce#waiting_for_confirmation', array(
                   'msg' => __("We haven't received payment confirmation yet. This may take a few minutes. Please wait...")
               ), TRUE);
           }
       }

       return $retval;
    }

    function get_gallery_storage()
    {
        $storage        = C_Gallery_Storage::get_instance();
        if (version_compare(NGG_PLUGIN_VERSION, '2.0.66.99') <= 0) {
            $storage->get_wrapped_instance()->add_mixin('Mixin_Pro_Storage');
        }
        else {
            $storage->get_wrapped_instance()->add_mixin('Mixin_Pro_Ecomm_Storage');
        }

        return $storage;
    }

    function render_download_list($order)
    {
        $cart = $order->get_cart()->to_array();
        $storage        = $this->get_gallery_storage();
        $images          = array();
        $settings = C_NextGen_Settings::get_instance();
        foreach ($cart['images'] as $image_obj) {
            foreach ($image_obj->items as $item) {

                $image = new stdClass();
                foreach (get_object_vars($image_obj) as $key => $val) $image->$key = $val;

                if ($item->source == NGG_PRO_DIGITAL_DOWNLOADS_SOURCE) {

                    $named_size = 'backup';

                    // Use the full resolution image
                    if ($item->resolution != 0) {
                        $dynthumbs  = C_Dynamic_Thumbnails_Manager::get_instance();
                        $params = array(
                            'width'     =>  $item->resolution,
                            'height'    =>  $item->resolution,
                            'crop'      =>  FALSE,
                            'watermark' =>  FALSE,
                            'quality'   =>  100
                        );
                        $named_size = $dynthumbs->get_size_name($params);

                        if (!$storage->get_image_abspath($image, $named_size, TRUE)) {
                            $storage->generate_image_size($image, $named_size);
                        }
                    }

                    if ($named_size == 'backup')
                    {
                        // in case the backup files are protected by server side rules we serve fullsize images from
                        // an ajax endpoint.
                        //
                        // we don't need to honor permalink styles as this is mostly hidden just determine the most
                        // reliable path to the photocrati_ajax controller
                        $url = $settings->get('ajax_url');
                        $pos = strpos($url, '?');
                        if ($pos === FALSE) {
                            $url .= '?';
                        } else {
                            $url .= '&';
                        }
                        $url .= 'action=get_image_file&order_id=' . $order->hash . '&image_id='  . $image_obj->{$image_obj->id_field};
                        $image->download_url = $url;
                    }
                    else {
                        $image->download_url = $storage->get_image_url($image, $named_size);
                    }

                    // Set other properties
                    $dimensions = $storage->get_image_dimensions($image, $named_size);
                    $image->dimensions          = $dimensions;
                    $image->resolution          = $dimensions['width'].'x'.$dimensions['height'];
                    $image->item_description    = $item->title;
                    $image->thumbnail_url       = $storage->get_thumbnail_url($image);

                    array_push($images, $image);
                }
            }
        }

        return $this->render_partial('photocrati-nextgen_pro_ecommerce#digital_downloads_list', array(
            'images' =>  $images,
            'order' =>  $order,
            'i18n'  =>  $this->get_i18n_strings($order)
        ), TRUE);
    }
}