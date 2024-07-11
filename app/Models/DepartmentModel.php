<?php namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table         = 'department';
    protected $returnType    = 'App\Entities\Department';

    function getCategoryByDepartmentId($id){
        $db = db_connect();
        return $db->table("maincategory")->where("DepartmentId",$id)->orderBy('DepartmentId','asc')->get()->getResult();
    }
    function getsubCategoryByDepartmentId($id){
        $db = db_connect();
        return $db->table("subcategory")->where("DepartmentId",$id)->orderBy('DepartmentId','asc')->get()->getResult();
    }
    function getCategoryByBrandId($id,$city){
        $db = db_connect();
        return $db->table("maincategory m")->join($city.'brandcategorymapping bcm','m.MainCategoryId=bcm.MainCategoryId')->where("bcm.BrandId",$id)->groupBy('m.MainCategoryId')->orderBy('m.DepartmentId','asc')->get()->getResult();
    }

    function getDepartmentByBrandId($id,$city){
        $db = db_connect();
        return $db->table("department m")->join($city.'brandcategorymapping bcm','m.DepartmentId=bcm.DepartmentId')->where("bcm.BrandId",$id)->groupBy('m.DepartmentId')->orderBy('m.DepartmentId','asc')->get()->getResult();
    }

    function getCategoryByDepandBrandId($depId, $brandId,$city){
        $db = db_connect();
        return $db->table("maincategory m")->where("m.DepartmentId", $depId)->join($city.'brandcategorymapping bcm','m.MainCategoryId=bcm.MainCategoryId')->where("bcm.BrandId",$brandId)->groupBy('m.MainCategoryId')->orderBy('m.MainCategoryId','asc')->get()->getResult();
    }
    function getSubCategoryByMaincategoryandBrandId($id, $brandId,$city){
        $db = db_connect();
        return $db->table("subcategory m")->where("m.MainCategoryId", $id)->join($city.'brandcategorymapping bcm','m.SubCategoryId=bcm.SubCategoryId')->where("bcm.BrandId",$brandId)->groupBy('m.SubCategoryId')->orderBy('m.SubCategoryId','asc')->get()->getResult();
    }
    function getCategoryCategoryandBrandId($id, $brandId,$city){
        $db = db_connect();
        return $db->table("maincategory m")->select("m.MaincategoryName,m.MainCategoryId")->where("m.DepartmentId", $id)->join($city.'brandcategorymapping bcm','m.MainCategoryId=bcm.MainCategoryId')->where("bcm.BrandId",$brandId)->groupBy('m.MainCategoryId')->orderBy('m.MainCategoryId','asc')->get()->getResult();
    }

    function getDubCategoryByMainCategoryId($id){
        $db = db_connect();
        return $db->table("subcategory")->where("MainCategoryId",$id)->get()->getResult();
    }

    function newArrival($city)
    {
        $db = db_connect();
        return $db->table("department d")
        ->select("d.DepartmentName,d.DepartmentId")
        ->join("products p","d.DepartmentId=p.DepartmentId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.Arrivalstatus",1)
        ->groupBy("p.DepartmentId")
        ->orderBy("p.DepartmentId")->get()->getResult();
    }
 function newdeal($city)
    {
        $db = db_connect();
            $where = "dp.MRP > dp.StorePrice";
        return $db->table("department d")
        ->select("d.DepartmentName,d.DepartmentId")
        ->join("products p","d.DepartmentId=p.DepartmentId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
            ->where('dp.QuantityAvailable > ',0)
            ->join('brands b', 'p.BrandId=b.BrandId')
            ->where('dp.ActiveStatus',1)
             ->where($where)
               ->groupBy("p.DepartmentId")
        ->orderBy("p.DepartmentId")->get()->getResult();
    }
    function browsebyShop($id, $city){
        set_time_limit(120);
        $db = db_connect();
        return $db->table($city."dealeraccounts da")
        ->select("da.DealerId,da.ShopName")
        ->join($city.'dealerdepartment dd', 'dd.DealerId = da.DealerId')
        ->where('dd.DepartmentId',$id)
      //   ->join('products p','p.DepartmentId=dd.DepartmentId')
        ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
        ->where('dp.QuantityAvailable >', 0)
        ->where('dp.ActiveStatus', 1) 
        ->where('da.VisibleStatus', 1)
        ->where('da.Activate', 1)

        ->groupBy('da.DealerId')->get()->getResult();
    }
    function browsebyBrand($id, $city){
        set_time_limit(120);
        $db = db_connect();
        return $db->table($city."dealeraccounts da")
        ->select("b.BrandName,b.BrandId")
        ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
        ->where('dd.DepartmentId',$id)
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
}