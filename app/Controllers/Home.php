<?php namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		echo base_url();
		return view('welcome_message');
	}
public function send_sms(){

  $sender='CITYID';
 	$service_url="http://alerts.solutionsinfini.com";
 	$api_key="Ab429e3d02a1a3721410a2b187ea5da2a";

$number="9731137692";
$message="2345";
 	$service="http://alerts.solutionsinfini.com/api/v3/index.php?method=sms.xml";
 	$xmldata =  '<?xml version="1.0" encoding="UTF-8"?><api><sender>'.$sender.'</sender><message>'.$message.'</message><sms><to>91'.$number.'</to></sms></api>';
 	$api_url =$service.'&api_key='.$api_key.'&format=xml&xml='.urlencode($xmldata);

 	$response = file_get_contents($api_url);
 	if($response)
 	{
 		return $response;
 	}

	}
	//--------------------------------------------------------------------

}
