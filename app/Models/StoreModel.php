<?php namespace App\Models;

use CodeIgniter\Model;

class StoreModel extends Model
{
    protected $table         = 'dealeraccounts';
    protected $returnType    = 'App\Entities\Stores';


    function getStoreDetail($id, $city){
        $db = db_connect();

        $fields = "(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress";
        return $db->table($city."dealeraccounts da")
        ->select("$fields,avg(Ratings) as Ratings,count(dr.DealerReviewId)as Reviews,ShopTimings,BreakTime,PaymentOption,dc.LocalMinOrderPrice,VideoLink")
        ->where('da.VisibleStatus', 1)
        ->join($city.'dealerprice dp','dp.DealerId=da.DealerId ')
		->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealer_logistic_costdetails dc', 'da.DealerId = dc.DealerId and dc.ActiveStatus=1','left')
		->join($city.'dealerreviews dr', 'dr.DealerId = da.DealerId','left')
        ->join($city.'dealershoptimings dst', 'dst.DealerId = da.DealerId', 'left')
        ->join($city.'dealer_videos dv', 'dv.DealerId=da.DealerId', 'left')
       ->where("da.DealerId",$id)->get()->getRow();
    }
 function getstoredepartment($id, $city){
        $db = db_connect(); 
        
        return $db->table($city."dealeraccounts da")
        ->select("dp.*,d.DepartmentName,da.ShopName")
        ->where('da.VisibleStatus', 1)
        ->join($city.'dealerdepartment dp','dp.DealerId=da.DealerId ')
		->join('department d','dp.DepartmentId=d.DepartmentId ')
        ->where("dp.DepartmentId",$id)->groupBy('da.DealerId')->get()->getResult();
    }
     function getstoredepartments($id, $city){
        $db = db_connect(); 
        
        return $db->table($city."dealeraccounts da")
        ->select("(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress")
        ->where('da.VisibleStatus', 1)
        ->join($city.'dealerdepartment dp','dp.DealerId=da.DealerId ')
        ->join('department d','dp.DepartmentId=d.DepartmentId ')
        ->where("dp.DepartmentId",$id)->groupBy('da.DealerId')->get()->getResult();
    }
     function dealerdetail($id, $city){
        $db = db_connect(); 
        
        return $db->table($city."dealeraccounts da")
        ->select("(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress")
        ->where('da.DealerId', $id)
       ->get()->getRow();
    }
        function getstores($cityName){
              $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $db = db_connect(); 
        
        return $db->table($city."dealeraccounts da")
        ->select("(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress")->limit(10,0)->get()->getResult();
     
    }
      function getdepartmentstores($cityName){
              $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $db = db_connect(); 
        
        return $db->table($city."dealeraccounts da")
        ->select("(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress")
          ->join("dealerdepartment dd","da.DealerId=dd.DealerId")
          ->limit(24,0)->get()->getResult();
     
    }
    public function shoplist($cityName,$term){
         $city = $cityName == "mysuru" ? "" : $cityName . "_";
         $db = db_connect(); 
         
          return $db->table($city."dealeraccounts da")
          ->select("group_concat(DealerId) as DealerId")
         ->like("da.ShopName",$term,)
          ->get()->getRow();
    }
    function searchedstores($cityName,$term){
        $thePostIdArray = explode(',', $term);
     
         $city = $cityName == "mysuru" ? "" : $cityName . "_";
          $db = db_connect(); 
 return $db->table($city."dealeraccounts da")
          ->select("(da.DealerId) as DealerId,da.ShopName,da.ShopLogo,da.CityName,da.PinCode,da.Locality,da.LandMark,da.Latitude,da.Langtitude,MobileNumber,adress")
         ->whereIn('da.DealerId', $thePostIdArray)
          ->get()->getResult();

    }
    function storefrontcategory($id,$city)
	{
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
		->select('mc.MainCategoryName,mc.MainCategoryId')
		->where('dp.DealerId', $id)
		->where('dp.ActiveStatus', 1)
		->join('products p', 'p.ProductId=dp.ProductId')
		->join('maincategory mc', 'mc.MainCategoryId=p.MainCategoryId')
		->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->groupBy('mc.MainCategoryId')
       ->get()->getResult();
    }

