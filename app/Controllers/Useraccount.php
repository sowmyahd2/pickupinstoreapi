<?php 

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\User_Model;
use App\Models\ProductModel;
use App\Models\DepartmentModel;
use App\Models\StoreModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;
helper('response');
helper('cityonnet');
class Useraccount extends BaseController
{
  use ResponseTrait;
  public function index()
  {


    $data = $this->request->getJSON();
    $id = $data->userid;
    $response = new stdClass();
    $usermodel = new User_Model();
    $response = new stdClass();
    $user = $usermodel->userdetail($id);
    $response->user=$user;
    return $this->response->setJSON(success($response, 200));
  }

  public function addadress(){
$data = $this->request->getJSON();
    
    $name= $data->Name;
    $city= $data->city;
    $state= $data->state;
    $address= $data->address;
    $pincode= $data->pincode;
    $landmark= $data->landmark;
    $number= $data->mobile;
    $userId=$data->Userid;
    $locality=$data->locality;
    $usermodel = new User_Model();
    $usermodel->insertaddress($name,$city,$state,$address,$pincode,$landmark,$number,$userId,$locality);
      return $this->response->setJSON(success($name, 200));

  }

  public function updateuserdetails(){
    $response = new stdClass();
    $data = $this->request->getJSON();
    $name= $data->username;
    $email= $data->email;
    $mobile= $data->mobile;
    $userId=$data->userid;
    $usermodel = new User_Model();
   $userdetail=$usermodel->updateuserdetails($name,$email,$mobile,$userId);
    return $this->response->setJSON(success($userdetail, 200));

  }


  public function updateuserpassword(){

    $response = new stdClass();
    $data = $this->request->getJSON();
    $password= $data->password;
    $pass =hash( "sha256",$password);
    $userId=$data->userid;
    $usermodel = new User_Model();
    $userdetail=$usermodel->updateuserpassword($pass,$userId);
    return $this->response->setJSON(success($userdetail, 200));

  }


  public function getuseraddress($id){

    $response = new stdClass();
    $usermodel = new User_Model();
    $response = new stdClass();
    $user = $usermodel->getuseraddress($id);
    $response->user=$user;
    return $this->response->setJSON(success($response, 200));
    
  }
    
  public function getdefaultuseraddress($id){
    
    $response = new stdClass();
    $usermodel = new User_Model();
    $response = new stdClass();
    $user = $usermodel->getdefaultuseraddress($id);
    $response->user=$user;
    return $this->response->setJSON(success($response, 200));
    
  }

  public  function updateshippindid($id){
    echo $id;
    $response = new stdClass();
    $usermodel = new User_Model();
    $user =$usermodel->updateshipaddress($id);
    $response->user=$id;
    return $this->response->setJSON(success($response, 200));

  } 


  public  function setdeafaultaddress($shipid,$userid){
    $response = new stdClass();
    $usermodel = new User_Model();
    $usermodel->setdeafaultaddress($shipid,$userid);
    $response->user=$id;
    return $this->response->setJSON(success($userid, 200));

  }  

  public function pickuporders($userid,$city){
    $catfilter=[];
    $catfilter1=[];
    $response = new stdClass();
    $usermodel = new User_Model();
    $city = $city== "mysuru" ? "" : $city."_";
    $pickuporders=$usermodel->pickuporders($userid,$city);
    foreach($pickuporders as $filter){
      $products =  $usermodel->pickupordersdetail($filter->PS_OrderId,$city);
      $productArray = [];
      foreach ($products as $product) {
        $item = array(
        "ProductId" => $product->ProductId,
        "ProductName" => $product->ProductName,
        "DepartmentId" => $product->DepartmentId,
        "MainCategoryId" => $product->MainCategoryId,
        "SubcategoryId" => $product->SubCategoryId,
        "BrandId" => $product->BrandId,
        "ProductCode" => $product->ProductCode,
        "orderdetailid"=>$product->PS_OrderDetailsId,
        "PickatStoreId"=>$product->PickatStoreId,
        "status"=>$product->status,
        "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
        "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
        "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
        "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($productArray, $item);
     }
    $catfilter=["psorderid"=>$filter->PS_OrderId,
    "orderdetail"=>$productArray];
    $catfilter1[]=$catfilter;

    }

 return $this->response->setJSON(success($catfilter1, 200));
  }   


public function homeorders($userid,$city){
    $catfilter=[];
    $catfilter1=[];
    $response = new stdClass();
    $usermodel = new User_Model();
    $city = $city== "mysuru" ? "" : $city."_";
    $pickuporders=$usermodel->homeorders($userid,$city);
    foreach($pickuporders as $filter){
      $products =  $usermodel->gethorders($filter->H_MainOrderId,$city);
      $productArray = [];
        foreach ($products as $product) {
          
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "orderdetailid"=>$product->H_OrderDetailid,
                "conorderId"=>$product->H_conorderId,
                "status"=>$product->OrderStatus,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)            
            );
            array_push($productArray, $item);
            }
          $catfilter=["mainorderid"=>$filter->H_MainOrderId,
             "orderdetail"=>$productArray];
                 $catfilter1[]=$catfilter;
           
        }

