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
    function userdetail($userid)
	{
        $db = db_connect();
		return $db->table('useraccounts')
		->where('UserId',$userid)
		->get()->getRow();
	}
    function dealerdata($dealerid,$city)
    {
        $db = db_connect();
        return $db->table($city.'dealeraccounts')
        ->where('DealerId',$dealerid)
        ->get()->getRow();
    }
        public function getreciepeintdetail($userId,$city){
           $db = db_connect();
        $table = $city . "ps_order_recepients c";
return $db->table($table)
            ->select('c.*')
               ->where('c.UserId', $userId)
            
            ->get()->getRow();

    }
	function insertrecipient($data,$city){
		$db = db_connect();
    $table = $city."ps_order_recepients";
    
     $db->table($table)->insert($data);
        return $db->insertID();
    
	}
	function insertintopsorder($data,$city){
$db = db_connect();
    $table = $city."ps_orders";
    
     $db->table($table)->insert($data);
        return $db->insertID();

	}
        function insertintohmorder($data,$city){
$db = db_connect();
    $table = $city."hmainorder";
    
     $db->table($table)->insert($data);
        return $db->insertID();

    }
		function insertintopick($data,$city){
$db = db_connect();
    $table = $city."pickatstore_new";
    
     $db->table($table)->insert($data);
        return $db->insertID();
		
	}
            function insertintocon($data,$city){
$db = db_connect();
    $table = $city."hconorder";
    
     $db->table($table)->insert($data);
        return $db->insertID();
        
    }
	function insertintopickorderdetail($data,$city){
$db = db_connect();
    $table = $city."ps_orderdetails";
    
     $db->table($table)->insert($data);
        return $db->insertID();

	}
        function insertintohomeorderdetail($data,$city){
$db = db_connect();
    $table = $city."horderdetails";
    
     $db->table($table)->insert($data);
        return $db->insertID();

    }
	function get_pikcart($userid,$type,$city){

	 $db = db_connect();
        $table = "";
        switch ($type) {
            case 1:
                $table = $city . "cart c";
                break;
            case 2:
                $table = $city . "H_cart c";
                break;
            case 3:
                $table = $city . "pickatstore_cart c";
                break;
        }
        return $db->table($table)
         ->select('*')
          ->where('c.UserId', $userid)
           ->get()->getResult();

	}
		function orderdealerwise($userid,$type,$city){

	 $db = db_connect();
        $table = "";
        switch ($type) {
            case 1:
                $table = $city . "cart c";
                break;
            case 2:
                $table = $city . "H_cart c";
                break;
            case 3:
                $table = $city . "pickatstore_cart c";
                break;
        }
        return $db->table($table)
         ->select('c.*,SUM(Price*QuantityPurchased) as DealerPrice,MIN(dp.ReserveDays) as ReserveDays')
         ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
          ->where('c.UserId', $userid)
            ->groupBy('dp.DealerId')
           ->get()->getResult();

	}

	function dealerproductscart($dealerid,$type,$userid,$city){

	 $db = db_connect();
        $table = "";
        switch ($type) {
            case 1:
                $table = $city . "cart c";
                break;
            case 2:
                $table = $city . "H_cart c";
                break;
            case 3:
                $table = $city . "pickatstore_cart c";
                break;
        }
        return $db->table($table)
         ->select('*')
         ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
        ->where('c.UserId', $userid)
          ->where('c.DealerId', $dealerid)
           ->get()->getResult();

	}

		function deletepickproduct($userid,$type,$dealerpriceid, $city){
	

       $db = db_connect();
        $table = "";
        switch ($type) {
            case 1:
                $table = $city . "cart";
                break;
            case 2:
                $table = $city . "H_cart";
                break;
            case 3:
                $table = $city . "pickatstore_cart";
                break;
        }
$db->table($table) 
->where('userid', $userid)
->where('DealerPriceId', $dealerpriceid)->delete();

      
    
	}
    function updatedealerquanity($dealerpriceId,$city){
         $db = db_connect();
 $table = $city . "dealerprice";

  $this->db->set('QuantityAvailable', 'QuantityAvailable-1', false);
    $this->db->where('DealerPriceId' , $dealerpriceId);
    $this->db->update($table);
    }
}