    function storefrontsubcategory($id,$city, $maincategoryId)
	{
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
		->select('mc.SubCategoryName,mc.SubCategoryId')
		->where('dp.DealerId', $id)
		->where('dp.ActiveStatus', 1)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join('subcategory mc', 'mc.SubCategoryId=p.SubCategoryId')
        ->where('mc.MainCategoryId',$maincategoryId)
		->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->groupBy('mc.SubCategoryId')
        ->orderBy('dd.DisplayPriority', 'asc')
        ->get()->getResult();
    }

    function storefrontsubcategorybrands($id,$city, $maincategoryId)
	{
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
		->select('b.BrandName,b.BrandId')
		->where('dp.DealerId', $id)
		->where('dp.ActiveStatus', 1)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
		->join('subcategory mc', 'mc.SubCategoryId=p.SubCategoryId')
        ->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->where('mc.MainCategoryId',$maincategoryId)
        ->groupBy('b.BrandId')
        ->orderBy('b.BrandName', 'asc')
        ->get()->getResult();
    }
  function storefrontcatogrybrands($id,$city, $department)
    {
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('b.BrandName,b.BrandId')
        ->where('dp.DealerId', $id)
        ->where('dp.ActiveStatus', 1)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
        ->join('subcategory mc', 'mc.SubCategoryId=p.SubCategoryId')
        ->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->where('dd.DepartmentId',$department)
        ->groupBy('b.BrandId')
        ->orderBy('b.BrandName', 'asc')
        ->get()->getResult();
    }
    function storefrontcategorybyDepartmentId($id,$city,$dealerId){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
		->select('mc.MainCategoryName,mc.MainCategoryId')
		->where('dp.DealerId', $dealerId)
		->where('dp.ActiveStatus', 1)
		->join('products p', 'p.ProductId=dp.ProductId')
        ->join('maincategory mc', 'mc.MainCategoryId=p.MainCategoryId')
        ->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->where('dd.DepartmentId',$id)
        ->groupBy('dd.DepartmentId')
        ->orderBy('dd.DisplayPriority', 'asc')
        ->get()->getResult();
    }

