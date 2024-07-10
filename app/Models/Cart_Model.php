<?php

namespace App\Models;

use CodeIgniter\Model;

class Cart_Model extends Model
{
    public function getProductDetali($id, $city)
    {
        $db = db_connect();

        return $db->table($city . 'dealerprice dp')
            ->select('p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,Group_concat(dps.SpecificationName) as SpecificationName ,Group_concat(dps.SpecificationValue) as SpecificationValue,da.ShopName,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dlc.LocalMinOrderPrice,dlc.ZoneMinOrderPrice,dlc.NationalMinOrderPrice')
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city . 'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId', 'left')
            ->where('dp.DealerPriceId', $id)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dp.DealerId')
            ->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
            ->get()->getRow();
    }

    public function add($item, $type, $city)
    {
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

        $db->table($table)->insert($item);
        return $db->insertID();
    }
    public function view($type, $city, $userId)
    {
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
            ->select('dp.DealerPriceId,p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,Group_concat(dps.SpecificationName) as SpecificationName ,Group_concat(dps.SpecificationValue) as SpecificationValue,da.ShopName,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dlc.LocalMinOrderPrice,dlc.ZoneMinOrderPrice,dlc.NationalMinOrderPrice,QuantityPurchased')
            ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city . 'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId', 'left')
            ->where('c.UserId', $userId)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dp.DealerId')
            ->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
            ->groupBy('c.DealerPriceId')
            ->get()->getResult();
    }
        public function pickupcartview($type, $city, $userId)
    {
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
            ->select('dp.DealerPriceId,p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,da.ShopName,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,QuantityPurchased')
            ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
            ->join('products p', 'p.ProductId=dp.ProductId')
     
            ->where('c.UserId', $userId)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dp.DealerId')
          
            ->groupBy('c.DealerPriceId')
            ->get()->getResult();
    }
    public function getreserverdays($userId,$city){
           $db = db_connect();
        $table = $city . "pickatstore_cart c";
return $db->table($table)
            ->select('min(dps.ReserveDays) as ReserveDays')
             ->join($city . 'dealerprice dps', 'dps.DealerPriceId = c.DealerPriceId')
            ->join('products p', 'p.ProductId=dps.ProductId')
           
            ->where('c.UserId', $userId)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dps.DealerId')
            ->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
            ->groupBy('c.DealerPriceId')
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
      public function getsellerdetail($type,$userId,$city){
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
            ->select('da.*')
             ->join($city . 'dealerprice dps', 'dps.DealerPriceId = c.DealerPriceId')
            ->join('products p', 'p.ProductId=dps.ProductId')
            ->where('c.UserId', $userId)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dps.DealerId')
            ->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
            ->groupBy('da.DealerId')
            ->get()->getResult();
          

}

}
