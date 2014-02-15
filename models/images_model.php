<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Images_model extends BF_Model {

	protected $table_name	= "images";
	protected $key			= "id";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";

	protected $log_user 	= FALSE;

	protected $set_created	= false;
	protected $set_modified = false;

	/*
		Customize the operations of the model without recreating the insert, update,
		etc methods by adding the method names to act as callbacks here.
	 */
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 		= array();
	protected $after_find 		= array();
	protected $before_delete 	= array('unlink_files');
	protected $after_delete 	= array('clean_up');

	/*
		For performance reasons, you may require your model to NOT return the
		id of the last inserted row as it is a bit of a slow method. This is
		primarily helpful when running big loops over data.
	 */
	protected $return_insert_id 	= TRUE;

	// The default type of element data is returned as.
	protected $return_type 			= "object";

	// Items that are always removed from data arrays prior to
	// any inserts or updates.
	protected $protected_attributes = array();

	/*
		You may need to move certain rules (like required) into the
		$insert_validation_rules array and out of the standard validation array.
		That way it is only required during inserts, not updates which may only
		be updating a portion of the data.
	 */
	protected $validation_rules 		= array(
		array(
			"field"		=> "images_title",
			"label"		=> "Title",
			"rules"		=> "required|trim|alpha_extra|max_length[255]"
		),
		array(
			"field"		=> "images_is_main",
			"label"		=> "Main Image",
			"rules"		=> "max_length[1]"
		),
		array(
			"field"		=> "images_ext",
			"label"		=> "Image Ext",
			"rules"		=> "max_length[6]"
		),
	);
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= TRUE;

	//--------------------------------------------------------------------
	
	public function clean_up($id){
    	$this->load->model('work/work_images_model');
    	$this->load->model('products/products_images_model');
    	$this->work_images_model->delete_where(array('image_id' => $id));
    	$this->products_images_model->delete_where(array('image_id' => $id));
	}
	
	public function unlink_files($id){
        $files = glob(FCPATH . 'uploads/images/' . $id . '.*');
        foreach($files as $f){
            unlink($f);
        }
        $files = glob(FCPATH . 'uploads/images/thumbs/' . $id . '.*');
        foreach($files as $f){
            unlink($f);
        }
	}
	
	public function find_all($ids=null)
    {
        if(isset($ids) && is_array($ids)){
            if(count($ids))
                $this->db->where_in('id', $ids);
            else
                return false;
        }
        $this->db->order_by('is_main DESC');
        $results = parent::find_all();
        
        if($results){
        	foreach($results as &$item){
            	$item->image_url = site_url() . 'uploads/images/' . $item->id . '.' . $item->ext;
            	$item->thumb_url = site_url() . 'uploads/images/thumbs/' . $item->id . '.' . $item->ext;
            }
        }
        return $results;
    }
    
    public function find($id)
    {
        $item = parent::find($id);
        
        if($item){
        	$item->image_url = site_url() . 'uploads/images/' . $item->id . '.' . $item->ext;
        	$item->thumb_url = site_url() . 'uploads/images/thumbs/' . $item->id . '.' . $item->ext;
        }
        return $item;
    }

    public function delete($id)
    {
        if(!empty($id) && is_array($id)){
            foreach($id as $i){
                $res = parent::delete($i);
                if(!$res){
                    return false;
                }
            }
            return true;
        }
        
        return parent::delete($id);
    }
}
