<?php namespace App\Models;

use CodeIgniter\Model;
class SubCategoryModel extends Model
{
    protected $table         = 'subcateegory';
    protected $returnType    = 'App\Entities\Products';

    public function getMainCategoryBrandsById($id, $city)
    {
        $db = db_connect();
        return $db->table('brands b')
            ->select("b.BrandName,b.BrandId")
            ->where('b.BrandType', 0)
            ->join($city . 'brandcategorymapping bcm', 'b.BrandId=bcm.BrandId')
            ->join('products p', 'p.BrandId=b.BrandId')
            ->join($city . 'dealerprice dp', 'dp.ProductId=p.ProductId')
            ->join($city . 'dealeraccounts da', 'da.DealerId = dp.DealerId')
            ->where('dp.ActiveStatus', 1)
            ->where('da.Activate', 1)
            ->where('da.VisibleStatus', 1)
            ->where('p.SubCategoryId ', $id)
            ->where('dp.QuantityAvailable > ', 0)
            ->groupBy('b.BrandId')
            ->orderBy('b.BrandName', 'asc')
            ->limit(20, 0)
            ->get()->getResult();
    }
    public function getMainCategoryLocalBrandsById($id, $city)
    {
        $db = db_connect();
        return $db->table('brands b')
            ->select("b.BrandName,b.BrandId")
            ->where('b.BrandType', 1)
            ->join($city . 'brandcategorymapping bcm', 'b.BrandId=bcm.BrandId')
            ->join('products p', 'p.BrandId=b.BrandId')
            ->join($city . 'dealerprice dp', 'dp.ProductId=p.ProductId')
            ->join($city . 'dealeraccounts da', 'da.DealerId = dp.DealerId')
            ->where('dp.ActiveStatus', 1)
            ->where('da.Activate', 1)
            ->where('da.VisibleStatus', 1)
            ->where('p.SubCategoryId ', $id)
            ->where('dp.QuantityAvailable > ', 0)
            ->groupBy('b.BrandId')
            ->orderBy('b.BrandName', 'asc')
            ->limit(20, 0)
            ->get()->getResult();
    }

    public function filter($id, $city)
    {
        $db = db_connect();
        return $db->table('filters f')
            ->select('SpecificationId, SpecificationName')
            ->where('SubCategoryId', $id)
            ->get()->getResult();
    }

    public function dealerSpecification($specificationId, $id, $city)
    {
        $db = db_connect();
        return $db->table($city . 'dealerproductspecification dps')
            ->select("dps.SpecificationId, dps.SpecificationValue")
            ->where('dps.SpecificationId', $specificationId)
            ->join($city . 'dealerprice dp', 'dp.DealerPriceId=dps.DealerPriceId')
            ->join('products p', 'p.ProductId=dp.ProductId')
            ->join($city . 'dealeraccounts da', 'da.DealerId = dp.DealerId')
            ->where('dp.ActiveStatus', 1)
            ->where('da.Activate', 1)
            ->where('da.VisibleStatus', 1)
            ->where('p.SubCategoryId ', $id)
            ->where('dp.QuantityAvailable > ', 0)
            ->groupBy('dps.SpecificationValue')
            ->orderBy('dps.SpecificationValue', 'asc')
            ->limit(20, 0)
            ->get()->getResult();
    }
    function browsebyShop($id, $city)
    {
        set_time_limit(120);
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("da.DealerId,da.ShopName")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.SubCategoryId', $id)
            //   ->join('products p','p.DepartmentId=dd.DepartmentId')
            ->join($city.'dealerprice dp', 'dp.DealerId=da.DealerId')
            ->where('dp.QuantityAvailable >', 0)
            ->where('dp.ActiveStatus', 1)
            ->where('da.VisibleStatus', 1)
            ->where('da.Activate', 1)

            ->groupBy('da.DealerId')->get()->getResult();
    }
    function browsebyBrand($id, $city)
    {
        set_time_limit(120);
        $db = db_connect();
        return $db->table($city . "dealeraccounts da")
            ->select("b.BrandName,b.BrandId")
            ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
            ->where('dd.SubCategoryId', $id)
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
     function getsubcaterory($id){
     	 $db = db_connect();
		return $db->table("subcategory")
		->select("SubCategoryId,SubCategoryName,MainCategoryId,DepartmentId")
		->where("MainCategoryId",$id)
		->orderBy('MainCategoryId')
		 ->groupBy('SubCategoryId')->get()->getResult();
	}
}
