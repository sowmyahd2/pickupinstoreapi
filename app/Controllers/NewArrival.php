<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CityModel;
use App\Models\DepartmentModel;
use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class NewArrival extends BaseController
{
    use ResponseTrait;
    public function index($cityName,$id=0)
    {
     
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        
        $response = new stdClass();
        $departmentModel = new DepartmentModel();
        $brandModel = new BrandModel();
        $department = $departmentModel->newArrival($city);
        $response->department = $department;
        
        if($id==0){
            $id=$department{0}->DepartmentId;	
        }
        $brands = $brandModel->newArrival($city,$id);
        $brandArray = [];
        foreach($brands as $brand){
            $item = array(
              "BrandId" => $brand->BrandId,
              "BrandLogo" => brandLogo($brand->Logo),
              "BrandName" => $brand->BrandName,  
              "DepartmentId" => $id,
            );
            array_push($brandArray, $item);
        }
        $response->brands = $brandArray;
        return $this->response->setJSON(success($response, 200));
    }
    public function newarrivaldetail($id,$cityName,$start=0,$limit=20)
    {
     
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        
        $response = new stdClass();
        $departmentModel = new DepartmentModel();
        $brandModel = new BrandModel();
        $department = $departmentModel->newArrival($city);
        $response->department = $department;
        
        
        $brands = $brandModel->newArrival($id,$city,$start,$limit);
        $brandArray = [];
        foreach($brands as $brand){
            $item = array(
              "BrandId" => $brand->BrandId,
              "BrandLogo" => brandLogo($brand->Logo),
              "BrandName" => $brand->BrandName,  
              "DepartmentId" =>$brand->DepartmentId,
            );
            array_push($brandArray, $item);
        }
        $response->brands = $brandArray;
        return $this->response->setJSON(success($response, 200));
    }
       public function newarrivalsearchedbrand($cityName,$term)
    {
     
        
        
        $response = new stdClass();
        $departmentModel = new DepartmentModel();
        $brandModel = new BrandModel();
       
        
        
        $brands = $brandModel->newarrivalsearchedbrand($cityName,$term);
        $brandArray = [];
        foreach($brands as $brand){
            $item = array(
              "BrandId" => $brand->BrandId,
              "BrandLogo" => brandLogo($brand->Logo),
              "BrandName" => $brand->BrandName,  
              "DepartmentId" =>$brand->DepartmentId,
            );
            array_push($brandArray, $item);
        }
        $response->brands = $brandArray;
        return $this->response->setJSON(success($response, 200));
    }
    
  public function newarrivalbrandproducts($cityName,$id,$dpid)
    {
     
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        
        $response = new stdClass();
        $departmentModel = new DepartmentModel();
        $brandModel = new BrandModel();
        
        
        
        $products = $brandModel->newArrivalproducts($id,$city,$dpid);
        $brandArray = [];
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
            array_push($brandArray, $item);
        }
       
        return $this->response->setJSON(success($brandArray, 200));
    }
}
