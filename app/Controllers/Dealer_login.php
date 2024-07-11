<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Dealer_Model;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Dealer_login extends BaseController
{
    use ResponseTrait;
    function index()
    {        
      $response = new stdClass();
      $data = $this->request->getvar();
      $password= $data['password'];
      $pass =hash( "sha256",$password);
      $email=$data['email'];
      $response = new stdClass();
      $dealermodel = new Dealer_Model();
      $dealer= $dealermodel->getdealerdetail($email,$pass);
       $date = date('Y-m-d h:i:s');
     if($dealer)
     {
     	 $detail= $dealermodel->getdealerdetails($dealer->DealerId,$dealer->City);
       $a=array(
        "MobileVerification"=>1,
        "LastLogin"=>$date,
       );
       $update=$dealermodel->changedetail($dealer->DealerId,$a,$dealer->City);
     	 $response->detail=$detail;
       $response->shopurl=$dealer->shop_url;
      return $this->response->setJSON(success($response, 200));
     }
    else{
      return $this->response->setJSON(success("", 403, "invalid credentials"));
    }
  }
  function sendregsms($mobile,$cityname){
  $message="Congratulations.You have registered your virtual Store on Cityonnet.com.Enjoy an uninterrupted visibility to your customers";
    $this->send_sms($mobile,$message);
    return $this->response->setJSON(success("sucess", 400)); 
  }
  public function viewemail($dealerid,$city,$emailid){

 
  $email = \Config\Services::email();

  $secure=base64_encode($emailid);
$data['url']='https://www.cityonnet.com/index.php/dlr_register/accountverification/'.$secure.'/'.ucfirst($city);
           
         $dealermodel = new Dealer_Model();
      $dealer= $dealermodel->getdealerdetails($dealerid,$city);      

$data['dealer']=$dealer;
$data['cost']="";
$data['qrcode']="https://cityonnet-virtualmall.s3.ap-southeast-1.amazonaws.com/cityonnetuserappqrcode.png";

$email->setFrom('info@cityon.net', 'cityonnet');
$email->setTo($emailid);

$email->setSubject('Action Required :: Activate Your Acccount');
$template=view('EmailRegistration',$data);

$email->setMessage($template);
 if($email->send())
  {
     
      
   } 
    else

    {

    

    }

}
  function dealerdetail($dealerid,$city){
   if(strtolower($city)=="mysuru")
{
  $city="mysore";
}    
    $response = new stdClass();
    $dealermodel = new Dealer_Model();
    $detail= $dealermodel->getdealerdetails($dealerid, $city);
    $cityname = strtolower($city)== "mysore" ? "" : strtolower($city)."_";	
    $pickuporders=count($dealermodel->psordercount($dealerid,$cityname));
        $horder=count($dealermodel->hordercount($dealerid,$cityname));
   $response->pickordertotal= $pickuporders;
    $response->homeordertotal= $horder;
    $response->detail=$detail;
   
    return $this->response->setJSON(success($response, 200));
  }
  function detail(){

    $response = new stdClass();
    $dealermodel = new Dealer_Model();
  $data = $this->request->getJSON();
  $number = $data->number;
     $date = date('Y-m-d h:i:s');
  $dealer= $dealermodel->getdealerdetailnumber($number);
   if($dealer)
   {
      $detail= $dealermodel->getdealerdetails($dealer->DealerId,$dealer->City);
       $a=array(
        "MobileVerification"=>1,
        "LastLogin"=>$date,
       );
       $update=$dealermodel->changedetail($dealer->DealerId,$a,$dealer->City);
      $response->detail=$detail;
      $response->shopurl=$dealer->shop_url;
    return $this->response->setJSON(success($response, 200));
   }
  else{
    return $this->response->setJSON(success("invalid details", 400));	
  }

  }
  public function sendotp()
  {
    $response = new stdClass();
      $dealermodel = new Dealer_Model();
    $data = $this->request->getJSON();
    $number = $data->number;
    $otp=$data->otp;
    $dealer= $dealermodel->getdealerdetailnumber($number);
    
     if($dealer)
     {
     	 $detail= $dealermodel->getdealerdetails($dealer->DealerId,$dealer->City);
        $message="Your Cityonnet.com current transaction OTP is ".$otp;
$this->send_sms($number,$message);
     	 $response->detail=$detail;
      return $this->response->setJSON(success($response, 200));
     }
    else{
      return $this->response->setJSON(success("invalid details", 400));	
    }
   
   
  }
    public function sendotp1()
  {
    $response = new stdClass();
      $dealermodel = new Dealer_Model();
    $data = $this->request->getJSON();
    $number = $data->number;
    $otp=$data->otp;
  
        $message="Your Cityonnet.com current transaction OTP is ".$otp;
$this->send_sms($number,$message);
       $response->detail=$detail;
      return $this->response->setJSON(success($response, 200));
     
    
   
   
  }
   public function checkemail($email){
    $dealermodel = new Dealer_Model();
    $dealer= $dealermodel->checkemail($email);
    if($dealer)
    {
      
       $this->sendmail($email);
     return $this->response->setJSON(success("Otp Sent to your registered email id", 200));
    }
   else{
     return $this->response->setJSON(success("Invalid Email", 400));	
   }
   } 
   public function updateaddress(){
    $data = $this->request->getvar();
   
     $lattitude=$data['latitude'];
     $lang=$data['lang'];
     
     $cityname=$data['cityname'];
    $dealerid=$data['dealerid'];
    $adre=$data['address'];
   
        $data1=
        [
          "Adress"=>$adre,
          "Latitude"=> $lattitude,
          "Langtitude"=> $lang,
          

        ];
       
        $dealermodel = new Dealer_Model();
        return $this->response->setJSON(success($adre, 200));
    $dealerid= $dealermodel->updateaddress($data1,$cityname,$dealerid);
   


   }
   public function updatebankdetail(){
    $data = $this->request->getvar();
    $upi=$data['upinumber'];
     $bank=$data['bankname'];
    $cityname=$data['cityname'];
    $dealerid=$data['dealerid'];
    $name=$data['name'];
    $ifsc=$data['ifscnumber'];
    $acnum=$data['accnumber'];
    $bname=$data['branchname'];

        $data1=
        [
          "AccountHolderName"=>$name,
          "AccountNumber"=> $acnum,
          "BankName"=> $bank,
          "BranchName"=> $bname,
          "IFSC_Code"=> $ifsc,
          "UPI_number"=> $upi,

        ];
       
        $dealermodel = new Dealer_Model();
        
        
    $dealerid= $dealermodel->updatebankdetail($data1,$cityname,$dealerid);
    return $this->response->setJSON(success($dealerid, 200));
  
   

   }
   public function updateadress(){
    $data = $this->request->getvar();
    $cityname=$data['cityname'];
    $dealerid=$data['dealerid'];
    $pincode=$data['pincode'];
    $city=$data['city'];
    $state=$data['state'];
    $address=$data['address'];
    $Latitude=$data['latitude'];
    $lang=$data['lang'];
    $data =  
    [
      
       "PinCode"                  =>    $pincode,
        "CityName"                =>    $city,
       "StateName"                =>    $state,
        "Adress"                  =>   $address,
        "Latitude"                =>  $Latitude,
        "Langtitude"              =>   $lang,
       
 
    ];

    $dealermodel = new Dealer_Model();
          
         $dealerid= $dealermodel->updateadress($data,$cityname,$dealerid);
         
        return $this->response->setJSON(success($dealerid, 200));
   }
   public function dealer_registration(){

    $data = $this->request->getvar();
   $date = date('Y-m-d');
    $dealermodel = new Dealer_Model();

    $password    = $data['password'];
    $pass        = hash( "sha256",$password);
    $email       = $data['email'];
    $username    = $data['username'];
    $shopname    = $data['shopname'];
    $mobile      = $data['mobile'];
    $city        =$data['cityname'];
    $pincode     = $data['pincode'];
    $cityname    = trim(ucfirst($data['cityname']));
    $address     = $data['address'];
    $state       = "karnataka";
    $excutiveid  = $data['executiveid'];
    $dealerid    = $data['Dealerid'];
   
    $upinumber   =$data['upinumber'];
    $whasapp     =$data['Whatsappnumber'];
    $fbpage      = $data['Fbpage'];
    $gstin       = $data['gstinnumber'];
     $pannumber   = $data['pannumber'];
    // $lat       = $data['lat'];
     // $lang     = $data['lang'];
     $cityname=trim($data['cityname']);  
if(strtolower($city)=="mysuru"){
  $city="mysore";
  $cityname="mysore";
}
if(strtolower($city)=="bengaluru"){
  $city="bangalore";
  $cityname="bangalore";
}

      $citydata= $dealermodel->getcityid(ucfirst($city));

     
$statedata= $dealermodel->getstateid($citydata->StateId);

    $data =  
    [
        "FirstName"              =>  trim($username),
        "ShopName"               =>    trim($shopname),
        "Url_ShopName"           =>  trim($shopname),
        "EmailId"                =>    trim($email),
        "Password"              =>    $pass,
        "MobileNumber"          =>    $mobile,
       "PinCode"                =>    $pincode,
         "CityName"             =>    trim($city),
         "StateName"            =>    $statedata->StateName,
        "Adress"                =>   $address,
        "SelectStore"           =>  "single",
        "ShopCode"              =>  "C00310005253",
        "WhatsappBusinessNumber"=>   $whasapp,
        
         "FacebookId"           =>   $fbpage,
        "upi_number"             =>   $upinumber,
        "Activate"               =>   1,
        "Enable_AddtoCart"        =>  0,
        "VerticalId"              =>  1,
        "Lastupdated"				=>$date,
        "Latitude"=>"12.311827",
        "Langtitude"=>"76.652985",
 
    ];
 
    
        
          $detail= $dealermodel->addtodealerccount($data,$city,$dealerid,$email);
         
    
            
         
          

          $data1= $dealermodel->addtodealershoptime($city,$detail->DealerId);
        
  $data2=[
         'DealerId'=>$detail->DealerId,
        'upi_number' =>$upinumber,

          ];
          $detai= $dealermodel->addtodealerbankdetail($data2,$city,$detail->DealerId);
           
           $execeid= $dealermodel->getexeid(trim($excutiveid));
           if($execeid){
            $a=["ExecutiveId"=>$execeid->AccountId
        ];
      $dealerid= $dealermodel->changedetail($detail->DealerId,$a,$city);
           }
		  $cityid=$citydata->CityId;
$stateid=$citydata->StateId;
	 $stateid1= sprintf(".02d\n", $stateid);	
				$cityids= sprintf(".03d\n", $cityid);	
				
				
				$stateid1= sprintf("%'.04d", $stateid);
				$cityids= sprintf("%'.06d", $cityid);
				
				
				$aconcode="C".$stateid1.$cityids.$detail->DealerId;
				$a=["ShopCode"=>$aconcode,"qr_code"=>$detail->DealerId.".png"
				];
			$dealerid= $dealermodel->changedetail($detail->DealerId,$a,$city);
      
		 
          if($gstin!="" || $pannumber!="" ){
            $data=[
        "PAN_Number"=>$pannumber,
        "GSTRegId"=>$gstin,
       "Dealerid"=>$detail->DealerId,
       "StateId"=>$stateid
            ];
     $dealerid= $dealermodel->addtogstin($data,$city,$stateid,$detail->DealerId);
          }
		   $url="https://www.cityonnet.com/dlr_register_confirmation/index/".strtolower($city)."/".$detail->DealerId;
		$ct=strtolower($city);
					$shopName=str_replace(' ','-', trim($shopname));
				$shurl="https://www.cityonnet.com/".strtolower($city)."/".$shopName.'/'.$aconcode;
					$shoplisturl=$this->get_tiny_url($shurl);
					$shopurl=$this->get_tiny_url($url);
         $dealerid= $dealermodel->inserttodealermap($cityname,$mobile,$email,$pass,$detail->DealerId,$shoplisturl,$shopurl);
        $userdtata=$dealermodel->inserttouseraccount($email,$pass,$mobile,$address,$username,$cityid,$stateid);
           $message="Congratulations.You have registered your virtual Store on Cityonnet.com.Enjoy an uninterrupted visibility to your customers -Cityonnet.com";
          $this->sendsms($mobile,$message);
        
            	 $this->viewemail($detail->DealerId,strtolower($city),$email);
        return $this->response->setJSON(success($detail, 200));

   
    
  } 
  public function test(){
  	 $this->viewemail($detail->DealerId,"mysore","sowmyahd@cityon.net");
  }
    public function sendsms($number,$message){
  
$sender='CITYID';
  $service_url="http://alerts.solutionsinfini.com";
  //$sender='CITIES';
  $api_key="Ab429e3d02a1a3721410a2b187ea5da2a";


  $service="http://alerts.solutionsinfini.com/api/v3/index.php?method=sms.xml";
  $xmldata =  '<?xml version="1.0" encoding="UTF-8"?><api><sender>'.$sender.'</sender><message>'.$message.'</message><sms><to>91'.$number.'</to></sms></api>';
  $api_url =$service.'&api_key='.$api_key.'&format=xml&xml='.urlencode($xmldata);

  $response = file_get_contents($api_url);
  
  
  if($response)
  {
    return $response;
  }

 }
  	public function get_tiny_url($url)  {  
		$long_url = 'https://www.cityonnet.com/';
		$ch = curl_init();  
		$timeout = 5;  
		curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.trim($url));  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$data = curl_exec($ch);  
		curl_close($ch);  
		return $data;
	}
  public function dealer_registration1(){

    $data = $this->request->getvar();
   
  
       
        
    
  //  $city       = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
    $password    = $data['password'];
    $pass        = hash( "sha256",$password);
    $email       = $data['email'];
    $username    = $data['username'];
    $shopname    = $data['shopname'];
    $mobile      = $data['mobile'];
    $city        =$data['cityname'];
    $pincode     = $data['pincode'];
    $cityname    = $data['cityname'];
    $address     = $data['address'];
    $state       ="karnataka";
    $excutiveid  = $data['executiveid'];
    $dealerid    = $data['dealerid'];
    $googleid    =$data['googleid'];
    $upinumber   =$data['upinumber'];
    $whasapp     =$data['Whatsappnumber'];
    $fbpage      = $data['Fbpage'];

  $data =  
   [
       "FirstName"             =>     $username,
       "ShopName"              =>    $shopname,
       "EmailId"               =>    $email,
       "Password"              =>    $pass,
       "MobileNumber"          =>    $mobile,
      "PinCode"                =>    $pincode,
        "CityName"             =>    $city,
        "StateName"            =>    $state,
       "Adress"                =>   $address,
       "SelectStore"           =>  "single",
       "ShopCode"              =>  "C00310005253",
       "Url_ShopName"           => $shopname,
       "WhatsappBusinessNumber"=>   $whasapp,
        "GooglePlus"           =>   $googleid,
        "FacebookId"           =>   $fbpage,
      "upi_number"              =>   $upinumber,

   ];

         $dealermodel = new Dealer_Model();
         
        $dealerid= $dealermodel->addtodealerccount($data,$cityname,$dealerid);
       return $this->response->setJSON(success($dealerid, 200));
  

   
    
  }  
  public function savesubscriptiondetail(){

 $data = $this->request->getvar();
  $transnumber  = $data['tranactionnumber'];
  $date=new Date();
  $planid=$data['planid'];
  $CustomerId=$data['dealerid'];


  }
  public function getsubscription($cityname){

 $dealermodel = new Dealer_Model();
                          $detail= $dealermodel->getsubscriptionplans($cityname);
   return $this->response->setJSON(success($detail, 200));

  }
  public function getdealerdepartment($dealerId,$city){

    $response = new stdClass();
    $dealermodel = new Dealer_Model();
    $response = new stdClass();
    $departments = $dealermodel->getdealerdepartment($dealerId,$city);
        $departmentArray = [];
        foreach ($departments as $department) {
            array_push($departmentArray, array(
                "DepartmentId" => $department->DepartmentId,
                "DepartmentName" => $department->DepartmentName,
                "Icons" => departmentIcon($department->Icons),
                "VerticalId"=> $department->VerticalId,
                "DepartmentGroupId" => $department->DepartmentGroupId
            ));
        }
    return $this->response->setJSON(success($departmentArray, 200));
  }
  public function getmaincatbrands($mainid,$subid){
      $response = new stdClass();
    $dealermodel = new Dealer_Model();
    $response = new stdClass();
    $departments = $dealermodel->getcatbrands($mainid,$subid);
        $departmentArray = [];
        
        foreach ($departments as $department) {
          
          
         if($subid==0){
          array_push($departmentArray, array(
            "BrandId" => $department->BrandId,
            "BrandName" => $department->BrandName,
            "MainCategoryId" => $department->MainCategoryId,
            "SubCategoryId"=>$department->SubCategoryId,
            "MainCategoryName" => $department->MainCategoryName,
           
        ));
         }
         else{
          array_push($departmentArray, array(
            "BrandId" => $department->BrandId,
            "BrandName" => $department->BrandName,
            "MainCategoryId" => $department->MainCategoryId,
            "SubCategoryId"=>$department->SubCategoryId,
            "SubCategoryName"=>$department->SubCategoryName
           
        ));
         }
        }
    return $this->response->setJSON(success($departmentArray, 200));
  }
 public function getmaincategory($id){

    $response = new stdClass();
    $dealermodel = new Dealer_Model();
    $response = new stdClass();
    $departments = $dealermodel->maincategory($id);
        $departmentArray = [];
        foreach ($departments as $department) {
            array_push($departmentArray, array(
                "MainCategoryId" => $department->MainCategoryId,
                "MainCategoryName" => $department->MainCategoryName,
                "DepartmentId" => $department->DepartmentId,
               
            ));
        }
    return $this->response->setJSON(success($departmentArray, 200));
  }
   public function getsubcategory($id){

    $response = new stdClass();
    $dealermodel = new Dealer_Model();
    $response = new stdClass();
    $departments = $dealermodel->subcategory($id);
        $departmentArray = [];
        foreach ($departments as $department) {
            array_push($departmentArray, array(
                "subCategoryId" => $department->SubCategoryId,
                "subCategoryName" => $department->SubCategoryName,
                "MainCategoryId" => $department->MainCategoryId,
                "MainCategoryName" => $department->MainCategoryName,
            ));
        }
    return $this->response->setJSON(success($departmentArray, 200));
  }
  public function dealer_department(){

    $data = $this->request->getvar();
    $city=$data['city'];
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
    $department = $data['depart'];
    $dealerId='348';
    $dept=json_decode($department, true);
    if(isset($dept)){

      foreach ($dept as $departval) {
           $dealermodel->insertDealerdepartment($dealerId,$city,$departval['value']);
      
      }
      return $this->response->setJSON(success('inserted successfully', 200)); 

    }
   


  }
  public function dashboard($id){
$dealermodel = new Dealer_Model();
    $dashboard=$dealermodel->dashboard($id);
    $a=[];
   foreach($dashboard as $d ){
array_push($a,$d->count);
   }

   $a = implode(",", $a);
      
    return $this->response->setJSON(success($dashboard, 200));

  }
