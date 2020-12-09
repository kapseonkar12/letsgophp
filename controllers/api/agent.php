<?php
// new changes added
header('Access-Control-Allow-Origin: *'); 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Agent extends REST_Controller {

	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
    	header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	
       parent::__construct();
	 
    }

	public function getLoginDetails_post() {
		
		$this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');

			if($this->form_validation->run() === false ) {

				return $this->response(array("status" =>0 , "error" => "Request Data having problem...1"), REST_Controller::HTTP_BAD_REQUEST);

			} else {

				$username = $this->input->post('username');    
				$password = $this->input->post('password');
				//echo $enc_password = password_hash($password, PASSWORD_BCRYPT);
				$this->db->select('agentid, email , mobile , pancard');
          		$this->db->where('email', $username);
				$this->db->where('password', $password);
				$this->db->where('status', 1);
				//$this->db->where('password', $this->validate_password($password));
			
				$query = $this->db->get('posagent');
				$result = $query->result();

				if($query->num_rows() ==1  ){

						return $this->response(json_encode($result[0]), REST_Controller::HTTP_OK);
				}else{
					return $this->response(REQUEST_ERROR, REST_Controller::HTTP_UNAUTHORIZED);
				}

			}
		
	}	

	public function agentDetails_get() {
			$agentId =xss_clean( trim($this->input->get('agentid')) );    
			if($agentId != "") {
					//$this->db->select('agentid, email , mobile , pancard');
	          		$this->db->where('agentid', $agentId);
					$query = $this->db->get('posagent');
					$result = $query->result();

					 $i = 0; $agentdetail = [];
					if($query->num_rows() ==1  ){
						//print_r($result); exit;
						//$agentdetail[$i]["agentid"]=$result[0]->agentid;
						//$agentdetail[$i]["gender"]=$result[0]->gender;
						////$agentdetail[$i]["firstName"]=$result[0]->firstname;
						//$agentdetail[$i]["middleName"]=$result[0]->middlename;
						//$agentdetail[$i]["lastName"]=$result[0]->lastname;
						//return $this->response(json_encode( ['data'=>$agentdetail]), REST_Controller::HTTP_OK);

						return $this->response(json_encode( $result[0]), REST_Controller::HTTP_OK);
					}else{
						return $this->response(REQUEST_ERROR, REST_Controller::HTTP_BAD_REQUEST);
					}

			}

	}

	public function agentLeadDetails_get() {

			$agentId =xss_clean( trim($this->input->get('agentid')) );    
			if($agentId != "") {
					//$this->db->select('agentid, email , mobile , pancard');
	          		$this->db->where('agentid', $agentId);
					$query = $this->db->get('lead');
					$result = $query->result();
					//print_r($result);
					$i = 0; $leadDetail = [];
					 
					if($query->num_rows() !=0  ){

						//$leadDetail[$i]["name"]=$result[0]->agentid;
						//$leadDetail[$i]["mobile"]=$result[0]->agentid;
						//$leadDetail[$i]["email"]=$result[0]->agentid;
						//$leadDetail[$i]["status"]=$result[0]->agentid;
						//$leadDetail[$i]["policy"]=$result[0]->agentid;
						//$leadDetail[$i]["agentid"]=$result[0]->agentid;
						$i++;

						return $this->response(json_encode($result), REST_Controller::HTTP_OK);
					}else{
						return $this->response(REQUEST_ERROR, REST_Controller::HTTP_BAD_REQUEST);
					}

			}

	}
	

	public function addagentLead_post() {
		
		$this->form_validation->set_rules('name', 'Agent Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|xss_clean|max_length[14]|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|max_length[50]');
		$this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
		$this->form_validation->set_rules('agentid', 'Agent Id', 'required|trim|xss_clean');
		$this->form_validation->set_rules('policy', 'Policy Type', 'required|trim|xss_clean');

			if($this->form_validation->run() === false ) {
				   // echo validation_errors();
 					return $this->response(REQUEST_ERROR, REST_Controller::HTTP_OK);

			} else {
				
					$data['agentid'] =    $this->input->post("agentid") ;
					$data['name']			=	$this->input->post("name");
					$data['mobile']		=	$this->input->post("mobile");
					$data['email']			=	$this->input->post("email");
					$data['status']	=	$this->input->post("status");
					$data['policy'] = $this->input->post("policy");
					//print_r($data);
					$this->db->insert('lead',$this->security->xss_clean($data));
					if($this->db->insert_id()) {
          							
						return $this->response(REQUEST_SUCESS, REST_Controller::HTTP_OK);
          			} else {
          				return $this->response(REQUEST_ERROR, REST_Controller::HTTP_OK);
          			}	
					
			}
		
	}	



}