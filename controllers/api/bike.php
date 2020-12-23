<?php
header('Access-Control-Allow-Origin: *'); 

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Bike extends REST_Controller {

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
       $this->all_API_RESULT=[];
	 
     } 
	public function getQuote_1_post() {
		$_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream));

			$insu_qoute_ary_1 = $this->executeDigitAPI($_POST) ;
				if(isset($insu_qoute_ary_1['idv']))
				 	array_push($this->all_API_RESULT, $insu_qoute_ary_1 );
			
			$insu_qoute_ary_2 = $this->executeTATAgiAPI($_POST) ;
				if(isset($insu_qoute_ary_2['idv']))
				 	array_push($this->all_API_RESULT, $insu_qoute_ary_2 );

				 //print_r($this->all_API_RESULT);
		return $this->response(json_encode( $this->all_API_RESULT ), REST_Controller::HTTP_OK);
  	}

	public function executeDigitAPI($POST) {
		
		$api_digit_url ="https://preprod-qnb.godigit.com/digit/motor-insurance/services/integration/v2/quickquote?isUserSpecialDiscountOpted=false";

		$enq_id = rand() . "_1" ;
		/*
		** Get Vehicle main code logi get the vehicale code from database
		*/ 
		$this->db->distinct();
		$this->db->select('vehicle_code');
		$this->db->where("make", $POST->requestPara->vh_make);
		$this->db->where("model", $POST->requestPara->vh_model);
		
		/**
		*  Code separated by Varient and Capacity cc 
		*/
		$motor_variant_cc_ary = explode("(" , $POST->requestPara->vh_varient );
		
		$motor_variant = trim( $motor_variant_cc_ary[0] );
		$motor_cc = trim( explode( "cc" , $motor_variant_cc_ary[1] )[0]);
		
		$this->db->where("variant", $motor_variant);
		$this->db->where("variant", $motor_variant);
		$this->db->where("cubic_capacity", $motor_cc);
		$where = " vehicle_type in('MotorBike','Scooter')  ";
		$this->db->where($where);

		$query = $this->db->get('master_motor_digit');
		$result = $query->result_array();
		
		$vehicleMaincode = $result[0]['vehicle_code'];
		$productid = 20201 ;
		$subInsuProCode = "PB";
		$policyHolderType = "INDIVIDUAL";
		$voluntaryDeductible = "ZERO";
		$licensePlateNumber = str_replace("-", "", $POST->requestPara->vh_no);  
		$registrationDate =$POST->requestPara->vh_regis_dt . "-12-01";
		$pincode ="411046";
		$isVehicleNew = "false";

       $request = '{
							    "enquiryId": "'. $enq_id .'",
							    "contract": {
							        "insuranceProductCode": "'.$productid.'",
							        "subInsuranceProductCode": "'. $subInsuProCode.'",
							        "startDate": null,
							        "endDate": null,
							        "policyHolderType": "'. $policyHolderType .'",
							        "coverages": {
							        "voluntaryDeductible":"'.$voluntaryDeductible .'",
							            "thirdPartyLiability": {
							                "isTPPD": false
							            },
							            "ownDamage": {
							                "discount": {
							                    "userSpecialDiscountPercent": 0,
							                    "discounts": []
							                },
							                "surcharge": {
							                    "loadings": []
							                }
							            },
							            "personalAccident": {
							                "selection": null,
							                "insuredAmount": null,
							                "coverTerm": null
							            },
							            "accessories": {
							                "cng": {
							                    "selection": null,
							                    "insuredAmount": null
							                },
							                "electrical": {
							                    "selection": null,
							                    "insuredAmount": null
							                },
							                "nonElectrical": {
							                    "selection": null,
							                    "insuredAmount": null
							                }
							            },
							            "addons": {
							                "partsDepreciation": {
							                    "claimsCovered": null,
							                    "selection": null
							                },
							                "roadSideAssistance": {
							                    "selection": null
							                },
							                "engineProtection": {
							                    "selection": null
							                },
							                "tyreProtection": {
							                    "selection": null
							                },
							                "rimProtection": {
							                    "selection": null
							                },
							                "returnToInvoice": {
							                    "selection": null
							                },
							                "consumables": {
							                    "selection": null
							                }
							            },
							            "legalLiability": {
							                "paidDriverLL": {
							                    "selection": null,
							                    "insuredCount": null
							                },
							                "employeesLL": {
							                    "selection": null,
							                    "insuredCount": null
							                },
							                "unnamedPaxLL": {
							                    "selection": null,
							                    "insuredCount": null
							                },
							                "cleanersLL": {
							                    "selection": null,
							                    "insuredCount": null
							                },
							                "nonFarePaxLL": {
							                    "selection": null,
							                    "insuredCount": null
							                },
							                "workersCompensationLL": {
							                    "selection": null,
							                    "insuredCount": null
							                }
							            },
							            "unnamedPA": {
							                "unnamedPax": {
							                    "selection": null,
							                    "insuredAmount": null,
							                    "insuredCount": null
							                },
							                "unnamedPaidDriver": {
							                    "selection": null,
							                    "insuredAmount": null,
							                    "insuredCount": null
							                },
							                "unnamedHirer": {
							                    "selection": null,
							                    "insuredAmount": null,
							                    "insuredCount": null
							                },
							                "unnamedPillionRider": {
							                    "selection": null,
							                    "insuredAmount": null,
							                    "insuredCount": null
							                },
							                "unnamedCleaner": {
							                    "selection": null,
							                    "insuredAmount": null
							                },
							                "unnamedConductor": {
							                    "selection": null,
							                    "insuredAmount": null,
							                    "insuredCount": null
							                }
							            }
							        }
							    },
							    "vehicle": {
							        "isVehicleNew": "'.$isVehicleNew.'",
							        "vehicleMaincode": "'.$vehicleMaincode.'",
							        "licensePlateNumber": "'.$licensePlateNumber .'",
							        "vehicleIdentificationNumber": null,
							        "engineNumber": null,
							        "manufactureDate": "'.$registrationDate .'",
							        "registrationDate": "'.$registrationDate .'",
							        "vehicleIDV": null
							    },
							    "previousInsurer": {
							        "isPreviousInsurerKnown": false,
							        "previousInsurerCode": null,
							        "previousPolicyNumber": null,
							        "previousPolicyExpiryDate": null,
							        "isClaimInLastYear": null,
							        "originalPreviousPolicyType": null,
							        "previousPolicyType": null,
							        "previousNoClaimBonus": null,
							        "previousNoClaimBonusValue": null,
							        "currentThirdPartyPolicy": null
							    },
							    "pospInfo": {
							        "isPOSP": false
							    },
							    "pincode": "'.$pincode .'"
							}';
		
		
		//echo $api_digit_url;
        /* Init cURL resource */
        $ch = curl_init($api_digit_url);
        
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        /* Array Parameter Data */
  		
            /* set return type json */
        

        curl_setopt($ch, CURLOPT_POST,1);
            
        /* pass encoded JSON string to the POST fields */

        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
           
        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        			 'Content-Type: application/json',
                     'Authorization: Basic MjU4NjA3MTE6ZGlnaXQxMjM='
        ));
            
       
       
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
        /* execute request */
        $result_resp = curl_exec($ch);

        //echo "##############<pre>#######";
        //print_r(json_decode($result));
        //LIST_BIKE_ARY["name"] ="Digit";
        //LIST_BIKE_ARY["net_preimium"] ="1000.00";
        //print_r(LIST_BIKE_ARY);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$error_message}";
		}

		
		$result_jsonObject = json_decode($result_resp);
		//print_r($result_jsonObject);
		$actualresult = array();


		$actualresult["policy_name"] ="Digit";
		$actualresult["idv"] =$result_jsonObject->vehicle->vehicleIDV->idv;
		$actualresult["minidv"] =$result_jsonObject->vehicle->vehicleIDV->minimumIdv;
		$actualresult["maximumidv"] =$result_jsonObject->vehicle->vehicleIDV->maximumIdv;
		$actualresult["netpremium"] =$result_jsonObject->netPremium;
		$actualresult["totalpayable"] =$result_jsonObject->grossPremium;
		$actualresult["servicetax"] =$result_jsonObject->serviceTax->totalTax;
		$actualresult["policy_img"] ="../assets/images/digit.png";

		
		return $actualresult;
	}
	
	public function executeTATAgiAPI($POST) {
		$api_tata_url ="https://pipuat.tataaiginsurance.in/tagichubms/ws/v1/ws/tran1/quotation";

		$this->db->distinct();
		$this->db->select('vehiclemodelcode, num_parent_model_code , bodytypecode , txt_segmenttype , manufacturercode');
		$this->db->where("manufacturer", $POST->requestPara->vh_make);
		$this->db->where("vehiclemodel", $POST->requestPara->vh_model);
		
		/**
		*  Code separated by Varient and Capacity cc 
		*/
		$motor_variant_cc_ary = explode("(" , $POST->requestPara->vh_varient );
		
		$motor_variant = trim( $motor_variant_cc_ary[0] );
		$motor_cc = trim( explode( "cc" , $motor_variant_cc_ary[1] )[0]);
		
		$motor_variant_ary = explode(" ", $motor_variant);
		
		$q='  ( ';

		for ($i = 0; $i < count($motor_variant_ary); $i++)
		{
			if($i ==0 )
				$this->db->like("txt_variant", $motor_variant_ary[$i] , "both");
			else {
					$or = "";
					if($i != ( count($motor_variant_ary) -1 ) )
						$or =' OR ' ; 

				$q.="  txt_variant like " .  "'%".  $motor_variant_ary[$i] . "%' "  . $or ;
			}

		}
			$q .= " ) ";
		
		if(count($motor_variant_ary) > 1 )		
			$this->db->where($q);
		
		$this->db->where("cubiccapacity", $motor_cc);
		$where = " txt_segmenttype  in ('MOTOR CYCLE','Scooter', 'Moped')  ";
		$this->db->where($where);

		$query1 = $this->db->get('master_motor_tata_agi');

		$result1 = $query1->result_array();

		//print_r( $this->db);

		if($query1->num_rows() == 0) {
			 return ;
		}

		
		
		$SRC="TP";
		$T="2D9D1BC5A837E7A2741C6121317E9EE6CE1D32145CBCF7084FA4493ECDA2C2804969A5473610BC2AB4FC034359C11D55F99F8AEC736D84F0EFD531DFE24FFC74F0923F1288A83121B8045A8AAA4D9F920B4D737E3A1134B824E23B1F0561D97AEA647554A31570720BDB6E4CE3D8813A1138ABF16F2A23A8E6BAB012DD07B768019A5B583351F6D36C1F6F26B5C8D474D2F701E664A96F73806EE3A5235DEFFD76CF4106F7F074A55258D75B1DDEFD38";
		$productid = 3122;
		$functinality ="validatequote";
		$quote_type= "quick";
		$quotation_no="";
		$QDATA="";
		/**
		*  segment code to to be as below 
		*	7 Scooter
		*	8 MOTOR CYCLE
		*	9 Moped
		*/
		$segment_name =$result1[0]['txt_segmenttype'];

		if($segment_name == "MOTOR CYCLE"){
			$segment_code ="8";
		} elseif($segment_name == "Scooter") {
			$segment_code ="7";
		} elseif ($segment_name == "Moped") {
			$segment_code ="9";
		}


		
		$cubic_capacity = "";
		$seating_capacity="";
		$sol_id="1001";
		$mobile_no="";
		$email_id="";

		$customer_type="Individual";
		$product_code="3122";
		$product_name="Two Wheeler";
		$subproduct_code="37";
		$subproduct_name="Two Wheeler";
		/**
		*	"Value should be 1 or 2
		*	1-  for  Package
		*	2 - for  Liability"
		*
		*/
		$covertype_code="1";

		if($covertype_code == "1") {
			$covertype_name="Package";	
		} else {
			$covertype_name="Liability";	
		}
		
		/**
		*	"Value should be 1 or 2
		*	1-  for  New Business
		*	2 - for  Roll Over"
		*/
		$btype_code="1";

		if($btype_code=="2")
		{
			$btype_name="Roll Over";
		} else {
			$btype_name="New Business";	
		}
		
		/**
		**	From User Input - Format YYYYMMDD
		*	pass Expiry + 1, T+1 for breakin cases
		*	T+1 for Liability cases"
		*	From User Input/DateAdd 1 year from policy start date - Format YYYYMMDD
		*	For New Business Add 5 year, Roll Over add based in Tenure  in expiry / T (irrespectiveof leap year)"
		*
		* 		$risk_startdate= date("Ymd"); 
		*
		*		if($btype_code=="2")
		*			$risk_enddate= date('Ymd', strtotime('+1 years -1 day'));
		*		else 
		*			$risk_enddate= date('Ymd', strtotime('+5 years -1 day'));
		*			
		*
		*		$purchase_date= $risk_startdate;	
		*
		* $risk_startdate= date("Ymd", strtotime('+1 day'));; 
		*
		*if($btype_code=="2")
		*	$risk_enddate= date('Ymd', strtotime('+1 years'));
		*else 
		*	$risk_enddate= date('Ymd', strtotime('+5 years'));
		*/

		$risk_startdate= date("Ymd"); 
		
		if($btype_code=="2")
			$risk_enddate= date('Ymd', strtotime('+1 years  -1 day'));
		else 
			$risk_enddate= date('Ymd', strtotime('+5 years  -1 day'));


		$purchase_date= $risk_startdate;
		$veh_age="";  //Calculated age from Purchase date to Current date
		/**
		*  This is call from Database to identify all many_year , make_code, $model_name
		*
		*/
		/**
		*  Code separated by Varient and Capacity cc 
		*/
		$motor_variant_cc_ary = explode("(" , $POST->requestPara->vh_varient );
		
		$motor_variant = trim( $motor_variant_cc_ary[0] );
		$motor_cc = trim( explode( "cc" , $motor_variant_cc_ary[1] )[0]);
		

		$manf_year="2018";
		$make_code=  $result1[0]['manufacturercode'];

		$make_name=$POST->requestPara->vh_make;
		$model_code= $result1[0]['num_parent_model_code'];
		$model_name=  $POST->requestPara->vh_model;
		$variant_code=$result1[0]['vehiclemodelcode'];
		$variant_name =  $motor_variant ; 
		$model_parent_code=$result1[0]['num_parent_model_code'];

	
		
		//optional ("Fuel code to be as below 1 for Petrol 2 for Diesel 3 for CNG 4 for Battery 5 for External CNG 6 for External LPG 7 for Electricity 8 for Hydrogen 9 for LPG")
		$fuel_code="";
		$fuel_name="";
		$bodytype_id =$result1[0]['bodytypecode']; // From Make Master (Pass BODYTYPECODE) based on selected vehicle Model
		/**
		* From User Input-Mandatory for both New and Roll Over case
		* From User Input-Mandatory for both New and Roll Over case
		* From User Input-Mandatory for Roll Over case and Optional for New business
		* From User Input-Mandatory for Roll Over case and Optional for New business
		*/
		$POST->requestPara->vh_no;
		$licensePlateNumber_reg = explode("-",$POST->requestPara->vh_no);  
		
		$regno_1 = $licensePlateNumber_reg[0];
		$regno_2 =$licensePlateNumber_reg[1];
		$regno_3 = $licensePlateNumber_reg[2];
		$regno_4 =$licensePlateNumber_reg[3];

		$rto_loc_code =$licensePlateNumber_reg[0] ."-". $licensePlateNumber_reg[1]; 
		// Pass TXT_RTO_LOCATION_CODE from RTO Master(GC)or Registration number 1-Registration number 2 ( eg: MH-23)
		// From RTO Master (Pass 'TXT_RTO_LOCATION_DESC') based on selected RTO

		$this->db->select('registered_city_name');
		$this->db->like("region_code",$regno_1.$regno_2);
		$this->db->order_by('id desc');
		$query_rto = $this->db->get('rto');

		$result_rto = $query_rto->result_array();


		$rto_loc_name =$result_rto[0]['registered_city_name']; 
		$rtolocationgrpcd="2"; //From RTO Master (Pass 'TXT_RTO_LOCATION_DESC') based on selected RTO
		$rto_zone="B" ; //From RTO Master (Pass ' TXT_REGISTRATION_ZONE') based on selected RTO
		$pp_covertype_code=""; //Mandatory for Roll Over case and values Optional for New businessValue should be 1 or 2.1 for Package 2 for Liability
		$pp_covertype_name="";
		$pp_enddate ="";//Mandatory for Roll Over case and values Optional for New business From User Input - Format YYYYMMDD
		$pp_claim_yn="";
		$pp_prev_ncb="";
		$pp_curr_ncb="";
		$cust_name ="";
		$cust_pincode="411046";//From User Input or From PINCODE Master
		/** tenure
		*	1-  for  New Business
     	* IF  Package
        *   Hard Coded(1 or 5)
     	* IF Liability
        *   Hard Coded("5")
		* 2 - for  Roll Over
      	* IF  Package
        *   Hard Coded(1 or 2 or 3)
     	* IF Liability
        * `	  Hard Coded("1")
		*/
		
     	$tenure="";

		if($btype_name == "New Business") {
				if($covertype_name == "Package"){

					$tenure="1";
				} else {

					$tenure="5";
				}

		} else {

				if($covertype_name == "Package"){

					$tenure="3";
				} else {
					$tenure="1";
				}
		}


		/** driver_declaration
     	* User selection  Hard Coded("ODD01")
		* ODD01   -   None
		* ODD02   -  No valid driving license
		* ODD03   -  Other motor policy with CPA Cover
		* ODD04   -  Have standalone CPA with SI>=15 lakh
		*/


		$driver_declaration="ODD01";
		$ac_opted_in_pp = "Y";
		if($covertype_name == "Package"){
					$c1="Y";
		} else {
				$c1="N";
		}
		$c2="Y";
		/* 
		*	If select driver_declaration is ODD01  Hard Coded(""opted"":""Y"") User selected values (""tenure"":""1"") 
		*	Else
		*   Hard Coded(""opted"":""N"")
		*   User selected values (""tenure"":"""")"
		*/
		if($driver_declaration == 'ODD01'){
			$c3="Y";
			$tenure_c3 = '1';
		} else {
			$c3="N";
			$tenure_c3 = '';
		}


		/*
		** Get Vehicle main code logi get the vehicale code from database
		*/ 
		$this->db->distinct();
		$this->db->select('vehicle_code');
		$this->db->where("make", $POST->requestPara->vh_make);
		$this->db->where("model", $POST->requestPara->vh_model);
		
		

      	$QDATA = '{
						"functionality": "'. $functinality .'",
						"quote_type": "'.$quote_type.'",
						"vehicle": {
							"quotation_no": "",
							"segment_code": "'.$segment_code.'",
							"segment_name": "' .$segment_name. '",
							"cc": "'.$cubic_capacity.'",
							"sc": "'.$seating_capacity.'",
							"sol_id": "'. $sol_id .'",
							"lead_id": "",
							"mobile_no": "'.$mobile_no.'",
							"email_id": "'.$email_id.'",
							"emp_email_id": "",
							"customer_type": "'.$customer_type.'",
							"product_code": "'. $product_code .'",
							"product_name": "'.$product_name.'",
							"subproduct_code": "'.$subproduct_code.'",
							"subproduct_name": "'.$subproduct_name.'",
							"subclass_code": "",
							"subclass_name": "",
							"covertype_code": "'.$covertype_code.'",
							"covertype_name": "'.$covertype_name.'",
							"btype_code": "'.$btype_code.'",
							"btype_name": "'.$btype_name.'",
							"risk_startdate": "'.$risk_startdate.'",
							"risk_enddate": "'.$risk_enddate.'",
							"purchase_date": "'.$purchase_date.'",
							"veh_age": "'.$veh_age.'",
							"manf_year": "'.$manf_year.'",
							"make_code": "'.$make_code.'",
							"make_name": "'. $make_name .'",
							"model_code": "'.$model_code.'",
							"model_name": "'.$model_name.'",
							"variant_code": "'.$variant_code.'",
							"variant_name": "'.$variant_name.'",
							"model_parent_code": "'.$model_parent_code.'",
							"fuel_code": "'.$fuel_code.'",
							"fuel_name": "'.$fuel_name.'",
							"gvw": "",
							"age": "",
							"miscdtype_code": "",
							"bodytype_id": "'.$bodytype_id.'",
							"idv": "",
							"revised_idv": "",
							"regno_1": "'.$regno_1.'",
							"regno_2": "'. $regno_2 .'",
							"regno_3": "'. $regno_3 .'",
							"regno_4": "'. $regno_4.'",
							"rto_loc_code": "'.$rto_loc_code.'",
							"rto_loc_name": "'.$rto_loc_name .'",
							"rtolocationgrpcd": "'.$rtolocationgrpcd.'",
							"rto_zone": "'.$rto_zone.'",
							"rating_logic": "",
							"campaign_id": "",
							"fleet_id": "",
							"discount_perc": "",
							"pp_covertype_code": "'.$pp_covertype_code.'",
							"pp_covertype_name": "'.$pp_covertype_name.'",
							"pp_enddate": "'.$pp_enddate.'",
							"pp_claim_yn": "'.$pp_claim_yn.'",
							"pp_prev_ncb": "'.$pp_prev_ncb.'",
							"pp_curr_ncb": "'.$pp_curr_ncb.'",
							"addon_plan_code": "",
							"addon_choice_code": "",
							"cust_name": "'.$cust_name.'",
							"ab_cust_id": "",
							"ab_emp_id": "",
							"usr_name": "Rajesh",
							"producer_code": "",
							"pup_check": "N",
							"pos_panNo": "",
							"pos_aadharNo": "",
							"is_cust_JandK": "NO",
							"cust_pincode": "'.$cust_pincode.'",
							"cust_gstin": "",
							"tenure": "'.$tenure.'",
							"uw_discount": "",
							"Uw_DisDb": "",
							"uw_load": "",
							"uw_loading_discount": "0",
							"uw_loading_discount_flag": "D",
							"engine_no": "",
							"chasis_no": "",
							"driver_declaration":"'.$driver_declaration.'",
							"ac_opted_in_pp":"'.$ac_opted_in_pp.'" 
						},
						"cover": {
							"C1": {
								"opted": "'.$c1.'"
							},
							"C2": {
								"opted": "'.$c2.'"
							},
							"C3": {
								"opted": "'.$c3.'",
								"tenure":"'.$tenure_c3.'"
							}
						}
						}
						';
		
		
		//echo $api_digit_url;
        /* Init cURL resource */
        $qry_str = "SRC=".$SRC."&productid=".$productid ."&QDATA=".$QDATA."&T=".$T;
       	//echo $main_url = $api_tata_url . $qry_str ;
        //echo $api_tata_url;
        $ch = curl_init($api_tata_url);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST,1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
		curl_setopt($ch,CURLOPT_TIMEOUT, 40);
        /* execute request */

        $result_resp = curl_exec($ch);

        if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$error_message}";
		}

        //echo "##############<pre>#######";

	
				$result_jsonObject = json_decode($result_resp);
				//print_r($result_jsonObject);
				$actualresult = array();
				//print_r($result_jsonObject->data);

				$actualresult["policy_name"] ="Tata Agi";
				$actualresult["idv"] =$result_jsonObject->data->quotationdata->revised_idv;
				$actualresult["minidv"] =$result_jsonObject->data->quotationdata->idvlowerlimit;
				$actualresult["maximumidv"] =$result_jsonObject->data->quotationdata->idvupperlimit;
				$actualresult["netpremium"] =$result_jsonObject->data->NETPREM;
				$actualresult["totalpayable"] =$result_jsonObject->data->TOTALPAYABLE;
				$actualresult["servicetax"] =$result_jsonObject->data->TAX->total_prem;
				$actualresult["policy_img"] ="../assets/images/Tataagi.png";
				//print_r($actualresult);

				return $actualresult;	
	
        
	}
		
}
