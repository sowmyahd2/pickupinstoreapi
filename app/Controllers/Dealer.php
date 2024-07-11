<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Dealer_Model;
use App\Models\StoreModel;
use CodeIgniter\API\ResponseTrait;

use stdClass;

helper('response');
helper('cityonnet');

class Dealer extends BaseController
{
     use ResponseTrait;
     public function testw(){

     $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://global.kaleyra.com/api/v4/?',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('method' => 'wa','api_key' => 'Aa37579e4577fc836a038ffee08b27f85
','body' => '{
	"to": 919731137692,
	"type": "text",
	"preview_url": true,
	"callback": "http://example.com/callback?",
	"text": {
		"message": "new tezt, https://www.google.com"
	}
}','from' => 'wa business number'),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;


     }
    public function addtopos()
      {
          $response      =      new stdClass();
          $dealermodel  =      new Dealer_Model();
          $result       =      new stdClass();
          $data         =      $this->request->getJSON();
          $username     =      $data->username;
          $usermail     =      $data->useremail;
          $usermobile   =      $data->usermobile;
          $dealerid     =      $data->dealerid;
          $city         =      $data->cityname;
          $detail       =      $dealermodel->getdealerdetails($dealerid,$city);
          $det          =      $dealermodel->dealerdetail($dealerid,$city);
          $cityid       =       $dealermodel->getcityid($city);
          $data1       =       $dealermodel->smsverify($dealerid,$cityid->CityId,$city);
            
          if($data1){
            $array=array(
            "DealerId"   =>  $dealerid,
            "CityId"     =>  $cityid->CityId,
            "SMS_Count " =>  $data1->SMS_Count+1,
            "Email_Count" => $data1->Email_Count+1,
            "Date"=>date("Y-m-d"),
            );

            $s=$dealermodel->updatepos($array,$dealerid,$cityid->CityId,$city);
             $id=$data1->DealerId;
          	
    				
          }
          else{

          	$array=array(
            "DealerId"    => $dealerid,
            "CityId"      => $cityid->CityId,
            "SMS_Count"   => 1,
            "Email_Count" => 1,
            "Date"        => date("Y-m-d"),
            );
            $id= $dealermodel->inserttopos($array,$city);
          	
          }

          $avalibility=$dealermodel->userdetail($usermail);
          if($avalibility){
           $restatus=1;
          }
          else{
          	$restatus=0;
          }
            $exists=$dealermodel->poscontact($usermobile);
            $ins=array("FirstName"=>$username,"LastName"=>'',"EmailId"=>$usermail,"PhoneNum"=>$usermobile,"CityId"=>$cityid->CityId,"StateId"=>$detail->StateId,"PinCode"=>$detail->PinCode,"Cityonnet_RegStatus"=>$restatus);
            if($exists){

              $id=$dealermodel->updatedealerpos($ins,$exists->PosContactId);
             $contactid=$exists->PosContactId;
               

            }
            else{
$exists=$dealermodel->insertdealerpos($ins);
               $contactid=$exists->PosContactId;
             
				//	$this->db_deals->insert('poscontacts_new', $ins);
            }
                   
            $arr=array('DealerId'=>$dealerid,"Deals_pos_RegId"=>0,'PosContactId'=>$contactid,'LastPurchasedOn'=>date('Y-m-d H:i:s'),'Num_of_Visits'=>1);
            $dealermodel->insert_pos_dealer_cust_map($arr,$contactid,$city);
            $dataarray=array("DealerId"=>$dealerid,
              
                "POSContactId"=>$contactid,
                "Date"=>date('Y-m-d'),
                "SMS_Status"=>1,
                
                "SentDateTime"=>date('Y-m-d H:i:s'));
            $s=$dealermodel->insert_pos_user_cust_map($dataarray,$city);
           
          $message="Hi ".$username."Thank you for showing interest to shop with ".$detail->ShopName." at Cityonnet.com. You can also check our entire range of products at ".$det->shop_url;
         
          
        $this->send_sms($usermobile,$message);
         //$this->whatsapp($usermobile,$username);
       return $this->response->setJSON(success("success" , 200));  
      }
public function changepassword($dealerid,$cityname,$password){
  $response     =      new stdClass();
  $dealermodel  =      new Dealer_Model();
  $a=[
  "Password"=>hash( "sha256",$password)
];
  $dealer       =      $dealermodel->changedealermap($dealerid,$a,$cityname);
  return $this->response->setJSON(success($dealer, 200));
}
public function updatedealerprofiledetail(){
  $data = $this->request->getJSON();

$array = array();
foreach ($data[0] as $key => $value) {
  $array[$key] ??= $value;
}
var_dump($array);
  

var_dump($array);
      if (isset($data[1])) {
  $dealerid = $data[1]->id ?? null;
  $cityName = $data[1]->cityName ?? null;
     }
        $response     =      new stdClass();
       $dealermodel  =      new Dealer_Model();

  $dealer       =      $dealermodel->changedetail($dealerid,$array,$cityName);
  return $this->response->setJSON(success($dealer, 200));
}
public function updatelogodetail($dealerid,$cityname){
  $response     =      new stdClass();
  $dealermodel  =      new Dealer_Model();
   $logopath="uploaded_files/".strtolower($cityname)."/".$dealerid.".jpg";
  
    $a=[
  "ShopLogo"=>$logopath,
];

  $dealer       =      $dealermodel->changedetail($dealerid,$a,$cityname);
  return $this->response->setJSON(success($dealer, 200));
}
public function changenumber($dealerid,$cityname,$number){
  $response     =      new stdClass();
  $dealermodel  =      new Dealer_Model();
    $a=[
  "MobileNumber"=>$number,
];

  $dealer       =      $dealermodel->changedealermap($dealerid, $a,$cityname);
  return $this->response->setJSON(success($dealer, 200));
}
public function updatepromotion()
{
	    $response     =      new stdClass();
	      $dealermodel  =      new Dealer_Model();
		    $data         =      $this->request->getJSON();
		    $dealerid     =      $data->dealerid;
		    $category     =      $data->promo;
			
		    $dealer       =      $dealermodel->updateaddress($category,$data->cityname,$dealerid);
	      return $this->response->setJSON(success($dealer, 200));

}
public function changemail($dealerid,$cityname,$mail){
  $response     =      new stdClass();
  $dealermodel  =      new Dealer_Model();
      $a=[
  "EmailId"=>$mail,
];
  $a1=[
  "Emailid"=>$mail,
];
  $dealer       =      $dealermodel->changeemail($dealerid,$a,$a1,$cityname);
  return $this->response->setJSON(success($dealer, 200));
}
     public function getdealerdata($cityname,$dealerid){
      if($cityname=="Mysuru"){
        $cityname="Mysore";
      }
      $response     =      new stdClass();
      $dealermodel  =      new Dealer_Model();
       $dealer       =      $dealermodel->getdealerdetails($dealerid,$cityname);
        $gstin       =      $dealermodel->getgstindetail($cityname,$dealerid);
        $response ->detail=$dealer;
        $response ->gstin= $gstin;
      return $this->response->setJSON(success($response, 200));
     } 
     public function getdealershoptime($dealerid,$cityname){
     
      $response     =      new stdClass();
      $dealermodel  =      new Dealer_Model();
       $dealer       =      $dealermodel->getdealershoptime($dealerid,$cityname);
      return $this->response->setJSON(success($dealer, 200));
     } 
      public function getbrands($id)
      {

        $response     =      new stdClass();
        $dealermodel  =      new Dealer_Model();
         $dealer       =      $dealermodel->getbrands($id);
        return $this->response->setJSON(success($dealer, 200));
       }
      public function addcatergories()
      {

	      $response     =      new stdClass();
	      $dealermodel  =      new Dealer_Model();
		    $data         =      $this->request->getJSON();
		    $dealerid     =      $data->dealerid;
		    $category     =      $data->category;
		    $dealer       =      $dealermodel->addcategories($category,$dealerid,$data->cityname);
	      return $this->response->setJSON(success($category, 200));
      }
   
