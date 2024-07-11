<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   

    function userdetail($id){
       
       
       
        $db = db_connect();
        $user = $db->table('useraccounts u')
		->where('UserId',$id)
        ->join('citytable c','u.CityId=c.CityId')
        ->join('statetable s','u.StateId=c.StateId')
	   ->get()->getRow();
      if($user){
                return $user;
      }
      return false;
    }
    public function getuserdetail($number)
{

   $db = db_connect();
    $table = "useraccounts";
    $user = $db->table('useraccounts')->where('Mobile',$number)->get()->getRow();
    if($user){
      return $user;
    }
    else{



  
  $items = array(
      'Mobile' => $number,
      );
  $user = $db->table('useraccounts');
    
    $user->insert($items);
    return $db->insertID();
  }
}
    public function updateuserpassword($userId,$password){

        $db = db_connect();
        $user = $db->table('useraccounts');
        $user->set('Password', $password);
        $user->where('UserId', $userId);
        $user->update();
      }
    
      public function getuseraddress($id)
      {
        $db = db_connect();
        $user = $db->table('usershippingdetails')->where('UserId',$id)->where('ActiveStatus',1)->get()->getResult();
        if($user){
        return $user;
        }
        return false;
      }
      public function getlocality($pincode){
       
       
        $db = db_connect();
        $user = $db->table('areatable u')
        ->select('s.StateName,c.CityName,u.AreaName')
        ->join('citytable c','u.CityId=c.CityId')
        ->join('statetable s','u.StateId=s.StateId')
	    	->where('u.PinCode',$pincode)
        
	       ->get()->getRow();
          if($user){
                return $user;
    }
      return false;

      }
      public function inactiveaddress($shipid){
        $db = db_connect();
        $user = $db->table('usershippingdetails');
        $user->set('ActiveStatus',0);
        $user->where('ShippingId', $shipid);
        $user->update();
      }
      public function insertaddress($userId,$mobile,$name,$landmark,$pincode,$address,$city,$state,$location)
      {
        $db = db_connect();
        $user = $db->table('usershippingdetails');
        $data = [
        'UserId' => $userId,
        'CustomerName' => $name,
        'BillingAddress'=>$address,
        'PinCode'=>$pincode,
        'MobileNumber'=>$mobile,
        "Locality"=>$location,
        "Landmark"=>$landmark,
        "city"=>$city,
        "state"=>$state
        ];
        $user->insert($data);
      }
      public function pickupinshoporders($userid,$city){
       
        $db = db_connect();
        $data= $db->table($city."ps_orders ps")
        ->select('p.*,ps.PS_OrderId,pick.PickatStoreId,OrderExpiryDate,Num_of_Products,ps.OrderAmount,Quantity')
        ->join($city.'pickatstore_new pick', 'ps.PS_OrderId=pick.PS_OrderId')
        ->join($city.'ps_orderdetails pso', 'pick.PickatStoreId=pso.PickatStoreId')
        ->join('products p', 'pso.ProductId=p.ProductId')
         ->where('ps.UserId', $userid)
         ->groupBy('ps.PS_OrderId')
          ->get()->getResult();
          return $data;

      }
      public function userorderdetail($orderid,$city){
        $db = db_connect();
        $data= $db->table($city."ps_orders ps")
        ->select("UserName,Mobile,MobileNumber,Name,CreatedOn,OrderAmount,EmailId")
        ->join("useraccounts us",'ps.UserId=us.UserId')
        ->join("ps_order_recepients pr",'ps.UserId=pr.UserId')
        ->where('ps.PS_OrderId', $orderid)
        ->groupBy('ps.UserId')
           ->get()->getRow();
           return $data;
      }
      public function pickshopdetail($orderid,$city){
        $db = db_connect();
        $data= $db->table($city."ps_orders ps")
        ->select("da.*")
        ->join($city.'pickatstore_new pick', 'ps.PS_OrderId=pick.PS_OrderId')
        ->join($city.'dealeraccounts da', 'pick.DealerId=da.DealerId')
        ->where('ps.PS_OrderId', $orderid)
        ->groupBy('da.DealerId')
           ->get()->getResult();
           return $data;
      }
      public function pickorderdetail($orderid,$city){
        $db = db_connect();
        $data= $db->table($city."ps_orders ps")
           ->select('p.*,ps.PS_OrderId,pick.PickatStoreId,OrderExpiryDate,Num_of_Products,ps.OrderAmount,Quantity,PS_Status,PaymentType,Ticket,pick.PickatStoreId,PayablePrice,PS_OrderDetailsId')
        ->join($city.'pickatstore_new pick', 'ps.PS_OrderId=pick.PS_OrderId')
        ->join($city.'ps_orderdetails pso', 'pick.PickatStoreId=pso.PickatStoreId')
        ->join($city.'ps_orderstatus prs', 'pso.PS_OrderStatusId=prs.PS_OrderStatusId')
        ->join('products p', 'pso.ProductId=p.ProductId')
         ->where('ps.PS_OrderId', $orderid)
         ->groupBy('pso.PS_OrderDetailsId')
          ->get()->getResult();
          return $data;

      }
      public function homedeliveryorders($userid,$city){
         $db = db_connect();
         $data= $db->table($city."hmainorder ps")
        ->select('*')
        ->join($city.'hconorder pick', 'ps.H_MainOrderId=pick.H_MainOrderId')
        ->join($city.'horderdetails pso', 'pick.H_conorderId=pso.H_conorderId')
        ->join('products p', 'pso.ProductId=p.ProductId')
        ->where('ps.UserId', $userid)
        ->groupBy('ps.H_MainOrderId')
        ->get()->getResult();
        return $data;
      }
      public function userhomeorderdetail($orderid,$city){
        $db = db_connect();
        $data= $db->table($city."hmainorder ps")
        ->select("*")
        ->join("useraccounts us",'ps.UserId=us.UserId')
        ->join("usershippingdetails pr",'ps.UserId=pr.UserId')
        ->where('ps.H_MainOrderId', $orderid)
        ->groupBy('ps.UserId')
           ->get()->getRow();
           return $data;
      }
      public function homedeliveryordersdetail($orderid,$city){
        $db = db_connect();
        $data= $db->table($city."hmainorder ps")
       ->select('*')
       ->join($city.'hconorder pick', 'ps.H_MainOrderId=pick.H_MainOrderId')
       ->join($city.'horderdetails pso', 'pick.H_conorderId=pso.H_conorderId')
       ->join('products p', 'pso.ProductId=p.ProductId')
       ->where('ps.H_MainOrderId', $orderid)
       ->groupBy('pso.H_OrderDetailid')
       ->get()->getResult();
       return $data;
     }
     public function homeshopdetail($orderid,$city){
      $db = db_connect();
      $data= $db->table($city."hmainorder ps")
      ->select("da.*")
      ->join($city.'hconorder pick', 'ps.H_MainOrderId=pick.H_MainOrderId')
      ->join($city.'dealeraccounts da', 'pick.DealerId=da.DealerId')
      ->where('ps.H_MainOrderId', $orderid)
      ->groupBy('da.DealerId')
         ->get()->getResult();
         return $data;
    }
    public function pc_cancelreasons(){
      $db = db_connect();
      $data= $db->table("ps_oc_reasons")
      ->select("*")
      ->where('CancellationBy',2)
    ->get()->getResult();
         return $data;
    }
    public function insuserdetails($mobile){

    
    $db = db_connect();
   
   
    $pickid= $db->table("useraccounts")->select("*")->where('Mobile', $mobile)->get()->getRow();
  if($pickid){
return $pickid;
  }
  else{
       $userins = $db->table('useraccounts');
    $data = [
      'Mobile' => $mobile,
      
     ];
      $userins->insert($data);
  }
    
   }
   public function cancelpickorder($city,$orderid,$reasonid,$comments){

    $city = $city == "mysore" ? "" : $city . "_";
    $db = db_connect();
    $user = $db->table($city.'ps_orderdetails');
    $user->set('PS_OrderStatusId', 8);
    $user->where('PS_OrderDetailsId', $orderid);
    $user->update();
   
    $pickid= $db->table($city."ps_orderdetails")->select("PickatStoreId")->where('PS_OrderDetailsId', $orderid)->get()->getRow();
  
    $userins = $db->table($city.'ps_oc_reasonsmap');
    $data = [
      'PS_OrderDetailsId' => $orderid,
      'PickAtStoreId' =>  $pickid->PickatStoreId,
      'PS_OC_ReasonsId'=>$reasonid,
      'UserComment'=>$comments,
    
      ];
      $userins->insert($data);
   }
   public function addtowishlist($userid,$pid,$city){
    $db = db_connect();
  
    $list= $db->table($city."wishlist")->select("*")->where('UserId', $userid)->where('ProductId', $pid)->get()->getRow();
  if(!$list){
    $userins = $db->table($city.'wishlist');
    $data = [
      
      'UserId' => $userid,
      'ProductId'=>$pid,
      'AddedFrom'=>"Web site",
    
      ];
      $userins->insert($data);
  }
}
  public function wishlist($userid,$city){
    $db = db_connect();
    $list= $db->table($city."wishlist w")->select("*") ->join('products p', 'w.ProductId=p.ProductId')->where('UserId', $userid)->get()->getResult();
    return $list;
  
  }
  
public function deletewishlist($userid,$pid,$city){
  $db = db_connect();
  $db->table($city.'wishlist')->where('UserId', $userid)->where('ProductId', $pid)->delete();
}
   
}