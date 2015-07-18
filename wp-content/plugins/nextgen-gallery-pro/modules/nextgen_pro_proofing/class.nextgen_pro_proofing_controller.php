<?php

class C_NextGen_Pro_Proofing_Controller extends C_MVC_Controller
{
    static $instance = NULL;

    static function get_instance()
    {
        if (!self::$instance)
        {
            $klass = get_class();
            self::$instance = new $klass;
        }
        return self::$instance;
    }

    function enqueue_static_resources()
    {
	    wp_enqueue_style('nextgen_pro_proofing-confirmation', $this->get_static_url('photocrati-nextgen_pro_proofing#confirmation.css'));
    }

    function index_action()
    {
        $this->enqueue_static_resources();
        $settings = C_NextGen_Settings::get_instance();

        $retval = $settings->proofing_user_confirmation_not_found;

        if (($proof = C_NextGen_Pro_Proofing_Mapper::get_instance()->find_by_hash($this->param('proof'), TRUE)))
        {
            $image_mapper = C_Image_Mapper::get_instance();
            $images = array();
            foreach ($proof->proofed_gallery['image_list'] as $image_id) {
                $images[] = $image_mapper->find($image_id);
            }

            $message = $settings->proofing_user_confirmation_template;
            if (preg_match_all('/%%(\\w+)%%/', $settings->proofing_user_confirmation_template, $matches, PREG_SET_ORDER) > 0)
            {
                foreach ($matches as $match) {
                    switch ($match[1]) {
                        case 'user_name':
                            $message = str_replace('%%user_name%%', $proof->customer_name, $message);
                            break;
                        case 'user_email':
                            $message = str_replace('%%user_email%%', $proof->email, $message);
                            break;
                        case 'proof_link':
                            $message = str_replace('%%proof_link%%', $proof->referer, $message);
                            break;
                        case 'proof_details':
                            $imagehtml = $this->object->render_partial(
                                'photocrati-nextgen_pro_proofing#confirmation',
                                array(
                                    'images'  => $images,
                                    'storage' => C_Gallery_Storage::get_instance()
                                ),
                                TRUE
                            );
                            $message = str_replace('%%proof_details%%', $imagehtml, $message);
                            break;
                    }
                }
                $retval = $message;
            }
        }

        return $retval;
    }

}
