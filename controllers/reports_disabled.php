<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * reports controller
 */
class reports extends Admin_Controller
{

	//--------------------------------------------------------------------


	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Images.Reports.View');
		$this->load->model('images_model', null, true);
		$this->lang->load('images');
		
		Template::set_block('sub_nav', 'reports/_sub_nav');

		Assets::add_module_js('images', 'images.js');
	}

	//--------------------------------------------------------------------


	/**
	 * Displays a list of form data.
	 *
	 * @return void
	 */
	public function index()
	{

		// Deleting anything?
		if (isset($_POST['delete']))
		{
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->images_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('images_delete_success'), 'success');
				}
				else
				{
					Template::set_message(lang('images_delete_failure') . $this->images_model->error, 'error');
				}
			}
		}

		$records = $this->images_model->find_all();

		Template::set('records', $records);
		Template::set('toolbar_title', 'Manage Images');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Creates a Images object.
	 *
	 * @return void
	 */
	public function create()
	{
		$this->auth->restrict('Images.Reports.Create');

		if (isset($_POST['save']))
		{
			if ($insert_id = $this->save_images())
			{
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'images');

				Template::set_message(lang('images_create_success'), 'success');
				redirect(SITE_AREA .'/reports/images');
			}
			else
			{
				Template::set_message(lang('images_create_failure') . $this->images_model->error, 'error');
			}
		}
		Assets::add_module_js('images', 'images.js');

		Template::set('toolbar_title', lang('images_create') . ' Images');
		Template::render();
	}

	//--------------------------------------------------------------------


	/**
	 * Allows editing of Images data.
	 *
	 * @return void
	 */
	public function edit()
	{
		$id = $this->uri->segment(5);

		if (empty($id))
		{
			Template::set_message(lang('images_invalid_id'), 'error');
			redirect(SITE_AREA .'/reports/images');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Images.Reports.Edit');

			if ($this->save_images('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'images');

				Template::set_message(lang('images_edit_success'), 'success');
			}
			else
			{
				Template::set_message(lang('images_edit_failure') . $this->images_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict('Images.Reports.Delete');

			if ($this->images_model->delete($id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'images');

				Template::set_message(lang('images_delete_success'), 'success');

				redirect(SITE_AREA .'/reports/images');
			}
			else
			{
				Template::set_message(lang('images_delete_failure') . $this->images_model->error, 'error');
			}
		}
		Template::set('images', $this->images_model->find($id));
		Template::set('toolbar_title', lang('images_edit') .' Images');
		Template::render();
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_images($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['title']        = $this->input->post('images_title');
		$data['is_main']        = $this->input->post('images_is_main');

		if ($type == 'insert')
		{
			$id = $this->images_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$return = $this->images_model->update($id, $data);
		}

		return $return;
	}

	//--------------------------------------------------------------------


}