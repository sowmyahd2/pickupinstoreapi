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
        $stores = $storeModel->findAll(9, 0);
        return $this->response->setJSON(success($stores, 200));
    }
       public function index1($city,$id,$start=0,$end=24)
    {
        $data = $this->request->getJSON();
        $storeModel = new StoreModel();
         $city = $city == "mysuru" ? "" : $city . "_";
        $stores = $storeModel->getstoredepartments($id,$city);
        return $this->response->setJSON(success($stores, 200));
    }
    public function departmentstore($city,$id){
        $data = $this->request->getJSON();
        $storeModel = new StoreModel();
        $stores = $storeModel->getdepartmentstores($city,$id);
        return $this->response->setJSON(success($stores, 200));
    }
public function serachedstores($city,$term){
$data = $this->request->getJSON();
        $storeModel = new StoreModel();
        $stores = $storeModel->shoplist($city,$term);
        $stores1 = $storeModel->searchedstores($city,$stores->DealerId);
        return $this->response->setJSON(success($stores1, 200));
}
       public function storedepartment($id,$cityName)
    
    {
    	$city = $cityName == "mysuru" ? "" : $cityName . "_";
      
        $storeModel = new StoreModel();
  $stores= $storeModel->getstoredepartment($id, $city);
        return $this->response->setJSON(success($stores, 200));
    }
    public function mostView($cityName)
    {

        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $mostViewModel = new DealerViewCountModel();
        $stores = $mostViewModel->index($city);
        $storeArray = [];
        foreach ($stores as $store) {
            $item = array(
                "ShopName" => $store->ShopName,
                "CityName" => $store->CityName,
                "Locality" => $store->Locality,
                "PinCode" => $store->PinCode,
                "ShopLogo" => storeLogo($store->ShopLogo),
                "DealerId" => $store->DealerId,
            );
            array_push($storeArray, $item);
        }
        return $this->response->setJSON(success($storeArray, 200));
    }
public function dealerdetail($dealerid,$city){

$city = $city == "mysuru" ? "" : $city . "_";
        $storeModel = new StoreModel();
         $response = new stdClass();
    $response->detail = $storeModel->getStoreDetail($dealerid, $city);
    return $this->response->setJSON(success($response, 200));
}
    public function storeProducts($id, $cityName)
    {
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
      //  $response->detail = $storeModel->getStoreDetail($id, $city);
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
     //   $departments = $storeModel->storefrontDepartment($id, $city);
      //  foreach ($departments as $d) {
     //       $category = $storeModel->storefrontcategorybyDepartmentId($d->DepartmentId, $city, $id);
     //       $departmentName = $d->DepartmentName;
     //       $filter->$departmentName = $category;
    //    }
        $response->products = $obj;
     //   $response->filter = $filter;
        return $this->response->setJSON(success($response, 200));
    }
    public function storedepartmentProducts($id, $department, $cityName)
    {
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
        $response->detail = $storeModel->getStoreDetail($id, $city);
        $categories = $storeModel->storefrontcategorybyDepartmentId($department, $city, $id);
       
        $obj = new stdClass();
        $filter = new stdClass();
        $products = $storeModel->storefrontdepartmentProducts($id, $department,$city);
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
            "brands" => $storeModel->storefrontsubcategorybrands($id, $city, $department),
        );
        return $this->response->setJSON(success($response, 200));
    }
    public function storeCategoryProducts($id, $maincategoryId, $cityName)
    {
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
        $response->detail = $storeModel->getStoreDetail($id, $city);
        $categories = $storeModel->storefrontsubcategory($id, $city, $maincategoryId);
        $obj = new stdClass();
        $filter = new stdClass();
        $products = $storeModel->storefrontMainCategoryProducts($id, $maincategoryId, $city);
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
     public function storesubCategoryProducts($id, $maincategoryId, $cityName)
    {
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $storeModel = new StoreModel();
        $response = new stdClass();
        $response->detail = $storeModel->getStoreDetail($id, $city);
        $categories = $storeModel->storefrontsubcategory($id, $city, $maincategoryId);
        $obj = new stdClass();
        $filter = new stdClass();
        $products = $storeModel->storefrontSubCategoryProducts($id, $maincategoryId, $city);
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
