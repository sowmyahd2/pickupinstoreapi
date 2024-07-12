<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class Brands extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $data = $this->request->getJSON();
        $brandModel = new BrandModel();
        $brands = $brandModel->getbrands();
        return $this->response->setJSON(success($brands, 200));
    }

    public function offers($cityName,$id){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $brands = $brandModel->offers($city,$id);
        $brandArray = [];
        foreach($brands as $brand){
            $item = array(
              "BrandId" => $brand->BrandId,
              "BrandLogo" => brandLogo($brand->Logo),
              "BrandName" => $brand->BrandName,  
            );
            array_push($brandArray, $item);
        }
        return $this->response->setJSON(success($brandArray, 200));
    }
  public function get_allBrands($id,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $brands = $brandModel->get_allBrands($city,$id);
    
        return $this->response->setJSON(success($brands, 200));
    }
      public function get_whatsapppromotion($id,$cityName){
        $city = strtolower($cityName)== "mysuru" ? "" : strtolower($cityName)."_";
        $brandModel = new BrandModel();
        $brands = $brandModel->get_whatsapppromotion($city,$id);
    
        return $this->response->setJSON(success($brands, 200));
    }
      public function get_catBrands($id,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $brands = $brandModel->get_catBrands($city,$id);
    
        return $this->response->setJSON(success($brands, 200));
    }
public function serachedbrands($term){
      
        $brandModel = new BrandModel();
        $brands = $brandModel->serachedBrands($term);
    
        return $this->response->setJSON(success($brands, 200));
}
    public function offerProducts($cityName,$id,$brandId){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $products=$brandModel->offerProductsByBrand($id,$brandId,$city);
        $productArray = [];
        foreach($products as $product){
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "MRP" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "thumb_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'thumbs',1,$product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'medium',1,$product->Image1),
                "large_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'large',1,$product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'zoom',1,$product->Image1)
            );
            array_push($productArray, $item);
         }
         return $this->response->setJSON(success($productArray, 200));
    }
      public function categoryProducts($cityName,$brandId){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $products=$brandModel->categoryProductsByBrand($brandId,$city);
        $productArray = [];
        foreach($products as $product){
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                  "BrandName" => $product->BrandName,
                "ProductCode" => $product->ProductCode,
                "MRP" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "thumb_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'thumbs',1,$product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'medium',1,$product->Image1),
                "large_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'large',1,$product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'zoom',1,$product->Image1)
            );
            array_push($productArray, $item);
         }
         return $this->response->setJSON(success($productArray, 200));
    }
    public function newArrivalsByBrand($cityName,$id,$brandId){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $products=$brandModel->newArrivalsByBrand($id,$brandId,$city);
        $productArray = [];
        foreach($products as $product){
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "MRP" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "thumb_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'thumbs',1,$product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'medium',1,$product->Image1),
                "large_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'large',1,$product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId,$product->MainCategoryId,$product->SubCategoryId,'zoom',1,$product->Image1)
            );
            array_push($productArray, $item);
         }
         return $this->response->setJSON(success($productArray, 200));
    }

    function departmentBrowseby($id,$departmentId,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $shop = $brandModel->departmentStoreBrowseBy($id,$departmentId,$city);
        $category = $brandModel->departmentCategoryBrowseBy($id,$departmentId,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->category = $category;
        return $this->response->setJSON(success($response, 200));
    }
    function categoryBrowseby($id,$mainCategoryId,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $brandModel = new BrandModel();
        $shop = $brandModel->categoryStoreBrowseBy($id,$mainCategoryId,$city);
        $category = $brandModel->categoryCategoryBrowseBy($id,$mainCategoryId,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->category = $category;
        return $this->response->setJSON(success($response, 200));
    }
}
