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
	  	define("VEHICLE_MASTER_DIGIT_TBL","master_motor_digit");
	  	define("RTO_TBL","rto");
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


	public function getrtoList_get() {

                                  

                          

                                   $rtocode =trim($this->input->get('rtoCode'));

                                   $this->db->select('DISTINCT(region_code)');

                                                       $this->db->like('region_code', $this->security->xss_clean( $rtocode) , 'after');

                                                       $this->db->limit(10, 0);           

                                                       $query = $this->db->get(RTO_TBL);

                                                                   

                                                       $count_row = $query->num_rows();

                                                      

                                                       $a=[];

                                                       foreach ($query->result_array() as $row)

                                                       {           

                                                                    array_push($a , $row);

                                                       }

                                                       return $this->response(json_encode($a), REST_Controller::HTTP_OK);

                          

              }

              public function getallstates_get() {

	             	$this->db->select('DISTINCT(registered_state_name)');				
					$query = $this->db->get(RTO_TBL);
						
					$count_row = $query->num_rows();
					
					$a=[];
					foreach ($query->result_array() as $row)
					{	
						array_push($a , $row);
					}
					return $this->response(json_encode($a), REST_Controller::HTTP_OK);

              }

             public function getselectedrtodata_get() {
		
		        $region_code =trim($this->input->get('region_code'));
		        $this->db->select('registered_state_name,registered_city_name');	
                $this->db->where('region_code',$region_code );	
                $this->db->limit(1, 0);				
				$query = $this->db->get(RTO_TBL);
				return $this->response(json_encode($query->result()), REST_Controller::HTTP_OK);
					
	}
	public function getfilteredmodels_get() {
		        $maker =trim($this->input->get('maker'));
		        $this->db->select('DISTINCT(Model)');	
                $this->db->where('Make',$maker );		
				$this->db->order_by("Model", "asc");
				$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
				return $this->response(json_encode($query->result()), REST_Controller::HTTP_OK);
					
	}
	public function getfilteredfuelversions_get() {
		        $model =trim($this->input->get('model'));
		        $this->db->select('DISTINCT(Fuel_Type)');	
                $this->db->where('Model',$model );		
				$this->db->order_by("Fuel_Type", "asc");
				$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
				return $this->response(json_encode($query->result()), REST_Controller::HTTP_OK);
					
	}
	 public function getselectedmakerdata_get() {
		
		        $maker =trim($this->input->get('Make'));
		        $this->db->select('Make,Model,Fuel_Type');	
                $this->db->where('Make',$maker );	
                $this->db->limit(1, 0);						
				$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
				return trim($this->response(json_encode($query->result()), REST_Controller::HTTP_OK));
					
	}
	public function getallmaker_get() {
		
		        
		        $this->db->select('DISTINCT(Make)');				
				$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
		        $this->db->order_by("Make", "asc");
				$count_row = $query->num_rows();
				
				$a=[];
				foreach ($query->result_array() as $row)
				{	
					array_push($a , $row);
				}
				return $this->response(json_encode($a), REST_Controller::HTTP_OK);
		
	}
public function getSearchedmaker_get() {
		
		        $make =trim($this->input->get('make'));
				$this->db->select('DISTINCT(Make)');	
				$this->db->like('Make', $this->security->xss_clean( $make) , 'after');	
				$this->db->limit(10, 0);					
				$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
					
				$count_row = $query->num_rows();
				
				$a=[];
				foreach ($query->result_array() as $row)
				{	
					array_push($a , $row);
				}
				return $this->response(json_encode($a), REST_Controller::HTTP_OK);
		
	}
              public function getfilteredcities_get() {

					$stateid =trim($this->input->get('stateid'));			
					$this->db->select('DISTINCT(registered_city_name)');
					$this->db->where('registered_state_name',$stateid );				
					$query = $this->db->get(RTO_TBL);
					$count_row = $query->num_rows();
					$a=[];
					foreach ($query->result_array() as $row)
					{	
					array_push($a , $row);
					}
					return $this->response(json_encode($a), REST_Controller::HTTP_OK);

              }

              public function getfilteredrtos_get() {

                          

									$cityname =trim($this->input->get('cityName'));

									$this->db->select('region_code');       

									$this->db->like('LOWER(registered_city_name)', strtolower($cityname));

									$query = $this->db->get(RTO_TBL);

									           

									$count_row = $query->num_rows();



									$a=[];

									foreach ($query->result_array() as $row)

									{           

									            array_push($a , $row);

									}

									return $this->response(json_encode($a), REST_Controller::HTTP_OK);

                          

              }
