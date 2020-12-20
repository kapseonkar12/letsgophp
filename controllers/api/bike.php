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
	

		
}