      public function editedproducts($id,$cityName){
        $city = strtolower($cityName) == "mysore" ? "" : strtolower($cityName) . "_";
        $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
          $products = $dealermodel->editedproducts($id,$city);
          $newproducts=[];
          foreach ($products as $product) {
           $item = array(
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "Lastupdate"=>$product->LastUpdate,
               "MRP"=>$product->MRP,
                "dpid"=>$product->DealerPriceId,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
       }
          return $this->response->setJSON(success($newproducts, 200));
      }
      public function brandedsubcatproducts($id,$cityName,$subid,$brandid){
        $city = strtolower($cityName) == "mysore" ? "" : strtolower($cityName) . "_";
        $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
          $products = $dealermodel->storefrontsubproducts($id,$subid,$brandid,$city);
          $newproducts=[];
          foreach ($products as $product) {
           $item = array(
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "BrandName"=>$product->BrandName,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
       }
          return $this->response->setJSON(success($newproducts, 200));
      } 
       public function filtermainproducts($term,$id,$city,$subid,$brandid){
         $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
          $productarray = $dealermodel->getproducts($term);

 $newproducts=[];         foreach ($productarray as $p) {
            $product = $dealermodel->storefrontfiltermainproducts($id,$subid,$brandid,$city,$p->ProductId);
          

 if($product){
        
           $item = array(
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "BrandName"=>$product->BrandName,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
           }          
         
       }
          return $this->response->setJSON(success($newproducts, 200));
      } 
      public function filtersubproducts($term,$id,$city,$subid,$brandid){
         $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
          $productarray = $dealermodel->getproducts($term);

 $newproducts=[];         foreach ($productarray as $p) {
            $product = $dealermodel->storefrontfiltersubproducts($id,$subid,$brandid,$city,$p->ProductId);
          

 if($product){
        
           $item = array(
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "BrandName"=>$product->BrandName,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
           }          
         
       }
          return $this->response->setJSON(success($newproducts, 200));
      } 
      public function brandedmaincatproducts($id,$cityName,$mainid,$brandid){
        $city = strtolower($cityName) == "mysore" ? "" : strtolower($cityName) . "_";
        $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
          $products = $dealermodel->storefrontmainproducts($id,$mainid,$brandid,$city);
          $newproducts=[];
          foreach ($products as $product) {
           $item = array(
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
       }
          return $this->response->setJSON(success($newproducts, 200));
      }
      public function shopnewarravail($id,$cityName){
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }
         $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        
          $obj = new stdClass();
          $filter = new stdClass();
          $newarraival  =  $dealermodel->shopnewarravail($id, $city);
          $newproducts=[];
          foreach ($newarraival as $product) {
           $item = array(
            "dpid"                =>$product->DealerPriceId,
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "MRP" => $product->MRP,
               "SellingPrice" => $product->SellingPrice,
               "StorePrice" => $product->StorePrice,
               "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
       }

          $response->newarrival = $newproducts;
       
       //   $response->filter = $filter;
          return $this->response->setJSON(success($response, 200));
      }
         public function shopnewarravails($id,$cityName){
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }
         $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        
          $obj = new stdClass();
          $filter = new stdClass();
          $newarraival  =  $dealermodel->shopnewarravails($id, $city);
          $newproducts=[];
          foreach ($newarraival as $product) {
           $item = array(
            
             "dpid"        =>$product->DealerPriceId,
               "ProductId" => $product->ProductId,
               "ProductName" => $product->ProductName,
               "DepartmentId" => $product->DepartmentId,
               "MainCategoryId" => $product->MainCategoryId,
               "SubcategoryId" => $product->SubCategoryId,
               "ProductCode" => $product->ProductCode,
               "MRP" => $product->MRP,
               "SellingPrice" => $product->SellingPrice,
               "StorePrice" => $product->StorePrice,
               "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
               "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
               "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
               "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
           );
           array_push($newproducts, $item);
       }

          $response->newarrival = $newproducts;
       
       //   $response->filter = $filter;
          return $this->response->setJSON(success($response, 200));
      }
        public function storedealProducts($id, $cityName)
      {
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }
          $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        //  $response->detail = $storeModel->getStoreDetail($id, $city);
        
          $obj = new stdClass();
          $filter = new stdClass();
         
       $deals  =  $dealermodel->shopofferproducts($id, $city);
       $dealproducts=[];
       foreach ($deals as $product) {
        $item = array(
           "dpid"                =>$product->DealerPriceId,
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "ProductCode" => $product->ProductCode,
            "MRP" => $product->MRP,
            "SellingPrice" => $product->SellingPrice,
            "StorePrice" => $product->StorePrice,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($dealproducts, $item);
    }
       
     
          $response->offerproducts = $dealproducts;
      
          return $this->response->setJSON(success($response, 200));
      }
      public function storedealProduct($id, $cityName)
      {
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }
          $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        //  $response->detail = $storeModel->getStoreDetail($id, $city);
        
          $obj = new stdClass();
          $filter = new stdClass();
         
       $deals  =  $dealermodel->shopofferproduct($id, $city);
       $dealproducts=[];
       foreach ($deals as $product) {
        $item = array(
           "dpid"                =>$product->DealerPriceId,
            "ProductId" => $product->ProductId,
            "ProductName" => $product->ProductName,
            "DepartmentId" => $product->DepartmentId,
            "MainCategoryId" => $product->MainCategoryId,
            "SubcategoryId" => $product->SubCategoryId,
            "ProductCode" => $product->ProductCode,
            "MRP" => $product->MRP,
            "SellingPrice" => $product->SellingPrice,
            "StorePrice" => $product->StorePrice,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
        );
        array_push($dealproducts, $item);
    }
       
     
          $response->offerproducts = $dealproducts;
      
          return $this->response->setJSON(success($response, 200));
      }
      public function storeProducts($id, $cityName)
      {
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }

          $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        //  $response->detail = $storeModel->getStoreDetail($id, $city);
          $categories = $storeModel->storefrontcategory($id, $city);
          $obj = new stdClass();
          $filter = new stdClass();
         
      
          foreach ($categories as $category) {
              $products = $storeModel->storefrontcategoryProduct($id, $category->MainCategoryId, $city);
              $MainCategoryName = $category->MainCategoryName.'_'.$category->MainCategoryId;
              $productArray = [];
              foreach ($products as $product) {
                  $item = array(
                     "dpid"                =>$product->DealerPriceId,
                      "ProductId" => $product->ProductId,
                      "ProductName" => $product->ProductName,
                      "DepartmentId" => $product->DepartmentId,
                      "MainCategoryId" => $product->MainCategoryId,
                      "SubcategoryId" => $product->SubCategoryId,
                      "ProductCode" => $product->ProductCode,
                      "MRP" => $product->MRP,
                      "SellingPrice" => $product->SellingPrice,
                      "StorePrice" => $product->StorePrice,
                      "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                      "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                      "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                      "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                  );
                  array_push($productArray, $item);
              }
              $obj->$MainCategoryName = $productArray;
          }
       //   $departments = $storeModel->storefrontDepartment($id, $city);
        //  foreach ($departments as $d) {
       //       $category = $storeModel->storefrontcategorybyDepartmentId($d->DepartmentId, $city, $id);
       //       $departmentName = $d->DepartmentName;
       //       $filter->$departmentName = $category;
      //    }
          $response->products = $obj;
     
       
       //   $response->filter = $filter;
          return $this->response->setJSON(success($response, 200));
      }
        public function storeProduct($id, $cityName,$catid)
      {
        $cityName=strtolower($cityName);
        if($cityName=="mysore"){
          $cityName="mysuru";
        }

          $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $storeModel = new StoreModel();
          $dealermodel  =  new Dealer_Model();
          $response = new stdClass();
        //  $response->detail = $storeModel->getStoreDetail($id, $city);
       
          $obj = new stdClass();
          $filter = new stdClass();
         
      
              $products = $storeModel->storecategoryProducts($id,$catid, $city);
             
              $productArray = [];
              foreach ($products as $product) {
                  $item = array(
                     "dpid"                =>$product->DealerPriceId,
                      "ProductId" => $product->ProductId,
                      "ProductName" => $product->ProductName,
                      "DepartmentId" => $product->DepartmentId,
                      "MainCategoryId" => $product->MainCategoryId,
                      "SubcategoryId" => $product->SubCategoryId,
                      "ProductCode" => $product->ProductCode,
                      "MRP" => $product->MRP,
                      "SellingPrice" => $product->SellingPrice,
                      "StorePrice" => $product->StorePrice,
                      "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                      "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                      "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                      "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                  );
                  array_push($productArray, $item);
              }
             
          
       //   $departments = $storeModel->storefrontDepartment($id, $city);
        //  foreach ($departments as $d) {
       //       $category = $storeModel->storefrontcategorybyDepartmentId($d->DepartmentId, $city, $id);
       //       $departmentName = $d->DepartmentName;
       //       $filter->$departmentName = $category;
      //    }
          $response->products = $productArray;
     
       
       //   $response->filter = $filter;
          return $this->response->setJSON(success($response, 200));
      }
    public function gstslab()
     {

         $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
         $slabs         =      $dealermodel->getgstslab();
         return $this->response->setJSON(success($slabs, 200));
     }
     public function getdealer($cityname,$search){
       if($cityname=="Mysuru"){
         $cityname="Mysore";
       }
      $response      =      new stdClass();
      $dealermodel   =      new Dealer_Model();
      $slabs         =      $dealermodel->getdealer($search,$cityname);
      return $this->response->setJSON(success($slabs, 200));
     }
    public function getaddress()
     {

	     $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
	     $data          =      $this->request->getJSON();
	     $detail        =      $dealermodel->getaddress($data->pincode);
	     return $this->response->setJSON(success($detail, 200));
     }
    public function emailvalidation()
     {

         $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
	      $data         =      $this->request->getJSON();
	     //$detail=   $dealermodel->emailverify($data->email,$data->cityname);
	     
	      return $this->response->setJSON(success($data->email, 200));
     }
       
     public function getproductsize($dpid,$mainid,$subid)
     {
         $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
	       $data          =      $this->request->getJSON();

	       

	     $dealer =   $dealermodel->get_product_size($dpid,$mainid,$subid);

	     return $this->response->setJSON(success($dealer, 200));
     }
     public function adddealerproducts(){
      $response      =      new stdClass();
      $dealermodel   =      new Dealer_Model();
      $data          =      $this->request->getJSON();
      $city          =      $data->cityname;
      $dealerid      =      $data->dealerId;
      $mrp           =      $data->mrp;
      $qty           =      $data->qty;
      $shiping       =      $data->Shipping;
      $size          =      $data->size;
      $productid     =      $data->productid;
      $dealer        =     $dealermodel->addbrandedproduct($size,$city,$dealerid,$qty,$mrp,$shiping,$productid);
      return $this->response->setJSON(success($data->cityname, 200));
     }
    public function addproduct()
     {
         $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
	       $data          =      $this->request->getJSON();
        $extype =             $data->checked;
	       $slabs         =      $data->gst;
	       $expiredate	    =      date("Y-m-d",strtotime($data->expirydate));
       //  $qty=$data->sizeqty;
       if($extype=="no"){
        $expiredate="0000-00-00";
      }
      else{
        $expiredate=$expiredate;
      }
         
          $sku="";
          if(count($data->sizemrp)>0){
         for ($i=0; $i < count($data->sizemrp); $i++) { 
          $values=explode('_', $data->sizemrp[$i]);
          $qty=explode('_', $data->sizeqty[$i]);

          $mrp=$values[1];
          $specid=$values[0];
          $srp=$mrp-((($data->srp)/100)*$mrp);
          $op=$mrp-((($data->op)/100)*$mrp);
          $productid=$data->productid;
          $dealerid=$data->dealerid;
          $city=$data->cityname;
          $products=array
          (
             "DealerId"            =>  $dealerid,
             "ProductId"           =>  $productid,
             "MRP"                 =>  $mrp,
             "SellingPrice"        =>  $mrp-$data->srp,
             "QuantityAvailable"   =>  $qty[1],
             "StorePrice"          =>  $mrp-$data->op,
             "SKU"                 =>  $data->sku,
            "Origin"               =>  $data->origin,
            "ExpiryDate"          =>   $expiredate,
 
            );
          
 
            $dpid=   $dealermodel->addproduct($products,$city);
        $dealermodel->addtogstmap($data->cityname,$slabs, $dpid);
$spec=array(
"DealerPriceId"=> $dpid,
"SpecificationId"=>61,
"SpecificationName"=>"Available Sizes",
"SpecificationValue"=>$specid

  
);

$dealer =   $dealermodel->addproductpecification($spec,$city,$dpid,$specid);  
  
      }
  
         if(count($data->sizemrp)>0){
          return $this->response->setJSON(success("", 403, $city));
         
         }
         else{ 
          return $this->response->setJSON(success("", 403, count($data->sizemrp)));
       
         }
        }
         else{
     
          $products=array
          (
             "DealerId"            =>   $data->dealerid,
             "ProductId"           =>   $data->productid,
             "MRP"                 =>   $data->mrp,
             "SellingPrice"        =>   $data->op,
             "QuantityAvailable"   =>   $data->qty,
             "StorePrice"          =>   $data->srp,
             "SKU"                 =>   $data->sku,
             "ExpiryDate"          =>   $expiredate,
            "Origin"               =>    $data->origin
  
            );
        
  
        $dpid =   $dealermodel->addproduct($products,$data->cityname);
        $dealermodel->addtogstmap($data->cityname,$slabs, $dpid);
        return $this->response->setJSON(success("sucess", 200));
        }
         

	     
     }
     public function updatedate($dealerid,$type,$dayid,$cityname,$t){
      $response      =      new stdClass();
      $dealermodel   =      new Dealer_Model();
  $dealer =   $dealermodel->updatedate($dealerid,$type,$dayid,$cityname,$t);
    $detail   =    $dealermodel->getdealershoptime($dealerid,$cityname);
      return $this->response->setJSON(success($detail, 200));
     }
     public function updateproduct()
     {
         $response      =      new stdClass();
         $dealermodel   =      new Dealer_Model();
	       $data          =      $this->request->getJSON();
	       $slabs       =      $data->gst;
	       $expiredate	  =      date("Y-m-d",strtotime($data->expirydate));

         $extype =             $data->checked;
	      
       //  $qty=$data->sizeqty;
       if($extype=="no"){
        $expiredate="0000-00-00";
      }
      else{
        $expiredate=$expiredate;
      }
       
 
          $products=array
          (
             "DealerId"            =>   $data->dealerid,
              "MRP"                 =>   $data->mrp,
             "SellingPrice"        =>  $data->op,
             "QuantityAvailable"   =>   $data->qty,
             "StorePrice"          =>   $data->srp,
             "SKU"                 =>   $data->sku,
             "ExpiryDate"          =>   $expiredate,
              "Origin"             =>    $data->origin
  
            );
          
  
        $dealer =   $dealermodel->updateproduct($products,$data->cityname,$data->dpid);
        $gstid= $dealermodel->addtogstmap($data->cityname,$slabs,$data->dpid);
       
 
        return $this->response->setJSON(success($gstid, 200));
        
         

	     
     }
     public function getproductdetail($dpid,$cityname){
      $response          =     new stdClass();
      $dealermodel       =     new Dealer_Model();
      $product =   $dealermodel->getproductdetail($dpid,$cityname);
      $products=array
      (
        "dpid"                =>$product->DealerPriceId,
         "DealerId"            =>   $product->DealerId,
         "ProductId"           =>   $product->ProductId,
         "MRP"                 =>   $product->MRP,
         "SellingPrice"        =>   $product->SellingPrice,
         "QuantityAvailable"   =>   $product->QuantityAvailable,
         "StorePrice"          =>   $product->StorePrice,
         "SKU"                 =>   $product->SKU,
         "ExpiryDate"          =>   $product->ExpiryDate,
         "Origin"              =>   $product->Origin,
         "brandname"           =>   $product->BrandName,
         "productname"         =>   $product->ProductName,
         "productcode"         =>   $product->ProductCode,
         "specvalue"            =>  $product->SpecificationValue,
          "thumb_image"        =>   productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
         "medium_image"        =>   productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
         "large_image"         =>   productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
         "zoom_image"          =>   productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
      );
      $response->detail=$products;
      return $this->response->setJSON(success($response, 200)); 
     }
     public function getproductpricedetail($dealerid,$cityname,$pid,$dpid)
     {
      $response          =     new stdClass();
      $dealermodel       =     new Dealer_Model();
      $data =   $dealermodel->getproductprice($dealerid,$cityname,$pid,$dpid);
      $products=array
      (
        "dpid"                =>$data->DealerPriceId,
         "DealerId"            =>   $data->DealerId,
         "ProductId"           =>   $pid,
         "MRP"                 =>   $data->MRP,
         "SellingPrice"        =>   $data->SellingPrice,
         "QuantityAvailable"   =>   $data->QuantityAvailable,
         "StorePrice"          =>   $data->StorePrice,
         "SKU"                 =>   $data->SKU,
         "ExpiryDate"          =>   $data->ExpiryDate,
         "Origin"              =>    $data->Origin,
         "gst"              =>    $data->Percentage,
"specification"=>$data->SpecificationValue,
      );
      return $this->response->setJSON(success($products, 200));
     }
     public function addnonbranded()
     {
        
         $response          =     new stdClass();
         $dealermodel       =     new Dealer_Model();
	       $data              =     $this->request->getJSON();
	       $spec              =     $data->specification;
         $pname             =     $data->pname;
         $pcode             =     $data->pcode;
         $brandname         =     $data->brandname;
         $expiredate        =     date("Y-m-d",strtotime($data->expiredate));
         $dpartid           =     $data->dpid;
         $maincatid         =     $data->mainid;
         $subid             =     $data->subid;
         $extype            =     $data->checked;
          $slabs         =      $data->gst;

        if($extype=="no"){
          $expiredate="0000-00-00";
        }
        else{
          $expiredate=$expiredate;
        }
          $brandid =   $dealermodel->addbrand($brandname);
          $pid =   $dealermodel->insertproduct($brandid,$pname,$pcode,$dpartid,$maincatid,$subid);
   

           $products=array
           (
              "DealerId"            =>   $data->dealerid,
              "ProductId"           =>   $pid,
              "MRP"                 =>   $data->mrp,
              "SellingPrice"        =>  $data->srp,
              "QuantityAvailable"   =>   $data->qty,
              "StorePrice"          =>   $data->op,
              "SKU"                 =>   $data->sku,
              "ExpiryDate"          =>   $expiredate,
              "Origin"              =>    "India",

           );
          $dpid =   $dealermodel->addproduct($products,$data->cityname);
          
         $dealermodel->addtogstmap($data->cityname,$slabs, $dpid);
           if($pid){
          foreach ($spec as $key => $value)
         {

          
         $dealer   =   $dealermodel->insertproductspection($key,$value,$pid);
       }
	   }
     return $this->response->setJSON(success($pid , 200));
     }

     public function addsubscription($dealerid,$planid,$city,$transnumber)
     {
       $response              =     new stdClass();
       $dealermodel           =     new Dealer_Model();
	    $status=1;
	     $plan                  =     $dealermodel->getsubscriptionplandetail($city,$planid);
      
       $detail              =     $dealermodel->getdealerdetails($dealerid,$city);
	     $ServiceTaxAmount      =     ($plan->ServiceTax/100)*$plan->SubscriptionCost;
       $TotalPayingAmount     =     (($plan->ServiceTax/100)*$plan->SubscriptionCost)+$plan->SubscriptionCost;
       $start_date            =     date("Y-m-d");
	     $month                 =     $plan->ValidMonths;
		   $end_date              =     date( "Y-m-d", strtotime( "$start_date + $month months" ) );

         $log_arrsub=
          [  		
         'CustomerId'		             =>    $dealerid,
	    	 'CustomerTypeId'	           =>  $plan->CustomerTypeId,
			   'CityId'		                 =>  $plan->City,
			   'SubscriptionPlanId'        =>  $plan->SubscriptionPlanId,
		     'SubscriptionStartDate'     =>  $start_date,
		     'SubscriptionExpiryDate'    =>  $end_date, 
		     'TotalPaidMonths'           =>  $month, 
		     'TotalClearedMonths'        =>  $month,
		     'BalanceSubscriptionMonths' =>  0, 
		     'LastUpdatedOn'             =>  $start_date,
		     'PaymentStatus'             =>  3
		  ];
		      $planid       =$dealermodel->insertintosubscriptionplan($city,$log_arrsub);
         $log_arr=	
         [	
          'CustomerId'				       =>  $dealerid,
			    'CustomerTypeId'			     =>  $plan->CustomerTypeId,
			    'PaymentMethod'			       =>  3,
			    'SubscriptionPlanId'		   =>  $plan->SubscriptionPlanId,
			    'SubscriptionDetailsId'	   =>  $planid,
			    'PlanType'				      	 =>  $plan->SubscriptionPlanType,
			    'Payment_Of_Months'	    	 =>  $plan->ValidMonths,
			    'Part_SubscriptionCost'	   =>  $plan->SubscriptionCost ,
			    'ServiceTaxStatus'			   =>  1,
			    'ServiceTaxPercent'		     =>  $plan->ServiceTax,
			    'ServiceTaxAmount'		  	 =>  $ServiceTaxAmount,
			    'TDS_Deduction'			       =>   0,
			    'TotalPayableAmount'	   	 =>  $TotalPayingAmount,
			    'TDS_PreDeductionAmount'	 =>  0,
			    'ActualPaidAmount'		     =>  $TotalPayingAmount,
			    'BillingAddress'			     =>  $detail->Adress,
			    'PaymnetIssuedDateTime'    =>  $start_date,
			    'TransactionDate'			     =>  $start_date,
			    'Transaction_No'			     =>  $transnumber,
			    'TrasactionStatus'			   =>  $status,
			    'ExecutiveId'				       =>  1,
			    'ChequeClearenceStatusId'  =>  1
		 ];
			
          $planid=$dealermodel->insertintosubscriptionpayment($city,$log_arr);
        return $this->response->setJSON(success( $plan, 200));    
     }

     public function dealerproducts()
     {

         $response               =      new stdClass();
         $dealermodel            =      new Dealer_Model();
    	   $data                   =      $this->request->getJSON();
    	   $dealerid               =      $data->dealerid;
	       $city                   =      $data->cityname;
         $detail                 =      $dealermodel->getdealerproducts($dealerid,$city);

         $productArray = [];
           foreach ($detail as $product)
            {
             $item =
              array
              (

                "ProductId"      => $product->ProductId,
                "ProductName"    => $product->ProductName,
                "DepartmentId"   => $product->DepartmentId,
                "ProductCode"    => $product->ProductCode,
                "BrandName"      => $product->BrandName,
                "MRP"             => $product->MRP,
                "storeprice"      => $product->StorePrice,
                "thumb_image"   => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image"   => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image"    => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image"     => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
               );
              array_push($productArray, $item);
            }
            return $this->response->setJSON(success($productArray, 200));
     }
     public function dealerbranderproducts($dealerid,$city){
      $dealermodel       =   new Dealer_Model();
               $response = new stdClass();
     $productArray=[];
              
               
                  $products = $dealermodel->dealerproducts($dealerid,$city);
                 
             foreach ($products as $product) {
                 $item = array(
                     "ProductId" => $product->ProductId,
                     "DealerpriceId" => $product->DealerPriceId,
                     "ProductName" => $product->ProductName,
                     "DepartmentId" => $product->DepartmentId,
                     "MainCategoryId" => $product->MainCategoryId,
                     "SubcategoryId" => $product->SubCategoryId,
                     "mrp"=>$product->MRP,
                     "BrandId" => $product->BrandId,
                     "ProductCode" => $product->ProductCode,
                     "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                     "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                     "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                     "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                 );
                 array_push($productArray, $item);
             }    
     
                return $this->response->setJSON(success($productArray, 200));  
     
         }
         public function dealerproduct($dealerid,$city){
          $dealermodel       =   new Dealer_Model();
                   $response = new stdClass();
         $productArray=[];
                  
            $products = $dealermodel->dealerproduct($dealerid,$city);
                     
                 foreach ($products as $product) {
                     $item = array(
                         "ProductId" => $product->ProductId,
                         "DealerpriceId" => $product->DealerPriceId,
                         "ProductName" => $product->ProductName,
                         "DepartmentId" => $product->DepartmentId,
                         "MainCategoryId" => $product->MainCategoryId,
                         "SubcategoryId" => $product->SubCategoryId,
                         "mrp"=>$product->MRP,
                         "BrandId" => $product->BrandId,
                         "ProductCode" => $product->ProductCode,
                          "brandname"=>$product->BrandName,
                          "storeprice"=>$product->StorePrice,
                          "specification"=>$product->SpecificationValue,
                         "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                         "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                         "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                         "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                     );
                     array_push($productArray, $item);
                 }    
         
                    return $this->response->setJSON(success($productArray, 200));  
         
             } 
         public function dealercatproducts($dealerid,$maincatid,$subcatid,$city){
          $dealermodel       =   new Dealer_Model();
                   $response = new stdClass();
         $productArray=[];
                  
            $products = $dealermodel->dealercatproducts($dealerid,$maincatid,$subcatid,$city);
                     
                 foreach ($products as $product) {
                     $item = array(
                         "ProductId" => $product->ProductId,
                         "DealerpriceId" => $product->DealerPriceId,
                         "ProductName" => $product->ProductName,
                         "DepartmentId" => $product->DepartmentId,
                         "MainCategoryId" => $product->MainCategoryId,
                         "SubcategoryId" => $product->SubCategoryId,
                         "mrp"=>$product->MRP,
                         "BrandId" => $product->BrandId,
                         "ProductCode" => $product->ProductCode,
                         "brandname"=>$product->BrandName,
                         "storeprice"=>$product->StorePrice,
                         "specification"=>$product->SpecificationValue,
                         "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                         "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                         "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                         "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                     );
                     array_push($productArray, $item);
                 }    
         
                    return $this->response->setJSON(success($productArray, 200));  
         
             } 
             public function dealercatnobrandedproducts($dealerid,$maincatid,$subcatid,$city){
              $dealermodel       =   new Dealer_Model();
                       $response = new stdClass();
             $productArray=[];
                      
                $products = $dealermodel->dealercatnobrandedproducts($dealerid,$maincatid,$subcatid,$city);
                         
                     foreach ($products as $product) {
                         $item = array(
                             "ProductId" => $product->ProductId,
                             "DealerpriceId" => $product->DealerPriceId,
                             "ProductName" => $product->ProductName,
                             "DepartmentId" => $product->DepartmentId,
                             "MainCategoryId" => $product->MainCategoryId,
                             "SubcategoryId" => $product->SubCategoryId,
                             "mrp"=>$product->MRP,
                             "BrandId" => $product->BrandId,
                             "ProductCode" => $product->ProductCode,
                             "size"=>$product->SpecificationValue,
                             "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                             "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                             "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                             "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                         );
                         array_push($productArray, $item);
                     }    
             
                        return $this->response->setJSON(success($productArray, 200));  
             
                 }  
                 public function dealercatnobrandedproduct($dealerid,$city){
                  $dealermodel       =   new Dealer_Model();
                           $response = new stdClass();
                 $productArray=[];
                          
                    $products = $dealermodel->dealercatnobrandedproduct($dealerid,$city);
                             
                         foreach ($products as $product) {
                             $item = array(
                                 "ProductId" => $product->ProductId,
                                 "DealerpriceId" => $product->DealerPriceId,
                                 "ProductName" => $product->ProductName,
                                 "DepartmentId" => $product->DepartmentId,
                                 "MainCategoryId" => $product->MainCategoryId,
                                 "SubcategoryId" => $product->SubCategoryId,
                                 "mrp"=>$product->MRP,
                                 "BrandId" => $product->BrandId,
                                 "ProductCode" => $product->ProductCode,
                                 "size"=>$product->SpecificationValue,
                                 "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                                 "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                                 "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                                 "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                             );
                             array_push($productArray, $item);
                         }    
                 
                            return $this->response->setJSON(success($productArray, 200));  
                 
                     }             
     public function dealerproductsfilter()
     {

         $response               =      new stdClass();
         $dealermodel            =      new Dealer_Model();
         $data                   =      $this->request->getJSON();
         $dealerid               =      $data->dealerid;
         $city                   =      $data->cityname;
         $filter                 =      $data->filter;    
         $detail                 =      $dealermodel->getdealerfilterproducts($dealerid,$city,$filter);

         $productArray = [];
           foreach ($detail as $product)
            {
             $item =
              array
              (

                "ProductId"      => $product->ProductId,
                "ProductName"    => $product->ProductName,
                "DepartmentId"   => $product->DepartmentId,
             );
              array_push($productArray, $item);
            }
            return $this->response->setJSON(success($productArray, 200));
     }
     public function productspecificaion()
     {


         $dealermodel           =     new Dealer_Model();
    	   $data                  =     $this->request->getJSON();
    	   $subid                 =     $data->subid;
    	   $dpid                  =     $data->dpid;
    	   $mainid                =     $data->mainid;
    	   $detail                =     $dealermodel->getproductspecification($dpid,$mainid,$subid);
         $mad                   =     $dealermodel->getmandatoryspecification($dpid,$mainid,$subid);
         $response = new stdClass();
         $response->detail = $detail;
         $response->mand = $mad;
         return $this->response->setJSON(success($response, 200));
     }
     public function checkmandatory()
     {

         $response              =     new stdClass();
         $dealermodel           =     new Dealer_Model();
    	   $data                  =     $this->request->getJSON();
    	   $subid                 =     $data->subid;
    	   $dpid                  =     $data->dpid;
    	   $mainid                =     $data->mainid;
         $specid                =     $data->specid;
    	   $detail                =     $dealermodel->checkmandatory($dpid,$mainid,$subid,$specid );
         return $this->response->setJSON(success($detail, 200));
     }
     public function dealernonbrandedproduts()
     {

         $response            =     new stdClass();
         $dealermodel         =     new Dealer_Model();
    	   $data                =     $this->request->getJSON();
    	   $dealerid            =     $data->dealerid;
	       $city                =     $data->cityname;
         $detail              =     $dealermodel->getnondealerproducts($dealerid,$city);
         $productArray = [];
         foreach ($detail as $product) {
          $item = array(
              "ProductId" => $product->ProductId,
              "DealerpriceId" => $product->DealerPriceId,
              "ProductName" => $product->ProductName,
              "DepartmentId" => $product->DepartmentId,
              "MainCategoryId" => $product->MainCategoryId,
              "SubcategoryId" => $product->SubCategoryId,
              "mrp"=>$product->MRP,
              "BrandId" => $product->BrandId,
              "ProductCode" => $product->ProductCode,
              "size"=>$product->SpecificationValue,
              "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
              "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
              "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
              "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
          );
          array_push($productArray, $item);
        }
         return $this->response->setJSON(success($productArray, 200));
     }
     public function updatedealerproductstatus($city,$dealerpriceid){
      $dealermodel         =     new Dealer_Model();
      $detail   =  $dealermodel->updatedealerproductstatus($dealerpriceid,$city);

     }
          public function getnondealerproductsfilter()
     {

         $response            =     new stdClass();
         $dealermodel         =     new Dealer_Model();
         $data                =     $this->request->getJSON();
         $dealerid            =     $data->dealerid;
         $city                =     $data->cityname;
         $filter              =     $data->filter;
         $detail              =     $dealermodel->getnondealerproductsfilter($dealerid,$city,$filter);
         return $this->response->setJSON(success($detail, 200));
     }
     public function updateprice(){
         $response            =     new stdClass();
         $dealermodel         =     new Dealer_Model();
	       $data                =     $this->request->getJSON();
	       $dip                 =     $data->dealerpriceid;
	       $city                =     $data->cityname;
	       $price               =     $data->mrp;
         $planid              =      $dealermodel->updateprice($dip,$city,$price);
         return $this->response->setJSON(success($price, 200));
     }
     public function ordercount($dealerId,$city){
      $response = new stdClass();
        $dealermodel = new Dealer_Model();
        $city=strtolower($city);
        $city = $city== "mysore" ? "" : $city."_";
        $pickuporders=count($dealermodel->psordercount($dealerId,$city));
        $horder=count($dealermodel->hordercount($dealerId,$city));
        $catfilter=["pcount"=>$pickuporders,
        "hcount"=>$horder];
        $catfilter1[]=$catfilter;
        return $this->response->setJSON(success($catfilter1, 200));
     }
      public function pickuporders($dealerId,$city){
        $catfilter=[];
        $catfilter1=[];
        $response = new stdClass();
        $dealermodel = new Dealer_Model();
        $city=strtolower($city);
        $city = $city== "mysore" ? "" : $city."_";
        $pickuporders=$dealermodel->pickuporders($dealerId,$city);
        foreach($pickuporders as $filter){
          $products =  $dealermodel->pickupordersdetail($filter->PickatStoreId,$city);
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
            "sellingprice"=>$product->SellingPrice,
            "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
            "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
            "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
            "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
         
            );
            array_push($productArray, $item);
          
         }
         if(count($products)>0){
          $catfilter=["psorderid"=>$filter->PickatStoreId,
          "pcount"=>$filter->pscount,
          "orderdetail"=>$productArray];
          $catfilter1[]=$catfilter;
         }
       
      }
      return $this->response->setJSON(success($catfilter1, 200));
    }
    public function pickupordersfilter($dealerId,$city,$id){
      $catfilter=[];
      $catfilter1=[];
      $response = new stdClass();
      $dealermodel = new Dealer_Model();
      $city=strtolower($city);
      $city = $city== "mysore" ? "" : $city."_";
      $pickuporders=$dealermodel->pickupordersfilter($dealerId,$city,$id);
      foreach($pickuporders as $filter){
        $products =  $dealermodel->pickupordersdetail($filter->PS_OrderId,$city);
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
          "sellingprice"=>$product->SellingPrice,
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
     public function getpsorderdetail(){
      $data = $this->request->getJSON();
      $dealermodel = new Dealer_Model();
      $city = $data->city;
      $orderid=$data->orderid;
      $city=strtolower($city);
      $city = $city== "mysore" ? "" : $city."_";
      $response = new stdClass();
      $product =  $dealermodel->getordersdetail($orderid,$city);
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
      $response->orderdate=date("d-j-Y",strtotime($product->CreatedOn));
    
     
      $response->mobile=$product->mobile;
      $response->thumb_image=productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1);
      $response->shoplogo="https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/".$product->ShopLogo;
    return $this->response->setJSON(success($response, 200));
     }
public function completehorderdetail(){
  $data = $this->request->getJSON();
  $dealermodel = new Dealer_Model();
  $city = $data->city;
  $orderid=$data->orderid;
  $product =  $dealermodel->completehorderdetail($orderid,$city);
  return $this->response->setJSON(success("sucess", 200));

}
     public function updatepsorderstatus(){
      $data = $this->request->getJSON();
      $dealermodel = new Dealer_Model();
      $city = $data->city;
      $orderid=$data->orderid;
      $status=$data->status;
      $city=strtolower($city);
      $city = $city== "mysore" ? "" : $city."_";
      $response = new stdClass();
      $product =  $dealermodel->updatepsorderstatus($status,$orderid,$city);
      return $this->response->setJSON(success("sucess", 200));

     }
     public function getpsorderstatus(){
      $response = new stdClass();
      $usermodel = new Dealer_Model();
      $reasons=$usermodel->getpsorderstatus();
      $response->reasons=$reasons;
      return $this->response->setJSON(success($response, 200));
  }
  public function getbankdetail($dealerid,$cityname){
    $response = new stdClass();
    $usermodel = new Dealer_Model();
    $reasons=$usermodel->getbankdetail($dealerid,$cityname);
    $response->reasons=$reasons;
    return $this->response->setJSON(success($response, 200));
}
    public function homeorders($dealerid,$city,$type){
      $catfilter=[];
      $catfilter1=[];
      $response = new stdClass();
      $dealermodel = new Dealer_Model();
      $city=strtolower($city);
      $city = $city== "mysore" ? "" :$city."_";
      $pickuporders=$dealermodel->homeorders($dealerid,$city,$type);
  
       // $products =  $dealermodel->gethorders($filter->H_MainOrderId,$city);
        $productArray = [];
          foreach ($pickuporders as $product) {
            
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
                  "orderdate"=>$product->OrderDate,
                  "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                  "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                  "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                  "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)            
              );
              array_push($productArray, $item);
              }
            $catfilter=["mainorderid"=>$product->H_conorderId,"hcount"=>$product->pscount,
             "orderdetail"=>$productArray];
                   $catfilter1[]=$catfilter;
             
          
  