public function getSearchedrtos_get() {
		
		        $region =trim($this->input->get('region'));
				$this->db->select('DISTINCT(region_code)');	
				$this->db->like('region_code', $this->security->xss_clean( $region) , 'after');	
				$this->db->limit(10, 0);					
				$query = $this->db->get(RTO_TBL);
					
				$count_row = $query->num_rows();
				
				$a=[];
				foreach ($query->result_array() as $row)
				{	
					array_push($a , $row);
				}
				return $this->response(json_encode($a), REST_Controller::HTTP_OK);
		
	}
              public function getAllBikeMake_get() {

						$this->db->distinct();
						$this->db->select('make');
						$where = " vehicle_type in('MotorBike','Scooter') ";
						$this->db->where($where);
						$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
						$count_row = $query->num_rows();
						//echo "<pre>"; print_r( $this->db);
						$a=[];

						if($count_row > 0){
							foreach ($query->result_array() as $row)

									{           

									            array_push($a , $row);

									}

									return $this->response(json_encode( $a ), REST_Controller::HTTP_OK);
						} else 
							return $this->response(json_encode('No Make found'), REST_Controller::HTTP_OK);
              }
              

              public function getAllBikeModel_get() {
					
					$bikemaker =trim($this->input->get('bikemaker'));
					$this->form_validation->set_data($this->input->get());
					$this->form_validation->set_rules('bikemaker', 'Bike Maker', 'required|trim|xss_clean');

					if($this->form_validation->run() === false ) {


					} else {

						$this->db->distinct();
						$this->db->select('model');
						$where = " make  like '%$bikemaker%' and vehicle_type in('MotorBike','Scooter')  ";
						$this->db->where($where);
						$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
						$count_row = $query->num_rows();
						//echo "<pre>"; print_r( $this->db);
						$a=[];

						if($count_row > 0){
							foreach ($query->result_array() as $row)

									{           

									            array_push($a , $row);

									}

									return $this->response(json_encode( $a ), REST_Controller::HTTP_OK);
						} else 
							return $this->response(json_encode('No Make found'), REST_Controller::HTTP_OK);
					}
              			
              }

              public function getAllBikeVariant_get() {

              	$bikemodel =trim($this->input->get('bikemodel'));
					$this->form_validation->set_data($this->input->get());
					$this->form_validation->set_rules('bikemodel', 'Bike Model', 'required|trim|xss_clean');

					if($this->form_validation->run() === false ) {


					} else {

						$this->db->distinct();
						$this->db->select('vehicle_code');
						$this->db->select('make');
						$this->db->select('model');
						$this->db->select('cubic_capacity');
						$this->db->select('variant');
						$this->db->select('body_type');
						$this->db->select('vehicle_type');
						$where = " model  like '$bikemodel%'  and vehicle_type in('MotorBike','Scooter')";
						$this->db->where($where);
						$query = $this->db->get(VEHICLE_MASTER_DIGIT_TBL);
						$count_row = $query->num_rows();
						//echo "<pre>"; print_r( $this->db);
						$a=[];

						if($count_row > 0){
										foreach ($query->result_array() as $row)
										{           

									            array_push($a , $row);

										}

									return $this->response(json_encode( $a ), REST_Controller::HTTP_OK);
						} else 
							return $this->response(json_encode('No Make found'), REST_Controller::HTTP_OK);
					}

              }
}