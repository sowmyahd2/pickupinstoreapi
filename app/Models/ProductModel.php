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
        ->select('*, count(dp.DealerId) as ShopCount')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('p.MainCategoryId ', $id)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
    }

    function brandDepartment($id,$city,$brandId){
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
        ->where('p.BrandId ', $brandId)
	    ->groupBy('dp.ProductId')
	    ->orderBy('dp.DealerPriceId', 'desc')
        ->limit(4,0)->get()->getResult();
    }

    function subcategoryProducts($id, $city,$limit=24,$offset=0,$brandIds=[], $min, $max,$sort){
        $db = db_connect();
        if($sort=="new"){

            $sortcol="dp.DealerPriceId";
            $sort="desc";
        }
        else{
            $sortcol="dp.SellingPrice";
            $sort=$sort;
        }
        
        if(count($brandIds) > 0) {
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
            ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
            ->where('p.SubCategoryId ', $id)
            ->whereIn('p.BrandId ', $brandIds)
            ->groupBy('dp.ProductId')
            ->orderBy($sortcol, $sort)
            ->limit($limit,$offset)->get()->getResult();
        }else {
            return $db->table($city.'dealerprice dp')
            ->select('*, count(da.DealerId) as ShopCount')
            ->where('dp.QuantityAvailable > ',0)
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
            ->join('department d', 'd.DepartmentId=p.DepartmentId')
            ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
            ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
            ->where('dp.ActiveStatus',1)
            ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
            ->where('da.Activate',1)
            ->where('da.VisibleStatus',1)
            ->where('p.SubCategoryId ', $id)
            ->groupBy('dp.ProductId')
            ->orderBy($sortcol, $sort)
            ->limit($limit,$offset)->get()->getResult();
        }

    }
    function similarproducts($city, $mainid,$subid,$limit=5,$offset=0){
      
        $db = db_connect();
        if($subid==0) {
            return $db->table($city.'dealerprice dp')
        ->select('p.ProductCode,p.DepartmentId,p.ProductId,p.ProductName,p.MainCategoryId,p.SubCategoryId,p.BrandId,p.Image1')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
         ->where('p.MainCategoryId ', $mainid)
         ->groupBy('dp.ProductId')
        ->orderBy('dp.ProductId')
        ->limit($limit,$offset)->get()->getResult();
        } else {
            return $db->table($city.'dealerprice dp')
        ->select('*')
        ->where('dp.QuantityAvailable > ',0)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->join('department d', 'd.DepartmentId=p.DepartmentId')
        ->join('maincategory m', 'm.MainCategoryId=p.MainCategoryId')
        ->join('subcategory s', 's.SubCategoryId=p.SubCategoryId', 'left')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.SubCategoryId ',$subid)
	    ->groupBy('dp.ProductId')
        ->orderBy('dp.ProductId')
        ->limit($limit,$offset)->get()->getResult();
        }


    }
    function maincategoryProducts($id,$city,$limit=24,$offset=0,$brandIds=[],$min,$max,$sort){
        if($sort=="new"){

            $sortcol="dp.DealerPriceId";
            $sort="desc";
        }
        else{
            $sortcol="dp.SellingPrice";
            $sort=$sort;
        }
        $db = db_connect();
        if(count($brandIds) > 0) {
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
        ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
        ->where('p.MainCategoryId ', $id)
        ->whereIn('p.BrandId ', $brandIds)
	    ->groupBy('dp.ProductId')
        ->orderBy($sortcol, $sort)
        ->limit($limit,$offset)->get()->getResult();
        } else {
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
        ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
        ->where('p.MainCategoryId ', $id)
	    ->groupBy('dp.ProductId')
	    ->orderBy($sortcol, $sort)
        ->limit($limit,$offset)->get()->getResult();
        }


    }

    function maincategoryProductsByBrandId($id,$city, $brandId, $limit=24,$offset=0,$brandIds=[],$min,$max,$sort){
        $db = db_connect();
        if($sort=="new"){

            $sortcol="dp.DealerPriceId";
            $sort="desc";
        }
        else{
            $sortcol="dp.SellingPrice";
            $sort=$sort;
        }
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
        ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
        ->where('p.BrandId ', $brandId)
	    ->groupBy('dp.ProductId')
        ->orderBy($sortcol, $sort)
        ->limit($limit,$offset)->get()->getResult();
    }

    function subCategoryProductsByBrandId($id,$city, $brandId, $limit=24,$offset=0,$brandIds=[],$min,$max,$sort){
        $db = db_connect();
        if($sort=="new"){

            $sortcol="dp.DealerPriceId";
            $sort="desc";
        }
        else{
            $sortcol="dp.SellingPrice";
            $sort=$sort;
        }
        if(count($brandIds) > 0){
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
        ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
        ->where('p.BrandId ', $brandId)
        ->whereIn('p.SubCategoryId ', $brandIds)
	    ->groupBy('dp.ProductId')
        ->orderBy($sortcol, $sort)
        ->limit($limit,$offset)->get()->getResult();
        } else {
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
        ->where('dp.SellingPrice BETWEEN "'. $min . '" and "'. $max .'"')
        ->where('p.BrandId ', $brandId)
	    ->groupBy('dp.ProductId')
        ->orderBy($sortcol, $sort)
        ->limit($limit,$offset)->get()->getResult();
        }
    }

    function detail($id, $city){
        $db = db_connect();
        return $db->table('products p')
        ->select('p.*,b.BrandName,b.BrandId,MIN(dp.SellingPrice) as LowestSellingPrice,MIN(dp.StorePrice) as LowestStorePrice,MRP,dp.ExpiryDate,dp.Origin')
        ->join('brands b', 'b.BrandId = p.BrandId')
        ->join($city.'dealerprice dp', 'dp.ProductId=p.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
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

    function ProductSellers($id, $city,$pincode){
        $db = db_connect();
        if($pincode!=0){
        return $db->table($city.'dealerprice dp')
        ->select('dp.DealerPriceId,dp.DealerId,dp.QuantityAvailable,dp.ReserveDays,da.ShopName,da.Adress,da.Locality,da.CityName,da.StateName,da.Latitude,da.Langtitude,MIN(dp.SellingPrice) as LowestSellingPrice,MIN(dp.StorePrice) as LowestStorePrice,MRP,dp.FreeShipmentStatus,dp.LocalShipmentCost,dp.ZoneShipmentCost,dp.NationalShipmentCost,dp.Return_Status,dp.QuantityAvailable,dlc.Dealer_Logistic_CostDetailsId,IFNULL(dlc.LocalMinOrderPrice,0) as LocalMinOrderPrice,IFNULL(dlc.ZoneMinOrderPrice,0) as ZoneMinOrderPrice,IFNULL(dlc.NationalMinOrderPrice,0) as NationalMinOrderPrice ',false)
        ->where('dp.ProductId', $id)
        ->join('products p', 'p.ProductId=dp.ProductId')
        ->join($city.'dealeraccounts da', 'da.DealerId=dp.DealerId')
        ->join($city.'dealer_logistic_costdetails dlc', 'dlc.DealerId=da.DealerId and dlc.ActiveStatus=1', 'left')
        ->where('dp.QuantityAvailable >', 0)
        ->where('dp.ActiveStatus', 1)
        ->where('da.PinCode', $pincode)
        ->where('da.VisibleStatus', 1)
        ->where('da.Activate', 1)
        ->orderBy('dp.SellingPrice', 'asc')
        ->groupBy('da.DealerId')
        ->get()->getResult();
        }
        else{
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
        ->get()->getResult();
        }
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