    function storefrontDepartment($id,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
		->select('d.DepartmentId,d.DepartmentName')
		->where('dp.DealerId', $id)
		->where('dp.ActiveStatus', 1)
		->join('products p', 'p.ProductId=dp.ProductId')
        ->join('dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->join('department d', 'dd.DepartmentId = d.DepartmentId')
        ->groupBy('dd.DepartmentId')
        ->orderBy('dd.DisplayPriority', 'asc')
        ->get()->getResult();
    }
    
    function storefrontcategoryProducts($id,$maincategoryid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('p.DepartmentId,p.MainCategoryId,p.ProductId,p.SubCategoryId,p.Image1,p.UploadedDate,p.ProductName,p.ProductCode,dp.DealerId,MIN(dp.SellingPrice) AS SellingPrice,MIN(dp.StorePrice) AS StorePrice,p.BrandId,MIN(dp.MRP) as MRP,da.CityName,da.ShopName,mc.MainCategoryName,sc.SubCategoryName,dc.LocalMinOrderPrice')
		->where('dp.DealerId', $id)
		->where('dp.QuantityAvailable > ', 0)
		->where('dp.ActiveStatus',1)
		->join('products p', 'p.ProductId=dp.ProductId')
		->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId and da.Activate=1 and da.VisibleStatus=1')
		->join('dealer_logistic_costdetails dc', 'da.DealerId = dc.DealerId and dc.ActiveStatus=1','LEFT')
		->join('maincategory mc', 'p.MainCategoryId = mc.MainCategoryId')
		->join('subcategory sc', 'sc.SubCategoryId=p.SubCategoryId', 'left')
		->where('p.MainCategoryId', $maincategoryid)
		->groupBy('dp.ProductId')
		->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
    }
    function storefrontcategoryProduct($id,$maincategoryid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*')
		->where('dp.DealerId', $id)
		->where('dp.QuantityAvailable > ', 0)
		->where('dp.ActiveStatus',1)
		->join('products p', 'p.ProductId=dp.ProductId')
	->where('p.MainCategoryId', $maincategoryid)
		->groupBy('dp.ProductId')
		->orderBy('dp.DealerPriceId', 'desc')
        ->limit(2,0)->get()->getResult();
    }
     function storecategoryProducts($id,$maincategoryid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*')
        ->where('dp.DealerId', $id)
        ->where('dp.QuantityAvailable > ', 0)
        ->where('dp.ActiveStatus',1)
        ->join('products p', 'p.ProductId=dp.ProductId')
    ->where('p.MainCategoryId', $maincategoryid)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(30,0)->get()->getResult();
    }
        function storefrontdepartmentProducts($id,$department,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('p.DepartmentId,p.MainCategoryId,p.ProductId,p.SubCategoryId,p.Image1,p.UploadedDate,p.ProductName,p.ProductCode,dp.DealerId,MIN(dp.SellingPrice) AS SellingPrice,MIN(dp.StorePrice) AS StorePrice,p.BrandId,MIN(dp.MRP) as MRP,da.CityName,da.ShopName,mc.MainCategoryName,sc.SubCategoryName,dc.LocalMinOrderPrice')
        ->where('dp.DealerId', $id)
        ->where('dp.QuantityAvailable > ', 0)
        ->where('dp.ActiveStatus',1)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId and da.Activate=1 and da.VisibleStatus=1')
        ->join($city.'dealerdepartment dd', 'dd.DepartmentId = p.DepartmentId')
        ->join('dealer_logistic_costdetails dc', 'da.DealerId = dc.DealerId and dc.ActiveStatus=1','LEFT')
        ->join('maincategory mc', 'p.MainCategoryId = mc.MainCategoryId')
        ->join('subcategory sc', 'sc.SubCategoryId=p.SubCategoryId', 'left')
        ->where('p.DepartmentId', $department)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(6,0)->get()->getResult();
    }
    function storefrontMainCategoryProducts($id,$maincategoryid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('p.DepartmentId,p.MainCategoryId,p.ProductId,p.SubCategoryId,p.Image1,p.UploadedDate,p.ProductName,p.ProductCode,dp.DealerId,MIN(dp.SellingPrice) AS SellingPrice,MIN(dp.StorePrice) AS StorePrice,p.BrandId,MIN(dp.MRP) as MRP,da.CityName,da.ShopName,mc.MainCategoryName,sc.SubCategoryName,dc.LocalMinOrderPrice')
		->where('dp.DealerId', $id)
		->where('dp.QuantityAvailable > ', 0)
		->where('dp.ActiveStatus',1)
		->join('products p', 'p.ProductId=dp.ProductId')
		->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId and da.Activate=1 and da.VisibleStatus=1')
		->join('dealer_logistic_costdetails dc', 'da.DealerId = dc.DealerId and dc.ActiveStatus=1','LEFT')
		->join('maincategory mc', 'p.MainCategoryId = mc.MainCategoryId')
		->join('subcategory sc', 'sc.SubCategoryId=p.SubCategoryId', 'left')
		->where('p.MainCategoryId', $maincategoryid)
		->groupBy('dp.ProductId')
		->orderBy('dp.DealerPriceId', 'desc')
        ->limit(24,0)->get()->getResult();
    }
  function storefrontSubCategoryProducts($id,$subcategoryid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('p.DepartmentId,p.MainCategoryId,p.ProductId,p.SubCategoryId,p.Image1,p.UploadedDate,p.ProductName,p.ProductCode,dp.DealerId,MIN(dp.SellingPrice) AS SellingPrice,MIN(dp.StorePrice) AS StorePrice,p.BrandId,MIN(dp.MRP) as MRP,da.CityName,da.ShopName,mc.MainCategoryName,sc.SubCategoryName,dc.LocalMinOrderPrice')
        ->where('dp.DealerId', $id)
        ->where('dp.QuantityAvailable > ', 0)
        ->where('dp.ActiveStatus',1)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId and da.Activate=1 and da.VisibleStatus=1')
        ->join('dealer_logistic_costdetails dc', 'da.DealerId = dc.DealerId and dc.ActiveStatus=1','LEFT')
        ->join('maincategory mc', 'p.MainCategoryId = mc.MainCategoryId')
        ->join('subcategory sc', 'sc.SubCategoryId=p.SubCategoryId', 'left')
        ->where('p.SubCategoryId', $subcategoryid)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(24,0)->get()->getResult();
    }
}