<?php

namespace App\Models;

use CodeIgniter\Model;

class CheckoutModel extends Model
{
    function isreturnuser($city)
	{
        $db = db_connect();
		$count = $db->table($city.'ps_orders')
	    ->where('UserId', $this->session->userdata('user_id'))
        ->get()->getResult();
        return count($count);
    }
	function pickcartcount($userid,$city){
  $db = db_connect();
		$count = $db->table($city.'pickatstore_cart')->where('UserId', $userid) ->get()->getResult();
        return count($count);

	}
	function homecartcount($userid,$city){
		$db = db_connect();
			  $count = $db->table($city.'H_cart')->where('UserId', $userid)->get()->getResult();
			  return count($count);
	  
		  }
	
    function userdetail()
	{
        $db = db_connect();
		return $db->table('useraccounts')
		->where('UserId', $this->session->userdata('user_id'))
		->get()->getRow();
	}
		function insertrecipient($data,$city){
		$db = db_connect();
    $table = $city."ps_order_recepients";
    
     $db->table($table)->insert($data);
        return $db->insertID();
    
	}
	function placeOrder($type, $city, $shipid, $pickupdate){
		$db = db_connect();
        $table = "";
		$mainOrder = '';
        switch ($type) {
            case "1":
                $table = $city . "cart c";
				$mainOrder = $city . "mainorders";
                break;
            case "2":
                $table = $city ."H_cart c";
				$mainOrder = $city ."hmainorder";
				$conoreder = $city ."hconorder";
				$orderdetail=$city."horderdetails";
                break;
            case "3":
                $table = $city ."pickatstore_cart c";
				$mainOrder = $city . "ps_orders";
				$conoreder = $city ."pickatstore_new";
				$orderdetail=$city."ps_orderdetails";
                break;
        }
		if($type==2){
			$userdetail=$db->table("usershippingdetails us")->select('us.UserId,UserName,Mobile')->join("useraccounts ua","ua.UserId=us.UserId")->where('us.ShippingId', $shipid)->groupBy('us.ShippingId')
			->get()->getRow();

		}
	else{
		$userdetail=$db->table("useraccounts")->select('UserId,UserName,Mobile')->where('UserId', $shipid)->get()->getRow();

	}

         $con=$db->table($table)
         ->select('c.*,SUM(Price*QuantityPurchased) as DealerPrice,MIN(dp.ReserveDays) as ReserveDays')
         ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
          ->where('c.UserId', $userdetail->UserId)
        ->groupBy('dp.DealerId')
           ->get()->getResult();
		$items = $db->table($table)
		->select('p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,Group_concat(dps.SpecificationName) as SpecificationName ,Group_concat(dps.SpecificationValue) as SpecificationValue,da.ShopName,da.Adress,da.ShopLogo,da.MobileNumber,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dlc.LocalMinOrderPrice,dlc.ZoneMinOrderPrice,dlc.NationalMinOrderPrice,QuantityPurchased, dp.DealerPriceId')
		->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
		->join('products p', 'p.ProductId=dp.ProductId')
		->join($city . 'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId', 'left')
		->where('c.UserId', $userdetail->UserId)
		->join($city . 'dealeraccounts da', 'da.DealerId=dp.DealerId')
		->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
		->groupBy('c.DealerPriceId')
		->get()->getResult();
		$date=date('Y-m-d');
		$datetime=date('Y-m-d h:i:s'); 
		$total = 0;
		foreach($items as $item) {
			$total += $item->SellingPrice;
		}	
		if($type == 2){
			$mainorderData = array (
				"UserId" =>$userdetail->UserId,
				"NumofProducts" => count($items),
				"ShippingId" =>$shipid,
				"OrderAmount" => $total
			);
			$db->table($mainOrder)->insert($mainorderData);
			$mainOrderId = $db->insertID();
			foreach ($con as $order) {
			$payable=$order->DealerPrice;
 						$conorderarray=array(
 				    	"H_MainOrderId" =>$mainOrderId,
 					    "OrderDate" =>$date,
 					    "DealerId"=>$order->DealerId,
 					    "OrderAmount"=>$payable,
 					    "LastUpdateTime"=>$datetime,
 					 );
					  $db->table($conoreder)->insert($conorderarray);
					  $conid = $db->insertID();
					  $items=$db->table($table)
					  ->select('*')
					  ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
					 ->where('c.UserId', $userdetail->UserId)
					   ->where('c.DealerId',$order->DealerId)
					->groupBy('dp.DealerPriceId')
						->get()->getResult();
			foreach($items as $dealerproduct) {
				
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
				 "H_conorderId"=>$conid,
				 "DealerPriceId"=>$dealerproduct->DealerPriceId,
				 "ProductId"=>$dealerproduct->ProductId,
				 "SellingPrice"=>$dealerproduct->SellingPrice,
				 "Quantity"=>$dealerproduct->QuantityPurchased,
				 "SubTotal"=>$payable,
				 "OrderStatus"=>1,
				 "LastUpdateTime"=>$datetime,
				 );
				 $db->table($orderdetail)->insert($orderdetailarray);
				 $db->table($city ."H_cart")->where("UserId", $userdetail->UserId)->where("DealerPriceId", $dealerproduct->DealerPriceId)->delete();
		}
		$dealermsg="You have received order No #".$conid." for delivery to mr/s " .$userdetail->UserName." at".$userdetail->Mobile.".kindly check your mail for order details.Login to your dealer dashboard to update.";
		//$this->send_sms($deal_data->MobileNumber,$dealermsg);
$this->send_sms("9731137692",$dealermsg);
		}
		}
		if($type == 3){
			$mainorderData = array (
				"UserId" =>$userdetail->UserId,
				"CityId" => 0,
				"Num_of_Products" => count($items),
				"PaymentType" => 0,
				"OrderPickup_Date" => $date,
				"OrderAmount" => $total
			);
			$db->table($mainOrder)->insert($mainorderData);
			$mainOrderId = $db->insertID();
			$con=$db->table($table)
         ->select('c.*,SUM(Price*QuantityPurchased) as DealerPrice,MIN(dp.ReserveDays) as ReserveDays')
         ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
          ->where('c.UserId', $userdetail->UserId)
        ->groupBy('dp.DealerId')
           ->get()->getResult();
		   foreach ($con as $order) {
			$payable=$order->DealerPrice;
		$coupon=rand(10000000,99999999);
		 $pickatstore_array=array(
	
							"PS_OrderId"=>$mainOrderId ,
							"DealerId"=>$order->DealerId,
							"Msg_SentStatus"=>1,
							"Ticket"=>$coupon,
							"OrderExpiryDate"=>date('Y-m-d h:i:s', strtotime("+".$pickupdate."days")),
							"PS_OrderStatusId"=>1,
							"SubTotalOrderAmount"=>$payable,
							"LastUpdate"=>$date,
							"CreatedOn"=>$date
	
							);
							$db->table($conoreder)->insert($pickatstore_array);
							$pickid = $db->insertID();
							$items=$db->table($table)
							->select('*')
							->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
						   ->where('c.UserId', $userdetail->UserId)
							 ->where('c.DealerId',$order->DealerId)
						  ->groupBy('dp.DealerPriceId')
							  ->get()->getResult();
			foreach($items as $item) {
			$order = array(
				"PickatStoreId" => $pickid,
				"ProductId" => $item->ProductId,
				"DealerPriceId" => $item->DealerPriceId,
				"StorePrice" => $item->SellingPrice,
				"Quantity" => $item->QuantityPurchased,
				"GST_Percent" => 18,
				"PayablePrice" => $item->SellingPrice,
			);
			$db->table($orderdetail)->insert($order);

			$db->table($city.'pickatstore_cart')->where('UserId', $userdetail->UserId)->delete();
		}

	}
		}
	}
	public function send_sms($number,$dealermsg){
		
		$message=$dealermsg;
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