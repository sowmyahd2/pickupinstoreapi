<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DealerViewCountModel;
use App\Models\StoreModel;
use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class Stores extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $data = $this->request->getJSON();
        $storeModel = new StoreModel();
        $stores = $storeModel->findAll(30, 0);
        return $this->response->setJSON(success($stores, 200));
    }
    public function catstore($cityName,$id)
    {
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
        
        $storeModel = new StoreModel();
        $stores = $storeModel->catstore($city,$id);
        return $this->response->setJSON(success($stores, 200));
    }
    public function mostView($cityName,$pincode)
    {
        $data = $this->request->getJSON();
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        $mostViewModel = new DealerViewCountModel();
        $stores = $mostViewModel->index($city,$pincode);
        $storeArray = [];
        foreach ($stores as $store) {
            $item = array(
                "ShopName"     => $store->ShopName,
                "CityName"     => $store->CityName,
                "Locality"     => $store->Locality,
                "PinCode"      => $store->PinCode,
                "ShopLogo"     => storeLogo($store->ShopLogo),
                "DealerId"     => $store->DealerId,
                "MobileNumber" => $store->MobileNumber
            );
            array_push($storeArray, $item);
        }
        return $this->response->setJSON(success($storeArray, 200));
    }

    public function storeProducts($id, $cityName)
    {
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
        $response->detail = $storeModel->getStoreDetail($id, $city);
        $categories = $storeModel->storefrontcategory($id, $city);
        $obj = new stdClass();
        $filter = new stdClass();
        foreach ($categories as $category) {
            $products = $storeModel->storefrontcategoryproducts($id, $category->MainCategoryId, $city);
            $MainCategoryName = $category->MainCategoryName.'_'.$category->MainCategoryId;
            $productArray = [];
            foreach ($products as $product) {
                $item = array(
                    "ProductId" => $product->ProductId,
                    "ProductName" => $product->ProductName,
                    "DepartmentId" => $product->DepartmentId,
                    "MainCategoryId" => $product->MainCategoryId,
                    "SubcategoryId" => $product->SubCategoryId,
                    "ProductCode" => $product->ProductCode,
                    "MRP" => $product->MRP,
                    "SellingPrice" => $product->SellingPrice,
                    "StorePrice" => $product->StorePrice,
                    "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                    "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                    "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                    "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                );
                array_push($productArray, $item);
            }
            $obj->$MainCategoryName = $productArray;
        }
        $departments = $storeModel->storefrontDepartment($id, $city);
        foreach ($departments as $d) {
            $category = $storeModel->storefrontcategorybyDepartmentId($d->DepartmentId, $city, $id);
            $departmentName = $d->DepartmentName;
            $filter->$departmentName = $category;
        }
        $response->products = $obj;
        $response->filter = $filter;
        return $this->response->setJSON(success($response, 200));
    }

    public function storeCategoryProducts($id, $maincategoryId, $cityName)
    {
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        $limit = 24;
        $offset = 0;
        $brandIds = [];
        $min =0;
        $max = 0;
        $sort="desc";
        $catid=[];
        if(isset($_GET['limit'])){
            $limit = $this->request->getVar('limit');
        }
        if(isset($_GET['sort'])){
            $sort = $this->request->getVar('sort');
        }
       
        if(isset($_GET['offset'])){
            $offset = $this->request->getVar('offset');
        }
        if(isset($_GET['brandIds']) &&  strlen(trim($this->request->getVar('brandIds')) > 0) ){
            $brandIds = $this->request->getVar('brandIds');
            $brandIds = explode(',', $brandIds);
        } 
        if(isset($_GET['catIds']) &&  strlen(trim($this->request->getVar('catIds')) > 0) ){
            $catid = $this->request->getVar('catIds');
            $catid = explode(',', $catid);
        }     
        if(isset($_GET['price'])){
            $price = explode(',',$this->request->getVar('price'));
            $min = $price[0];
            $max = $price[1] == 0 ? 99999 : $price[1] ;
            
        }  
        $storeModel = new StoreModel();
        $response = new stdClass();
        $response->detail = $storeModel->getStoreDetail($id, $city);
        $categories = $storeModel->storefrontsubcategory($id, $city, $maincategoryId);
        $obj = new stdClass();
        $filter = new stdClass();
        $products = $storeModel->storefrontMainCategoryProducts($id, $maincategoryId, $city, $limit, $offset, $brandIds,$catid, $min, $max,$sort);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "ProductCode" => $product->ProductCode,
                "MRP" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        $response->products = $productArray;
        $response->filter = array(
            "category" => $categories,
            "brands" => $storeModel->storefrontsubcategorybrands($id, $city, $maincategoryId),
        );
        return $this->response->setJSON(success($response, 200));
    }
}
