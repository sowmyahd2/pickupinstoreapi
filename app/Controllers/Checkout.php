<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth_Model;
use App\Models\BrandModel;
use App\Models\Cart_Model;
use App\Models\CheckoutModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Checkout extends BaseController
{
    use ResponseTrait;
    function index()
    {
        $token = getBearerToken();
        if ($token !== null) {
            $user = JWT::decode($token, JWT_KEY,array('HS256'));
            if ($user) {
                $data = $this->request->getJSON();
                $cityName = $data->city;
                $city = $cityName == "mysuru" ? "" : $cityName . "_";
                $type= $data->type;
                $date = $data->date;
                $checkoutModel = new CheckoutModel();
                return $this->response->setJSON(success("", 200, "order placed successfully"));        
            } else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        } else {
            return $this->response->setJSON(success("", 403, "unauthorized"));
        }
    }
    function addrecipient(){
         $data = $this->request->getJSON();
           $result = new stdClass();
           
         $name=$data->name;
         $number=$data->number;
         $userid=$data->userid;
         $city=$data->city;
         $city = $city == "mysuru" ? "" : $city . "_";
         $checkoutModel = new CheckoutModel();
              $data = array(
          'Name' => $name,
    'MobileNumber'=>$number,
    'UserId'=>$userid,

    );
              
     $result->insertid = $checkoutModel->insertrecipient($data, $city);
     $result->name=$name;
        $result->number=$number;
     return $this->response->setJSON(success($result, 200));
    }
    function deletepickproduct($dealerpriceid,$userid,$city){
            $result = new stdClass();
	$city = $city == "mysuru" ? "" : $city . "_";
 $checkoutModel = new CheckoutModel();
 $s= $checkoutModel->deletepickproduct($userid,$type=3,$dealerpriceid,$city);
 return $this->result->setJSON(success("sucess", 200));
    }
     function deletehomeproduct($dealerpriceid,$userid,$city){
          $result = new stdClass();
	$city = $city == "mysuru" ? "" : $city . "_";
 $checkoutModel = new CheckoutModel();
 $checkoutModel->deletepickproduct($userid,$type=2,$dealerpriceid,$city);
 return $this->result->setJSON(success("sucess", 200));
    }
    public function placepickorder(){
        $result = new stdClass();
        $data = $this->request->getJSON();
        $city = $data->city;
        $userid=$data->userid;
        $receipientid=$data->receipentId;
        $placedate=$data->date;
        $pickda=$data->pickupdate;
        $pickupdate=date('Y-m-d', strtotime($pickda));
        $city = $city == "mysuru" ? "" : $city . "_";
        $checkoutModel = new CheckoutModel();
        $price=0;
        $date=date('Y-m-d h:i:s'); 
       $cart= $checkoutModel->get_pikcart($userid,$type=3,$city);
        $Number_of_Products=count($cart);
            foreach ($cart as $items) {
            $price=$price + ($items->Price * $items->QuantityPurchased);

        }
        $ps_orders_array=array(
                "UserId"=>$userid,
               "Num_of_Products"=>$Number_of_Products,
                "OrderAmount"=>$price,
                "PaymentType"=>'0',
                "OrderRecipientId"=>$receipientid,
                "OrderPickup_Date"=>$pickupdate,
                "CreatedOn"=>$date,
                "LastUpdate"=>$date
                );

$psid=$checkoutModel->insertintopsorder($ps_orders_array, $city);

if($psid){

     $orderdealerwise= $checkoutModel->orderdealerwise($userid,$type=3,$city);
     
foreach ($orderdealerwise as $order) {
        $payable=$order->DealerPrice;
    $coupon=rand(10000000,99999999);
     $pickatstore_array=array(

                        "PS_OrderId"=>$psid,
                        "DealerId"=>$order->DealerId,
                        "Msg_SentStatus"=>1,
                        "Ticket"=>$coupon,
                        "OrderExpiryDate"=>date('Y-m-d h:i:s', strtotime("+".$pickupdate."days")),
                        "PS_OrderStatusId"=>1,
                        "SubTotalOrderAmount"=>$payable,
                        "LastUpdate"=>$date,
                        "CreatedOn"=>$date

                        );
     $pickid=$checkoutModel->insertintopick($pickatstore_array, $city);

if($pickid){

 $dealerproducts= $checkoutModel->dealerproductscart($order->DealerId,$type=3,$userid,$city);

 foreach ($dealerproducts as $dealerproduct) {
       if(intval($dealerproduct->StorePrice)==0)
                            {
                                $storeprice=$dealerproduct->SellingPrice;
                            }
                            else
                            {
                                $storeprice=$dealerproduct->StorePrice;
                            }


                            $quantity=$dealerproduct->QuantityPurchased;
                            $payable=$storeprice*$quantity;
                          
                            $ps_orderdetails_array=array(

                                "PickatStoreId"=>$pickid,
                                "ProductId"=>$dealerproduct->ProductId,
                                "DealerPriceId"=>$dealerproduct->DealerPriceId,
                                "StorePrice"=>$storeprice,
                                "Quantity"=>$quantity,
                                "PayablePrice"=>$payable,
                                "PickupStatus"=>0,
                                "PS_OrderStatusId"=>1,
                                "LastUpdate"=>$date


                                );
                            $psorid=$checkoutModel->insertintopickorderdetail($ps_orderdetails_array, $city);
                            if($psorid){

                        // $checkoutModel->updatedealerquanity($dealerproduct->DealerPriceId, $city);
                        $checkoutModel->deletepickproduct($userid,$type=3,$dealerproduct->DealerPriceId,$city);
                            }
 }
 $userdetail=$checkoutModel->userdetail($userid);
$receipent=$checkoutModel->getreciepeintdetail($userid,$city);
 $dealerdata=$checkoutModel->dealerdata($order->DealerId,$city);
 $msgtodealer="You Have Received a pickup in shop order ".$pickid." worth Rs ".$payable."/- Please login to your account for order details";
 //	$status=$this->send_sms($dealerdata->MobileNumber,$msgtodealer);	
 	$status=$this->send_sms("9731137692",$msgtodealer);
}
   
 }


}

$messageuser="Hurray  Your order #".$psid." on cityonnet has been successfully valid till ".$pickupdate." for Rs. ".$price.".";
 				$samemessage="Hi,  your order #".$psid." placed by ".$userdetail->UserName."  It is valid up to  ".$pickupdate;

if($receipent->MobileNumber!=$userdetail->Mobile){

						//	$status=$this->send_sms($mbno->MobileNumber,$samemessage);	
							//$status=$this->send_sms($emailid->Mobile,$messageuser);
$status=$this->send_sms("9731137692",$messageuser);
						}
						else{

							//	$status=$this->send_sms($userdetail->Mobile,$messageuser);
						}
  return $this->response->setJSON(success("success", 200));
         
    }

public function placehomeorder(){

 $result = new stdClass();
        $data = $this->request->getJSON();
        $city = strtolower($data->city);
        $userid=$data->userid;
        $shippingid=$data->shipingid;
  		 $city = $city == "mysuru" ? "" : $city . "_";
         $checkoutModel = new CheckoutModel();
         $date=date('Y-m-d');
       $price=0;
        $datetime=date('Y-m-d h:i:s'); 
    $cart= $checkoutModel->get_pikcart($userid,$type=2,$city);

    $Number_of_Products=count($cart);
foreach ($cart as $items) {
            $OrderAmount=$price + ($items->Price * $items->QuantityPurchased);

        }

        $MainOrderArray=array(
 				"NumofProducts"=>$Number_of_Products,
 				"OrderAmount"=>$OrderAmount,
 				"UserId"=>$userid,
 				"ShippingId"=>$shippingid,
 				"OrderCreatedOn"=>$datetime
 				);
         

        $mainid=$checkoutModel->insertintohmorder($MainOrderArray, $city);

      
        if($mainid){

    $orderdealerwise= $checkoutModel->orderdealerwise($userid,$type=2,$city);
    foreach ($orderdealerwise as $order) {

$payable=$order->DealerPrice;
 						$conorderarray=array(
 				    	"H_MainOrderId" =>$mainid,
 					    "OrderDate" =>$date,
 					    "DealerId"=>$order->DealerId,
 					    "OrderAmount"=>$payable,
 					    "LastUpdateTime"=>$datetime,
 					 );

 						 $hconid=$checkoutModel->insertintocon($conorderarray, $city);

 			
 						 if($hconid){

 						 	$dealerproducts= $checkoutModel->dealerproductscart($order->DealerId,$type=2,$userid,$city);
 						foreach ($dealerproducts as $dealerproduct) {

							if(intval($dealerproduct->StorePrice)==0)
							{
								$storeprice=$dealerproduct->SellingPrice;
							}
							else
							{
								$storeprice=$dealerproduct->StorePrice;
							}


							$quantity=$dealerproduct->QuantityPurchased;
							$payable=$storeprice*$quantity;
							
						$orderdetailarray=array(
 							"H_conorderId"=>$hconid,
 							"DealerPriceId"=>$dealerproduct->DealerPriceId,
 							"ProductId"=>$dealerproduct->ProductId,
 							"SellingPrice"=>$dealerproduct->SellingPrice,
 							"Quantity"=>$dealerproduct->QuantityPurchased,
 						    "SubTotal"=>$payable,
 							"OrderStatus"=>1,
 							"LastUpdateTime"=>$datetime,
 							);
 							$hmorid=$checkoutModel->insertintohomeorderdetail($orderdetailarray, $city);

 							
						if($hmorid){
							 $checkoutModel->deletepickproduct($userid,$type=2,$dealerproduct->DealerPriceId,$city);
								}

 							}

 							$userdetail=$checkoutModel->userdetail($userid);
 							$dealerdata=$checkoutModel->dealerdata($order->DealerId,$city);
				$dealermsg="You have received order No #".$hmorid." for delivery to mr/s " .$userdetail->UserName." at".$userdetail->Mobile.".kindly check your mail for order details.Login to your dealer dashboard to update.";
					//$this->send_sms($deal_data->MobileNumber,$dealermsg);
$this->send_sms("9731137692",$dealermsg);
 
 						 }

    }
}
 	 return $this->response->setJSON(success("success", 200)); 
    	 
        }
 
    public function send_sms($number,$msg){

	$service_url="http://alerts.solutionsinfini.com";
	$sender='CITYID';
	$api_key="Ab429e3d02a1a3721410a2b187ea5da2a";


	$service="http://alerts.solutionsinfini.com/api/v4/index.php?method=sms.xml";
	$xmldata =  '<?xml version="1.0" encoding="UTF-8"?><api><sender>'.$sender.'</sender><msgid>1108162874734889839</msgid><message>'.$message.'</message><sms><to>91'.$number.'</to></sms></api>';
	$api_url =$service.'&api_key='.$api_key.'&format=xml&xml='.urlencode($xmldata);

	$response = file_get_contents($api_url);
	if($response)
	{
		return $response;
	}

}
}