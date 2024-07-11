<?php namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model
{
    protected $table         = 'brands';
    protected $returnType    = 'App\Entities\Brands';

    function offers($cityName, $id){
        $where = "dp.MRP > dp.SellingPrice";
        $db = db_connect();
        return $db->table("brands b")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($cityName."dealerprice dp","p.ProductId=dp.ProductId")
        ->where($where)
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where("p.DepartmentId",$id)
        ->groupby("b.BrandId")
        ->orderby("BrandName")
        ->limit(30,0)->get()->getResult();
    }
    function getcatBrand($cityName, $id){
      
        $db = db_connect();
        return $db->table("brands b")
        ->join("products p","b.BrandId=p.BrandId")
        ->where("p.DepartmentId",$id)
        ->groupby("b.BrandId")
        ->orderby("BrandName")
        ->limit(30,0)->get()->getResult();
    }
    function newArrivalbrands($cityName, $id){
      
          $db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($cityName."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.SubCategoryId",$id)
        ->where("p.Arrivalstatus",1)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")
        ->limit(30,0)->get()->getResult();
    }
    function newArrival($city,$id){
        $db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.DepartmentId",$id)
        ->where("p.Arrivalstatus",1)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")
        ->limit(30,0)->get()->getResult();
    }
    function offerProductsByBrand($id,$brandId,$city){
        $db = db_connect();
        $where = "dp.MRP > dp.SellingPrice";
        return $db->table("brands b")
        ->select("*,count(DISTINCT da.ShopName) AS shopname")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->join($city."dealeraccounts da","dp.DealerId=da.DealerId")
        ->where($where)
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where("p.DepartmentId",$id)
        ->where("p.BrandId",$brandId)
        ->groupBy("p.ProductId")
        ->limit(30,0)->get()->getResult();
    }

    function newArrivalsByBrand($id,$brandId,$city)
    {
        $db = db_connect();

        return $db->table("brands b")
        ->select("*,count(DISTINCT da.ShopName) AS shopname")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->join($city."dealeraccounts da","dp.DealerId=da.DealerId")
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where("p.DepartmentId",$id)
        ->where("p.BrandId",$brandId)
        ->where("p.Arrivalstatus",1)
        ->groupBy("p.ProductId")
        ->limit(30,0)->get()->getResult();
    }

    function newArrivalsBysubBrand($id,$brandId,$city)
    {
        $db = db_connect();

        return $db->table("brands b")
        ->select("*,count(DISTINCT da.ShopName) AS shopname")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->join($city."dealeraccounts da","dp.DealerId=da.DealerId")
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where("p.SubCategoryId",$id)
        ->where("p.BrandId",$brandId)
        ->where("p.Arrivalstatus",1)
        ->groupBy("p.ProductId")
        ->limit(30,0)->get()->getResult();
    }
    function departmentCategoryBrowseBy($id,$departmentId,$city){
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("d.DepartmentId,d.DepartmentName")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.DepartmentId', $departmentId)
            ->where('dd.brandId', $id)
            ->join('department d','d.DepartmentId=dd.DepartmentId')
            //   ->join('products p','p.DepartmentId=dd.DepartmentId')
            ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
            ->join('brands b', 'b.BrandId=dd.BrandId')
            ->where('dp.QuantityAvailable >', 0)
            ->where('dp.ActiveStatus', 1)
            ->where('da.VisibleStatus', 1)
            ->where('da.Activate', 1)
            ->orderBy('b.BrandName')
            ->groupBy('b.BrandId')->get()->getResult();
    }
    function departmentStoreBrowseBy($id,$departmentId,$city){
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("da.DealerId,da.ShopName")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.DepartmentId', $departmentId)
            ->where('dd.brandId', $id)
            //   ->join('products p','p.DepartmentId=dd.DepartmentId')
            ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
            ->where('dp.QuantityAvailable >', 0)
            ->where('dp.ActiveStatus', 1)
            ->where('da.VisibleStatus', 1)
            ->where('da.Activate', 1)

            ->groupBy('da.DealerId')->get()->getResult();
    }
    function categoryCategoryBrowseBy($id,$mainCategoryId,$city){
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("d.DepartmentId,d.DepartmentName")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.MainCategoryId', $mainCategoryId)
            ->where('dd.brandId', $id)
            ->join('department d','d.DepartmentId=dd.DepartmentId')
            //   ->join('products p','p.DepartmentId=dd.DepartmentId')
            ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
            ->join('brands b', 'b.BrandId=dd.BrandId')
            ->where('dp.QuantityAvailable >', 0)
            ->where('dp.ActiveStatus', 1)
            ->where('da.VisibleStatus', 1)
            ->where('da.Activate', 1)
            ->orderBy('b.BrandName')
            ->groupBy('b.BrandId')->get()->getResult();
    }
    function categoryStoreBrowseBy($id,$mainCategoryId,$city){
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("da.DealerId,da.ShopName")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.MainCategoryId', $mainCategoryId)
            ->where('dd.brandId', $id)
            //   ->join('products p','p.DepartmentId=dd.DepartmentId')
            ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
            ->where('dp.QuantityAvailable >', 0)
            ->where('dp.ActiveStatus', 1)
            ->where('da.VisibleStatus', 1)
            ->where('da.Activate', 1)

            ->groupBy('da.DealerId')->get()->getResult();
    }
}