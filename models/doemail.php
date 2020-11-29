<?php
class Doemail extends CI_Model {
	
		public function __construct() {
	  	header('Access-Control-Allow-Origin: *');
	  	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	  	
	  	parent::__construct();
	  	 $this->load->library('email');
	  }

	function email_toagentregistration( $to , $agent_name ) {

		$this->email->from(EMAIL_FROM, EMAIL_FROM_NAME);
        $this->email->to($to); 

        $this->email->subject(EMAIL_SUBJECT_AGENT_REGI);
        $this->email->set_mailtype("html");
      echo  $message = 
				     '<!DOCTYPE html>
		    <html lang="en">

				    <head>
				      <meta charset="UTF-8">
				      <title>Test mail</title>
				      <style>
				        .wrapper {
				          padding: 20px;
				          color: #444;
				          font-size: 1.3em;
				        }
				        .a_cls {
				          background: #592f80;
				          text-decoration: none;
				          padding: 8px 15px;
				          border-radius: 5px;
				          color: #fff;
				        }
				        .bordercls {
							border: 1px solid #4a4a4a3d;
							padding: 10px;
							box-shadow: 5px 10px #90191933;
							margin :10px;
				        }
				      </style>
				    </head>

		    <body>
				    
				        <table class="wrapper bordercls" cellspacing="0"> 
				            <tr> 
				                <td>Dear , <b>'. strtoupper(strip_tags($agent_name))  .'</b> 
				                	<p> Congratulations! Your Agent registration is confirmed! </p>
				                	<p>
				                	We will verify your profile and KYC.	We will complete our verification and send you activation link on your registered email id "<b>'. strip_tags($to).'</b>".
				                	</p>

				                </td> 
				            </tr> 
				            <tr>
				            	<td><br/></td>
				            </tr>
				            <tr> 
				                <td>Thanks,<br/>
				                	Let Secure Team.<br/>
				                	<p>
				                <a class="a_cls" href="http://www.letssecure.com">www.letsecure.com</a>
				                </p>
				                </td> 
				            </tr> 
				        </table> 
				    </body> 
				    </html>'; 
		$this->email->message($message);  

        $this->email->send();

       echo $this->email->print_debugger();
	}


}
?>