   return $this->response->setJSON(success($catfilter1, 200));
  
  }
     public function getdmsname(){
         $response            =     new stdClass();
         $dealermodel         =     new Dealer_Model();
         $data                =     $this->request->getJSON();
         $dpartid             =     $data->dpid;
         $maincatid           =     $data->mainid;
         $subid               =     $data->subid;
         $departname          =     $dealermodel->getdepartname($dpartid);
         $mainname            =     $dealermodel->getmaincatname($maincatid);
         if($subid!=0){
         $subname             =     $dealermodel->getsubcatname($subid);
         $response->sname     =    $subname->SubCategoryName;
         }

         $response->dname     =    $departname->DepartmentName;
         $response->mname     =    $mainname->MainCategoryName;
         
         return $this->response->setJSON(success($response, 200));

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
     public function psordercancel($orderid,$pickid,$reasonid,$comments,$city){
      $response = new stdClass();
      $dealermodel= new Dealer_Model();
      $city = $city== "mysore" ? "" : $city."_";
      $reasons=$dealermodel->psordercancel($orderid,$pickid,$reasonid,$comments,$city);
     return $this->response->setJSON(success("success", 200));
  }
  public function posdata($dealerid,$city){
    $response = new stdClass();
    $dealermodel= new Dealer_Model();
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
    $reasons=$dealermodel->posdata($city,$dealerid);
   return $this->response->setJSON(success($reasons, 200));
}
  public function getcityname($pincode){
    $response = new stdClass();
    $dealermodel= new Dealer_Model();
    $detail=$dealermodel->getaddress($pincode);
    return $this->response->setJSON(success($detail, 200));
  }
  public function gethorderdetail(){
    $data = $this->request->getJSON();
    $city = $data->city;
    $orderid=$data->orderid;
   
    $dealermodel =new Dealer_Model();
    $response = new stdClass();
      $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
      $product=$dealermodel->gethorders($orderid,$city);

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
        "caddress"=>$product->BillingAddress,
        "CustomerName"=>$product->CustomerName,
        "usercity"=>$product->City,
        "userstate"=>$product->State,
        "userlocation"=>$product->Locality,
        "userlandmark"=>$product->Landmark,
        "userpincode"=>$product->Pincode,
        "MobileNumber"=>$product->MobileNumber,
        "SubTotal"=>$product->SubTotal,
        "Orderdate"=>date("d-F-Y",strtotime($product->OrderDate)),
         "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
        "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
        "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
        "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)            
    );
    
    return $this->response->setJSON(success(  $item, 200));


  }
  public function updatedealerdetail($dealerid,$city){
  	 $response = new stdClass();
    $dealermodel= new Dealer_Model();
    $a=[
    	"TermsAndConditions"=>1
    ];
    $reasons=$dealermodel->changedetail($dealerid,$a,$city);
   return $this->response->setJSON(success($reasons, 200));
  }
    public function whatsapp($mobile,$name)
    {

	     $tempalte='';
         $tempalte.="Hi ".$name.",Thank you for showing interest to shop with. cityonnet.com Lets you check our entire range of products at" ;

         $tempalte.=" Reply yes to view the virtual store.";

	     $data = 
	     [
		    'phone' => "91".$mobile, // Receivers phone
		    'body' => $tempalte, // Message
		 ];
		
	      $json        =  json_encode($data); 
		  $token       =  'xjkn8jglonxuugx7';
          $instanceId  =   168564;
	      $url         =  'https://api.chat-api.com/instance'.$instanceId.'/sendMessage?token='.$token;
		 // Make a POST request
		 $options = stream_context_create(['http' => [
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/json',
		        'content' => $json
		    ]
		 ]);
		// Send a request
		 $result = file_get_contents($url, false, $options);
	}
   public function getgstindetail($cityname,$dealerid){
    if(strtolower($cityname)=="mysuru"){
      $cityname="mysore";
    }
  else{
    $cityname=$cityname;
  }
      $response     =      new stdClass();
      $dealermodel  =      new Dealer_Model();
     $gstin       =      $dealermodel->getgstindetail($cityname,$dealerid);
      
      return $this->response->setJSON(success($gstin, 200));
  }
   public function updategstindetail(){
     $response     =      new stdClass();
      $dealermodel  =      new Dealer_Model();
    $data         =      $this->request->getJSON();
    $cityname        =   $data->city;
    $gstin        =   $data->gstin;
    $pannumber        =   $data->pan;
    $dealerid        =   $data->dealerid;
    if(strtolower($cityname)=="mysuru"){
      $cityname="mysore";
    }
  else{
    $cityname=$cityname;
  }
     $citydata= $dealermodel->getcityid(ucfirst($cityname));
     $stateid=$citydata->StateId;
       $data=[
        "PAN_Number"=>$pannumber,
        "GSTRegId"=>$gstin,
       "Dealerid"=>$dealerid,
       "StateId"=>$stateid
            ];

     $gstin       =      $dealermodel->updategst($data,$cityname,$dealerid);
      
      return $this->response->setJSON(success($gstin, 200));
  }
  public function getdepartbrandmappinglist($cityname,$did){
      if(strtolower($cityname)=="mysuru"){
      $cityname="mysore";
    }
  else{
    $cityname=$cityname;
  }
      $response     =      new stdClass();
      $dealermodel  =      new Dealer_Model();
     $gstin       =      $dealermodel->getdepartbrandmappinglist($cityname,$did);
      
      return $this->response->setJSON(success($gstin, 200));
  }
}
