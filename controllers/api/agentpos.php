<?php
header('Access-Control-Allow-Origin: *'); 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Agentpos extends REST_Controller {

	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
    	header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
       
       parent::__construct();
	   //$this->load->database();
	   //$this->load->library('form_validation');
	   //$this->load->library("security");
    }

	

	public function confirmregistration_post() {
		print_r($_FILES);
		//print_r($this->input);
		$error_file_flg = $error_data_flg = 1 ;
		/**
		**  Logic For Createing Agent ID
		*   $data array for posagent table
		*   $userdata array for user  table
		* Status = 2 in active , 1 =active , 0 soft delete
		**/

			$data['agentid'] = "AGNT-" . $this->input->post("mobile") ;


			$data['gender']			=	$this->input->post("gender");
			$data['firstName']	=	$this->input->post("firstName");
			$data['middleName']	=	$this->input->post("middleName");
			$data['lastName']		=	$this->input->post("lastName");
			$data['dob']			=	$this->input->post("dob");
			$data['mobile']		=	$this->input->post("mobile");
			$data['email']			=	$this->input->post("email");
			$data['extramobile']	=	$this->input->post("extramobile");

			$data['password']		=	password_hash($this->input->post("password"), PASSWORD_BCRYPT);

			//$data['confirmPassword']=	$this->input->post("confirmPassword");
			$data['qualification']	=$this->input->post("qualification");
			$data['pancard']		=	$this->input->post("pancard");
			$data['address1']		=	$this->input->post("address1");
			$data['address2']		=	$this->input->post("address2");
			$data['address3']		=	$this->input->post("address3");
			$data['state']			=	$this->input->post("state");
			$data['city']			=	$this->input->post("city");
			$data['pincode']		=	$this->input->post("pincode");
			$data['bankname']		=	$this->input->post("bankname");
			$data['branchname']		=	$this->input->post("branchname");
			$data['accounttype']	=	$this->input->post("accounttype");
			$data['accountnumber']	=	$this->input->post("accountnumber");
			$data['ifsccode']		=	$this->input->post("ifsccode");
			$data['createdat']		= date("Y-m-d H:i:s");
			$data['status']		=	2 ;

			$password_h = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
					
			$userdata['firstName']	=	$data['firstName'];
			$userdata['lastName']	=	$data['lastName'];
			$userdata['mobile']		=	$data['mobile'];
			$userdata['email']		=	$data['email']	;
			$userdata['hash']		=	$data['password'];
			$userdata['usertype']		=	"agent";
			$userdata['status']		=	2 ;
			$userdata['createdat']		= $data['createdat'];
		

		$this->form_validation->set_rules('firstName', 'FirstName', 'required|trim|xss_clean|is_unique[posagent.firstname]');

		/*$this->form_validation->set_rules('lastName', 'LastName', 'required|trim|xss_clean');
		$this->form_validation->set_rules('dob', 'DateofBirth', 'required|trim|xss_clean');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|xss_clean');
		$this->form_validation->set_rules('email', 'email', 'required|trim|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
		$this->form_validation->set_rules('pancard', 'Pancard', 'required|trim|xss_clean');
		$this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|xss_clean');
		$this->form_validation->set_rules('bankname', 'Bank Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('branchname', 'Branch Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('accounttype', 'Accounttype', 'required|trim|xss_clean');
		$this->form_validation->set_rules('accountnumber', 'Account Number', 'required|trim|xss_clean');
		$this->form_validation->set_rules('ifsccode', 'IFSC code', 'required|trim|xss_clean');
	*/


		//print_r($this->input->post());
		if($this->form_validation->run() === false ) {

			return $this->response(array("status" =>0 , "error" => "Request Data having problem...1"), REST_Controller::HTTP_BAD_REQUEST);

		} else {

			if(  !empty($data['gender'])  &&  !empty($data['firstName']) &&  !empty($data['lastName'])  &&  !empty($data['dob'])  &&  !empty($data['mobile'])  &&  !empty($data['email'])  &&   !empty($data['password'])  &&  !empty($data['qualification']) &&  !empty($data['pancard']) && !empty($data['pincode']) &&  !empty($data['bankname'])   &&  !empty($data['branchname']) && !empty($data['accounttype']) &&  !empty($data['accountnumber']) &&  !empty($data['ifsccode'])  ) {
					
						if(isset($_FILES["document"])) {
					
							$config['upload_path']          = './agent/documents/';
							$config['allowed_types']        = 'gif|jpg|png';
							$config['max_size']             = 102423;
							$config['max_width']            = 10231234;
							$config['max_height']           = 1024123;
							$config['file_name'] =$this->security->sanitize_filename( $data['firstName'] . '-' .$data['mobile'].'-'.date("Y-m-d").'-'.$_FILES['document']['name'] );
							$upload_file ="";
							$this->load->library('upload', $config);

							if ( ! $this->upload->do_upload('document'))
							{

								$error = array('error' => $this->upload->display_errors());

								return $this->response(array("status" =>0 , "error" => "Request Data having problem file..."), REST_Controller::HTTP_BAD_REQUEST);
								
							}
							else
							{
								$upload_file = $this->security->sanitize_filename( base_url() .''.$config['upload_path']."". $config['file_name']);
							}

							$data['document']= 	$upload_file;
							$data = $this->security->xss_clean($data);
							$error_file_flg=0;

					} 

						/*
						*   WHile inserting data use $this->security->xss_clean($data);
						* $data include all required post data
						**
						*/
						
							$this->db->insert('posagent',$data);
          					if($this->db->insert_id()) {
          						$error_data_flg = 0 ;
          						echo  $this->db->insert_id();
          						print_r($userdata);
          						$userdata['agnt_ref_id'] = $this->db->insert_id();
          						$this->db->insert('users',$userdata);

          					}	

			} else {

				return $this->response(array("status" =>0 , "error" => "Request Data having problem...2"), REST_Controller::HTTP_BAD_REQUEST);
			}
		}
		

		if( ($error_file_flg==1) || ($error_data_flg ==1 )) {
			//Error
			return $this->response(array("status" =>0 , "error" => "Request Data having problem...flag"), REST_Controller::HTTP_BAD_REQUEST);
		} else {
			// Ok r
			return $this->response(array("status" => 1 , "message"=>"success.."), REST_Controller::HTTP_OK);	
		}
		
	}


}