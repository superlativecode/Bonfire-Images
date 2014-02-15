<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * content controller
 */
class content extends Admin_Controller
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

		$this->auth->restrict('Images.Content.View');
		$this->load->model('images_model', null, true);
		$this->lang->load('images');
		
		$this->load->library('upload');
        $this->load->library('image_lib');
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
	    
		$this->auth->restrict('Images.Content.Create');
		
		if ($insert_id = $this->save_images())
		{
			// Log the activity
			log_activity($this->current_user->id, lang('images_act_create_record') .': '. $insert_id .' : '. $this->input->ip_address(), 'images');

			$this->return_json(true, $insert_id, lang('images_create_success'), 'new');
		}
		else
		{
			$this->return_json(false, 0, lang('images_create_failure') . $this->images_model->error, 'error');
		}
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
			redirect(SITE_AREA .'/content/images');
		}

		if (isset($_POST['save']))
		{
			$this->auth->restrict('Images.Content.Edit');

			if ($this->save_images('update', $id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_edit_record') .': '. $id .' : '. $this->input->ip_address(), 'images');

				$this->return_json(true, $id, lang('images_create_success'), 'edit');
			}
			else
			{
				$this->return_json(false, 0, lang('images_create_failure') . $this->images_model->error, 'error');
			}
		}
		else if (isset($_POST['delete']))
		{
			$this->auth->restrict('Images.Content.Delete');

			if ($this->images_model->delete($id))
			{
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'images');

				$this->return_json(true, $id, lang('images_delete_success'), 'delete');
			}
			else
			{
				$this->return_json(false, 0, lang('images_delete_failure') . $this->images_model->error, 'error');
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
		$data['title'] = $this->input->post('images_title');
		$data['is_main'] = $this->input->post('images_is_main');
		
		if($this->input->post('images_ext')) 
		    $data['ext'] = pathinfo($this->input->post('images_ext'), PATHINFO_EXTENSION);
        
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

        if(!empty($_FILES['file']['tmp_name']) && !$this->do_upload($id)) return false;
        
		return $return;
	}
	
	private function do_upload($image_id)
    {
        $upload_path = FCPATH . 'uploads/images/';
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '300000';
        $config['overwrite'] = true;

        if(!is_dir($upload_path)){
            mkdir($upload_path);
        }
        
        $config['file_name'] = $image_id;
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file')) {
            $this->return_json(false, 0, 'Unable to upload');
            return false;
        }else{
            $data = $this->upload->data();
            $thumb_size = 250;
            $thumb_config = array(
                'source_image' => $data['full_path'],
                'new_image' => $upload_path . 'thumbs/' . $image_id . $data['file_ext'],
                'maintain_ration' => true,
                'width' => $thumb_size,
                'height' => $thumb_size
            );
            
            if(!is_dir($upload_path . 'thumbs/')){
               mkdir($upload_path . 'thumbs/');
            }
            
            $this->image_lib->initialize($thumb_config);
            if(!$this->image_lib->fit()){
                //Successful
            }
            
        }

        return true;
    }

    private function return_json($success=true, $id=0, $msg='', $type = false){
        $image = false;
        if(!empty($id)) $image = $this->images_model->find($id);
        echo json_encode(array(
            'success' => $success, 
            'id' => $id, 
            'msg' => $msg, 
            'new_image_row' => $this->load->view('content/image_row', array('image' => $image, 'type' => $type), true)));
        die();
    }

	//--------------------------------------------------------------------


}