 return $this->response->setJSON(success($catfilter1, 200));

}
  public function getpsorderdetail(){
    $data = $this->request->getJSON();
    $usermodel = new User_Model();
    $city = $data->city;
    $orderid=$data->orderid;
    $city = $city== "mysuru" ? "" : $city."_";
    $usermodel = new User_Model();
    $response = new stdClass();
    $product =  $usermodel->getordersdetail($orderid,$city);
    $response->ProductId = $product->ProductId;
    $response->ProductName= $product->ProductName;
    $response->DepartmentId = $product->DepartmentId;
    $response->MainCategoryId = $product->MainCategoryId;
    $response->SubcategoryId = $product->SubCategoryId;
    $response->BrandId = $product->BrandId;
    $response->ProductCode = $product->ProductCode;
    $response->orderdetailid=$product->PS_OrderDetailsId;
    $response->PickatStoreId=$product->PickatStoreId;
    $response->status=$product->PS_Status;
    $response->statusId=$product->PS_OrderStatusId;
    $response->payableamount=$product->PayablePrice;
    $response->orderdate=$product->CreatedOn;
    $response->shopname=$product->ShopName;
    $response->recipeintname=$product->Name;
    $response->number=$product->mob;
    $response->address=$product->address;
    $response->Locality=$product->Locality;
    $response->LandMark=$product->LandMark;
    $response->mobile=$product->mobile;
    $response->shoplogo="https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/".$product->ShopLogo;
    $response->thumb_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1);
    $response->medium_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1);
    $response->large_image= productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1);
    $response->zoom_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1);
     return $this->response->setJSON(success($response, 200));
  }    

    public function gethorderdetail(){
    $data = $this->request->getJSON();
    $usermodel = new User_Model();
    $city = $data->city;
    $orderid=$data->orderid;
    $userid=$data->userid;
    $city ="mysuru" ? "" : $city."_";
    $usermodel = new User_Model();
    $response = new stdClass();
    $product =  $usermodel->gethordersdetail($orderid,$city);
    $review =  $usermodel->gethordersrating($userid,$product->ProductId);
    $response->review=$review;

    $response->ProductId = $product->ProductId;
    $response->ProductName= $product->ProductName;
    $response->DepartmentId = $product->DepartmentId;
    $response->MainCategoryId = $product->MainCategoryId;
    $response->SubcategoryId = $product->SubCategoryId;
    $response->BrandId = $product->BrandId;
    $response->ProductCode = $product->ProductCode;
    $response->ProductId = $product->ProductId;
    $response->orderdetailid=$product->H_OrderDetailid;
    $response->conorderid=$product->H_conorderId;
    $response->status=$product->OrderStatus;
    $response->payableamount=$product->SubTotal;
    $response->orderdate=$product->OrderCreatedOn;
    $response->shopname=$product->ShopName;
    $response->CustomerName=$product->CustomerName;
    $response->number=$product->mob;
    $response->address=$product->Adress;
    $response->caddress=$product->BillingAddress;
    $response->Locality=$product->Locality;
    $response->LandMark=$product->LandMark;
    $response->userlocation=$product->loc;
    $response->userlandmark=$product->lmark;
    $response->usercity=$product->uscity;
    $response->userpincode=$product->pin;
    $response->userstate=$product->state;
    $response->shoplogo="https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/".$product->ShopLogo;
    $response->thumb_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1);
    $response->medium_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1);
    $response->large_image= productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1);
    $response->zoom_image = productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1);
     return $this->response->setJSON(success($response, 200));
  }  

public function getpsorderstatus(){
    $response = new stdClass();
    $usermodel = new User_Model();
    $reasons=$usermodel->getpsorderstatus();
    $response->reasons=$reasons;
    return $this->response->setJSON(success($response, 200));
}

public function psordercancel($orderid,$pickid,$reasonid,$city="",$comments=""){
    $response = new stdClass();
    $usermodel = new User_Model();
    $city = $city== "mysuru" ? "" : $city."_";
    $reasons=$usermodel->psordercancel($orderid,$pickid,$reasonid,$comments,$city);
   return $this->response->setJSON(success($reasons, 200));
}

