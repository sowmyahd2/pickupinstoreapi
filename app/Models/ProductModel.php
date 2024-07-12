<?php namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $returnType    = 'App\Entities\Products';

    function department($id, $city)
    {
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'ASC')
        ->limit(4,0)->get()->getResult();
    }
    public function getgstslab(){
        $db = db_connect();
                 return $db->table('gst_slabs')->select('*')->where('ActiveStatus', 1)->get()->getResult();
        
        
        }
        function newarrival($city){
            $db = db_connect();
            return $db->table($city.'dealerprice dp')
          ->select('p.ProductId,ProductName,MRP,DepartmentId,MainCategoryId,SubCategoryId,Image1,StorePrice,ProductCode')
          ->join('products p', 'p.ProductId=dp.ProductId')
          ->where('dp.QuantityAvailable > ',0)
         
          ->where('dp.ActiveStatus',1)
          ->where('p.Arrivalstatus',1)
            ->groupBy('dp.ProductId')
            ->orderBy('dp.DealerPriceId', 'asc')
            ->limit(6,0)->get()->getResult();
        }
        function offerproducts($city){
            $db = db_connect();
            $where = "dp.MRP > dp.StorePrice";
            return $db->table($city.'dealerprice dp')
            ->select('p.ProductId,ProductName,MRP,DepartmentId,MainCategoryId,SubCategoryId,Image1,StorePrice,ProductCode')
            ->where('dp.QuantityAvailable > ',0)
            ->join('products p', 'p.ProductId=dp.ProductId')
          ->where('dp.ActiveStatus',1)
          ->where($where)
             ->groupBy('dp.ProductId')
            ->orderBy('dp.DealerPriceId', 'desc')
            ->limit(6,0)->get()->getResult();
        }
           function offerproduct($city,$id,$dpid){
            $db = db_connect();
            $where = "dp.MRP > dp.StorePrice";
            return $db->table($city.'dealerprice dp')
            ->select('p.ProductId,ProductName,MRP,DepartmentId,MainCategoryId,SubCategoryId,Image1,StorePrice,ProductCode')
            ->where('dp.QuantityAvailable > ',0)
            ->join('products p', 'p.ProductId=dp.ProductId')
          ->where('dp.ActiveStatus',1)
            ->where('p.BrandId',$id)
              ->where('p.DepartmentId', $dpid)
           
          ->where($where)
             ->groupBy('dp.ProductId')
            ->orderBy('dp.DealerPriceId', 'desc')
            ->limit(30,0)->get()->getResult();
        }
          function offerbrands($city,$id){
            $db = db_connect();
            $where = "dp.MRP > dp.StorePrice";
            return $db->table($city.'dealerprice dp')
            ->select('b.BrandId,BrandName,b.Logo')
            ->where('dp.QuantityAvailable > ',0)
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join('brands b', 'p.BrandId=b.BrandId')
            ->where('dp.ActiveStatus',1)
             ->where('p.DepartmentId',$id)
             ->where($where)
             ->groupBy('p.BrandId')
             ->orderBy('b.BrandName')
             ->get()->getResult();
        }
            function searchedbrands($city,$term){
            $db = db_connect();
            $where = "dp.MRP > dp.StorePrice";
            return $db->table($city.'dealerprice dp')
            ->select('b.BrandId,BrandName,b.Logo')
            ->where('dp.QuantityAvailable > ',0)
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join('brands b', 'p.BrandId=b.BrandId')
          ->where('dp.ActiveStatus',1)
          ->where($where)
                   ->like("b.BrandName",$term)
             ->groupBy('p.BrandId')
            ->orderBy('b.BrandName', 'desc')
            ->limit(30,0)->get()->getResult();
        }
    function brandDepartment($id,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.DepartmentId ', $id)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
    }

    function subcategoryProducts($id,$brandid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.SubCategoryId ', $id)
         ->where('p.BrandId ', $brandid)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')->get()->getResult();
      
    }
   function dealersubcategoryProducts($id,$dealerid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)
        ->where('p.SubCategoryId ', $id)
         ->where('da.DealerId ', $dealerid)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')->get()->getResult();
      
    }
    function dealermaincategoryProducts($id,$dealerid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)
        
 ->where('p.MainCategoryId ', $id)
         ->where('da.DealerId ', $dealerid)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')->get()->getResult();
      
    }
    
    function similialrsubcategoryProducts($id, $city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)
        ->where('p.SubCategoryId ', $id)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
      
    }



    function similialrMaincategoryProducts($id,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
    }

    function maincategoryProducts($id,$brandid,$city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
         ->where('p.BrandId ', $brandid)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(24,0)->get()->getResult();
    }

    function maincategoryProduct($id,$city,$start,$limit){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
        ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit($limit,$start)->get()->getResult();
    }
    function SubCategoryProd($id,$city,$start,$limit){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('p.*,dp.*')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
       
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.SubCategoryId ', $id)
        ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit($limit,$start)->get()->getResult();
    }
     function subcategoryProduct($id,$city,$start,$limit,$sort,$brandid){
        if($brandid!=0){
             $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)
->where('p.BrandId ',$brandid)
        ->where('p.SubCategoryId ', $id)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', $sort)
        ->limit($limit,$start)->get()->getResult();  
        }
        else{
            $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
        ->where('da.Activate',1)
        ->where('da.VisibleStatus',1)

        ->where('p.SubCategoryId ', $id)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', $sort)
        ->limit($limit,$start)->get()->getResult();   
        }
     
    }
    function maincategoryProductsByBrandId($id,$city, $brandId){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->where('d.DepartmentId ', $id)
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.BrandId ', $brandId)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(24,0)->get()->getResult();
    }

    function subCategoryProductsByBrandId($id,$city, $brandId){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*, count(da.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
        ->where('p.BrandId ', $brandId)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(24,0)->get()->getResult();
    }

    function detail($id,$city){
        $db = db_connect();
        return $db->table('products p')
        ->select('p.*,b.BrandName,b.BrandId,MIN(dp.SellingPrice) as MRP,MIN(dp.StorePrice) as LowestStorePrice,(MRP) as LowestStorePrice,dp.ExpiryDate,dp.Origin,Enable_PickatStore,Enable_Homedelivery')
        ->join('brands b', 'b.BrandId = p.BrandId')
        ->join($city.'dealerprice dp', 'dp.ProductId=p.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->where('p.ProductId', $id)
        ->groupBy('p.ProductId')
        ->get()->getRow();
    }
    function pricedetail($id,$city){
        $db = db_connect();
        return $db->table('products p')
        ->select('MIN(dp.SellingPrice) as MRP,MIN(dp.StorePrice) as LowestStorePrice,(MRP) as LowestStorePrice')
    ->join($city.'dealerprice dp', 'dp.ProductId=p.ProductId')
       ->where('p.ProductId', $id)
        ->groupBy('p.ProductId')
        ->get()->getRow();
    }
    
    function fetchsizeprice($id,$city,$size){
        $db = db_connect();
        return $db->table('products p')
        ->select('p.*,b.BrandName,b.BrandId,MIN(dp.SellingPrice) as MRP,MIN(dp.StorePrice) as LowestStorePrice,(MRP) as LowestStorePrice,dp.ExpiryDate,dp.Origin,Enable_PickatStore,Enable_Homedelivery')
        ->join('brands b', 'b.BrandId = p.BrandId')
        ->join($city.'dealerprice dp', 'dp.ProductId=p.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->join($city.'dealerproductspecification dps', 'dps.DealerPriceId = dp.DealerPriceId')
        ->where('p.ProductId', $id)
          ->where('dps.SpecificationValue', $size)
        ->groupBy('p.ProductId')
        ->get()->getRow();
    }
 function productdetail($id){
        $db = db_connect();
        return $db->table('products p')
        ->select('*')
        ->join('brands b', 'b.BrandId = p.BrandId')
        
        ->where('p.ProductId', $id)
        ->groupBy('p.ProductId')
        ->get()->getRow();
    }

    function ProductSpecification($id){
        $db = db_connect();
        return $db->table('productspecification spec')
        ->select('distinct(s.SpecificationName) as SpecificationName ,spec.SpecificationValue,Sequence')
        ->where('spec.ProductId', $id)
        ->join('products p', 'p.ProductId=spec.ProductId')
        ->join('specifications s', 's.SpecificationId = spec.SpecificationId')
        ->join('specificationsmapping sm', 'sm.SpecificationId = spec.SpecificationId and p.DepartmentId=sm.DepartmentId and sm.MainCategoryId=p.MainCategoryId and sm.SubCategoryId = p.SubCategoryId')
        ->where('SpecificationValue !=',"N/A")
        ->groupBy('spec.SpecificationId')
        ->orderBy('Sequence','asc')
        ->get()->getResult();
    }
function ProductSeller($id,$city){
   $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('dp.DealerPriceId,dp.DealerId,dp.QuantityAvailable,dp.ReserveDays,da.ShopName,da.Adress,da.Locality,da.CityName,da.StateName,da.Latitude,da.Langtitude,MIN(dp.SellingPrice) as LowestSellingPrice,MIN(dp.StorePrice) as LowestStorePrice,MRP,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dp.Return_Status,dp.QuantityAvailable,dlc.Dealer_Logistic_CostDetailsId,IFNULL(dlc.LocalMinOrderPrice,0) as LocalMinOrderPrice,IFNULL(dlc.ZoneMinOrderPrice,0) as ZoneMinOrderPrice,IFNULL(dlc.NationalMinOrderPrice,0) as NationalMinOrderPrice ',false)
        ->where('dp.ProductId', $id)
         ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->join($city.'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
        ->where('dp.QuantityAvailable >', 0)
        ->where('dp.ActiveStatus', 1)
        ->where('da.VisibleStatus', 1)
        ->where('da.Activate', 1)
        ->orderBy('dp.SellingPrice', 'asc')
        ->groupBy('da.DealerId')
         ->get()->getResult();  
}
    function ProductSellers($id,$limit,$offset, $city){

        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('dp.DealerPriceId,dp.DealerId,dp.QuantityAvailable,dp.ReserveDays,da.ShopName,da.Adress,da.Locality,da.CityName,da.StateName,da.Latitude,da.Langtitude,MIN(dp.SellingPrice) as LowestSellingPrice,MIN(dp.StorePrice) as LowestStorePrice,MRP,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dp.Return_Status,dp.QuantityAvailable,dlc.Dealer_Logistic_CostDetailsId,IFNULL(dlc.LocalMinOrderPrice,0) as LocalMinOrderPrice,IFNULL(dlc.ZoneMinOrderPrice,0) as ZoneMinOrderPrice,IFNULL(dlc.NationalMinOrderPrice,0) as NationalMinOrderPrice ',false)
        ->where('dp.ProductId', $id)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->join($city.'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
        ->where('dp.QuantityAvailable >', 0)
        ->where('dp.ActiveStatus', 1)
        ->where('da.VisibleStatus', 1)
        ->where('da.Activate', 1)
        ->orderBy('dp.SellingPrice', 'asc')
        ->groupBy('da.DealerId')
         ->limit($offset,$limit)->get()->getResult();
       
    }
   function ProductSellerscount($id,$city){
   
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('da.DealerId')
        ->where('dp.ProductId', $id)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join('dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->join('dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
        ->where('dp.QuantityAvailable >', 0)
        ->where('dp.ActiveStatus', 1)
        ->where('da.VisibleStatus', 1)
        ->where('da.Activate', 1)
        ->orderBy('dp.SellingPrice', 'asc')
        ->groupBy('da.DealerId')->get()->getResult();
        
       
    }
    
    	public function fetchprice($product,$size,$city)
	{
		echo $city;
 	   $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('(dp.QuantityAvailable) as Stock,da.ShopName,dp.ProductId,dp.MRP, (dp.SellingPrice) as SellingPrice,dp.StorePrice,dp.QuantityAvailable,dp.DealerId,da.DealerId,dp.DealerPriceId,dp.Return_Status,dp.OfferDescription,dp.FreeShipmentStatus,dlc.LocalMinOrderPrice,dp.LocalShipmentCost')
          ->join('dealerproductspecification dps','dp.DealerPriceId = dps.DealerPriceId')
          ->where('dps.SpecificationValue', $size)
          ->where('dp.ProductId',$product)
          ->where('dp.QuantityAvailable >',0)
          ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
          ->join('dealer_logistic_costdetails dlc','dlc.DealerId = dp.DealerId and dlc.ActiveStatus=1','left') ->orderBy('dps.SpecificationValue')
        ->groupBy('dps.SpecificationValue')->get()->getRow();
	}

    public function dealerproducts($dealerid,$city){
      $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_"; 
   
       $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select('*')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->where('dp.ActiveStatus',1)
        ->where('dp.DealerId ', $dealerid)
        ->groupBy('dp.ProductId')
        ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(0,5)->get()->getResult();
    }
    function SellingSizes($id, $city){
        $db = db_connect();
        return $db->table($city.'dealerprice dp')
        ->select("dps.SpecificationName,dps.SpecificationValue")
        ->join('dealerproductspecification dps','dp.DealerPriceId = dps.DealerPriceId')
        ->where('dp.ProductId',$id)
        ->orderBy('dps.SpecificationValue')
        ->groupBy('dps.SpecificationValue')
        ->get()->getResult();
    }
    function get_reviews($id)
	{
        $db = db_connect();
		return $db->table('reviews')
		->where('ProductId',$id)
		->orderBy("up", "desc")
        ->get()->getResult();
    }
    

}