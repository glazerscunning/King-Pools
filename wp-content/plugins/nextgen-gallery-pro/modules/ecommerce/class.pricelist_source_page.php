<?php

class C_Pricelist_Source_Page extends C_NextGen_Admin_Page_Controller
{
	static function get_instance($context=FALSE)
	{
		if (!isset(self::$_instances[$context])) {
			self::$_instances[$context] = new C_Pricelist_Source_Page();
		}
		return self::$_instances[$context];
	}

	function define()
	{
		parent::define(NGG_PRO_PRICELIST_SOURCE_PAGE);
	}

	function get_required_permission()
	{
		return 'NextGEN Change options';
	}

	function get_page_heading()
	{
		return __('Manage Pricelist', 'nggallery');
	}

    function enqueue_backend_resources()
    {
        parent::enqueue_backend_resources();
        $router = C_Router::get_instance();

        if (!wp_script_is('sprintf')) {
            wp_register_script('sprintf', $router->get_static_url('photocrati-nextgen_pro_ecommerce#sprintf.js'));
        }

        wp_enqueue_script('sprintf');
        wp_enqueue_script('jquery.number');

        // Enqueue fontawesome
        if (method_exists('M_Gallery_Display', 'enqueue_fontawesome')) {
            M_Gallery_Display::enqueue_fontawesome();
        }
        else {
            C_Display_Type_Controller::get_instance()->enqueue_displayed_gallery_trigger_buttons_resources();
        }
        wp_enqueue_style('fontawesome');
    }

	function index_template()
	{
		return 'photocrati-nextgen_pro_ecommerce#manage_pricelist';
	}

	function get_model()
	{
		if (!isset($this->pricelist)) {
			$pricelist_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
			$mapper = C_Pricelist_Mapper::get_instance();
			if (!($this->pricelist = $mapper->find($pricelist_id, TRUE))) {
				$this->pricelist = $mapper->create();
			}
		}
		return $this->pricelist;
	}

	function get_i18n_strings()
	{
		$i18n = new stdClass;

		$i18n->saved	= __('Saved pricelist successfully', 'nggallery');
		$i18n->deleted	= __('Deleted pricelist', 'nggallery');

		return $i18n;
	}

    /**
     * Gets the action to be executed
     * @return string
     */
    function _get_action()
    {
        $action = $this->object->param('action');
        if (!$action && isset($_REQUEST['action_proxy'])) $action = $_REQUEST['action_proxy'];

        $retval = preg_quote($action, '/');
        $retval = strtolower(preg_replace(
            "/[^\w]/",
            '_',
            $retval
        ));
        return preg_replace("/_{2,}/", "_", $retval).'_action';
    }


	function get_success_message()
	{
		$retval = $this->param('message');

		if (!$retval) {
			if ($this->_get_action() == 'delete_action') {
				$retval = 'deleted';
			}
			else $retval = 'saved';
		}

		return $this->get_i18n_strings()->$retval;
	}

	function save_action()
	{
		$retval = FALSE;

		// Do I need to check security token?
		$pricelist = $this->get_model();

        // disable caching or the changes we're about to save() won't be displayed
        $mapper = C_Pricelist_Mapper::get_instance();
        $mapper->_use_cache = FALSE;

		if ($pricelist->save($_REQUEST['pricelist'])) {

			// Reset the pricelist object
			$this->pricelist = $pricelist;

			// Create price list items
			$item_mapper = C_Pricelist_Item_Mapper::get_instance();
			foreach ($_POST['pricelist_item'] as $id => $updates) {

				// Set the pricelist associated to each item
				$updates['pricelist_id'] = $pricelist->id();

				if (strpos($id, 'new-') !== FALSE) {
					$item = $item_mapper->create($updates);
					$item->save();
				}
				else {
					$item = $item_mapper->find($id, TRUE);
					$item->save($updates);
				}
			}

			if (!isset($_REQUEST['id'])) wp_redirect(admin_url("edit.php?post_type=ngg_pricelist&id=".$pricelist->id().'&message=saved'));
		}

		if (isset($_REQUEST['deleted_items'])) {
			$pricelist->destroy_items($_REQUEST['deleted_items']);
		}

		return $retval;
	}

	function delete_action()
	{
		if ($this->get_model()->destroy()) {
			wp_redirect(admin_url('edit.php?post_type=ngg_pricelist&ids='.$this->get_model()->id()));
		}
		else
			return FALSE;
	}
}