public function sendmail($to){
  $email = \Config\Services::email();
  //$to="sowmyahd@mysorecity.net";
  $subject="Password Reset Request – Cityonnet.com";
  
  $message='Dear dealer, You have requested for a password reset. Please click on the link below to reset (set a new password) your password.
  <a href=https://www.cityonnet.com/dealer_login/login/setpwd?mail='.$to.' >http://www.cityon.net/setpassword </a> 
  Please do not share your login and password details. If you have not requested for a password reset, then ignore this email. 
  Thanks,	  Team Cityon.net';
        $email->setTo($to);
        $email->setFrom('info@cityon.net', 'cityon.net');
        
        $email->setSubject($subject);
        $email->setMessage($message);

        if ($email->send()) 
		{
            echo 'Email successfully sent';
        } 
		else 
		{
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
}
public function send_sms($number,$message){

   
  $service_url="http://alerts.solutionsinfini.com";
  $sender='CITYID';
  $api_key="Ab429e3d02a1a3721410a2b187ea5da2a";
  $service="http://alerts.solutionsinfini.com/api/v3/index.php?method=sms.xml";
  $xmldata =  '<?xml version="1.0" encoding="UTF-8"?><api><sender>'.$sender.'</sender><message>'.$message.'</message><sms><to>91'.$number.'</to></sms></api>';
  $api_url =$service.'&api_key='.$api_key.'&format=xml&xml='.urlencode($xmldata);
  $response = file_get_contents($api_url);
  if($response)
   {
      return $response;
   }
  }
}