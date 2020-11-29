<?php
header('Access-Control-Allow-Origin: *'); 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Common extends REST_Controller {

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
	  	$this->load->model("Doemail");
	  }
	  
	  public function index_get() {
	  	echo "HI";
	  	$this->Doemail->email_toagentregistration("swap.no1@gmail.com","swapnil shripal shete");
	  	exit;
	  }

	  public function checkDuplicateEmail_get() {
	     $email_id =trim($this->input->get('email'));
		 $this->form_validation->set_data($this->input->get());

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');

		if($this->form_validation->run() === false ) {
			
				$status = array("status" =>0 , "error" => "error:having problem with parameter...");
			return $this->response($status, REST_Controller::HTTP_OK);	
		} else {

		  	if(!empty($email_id)) {
					$this->db->where('email',$this->security->xss_clean( $email_id) );

					$query = $this->db->get('posagent');
						
					$count_row = $query->num_rows();

					if ($count_row > 0) {
					//if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
					//return FALSE; // here I change TRUE to false.
						$status = array("status" =>0 , "error" => "error");  


					} else {
					// doesn't return any row means database doesn't have this email
					//return TRUE; // And here false to TRUE
						$status = array("status" =>1 , "message" => "sucess");
					}
					
					return $this->response($status, REST_Controller::HTTP_OK);	

				} else {

					$status = array("status" => 0 , "error" => "error");  
					return $this->response($status, REST_Controller::HTTP_OK);	
				}

		}
	  	
	}

	public function checkDuplicateMobile_get() {

		$mobile =trim($this->input->get('mobile'));
		 $this->form_validation->set_data($this->input->get());

		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|xss_clean');

		if($this->form_validation->run() === false ) {
			
			$status = array("status" =>0 , "error" => "error:having problem with parameter...");
			return $this->response($status, REST_Controller::HTTP_OK);	
		} else {

		  	if(!empty($mobile)) {
					$this->db->where('mobile',$this->security->xss_clean( $mobile) );

					$query = $this->db->get('posagent');
						
					$count_row = $query->num_rows();

					if ($count_row > 0) {
					//if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
					//return FALSE; // here I change TRUE to false.
						$status = array("status" =>0 , "error" => "error");  


					} else {
					// doesn't return any row means database doesn't have this email
					//return TRUE; // And here false to TRUE
						$status = array("status" =>1 , "message" => "sucess");
					}
					
					return $this->response($status, REST_Controller::HTTP_OK);	

				} else {

					$status = array("status" => 0 , "error" => "error");  
					return $this->response($status, REST_Controller::HTTP_OK);	
				}

		}
	}



	public function getpincodedetails_get() {
	     $pincode =trim($this->input->get('pincode'));
		$this->form_validation->set_data($this->input->get());

		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|xss_clean');

		if($this->form_validation->run() === false ) {
			
			$status = array("status" =>0 , "error" => "error:having problem with parameter...");
			return $this->response($status, REST_Controller::HTTP_OK);	
		} else {

		  	if(!empty($pincode)) {
					$this->db->where('Pincode',$this->security->xss_clean( $pincode) );

					$query = $this->db->get('Pincode');
						
					$count_row = $query->num_rows();
					$result = $query->result();

					if ($count_row > 0) {
					//if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
					//return FALSE; // here I change TRUE to false.
						$status = array("status" =>0 , "error" => "error");  


					} else {
					// doesn't return any row means database doesn't have this email
					//return TRUE; // And here false to TRUE
						$status = array("status" =>1 , "message" => "sucess");
					}
					
					return $this->response($status, REST_Controller::HTTP_OK);	

				} else {

					$status = array("status" => 0 , "error" => "error");  
					return $this->response($status, REST_Controller::HTTP_OK);	
				}

		}
	  	
	}


	public function getpincodes_get() {
	     		$pincode =trim($this->input->get('pincode'));
				$this->db->like('Pincode', $this->security->xss_clean( $pincode) , 'after');
				$this->db->limit(10, 0);	
					$query = $this->db->get('Pincode');
					
				$count_row = $query->num_rows();
				//$result = $query->result();
				 $a=[];
				foreach ($query->result_array() as $row)
				{	
					//$a['Pincode']=$row['Pincode'];
					//$a['city'] =  $row['City'];
					array_push($a , $row);
				}
				return $this->response(json_encode($a), REST_Controller::HTTP_OK);
				
	  	
	}

}