public function insertrating(){
  $data = $this->request->getJSON();
  $rating = $data->rating;
  $userid = $data->userid;
  $productid = $data->productid;
  $usermodel = new User_Model();
  $reasons=$usermodel->insertrating($rating,$userid,$productid);
  return $this->response->setJSON(success($reasons, 200));
}

 public function getreviews(){
  $data = $this->request->getJSON();
  $prodid = $data->prodid;
  $userid = $data->userid;
  $usermodel = new User_Model();
  $review=$usermodel->getreviews($prodid,$userid);
  $response = new stdClass(); 
  $response->reviewid=$review->ReviewId;
  $response->rating=$review->Ratings;
  $response->productname=$review->ProductName;
  $response->comments=$review->Comments;
  $response->thumb_image = productImageUrl($review->DepartmentId, $review->MainCategoryId, $review->SubCategoryId, 'thumbs', 1, $review->Image1);
  return $this->response->setJSON(success($response, 200));
 }

  public function writereviews(){
    $data = $this->request->getJSON();
    $prodid = $data->productid;
    $userid = $data->userid;
    $comment=$data->comment;
    $usermodel = new User_Model();
    $reasons=$usermodel->updatecomments($userid,$prodid,$comment);
    return $this->response->setJSON(success($userid, 200));
  }

 public function sendotp()
 {
   $usermodel = new User_Model();
   $data = $this->request->getJSON();
   $number = $data->number;
   $otp=$data->otp;
   $user=$usermodel->getuserdetail($number);
   $message="Your Cityonnet.com current transaction OTP is ".$otp;
  $this->sendsms($number,$message);
   return $this->response->setJSON(success($user, 200));
 }
 public function logindetail()
 {
   $usermodel = new User_Model();
   $data = $this->request->getJSON();
   $number = $data->number;
  
   $user=$usermodel->getuserdetail($number);
  
   return $this->response->setJSON(success($user, 200));
 }
 public function addtowishlist($pid,$userid,$city)
 {
   $city = $city== "mysuru" ? "" : $city."_";
   $response = new stdClass();
    $usermodel = new User_Model();
   $reasons=$usermodel->addtowishlist($userid,$pid,$city);
   return $this->response->setJSON(success($reasons, 200));

 }
 public function mywishlist($userid,$city)
 {
   $response = new stdClass();
    $usermodel = new User_Model();
     $city = $city== "mysuru" ? "" : $city."_";
   $products=$usermodel->getwishlist($userid,$city);

   $productArray=[];
   foreach ($products as  $p) {
    $item= array(
      "wishid"=>$p->WishListId,
      "productid"=>$p->ProductId,
      "productname"=>$p->ProductName,
      "Image1"=>productImageUrl($p->DepartmentId, $p->MainCategoryId, $p->SubCategoryId, 'medium', 1, $p->Image1));
     array_push($productArray, $item);
    
 }

 return $this->response->setJSON(success($productArray, 200));
 }
 public function deletewishproduct($wishid,$city)
 {
     $city = $city== "mysuru" ? "" : $city."_";
     $response = new stdClass();
     $usermodel = new User_Model();
     $usermodel->deletewishproduct($wishid,$city);
    
    return $this->response->setJSON(success($response, 200));
 }
 public function getarea($city)
 {
     $response = new stdClass();
     $usermodel = new User_Model();
   
     if($city=="mysuru"){
     $city="mysore";
     }
 $area=$usermodel->getareanames(ucfirst($city));
 return $this->response->setJSON(success($area, 200));
}
public function getstate(){
	$response = new stdClass();
     $usermodel = new User_Model();
  
 $area=$usermodel->getstates();
 return $this->response->setJSON(success($area, 200));
	
}
public function getcity($stateid){
	$response = new stdClass();
     $usermodel = new User_Model();
  
 $area=$usermodel->getcity($stateid);
 return $this->response->setJSON(success($area, 200));
}
public function getcityname($city){

  $response = new stdClass();
     $usermodel = new User_Model();
   $result = new stdClass();

 $area=$usermodel->getcityname($city);
 if($area->CityName=="Mysore"){
  $city="Mysuru";
 }
 else{
   $city=$area->CityName;
 }
 $result->CityName= $city;
 return $this->response->setJSON(success($result, 200));
}
public function getpopularcity(){
  $response = new stdClass();
     $usermodel = new User_Model();
  
 $area=$usermodel->getpopularcity();
 return $this->response->setJSON(success($area, 200));
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
