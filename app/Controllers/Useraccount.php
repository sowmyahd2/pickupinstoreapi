<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class Useraccount extends BaseController
{
	use ResponseTrait;
	public function index($id)
	{


    $response = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $user = $usermodel->userdetail($id);
    $response->user=$user;
    return $this->response->setJSON(success($response, 200));
	}
	public function register(){
          $data = $this->request->getJSON();
          $number = $data->number;
           $regModel = new UserModel();
       $user = $regModel->insuserdetails($number);
        return $this->response->setJSON(success($user, 200));
    }
     public function logindetail()
 {
   $usermodel = new UserModel();
   $data = $this->request->getJSON();
   $number = $data->number;
  
   $user=$usermodel->getuserdetail($number);
  
   return $this->response->setJSON(success($user, 200));
 }
    public function changepass($id,$pass)
	{


    $response = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $pass =hash( "sha256",$pass);
    $user = $usermodel->updateuserpassword($id,$pass);
    
    return $this->response->setJSON(success($user, 200));
	}
	public function getuseraddress($id){

        $response = new stdClass();
        $usermodel = new UserModel();
        $response = new stdClass();
        $user = $usermodel->getuseraddress($id);
       
        return $this->response->setJSON(success($user, 200));
            
        }
 
        public function sendotp()
        {
          
          $data = $this->request->getJSON();
          $number = $data->number;
          $otp = $data->otp;
   
         
          $message="Your Cityonnet.com current transaction OTP is ".$otp;
        //  $this->sendsms($number,$message);
          return $this->response->setJSON(success($otp, 200));
        }    
public function getlocality($pincode){
    $response = new stdClass();
    $usermodel = new UserModel();
    
    $user = $usermodel->getlocality($pincode);
    $response->city=$user->CityName;
    $response->state=$user->StateName;
    $response->location=$user->AreaName;
    return $this->response->setJSON(success($response, 200));

}

public function addbillingaddress($userId,$mobile,$name,$landmark,$pincode,$adress){
    $response = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $user  = $usermodel->getlocality($pincode);
    $city=$user->CityName;
    $state=$user->StateName;
    $location=$user->AreaName;
 
    $user = $usermodel->insertaddress($userId,$mobile,$name,$landmark,$pincode,$adress,$city,$state,$location);

    return $this->response->setJSON(success($response, 200));

}

public function pickorderdetail($city,$orderid){
    $result = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $usermodel = new UserModel();
    $response = new stdClass();
    $productArray = [];
    $response->userdetail  = $usermodel->userorderdetail($orderid,$city);
    $response->shopdetails = $usermodel->pickshopdetail($orderid,$city);
    $orderdetail = $usermodel->pickorderdetail($orderid,$city);
    $response->cancelreasons= $usermodel->pc_cancelreasons();
    foreach ($orderdetail as $product) {
        $item = array(
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "BrandId" => $product->BrandId,
            "ProductCode" => $product->ProductCode,
            "PS_OrderId"=>$product->PS_OrderId,
            "status"=>$product->PS_Status,
            "pmode"=>$product->PaymentType,
            "ticket"=>$product->Ticket,
            "pick_id"=>$product->PickatStoreId,
            "PayablePrice"=>$product->PayablePrice,
            "orderid"=>$product->PS_OrderDetailsId,
             "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
    }
    $response->orderdetail=$productArray;
    return $this->response->setJSON(success( $response , 200));
}
public function pickupinshoporders($userid,$cityName){

    $result = new stdClass();
    $city = $cityName == "mysore" ? "" : $cityName . "_";
    $usermodel = new UserModel();
    $response = new stdClass();
    $productArray = [];
    $orders  = $usermodel->pickupinshoporders($userid,$city);
    foreach ($orders as $product) {
        $item = array(
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "BrandId" => $product->BrandId,
            "ProductCode" => $product->ProductCode,
            "PS_OrderId"=>$product->PS_OrderId,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
    }
    return $this->response->setJSON(success($productArray , 200));
}
public function homedeliveryorders($city,$userid){
    $result = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $usermodel = new UserModel();
    $response = new stdClass();
    $productArray = [];
    $orders  = $usermodel->homedeliveryorders($userid,$city);
    foreach ($orders as $product) {
        if($product->OrderStatus==1){
            $status="Pending";
        }
        else{
            $status="Completed";
        }
        $item = array(
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "BrandId" => $product->BrandId,
            "ProductCode" => $product->ProductCode,
            "PS_OrderId"=>$product->H_MainOrderId,
            "status"=>$status,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
    }
    return $this->response->setJSON(success($productArray , 200));
}
public function homedeliveryordersdetail($city,$orderid){
    $result = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $usermodel = new UserModel();
    $response = new stdClass();
    $productArray = [];
    $response->userdetails  = $usermodel->userhomeorderdetail($orderid,$city);
    $response->shopdetail = $usermodel->homeshopdetail($orderid,$city);
    $orders  = $usermodel->homedeliveryordersdetail($orderid,$city);
     ;
    foreach ($orders as $product) {
        $item = array(
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "BrandId" => $product->BrandId,
            "ProductCode" => $product->ProductCode,
            "PS_OrderId"=>$product->H_MainOrderId,
            "H_conid"=>$product->H_conorderId,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
        $response->orderdetails  = $productArray;
    }
    return $this->response->setJSON(success($response , 200));
}
public function inactiveaddress($shipid){
    $result = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $user  = $usermodel->inactiveaddress($shipid);
    return $this->response->setJSON(success( $user , 200));
}
 public function cancelpickorder($city,$orderid,$reasonid,$comments=""){
    $result = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
  
    $user  = $usermodel->cancelpickorder($city,$orderid,$reasonid,$comments);
    return $this->response->setJSON(success( $user , 200));
 }
 public function addtowishlist($userid,$pid,$city){
    $result = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $user  = $usermodel->addtowishlist($userid,$pid,$city);
    return $this->response->setJSON(success( $user , 200));
 }
 public function wishlist($userid,$city){
    $result = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $user  = $usermodel->wishlist($userid,$city);
    $productArray = [];
    foreach ($user as $product) {
        $item = array(
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "BrandId" => $product->BrandId,
            "ProductCode" => $product->ProductCode,
            "WishListId"=>$product->WishListId,
             "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
        $response->products  = $productArray;
    }
    return $this->response->setJSON(success( $response , 200));
 }
 public function deletewhishlist($userid,$city,$pid){
    $result = new stdClass();
    $usermodel = new UserModel();
    $response = new stdClass();
    $city = $city == "mysore" ? "" : $city . "_";
    $user  = $usermodel->deletewishlist($userid,$pid,$city);
  
    
    return $this->response->setJSON(success( $user , 200));
 }
 public function sendsms($number,$message){
  
    $sender='CITYID';
    $service_url="http://alerts.solutionsinfini.com";
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


?>