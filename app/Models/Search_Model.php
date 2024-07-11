<?php

namespace App\Models;

use CodeIgniter\Model;

class Search_Model extends Model
{
    function getMainCategory($term, $id)
    {
        $db = db_connect();
        if ($id !== "All") {
            return $db->table('maincategory mc')
                ->join('department dp', 'mc.DepartmentId = dp.DepartmentId')
                ->where("dp.DepartmentId", $id)
                ->like("mc.MainCategoryName", $term, 'both')
                ->where('dp.Status', 1)
                ->groupBy('mc.MainCategoryId')
                ->orderBy('mc.MainCategoryName', 'asc')
                ->limit(5, 0)->get()->getResult();
        } else {
            return $db->table('maincategory mc')
                ->join('department dp', 'mc.DepartmentId = dp.DepartmentId')
                ->like("mc.MainCategoryName", $term, 'both')
                ->where('dp.Status', 1)
                ->groupBy('mc.MainCategoryId')
                ->orderBy('mc.MainCategoryName', 'asc')
                ->limit(5, 0)->get()->getResult();
        }
    }
    function getSubCategory($term)
    {
        $db = db_connect();
        return $db->table("subcategory b")
            ->select("group_concat(SubCategoryId)as SubCategoryId")
            ->like('b.SubCategoryName', $term, 'after')
            ->limit(5, 0)->get()->getResult();
    }

    function getBrands($term)
    {
        $db = db_connect();
        return $db->table("brands b")
            ->select("b.BrandName,b.BrandId")
            ->like('b.BrandName', $term, 'after')
            ->limit(5, 0)->get()->getResult();
    }
    public function getshop($term, $dept, $city)

    {
        $db = db_connect();
        if ($dept == "All") {
            return $db->table($city . 'dealeraccounts da')
            ->select("da.ShopName,da.DealerId,LandlineNumber,MobileNumber")
            ->join($city . 'dealerprice dp', 'dp.DealerId = da.DealerId')
            ->join('products p', 'p.ProductId = dp.ProductId')
            ->like('da.ShopName', $term, 'after')
            ->where('Activate', 1)
            ->where('dp.QuantityAvailable >', 0)
            ->groupBy("da.DealerId")
            ->limit(5, 0)->get()->getResult();
        } else {
            return $db->table($city . 'dealeraccounts da')
            ->select("da.ShopName,da.DealerId,LandlineNumber,MobileNumber")
            ->join($city . 'dealerprice dp', 'dp.DealerId = da.DealerId')
            ->join('products p', 'p.ProductId = dp.ProductId')
            ->like('da.ShopName', $term, 'after')
            ->whereIn('p.DepartmentId', $dept)
            ->where('Activate', 1)
            ->where('dp.QuantityAvailable >', 0)
            ->groupBy("da.DealerId")
            ->limit(5, 0)->get()->getResult();
        }
    }
}
