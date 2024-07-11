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
    function getbrands(){
         $db = db_connect();
      return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo")
        
        ->limit(9,0)->get()->getResult();  
    }
function serachedBrands($term){
    
        $db = db_connect(); 
        
          return $db->table("brands da")
          ->select("BrandId,BrandName,Logo")
         ->like("da.BrandName",$term,'after')
          ->get()->getResult();
}
function get_whatsapppromotion($city,$term){
    
        $db = db_connect(); 
        
          return $db->table($city."brandcategorymapping da")
          ->select("p.*")
          ->join("dealerdepartment dd","da.DepartmentId=dd.DepartmentId")
           ->join("brand_promotion p","p.BrandId=da.BrandId")
           ->where("dd.DealerId",$term)
          ->get()->getResult();
}
    function get_allBrands($city, $id){
      $db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
       ->where("p.DepartmentId",$id)
        ->where("p.Arrivalstatus",1)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")->get()->getResult();
      
    }
        function get_catBrands($city, $id){
      $db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
       ->where("p.DepartmentId",$id)
        ->where("p.Arrivalstatus",1)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")->limit(9,0)->get()->getResult();  
      
    }
    function newArrival($id,$city,$start,$limit){
        $db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo,p.DepartmentId")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.Arrivalstatus",1)
           ->where("p.DepartmentId",$id)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")
        ->limit($limit,$start)->get()->getResult();
    }
     function newArrivalproducts($id,$city,$dpid){
        $db = db_connect();
        return $db->table("brands b")
        ->select("*")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.Arrivalstatus",1)
           ->where("p.DepartmentId",$dpid)
            ->where("p.BrandId",$id)
        ->groupBy("p.ProductId")
        ->orderBy("p.ProductId")
        ->get()->getResult();
    }
     public function brandlistlist($cityName,$term){
         $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $db = db_connect(); 
        
          return $db->table($city."brands da")
          ->select("group_concat(BrandId) as BrandId")
         ->like("da.BrandName",$term,)
          ->get()->getRow();
    }
    function newarrivalsearchedbrand($city,$term){
        $term=$this->brandlistlist($city,$term)->BrandId;
         $city = $city == "mysuru" ? "" : $city . "_";
         $thePostIdArray = explode(',', $term);
$db = db_connect();
        return $db->table("brands b")
        ->select("b.BrandName,b.BrandId,b.Logo,p.DepartmentId")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->whereIn('p.BrandId', $thePostIdArray)
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
        ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
        ->where("p.Arrivalstatus",1)
        ->groupBy("b.BrandId")
        ->orderBy("BrandName")
        ->get()->getResult();
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
    function categoryProductsByBrand($brandId,$city){
        $db = db_connect();
       
        return $db->table("brands b")
        ->select("*,count(DISTINCT da.ShopName) AS shopname")
        ->join("products p","b.BrandId=p.BrandId")
        ->join($city."dealerprice dp","p.ProductId=dp.ProductId")
        ->join($city."dealeraccounts da","dp.DealerId=da.DealerId")
        ->where("QuantityAvailable >",0)
        ->where("dp.ActiveStatus",1)
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