<?php namespace App\Models;

use CodeIgniter\Model;

class User_Model extends Model
{

  public function insertaddress($name,$city,$state,$address,$pincode,$landmark,$number,$userId,$locality)
  {
    $db = db_connect();
    $user = $db->table('usershippingdetails');
    $data = [
    'UserId' => $userId,
    'CustomerName' => $name,
    'BillingAddress'=>$address,
    'PinCode'=>$pincode,
    'MobileNumber'=>$number,
    "Locality"=>$locality,
    "Landmark"=>$landmark,
    "city"=>$city,
    "state"=>$state
    ];
    $user->insert($data);
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

  public function getdefaultuseraddress($id)
  {
    $db = db_connect();
    $user = $db->table('usershippingdetails')->where('UserId',$id)->where('ActiveStatus',1)->where('DefaultAddress',1)->get()->getRow();
    if($user){
    return $user;
    }
    return false;
  }

  public function gethordersrating($id,$productid)
  {
    $db = db_connect();
    $user = $db->table('reviews')->where('UserId',$id)->where('ProductId',$productid)->get()->getRow();
    if($user){
    return $user;
    }
    return false;
  }

  public function updateshipaddress($shipid)
  {
    echo $shipid;
  
     $db      = \Config\Database::connect();
    $user = $db->table('usershippingdetails');
    $user->set('ActiveStatus', 0);
  
    $user->where('ShippingId', $shipid);
    $user->update();
    
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
public function getuphoneuserdetail($number)
{

   $db = db_connect();
    $table = "useraccounts";
    $user = $db->table('useraccounts')->where('Mobile',$number)->get()->getRow();
    if($user){
      return $user;
    }

}
  public function updateuserdetails($name,$email,$mobile,$userId){
    $db      = \Config\Database::connect();
    $user = $db->table('useraccounts');
    $user->set('UserName', $name);
    $user->set('EmailId', $email);
    $user->set('Mobile', $mobile);
    $user->where('UserId', $userId);
    $user->update();
    $user = $db->table('useraccounts')->where('UserId', $userId)->get()->getRow();
    if($user){
      return $user;
    } 
  }

  public function updateuserpassword($password,$userId){

    $db   = \Config\Database::connect();
    $user = $db->table('useraccounts');
    $user->set('Password', $password);
    $user->where('UserId', $userId);
    $user->update();
     $user = $db->table('useraccounts')->where('UserId', $userId)->get()->getRow();
    if($user){
      return $user;
    }
  }

  public function setdeafaultaddress($shipid,$userid)
  {
    $db      = \Config\Database::connect();
    $builder = $db->table('usershippingdetails');
    $builder->set('DefaultAddress', 0);
    $builder->where('UserId', $userid);
    $builder->update();
    $builder->set('DefaultAddress', 1);
    $builder->where('ShippingId', $shipid);
    $builder->update();
  } 

  public function pickuporders($id, $city)
  {
    $db = db_connect();
    return $db->table($city.'ps_orders pso')
    ->select("count(PS_OrderDetailsId) as pscount,pso.*,ps.DealerId")
    ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
    ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
    ->where('pso.UserId', $id)
    ->groupBy('pso.PS_OrderId')
    ->orderBy('pso.PS_OrderId', 'Desc')
    ->get()->getResult();
  }
public function getareanames($city){
   $db = db_connect();
    return $db->table('citytable c')
    ->select("a.*")
    ->join('areatable a', 'c.CityId=a.CityId')
    ->where('c.CityName', $city)
    ->groupBy('a.AreaId')
    ->orderBy('a.AreaName', 'asc')
    ->limit(10)
    ->get()->getResult();
}
public function getcity($stateid){
   $db = db_connect();
    return $db->table('citytable c')->select("c.*")->where('c.StateId', $stateid)->get()->getResult();
}
public function getcityname($cityname){
   $db = db_connect();
    return $db->table('citytable c')->select("c.*")->where('c.NewCityName', $cityname)->get()->getRow();
}

public function getpopularcity(){
   $db = db_connect();
    return $db->table('citytable c')->select("c.CityId,(c.NewCityName) as CityName , (c.CityName) as city")->where('c.Popular_City', 1)->orderBy("CityName")->get()->getResult();
}

public function getstates(){
   $db = db_connect();
    return $db->table('statetable c')
    ->select("*")->get()->getResult();
}
  public function homeorders($id, $city)
  {
    $db = db_connect();
    return $db->table($city.'hmainorder pso')
    ->select("count(H_OrderDetailid) as pscount,pso.*,ps.DealerId")
    ->join($city .'hconorder ps', 'ps.H_MainOrderId=pso.H_MainOrderId')
    ->join($city . 'horderdetails po', 'po.H_conorderId=ps.H_conorderId')
    ->where('pso.UserId', $id)
    ->groupBy('pso.H_MainOrderId')
    ->orderBy('pso.H_MainOrderId', 'Desc')
    ->get()->getResult();
  }

  public function pickupordersdetail($id, $city)
  {
    $db = db_connect();
    return $db->table($city.'ps_orders pso')
    ->select("*,pos.PS_Status as status")
    ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
    ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
    ->join('ps_orderstatus pos', 'pos.PS_OrderStatusId=po.PS_OrderStatusId')
    ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
    ->join( 'products p', 'po.ProductId=p.ProductId')
    ->where('pso.PS_OrderId', $id)
    ->orderBy('pso.PS_OrderId', 'Desc')
    ->get()->getResult();
  }

  public function gethorders($id,$city){
    $db = db_connect();
    return $db->table($city.'hmainorder pso')
    ->select("*")
    ->join($city .'hconorder ps', 'ps.H_MainOrderId=pso.H_MainOrderId')
    ->join($city.'horderdetails pos', 'pos.H_conorderId=ps.H_conorderId')
    ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
    ->join( 'products p', 'pos.ProductId=p.ProductId')
    ->where('pso.H_MainOrderId', $id)
    ->orderBy('pso.H_MainOrderId', 'Desc')
    ->get()->getResult();
  }

  public function gethordersdetail($id,$city){
    $db = db_connect();
    return $db->table($city.'hmainorder pso')
    ->select("*,us.MobileNumber as mob,us.Locality as loc,us.Landmark as lmark,us.City uscity,us.Pincode as pin,us.State as state")
    ->join($city .'hconorder ps', 'ps.H_MainOrderId=pso.H_MainOrderId')
    ->join('usershippingdetails us', 'pso.ShippingId=us.ShippingId')
    ->join($city.'horderdetails pos', 'pos.H_conorderId=ps.H_conorderId')
    ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
    ->join( 'products p', 'pos.ProductId=p.ProductId')
    ->where('pos.H_OrderDetailid', $id)
    ->orderBy('pos.H_OrderDetailid', 'Desc')
    ->get()->getRow();
  }

  public function getordersdetail($id,$city){
    $db = db_connect();
    return $db->table($city.'pickatstore_new ps')
    ->select("*,da.MobileNumber as mobile,da.Adress as address,pr.MobileNumber as mob")
    ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
    ->join($city . 'ps_orders pso', 'pso.PS_OrderId=ps.PS_OrderId')
    ->join('ps_order_recepients pr', 'pso.OrderRecipientId=pr.OrderRecipientId')
    ->join('ps_orderstatus pos', 'pos.PS_OrderStatusId=po.PS_OrderStatusId')
    ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
    ->join( 'products p', 'po.ProductId=p.ProductId')
    ->where('po.PS_OrderDetailsId', $id)
    ->orderBy('po.PS_OrderDetailsId', 'Desc')
    ->get()->getRow();
  }

  public function getpsorderstatus()
    {
    $db = db_connect();
    $user = $db->table('ps_oc_reasons')->where('CancellationBy',2)->where('ActiveStatus',1)->get()->getResult();
    if($user){
      return $user;
    }
    return false;
  }

  public function psordercancel($orderid,$pickid,$reasonid,$comments,$city){
    $db      = \Config\Database::connect();
    $builder = $db->table($city.'ps_orderdetails'); 
    $builder->set('PS_OrderStatusId', 8);
    $builder->where('PS_OrderDetailsId', $orderid);
    $cancel=$builder->update();
    if($cancel){
      $builder = $db->table($city.'ps_oc_reasonsmap');
      $items = array(
      'PickAtStoreId' => $pickid,
      'PS_OrderDetailsId' => $orderid,
      'PS_OC_ReasonsId' =>$reasonid,
      'UserComment' => $comments,

    );
    $builder->insert($items);
    }
    return $comments;
  }

  public function getreviews($prodid,$userid){
    $db = db_connect();
    return $db->table('reviews r')
    ->select('*')
    ->join( 'products p', 'r.ProductId=p.ProductId')
    ->where('UserId', $userid)
    ->where('p.ProductId', $prodid)
    ->get()->getRow();
  }

  public function insertrating($rating,$userid,$productid){
    $db = db_connect();
    $da= $db->table('reviews')
    ->select('*')
    ->where('UserId', $userid)
    ->where('ProductId', $productid)
    ->get()->getResult();
    if(count($da)>0){
      $builder = $db->table('reviews');
      $builder->set('Ratings', $rating);
      $builder->where('ProductId', $productid);
      $builder->where('UserId', $userid);
      $sta=$builder->update();
      if($sta){
        $sta1="updated"; 
      }
    }
    else{
      $builder = $db->table('reviews');
      $items = array(
      'ProductId' => $productid,
      'Ratings' => $rating,
      'UserId' =>$userid,
      );
      $builder->insert($items);
      $sta1="inserted"; 
    }
    return $sta1;
  }

  public function updatecomments($userid,$productid,$comment){
    $db = db_connect();
    $builder = $db->table('reviews');
    $builder->set('Comments', $comment);
    $builder->where('ProductId', $productid);
    $builder->where('UserId', $userid);
    $builder->update();
  } 

  public function userdetail($userid){
    $db = db_connect();
    return $db->table('useraccounts')->select('*') ->where('UserId', $userid)  ->get()->getRow();
  }
  public function getwishlist($userid,$city){
       $db = db_connect();
 return $db->table($city.'wishlist w')
    ->select('*')
     ->join('products p', 'w.ProductId=p.ProductId')
    ->where('UserId', $userid) ->get()->getResult();
    
  }
  public function deletewishproduct($wishid,$city){
 $db = db_connect();
  $builder = $db->table($city.'wishlist') ->where('WishListId', $wishid)->delete();
     
    
  }
  public function addtowishlist($userid,$pid,$city){
       $db = db_connect();
    $da= $db->table($city.'wishlist')
    ->select('*')
    ->where('UserId', $userid)
    ->where('ProductId', $pid)
    ->get()->getResult();
    if(count($da)>0){
    
     
        $sta1="already in wishlist"; 
      
    }
    else{
      $builder = $db->table('wishlist');
      $items = array(
      'ProductId' => $pid,
      'AddedFrom' => "AndroidApp",
      'UserId' =>$userid,
      );
      $builder->insert($items);
      $sta1="inserted"; 
    }
    return $sta1;
  }
}