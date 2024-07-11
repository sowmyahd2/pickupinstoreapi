<?php

use CodeIgniter\Model;

class MainCategoryModel extends Model{
    protected $table         = 'maincategory';
    protected $returnType    = 'App\Entities\Products';

    public function getMainCategoryBrandsById($id,$city)
    {
        $db =db_connect();
        return $db->table('brands b')
        ->select("b.BrandName,b.BrandId")
        ->where('b.BrandType',0)
        ->join($city.'brandcategorymapping bcm','b.BrandId=bcm.BrandId')
        ->join('products p','p.BrandId=b.BrandId')
        ->join($city.'dealerprice dp','dp.ProductId=p.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
        ->where('dp.QuantityAvailable > ',0)
        ->groupBy('b.BrandId')
        ->orderBy('b.BrandName', 'asc')
        ->get()->getResult();
    }
    public function getMainCategoryLocalBrandsById($id,$city)
    {
        $db =db_connect();
        return $db->table('brands b')
        ->select("b.BrandName,b.BrandId")
        ->where('b.BrandType',1)
        ->join($city.'brandcategorymapping bcm','b.BrandId=bcm.BrandId')
        ->join('products p','p.BrandId=b.BrandId')
        ->join($city.'dealerprice dp','dp.ProductId=p.ProductId')
        ->join($city.'dealeraccounts da','da.DealerId = dp.DealerId')
        ->where('dp.ActiveStatus',1)
		->where('da.Activate',1)
		->where('da.VisibleStatus',1)
        ->where('p.MainCategoryId ', $id)
        ->where('dp.QuantityAvailable > ',0)
        ->groupBy('b.BrandId')
        ->orderBy('b.BrandName', 'asc')
        ->limit(20,0)
        ->get()->getResult();
    }
    function browsebyShop($id, $city){
        set_time_limit(120);
        $db = db_connect();
        return $db->table($city."dealeraccounts da")
        ->select("da.DealerId,da.ShopName")
        ->join($city.'dealerbrandsdeptmap dd', 'dd.DealerId = da.DealerId')
        ->where('dd.MainCategoryId',$id)
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
        ->where('dd.MainCategoryId',$id)
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