<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Page extends REST_Controller {

	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
    }

	public function home_get()
	{ 
		
		$data = $data = $this->db->get("test_user")->result();
		return $this->response("THis is Home Page APi....", REST_Controller::HTTP_OK);
	}

	public function about_get()
	{ 
		$data = $data = $this->db->get("test_user")->result();
		return $this->response("This is About us page Api..", REST_Controller::HTTP_OK);
	}

	
	public function contact_get()
	{ 
		$data = $data = $this->db->get("test_user")->result();
		return $this->response("This is contact us page Api...", REST_Controller::HTTP_OK);
	}

	
	

}
