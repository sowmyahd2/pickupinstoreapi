<?php

namespace App\Models;

use CodeIgniter\Model;

class Cart_Model extends Model
{
    public function getProductDetali($id, $city)
    {
        $db = db_connect();

        return $db->table($city.'dealerprice dp')
            ->select('p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,Group_concat(dps.SpecificationName) as SpecificationName ,Group_concat(dps.SpecificationValue) as SpecificationValue,da.ShopName,da.Adress,da.MobileNumber,da.ShopLogo,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dlc.LocalMinOrderPrice,dlc.ZoneMinOrderPrice,dlc.NationalMinOrderPrice')
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city.'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId', 'left')
            ->where('dp.DealerPriceId', $id)
            ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
            ->join($city.'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
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
    public function removeproduct($userid,$type,$pid,$city){
       
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
        $d=$db->table($table) 
        ->where('userid', $userid)
       ->where('DealerPriceId', $pid)->delete();

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
            ->select('p.ProductName,p.ProductId,p.ProductCode,p.Image1,dp.SellingPrice,dp.MRP,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,Group_concat(dps.SpecificationName) as SpecificationName ,Group_concat(dps.SpecificationValue) as SpecificationValue,da.ShopName,da.Adress,da.ShopLogo,da.MobileNumber,da.DealerId,dp.ReserveDays,dp.StorePrice,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dlc.LocalMinOrderPrice,dlc.ZoneMinOrderPrice,dlc.NationalMinOrderPrice,QuantityPurchased, dp.DealerPriceId')
            ->join($city.'dealerprice dp', 'dp.DealerPriceId=c.DealerPriceId')
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city . 'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId', 'left')
            ->where('c.UserId', $userId)
            ->join($city . 'dealeraccounts da', 'da.DealerId=dp.DealerId')
            ->join($city . 'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
            ->groupBy('c.DealerPriceId')
            ->get()->getResult();
    }
}
