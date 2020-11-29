<?php
// echo test
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Users extends REST_Controller {

	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
    	header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
       parent::__construct();
		
       $this->load->database();
    }

	public function index_get()
	{ 
		echo "exe"; exit;
		$data = array (  
				    [  
				        "ClientId" => 11,  
				        "ClientName"=>"Hytech Professionals"  
				    ],  
				    [  
				        "ClientId" => 12,  
				        "ClientName" => "RVS IT Consulting"  
				    ],  
				    [  
				        "ClientId" => 13,  
				        "ClientName" => "MCN Solution"  
				    ],  
				    
				    
		)  ;
		$this->response($data, REST_Controller::HTTP_OK);
	}

	public function index_post()
	{	
		 // check header parameter $this->input->request_headers()

		return $this->response($this->input->request_headers(), REST_Controller::HTTP_OK);
	
	
	}

	
	public function authentication_post() {
		return $this->response("this is Authentication APi...", REST_Controller::HTTP_OK);
	}


}
