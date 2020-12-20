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
		array_push($this->all_API_RESULT, $this->executeDigitAPI($_POST));
		// array_push($this->all_API_RESULT, $this->executeTATAgiAPI($_POST));
		//print(json_encode( $this->all_API_RESULT ));
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
							        "isVehicleNew": false,
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
        $result = curl_exec($ch);

        //echo "##############<pre>#######";
        //print_r(json_decode($result));
        //LIST_BIKE_ARY["name"] ="Digit";
        //LIST_BIKE_ARY["net_preimium"] ="1000.00";
        //print_r(LIST_BIKE_ARY);
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			echo "cURL error ({$errno}):\n {$error_message}";
		}

		
		$result_jsonObject = json_decode($result);
		//print_r($result_jsonObject);
		$actualresult = array();


		$actualresult["policy_name"] ="Digit";
		$actualresult["idv"] =$result_jsonObject->vehicle->vehicleIDV->idv;
		$actualresult["minidv"] =$result_jsonObject->vehicle->vehicleIDV->minimumIdv;
		$actualresult["maximumidv"] =$result_jsonObject->vehicle->vehicleIDV->maximumIdv;
		$actualresult["netpremium"] =$result_jsonObject->netPremium;
		$actualresult["servicetax"] =$result_jsonObject->serviceTax->totalTax;
		$actualresult["policy_img"] ="../assets/images/digit.png";

		
		return $actualresult;
	}
	
	public function executeTATAgiAPI($POST) {
		
		$api_digit_url ="https://pipuat.tataaiginsurance.in/tagichubms/ws/v1/ws/tran1/quotation";

		$QDATA="";
		$SRC="TB";
		$T="";
		$productid = 3122;

		$quote_type= "";
		$quotation_no="";
		$segment_code ="";
		$segment_name="";
		$cubic_capacity = "";
		$seating_capacity="";
		$sol_id="";
		$mobile_no="";
		$email_id="";
		$customer_type="";
		$product_code="";
		$product_name="";
		$subproduct_code="";
		$subproduct_name="";
		$covertype_code="";
		$covertype_name="";
		$btype_code="";
		$btype_name="";
		$risk_startdate="";
		$risk_enddate="";
		$purchase_date="";
		$veh_age="";  //Calculated age from Purchase date to Current date
		$manf_year="";
		$make_code="";
		$make_name="";
		$model_code="";
		$model_name="";
		$variant_code="";
		$model_parent_code="";
		//optional ("Fuel code to be as below 1 for Petrol 2 for Diesel 3 for CNG 4 for Battery 5 for External CNG 6 for External LPG 7 for Electricity 8 for Hydrogen 9 for LPG")
		$fuel_code="";
		$fuel_name="";
		$bodytype_id =""; // From Make Master (Pass BODYTYPECODE) based on selected vehicle Model
		$regno_1 = "";
		$regno_2 ="";
		$rto_loc_code =""; // Pass TXT_RTO_LOCATION_CODE from RTO Master(GC)or Registration number 1-Registration number 2 ( eg: MH-23)
		$rto_loc_name =""; // From RTO Master (Pass 'TXT_RTO_LOCATION_DESC') based on selected RTO
		$rtolocationgrpcd=""; //From RTO Master (Pass 'TXT_RTO_LOCATION_DESC') based on selected RTO
		$rto_zone="" ; //From RTO Master (Pass ' TXT_REGISTRATION_ZONE') based on selected RTO
		$pp_covertype_code=""; //Mandatory for Roll Over case and values Optional for New businessValue should be 1 or 2.1 for Package 2 for Liability
		$pp_covertype_name="";
		$pp_enddate ="";//Mandatory for Roll Over case and values Optional for New business From User Input - Format YYYYMMDD
		$pp_claim_yn="";
		$pp_prev_ncb="";
		$pp_curr_ncb="";
		$cust_pincode="";From User Input or From PINCODE Master
		$tenure="";
		$driver_declaration="ODD01";
		$ac_opted_in_pp= "Y";
		$C1="Y"; //IF  Package  Hard Coded(""opted"":""Y"")  IF Liability  Hard Coded(""opted"":""N"")
		$C2="Y";
		/* 
		*	If select driver_declaration is ODD01  Hard Coded(""opted"":""Y"") User selected values (""tenure"":""1"") 
		*	Else
		*   Hard Coded(""opted"":""N"")
		*   User selected values (""tenure"":"""")"
		*/
		$C3="Y";


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
							        "isVehicleNew": false,
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
        $result = curl_exec($ch);

        //echo "##############<pre>#######";
        print_r(json_decode($result));
        
	}
		
}
