<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\ProductModel;
use App\Models\ProductViewCountModel;
use App\Models\StoreModel;
use App\Models\SubCategoryModel;
use App\Models\MainCategoryModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\Cart_Model;
use stdClass;

helper('response');
helper('cityonnet');
class Products extends BaseController
{
    use ResponseTrait;
    public function index($id, $cityName)
    {
        $result = new stdClass();
       
        $city = strtolower($cityName) == "mysuru" ? "" : $cityName . "_";
        $productdetailobject = new stdClass();
        $productSpecificationobject = new stdClass();
        $ProductSellersobject = new stdClass();
        $SellingSizesobject = new stdClass();
        $reviewobject = new stdClass();
        $similarobject = new stdClass();
        $shareobject = new stdClass();
        $ratingobject = new stdClass();
        $productModel = new ProductModel();
        $productdetail = $productModel->detail($id, $city);
        $offset=4;
        $limit=0;
      
        $productSpecification = $productModel->ProductSpecification($id);
        $ProductSellers = $productModel->ProductSellers($id,$limit,$offset, $city);
        $SellingSizes = $productModel->SellingSizes($id, $city);
        $reviews = $productModel->get_reviews($id, $city);


        $product = $productModel->detail($id, $city);

    
if($product->SubCategoryId==0){

 $products = $productModel->similialrMaincategoryProducts($product->MainCategoryId, $city);

        $productArray = [];
       
       
        }
        else{

           $products = $productModel->similialrsubcategoryProducts($product->SubCategoryId, $city);
        }
        $productArray1=array();
         foreach ($products as $prod) {
            $item = array(
                "ProductId" => $prod->ProductId,
                "ProductName" => $prod->ProductName,
                "DepartmentId" => $prod->DepartmentId,
                "MainCategoryId" => $prod->MainCategoryId,
                "SubcategoryId" => $prod->SubCategoryId,
                "BrandId" => $prod->BrandId,
                "ProductCode" => $prod->ProductCode,
                "MRP" => $prod->MRP,
                "SellingPrice" => $prod->SellingPrice,
                "StorePrice" => $prod->StorePrice,
                "ShopCount" => $prod->ShopCount,
                "thumb_image" => productImageUrl($prod->DepartmentId, $prod->MainCategoryId, $prod->SubCategoryId, 'thumbs', 1, $prod->Image1),
                "medium_image" => productImageUrl($prod->DepartmentId, $prod->MainCategoryId, $prod->SubCategoryId, 'medium', 1, $prod->Image1),
                "large_image" => productImageUrl($prod->DepartmentId, $prod->MainCategoryId, $prod->SubCategoryId, 'large', 1, $prod->Image1),
                "zoom_image" => productImageUrl($prod->DepartmentId, $prod->MainCategoryId, $prod->SubCategoryId, 'zoom', 1, $prod->Image1)
            );
            array_push($productArray1, $item);
        }
        $result->similarproducts = $productArray1;
        $result->productdetail = $product;
        $result->images = array(
            "image1" => array(
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            ),
            "image2" => array(
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 2, $product->Image2),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 2, $product->Image2),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 2, $product->Image2),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 2, $product->Image2)
            ),
            "image3" => array(
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 3, $product->Image3),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 3, $product->Image3),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 3, $product->Image3),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 3, $product->Image3)
            ),
            "image4" => array(
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 4, $product->Image4),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 4, $product->Image4),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 4, $product->Image4),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 4, $product->Image4)
            ),
        );
        $result->specification = $productSpecification;
        $result->sellers = $ProductSellers;
        $result->availableSize = $SellingSizes;
        $result->reviews = $reviews;
        $result->share = "https://www.cityonnet.com/product_detail/index/" . $id;

        return $this->response->setJSON(success($result, 200));
    }
    public function fetchsizeprice($cityname,$id,$size){
      
         $result = new stdClass();
        $city = strtolower($cityname) == "mysuru" ? "" : $cityname . "_";
        $productModel = new ProductModel();
         $product = $productModel->fetchsizeprice($id,$city,$size);
          $result->productdetail = $product;
          return $this->response->setJSON(success($result, 200));

    }
    public function productstores($id,$cityname){
        $city = strtolower($cityname) == "mysuru" ? "" : $cityname . "_";
        $productModel = new ProductModel();
        $ProductSellers = $productModel->ProductSeller($id,$city);
        
          return $this->response->setJSON(success($ProductSellers, 200));
    }
    public function mostView($cityName)
    {

        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        else{
            $cityName=strtolower($cityName);
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $mostViewModel = new ProductViewCountModel();
        $products = $mostViewModel->index($city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubcategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubcategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubcategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubcategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubcategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function newarrival($cityName)
    {
     
        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";

        $productModel = new ProductModel();
        $products = $productModel->newarrival($city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "mrp" => $product->MRP,
                "storeprice" => $product->StorePrice,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
      public function offerbrands($cityName,$id)
    {

        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
         $response = new stdClass();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $productModel = new ProductModel();
        $departmentModel = new DepartmentModel();
        $products = $productModel->offerbrands($city,$id);
         $department = $departmentModel->newdeal($city);
         $response->department = $department;
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "BrandId" => $product->BrandId,
                "BrandName" => $product->BrandName,
                "Logo" => brandLogo($product->Logo),
               
            );
            array_push($productArray, $item);
        }
         $response->brands = $productArray;
        return $this->response->setJSON(success($response, 200));
    }
      public function searchedofferbrands($cityName,$term)
    {

        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $productModel = new ProductModel();
        $products = $productModel->searchedbrands($city,$term);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "BrandId" => $product->BrandId,
                "BrandName" => $product->BrandName,
                "Logo" => brandLogo($product->Logo),
               
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
public function offersproducts($cityName,$id,$dpid){
   if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $productModel = new ProductModel();
        $products = $productModel->offerproduct($city,$id,$dpid);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "mrp" => $product->MRP,
                "StorePrice" => $product->StorePrice,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));  
}
    public function offers($cityName)
    {

        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $productModel = new ProductModel();
        $products = $productModel->offerproducts($city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "mrp" => $product->MRP,
                "StorePrice" => $product->StorePrice,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function brands($cityName)
    {

        if($cityName=="mysore"){

            $cityName="mysuru";
        }
        $data = $this->request->getJSON();
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $productModel = new ProductModel();
        $products = $productModel->offerproducts($city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "mrp" => $product->MRP,
                "StorePrice" => $product->StorePrice,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function departmentProducts($cityName, $id)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getCategoryByDepartmentId($id);
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : $cityName;
        $categoryArray = new stdClass();
        foreach ($categories as $category) {
            $mainCategoryName = $category->MainCategoryName."_".$category->MainCategoryId;
            $products = $productModel->department($category->MainCategoryId, $city);
            $productArray = [];
            foreach ($products as $product) {
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
                    "ShopCount" => $product->ShopCount,
                    "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                    "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                    "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                    "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                );
                array_push($productArray, $item);
            }
            $categoryArray->$mainCategoryName = $productArray;
        }
        return $this->response->setJSON(success($categoryArray, 200));
    }

    public function mainCategory($cityName, $id,$brandid)
    {
        $productModel = new ProductModel();
               $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->maincategoryProducts($id,$brandid,$city);
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }

    public function mainCategoryproducts($cityName, $id,$start,$limit)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->maincategoryProduct($id,$city,$start,$limit);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" 		=> $product->ProductId,
                "ProductName" 		=> $product->ProductName,
                "DepartmentId" 		=> $product->DepartmentId,
                "MainCategoryId"	=> $product->MainCategoryId,
                "SubcategoryId" 	=> $product->SubCategoryId,
                "BrandId"			=> $product->BrandId,
                "ProductCode" 		=> $product->ProductCode,
                "MRP" 				=> $product->MRP,
                "SellingPrice" 		=> $product->SellingPrice,
                "StorePrice" 		=> $product->StorePrice,
                "ShopCount"			=> $product->ShopCount,
                "thumb_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function subCategoryproducts($cityName, $id,$start,$limit)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->SubCategoryProd($id,$city,$start,$limit);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" 		=> $product->ProductId,
                "ProductName" 		=> $product->ProductName,
                "DepartmentId" 		=> $product->DepartmentId,
                "MainCategoryId"	=> $product->MainCategoryId,
                "SubcategoryId" 	=> $product->SubCategoryId,
                "BrandId"			=> $product->BrandId,
                "ProductCode" 		=> $product->ProductCode,
                "MRP" 				=> $product->MRP,
                "SellingPrice" 		=> $product->SellingPrice,
                "StorePrice" 		=> $product->StorePrice,
                "ShopCount"			=> "10",
                "thumb_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" 		=> productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function similarproducts($city,$maincatid,$subcatid){
    	if($subcatid!=0){
    	return	$this->similialrsubCategory($city,$subcatid);
    	}
    	else{
    		return $this->similialrMaincategoryProducts($city,$maincatid);
    	}
    }
    public function SubCategoryproduct($cityName, $id,$start,$limit,$sort,$brandid)
    {
             $subcategoryModel = new SubCategoryModel();
        $productModel = new ProductModel();
         $categoryModel = new MainCategoryModel();
         $response = new stdClass();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        if($sort==1){
            $sort="DESC";
        }
        else if($sort==2){
            $sort="ASC";
        }

        
        $products = $productModel->SubCategoryproduct($id,$city,$start,$limit,$sort,$brandid);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId"         => $product->ProductId,
                "ProductName"       => $product->ProductName,
                "DepartmentId"      => $product->DepartmentId,
                "MainCategoryId"    => $product->MainCategoryId,
                "SubcategoryId"     => $product->SubCategoryId,
                "BrandId"           => $product->BrandId,
                "ProductCode"       => $product->ProductCode,
                "MRP"               => $product->MRP,
                "SellingPrice"      => $product->SellingPrice,
                "StorePrice"        => $product->StorePrice,
                "ShopCount"         => $product->ShopCount,
                "SubCategoryName"   => $product->SubCategoryName,
                "thumb_image"       => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image"      => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image"       => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image"        => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        $filters = $subcategoryModel->filter($id,$city);
         foreach($filters as $filter){
            $res =  $subcategoryModel->dealerSpecification($filter->SpecificationId,$id,$city);
            $specificationName = $filter->SpecificationName;
            $response->$specificationName = $res;
        }
        $response->products=$productArray;
        return $this->response->setJSON(success($response, 200));
    }
    
    public function similialrMaincategoryProducts($cityName, $id){
        echo $cityName;
        $productModel = new ProductModel();
                $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->similialrMaincategoryProducts($id, $city);
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));

        
    }

    public function brandMainCategory($cityName, $departmentId, $brandId)
    {
        set_time_limit(120);
        $productModel = new ProductModel();
               $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $departmentModel = new DepartmentModel();
        $products = $productModel->maincategoryProductsByBrandId($departmentId, $city, $brandId);
        $response = new stdClass();
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        $response->products = $productArray;
        $response->filters = $departmentModel->getCategoryCategoryandBrandId($departmentId, $brandId, $city);
        return $this->response->setJSON(success($response, 200));
    }

    public function brandSubCategory($cityName, $subCategoryId, $brandId)
    {
        set_time_limit(120);
        $productModel = new ProductModel();
               $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $departmentModel = new DepartmentModel();
        $products = $productModel->subCategoryProductsByBrandId($subCategoryId, $city, $brandId);
        $response = new stdClass();
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        $response->products = $productArray;
        $response->filters = $departmentModel->getSubCategoryByMaincategoryandBrandId($subCategoryId, $brandId, $city);
        return $this->response->setJSON(success($response, 200));
    }


    public function subCategory($cityName,$id,$brandid)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->subcategoryProducts($id,$brandid,$city);
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }

  public function dealersubCategory($cityName,$dealerid,$id)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->dealersubcategoryProducts($id,$dealerid,$city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "mrp" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function dealermainCategory($cityName,$dealerid,$id)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : strtolower($cityName)."_";
        $products = $productModel->dealermaincategoryProducts($id,$dealerid,$city);
        $productArray = [];
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "mrp" => $product->MRP,
                "SellingPrice" => $product->SellingPrice,
                "StorePrice" => $product->StorePrice,
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }
    public function similialrsubCategory($cityName, $id)
    {
        
        $productModel = new ProductModel();
        $city = $cityName == "mysuru" ? "" : $cityName."_";
        $products = $productModel->similialrsubcategoryProducts($id, $city);
        $productArray = [];
        foreach ($products as $product) {
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
                "ShopCount" => $product->ShopCount,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
        return $this->response->setJSON(success($productArray, 200));
    }

    public function brandCategoryProducts($cityName, $id)
    {
        set_time_limit(120);
        $departmentModel = new DepartmentModel();
        $city = $cityName == "mysuru" ? "" : $cityName;
        $departments = $departmentModel->getDepartmentByBrandId($id, $city);
        $productModel = new ProductModel();
        $departmentArray = new stdClass();
        $categoryObj = new stdClass();
        $categoryArray = [];
        $respone = new stdClass();
        foreach ($departments as $department) {
            $mainCategoryName = $department->DepartmentName.'_'.$department->DepartmentId;
            $products = $productModel->brandDepartment($department->DepartmentId, $city);
            $categories = $departmentModel->getCategoryByDepandBrandId($department->DepartmentId, $id, $city);
            $productArray = [];
            foreach ($categories as $category) {
                $item = array(
                    "MainCategoryName" => $category->MainCategoryName,
                    "MainCategoryId" => $category->MainCategoryId,
                );
                array_push($categoryArray, $item);
            }
            foreach ($products as $product) {
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
                    "ShopCount" => $product->ShopCount,
                    "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                    "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                    "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                    "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
                );
                array_push($productArray, $item);
            }
            $departmentArray->$mainCategoryName = $productArray;
            $categoryObj->$mainCategoryName = $categoryArray;
        }
        $respone->products = $departmentArray;
        $respone->filter = $categoryObj;
        return $this->response->setJSON(success($respone, 200));
    }
    function loadmoreseller(){
    	 $data = $this->request->getJSON();
    $city="";
     $productModel = new ProductModel();
    	  $response = new stdClass();
        $pid = $data->pid;
        $start= $data->start;
        $limit = $data->offset;
         $cityName = $data->cityname;
           $city = strtolower($cityName) == "mysuru" ? "" : $cityName . "_";
   $ProductSellers = $productModel->ProductSellers($pid,$start,$limit, $city);
   $ProductSellersc = count($productModel->ProductSellerscount($pid,$city));
   $response->sellers=$ProductSellers;
   $response->sellerscount=$ProductSellersc;
        return $this->response->setJSON(success($response, 200));
		
	}
	 function fetchprice($id,$specid){
	 	
	 	 $productModel = new ProductModel();
    	  $response = new stdClass();
    	  $city="";
    	     $ProductSellers = $productModel->fetchprice($id,$specid,$city);
    	   return $this->response->setJSON(success($ProductSellers, 200));  
	 }
     function addtocart(){
         $response = new stdClass();
 $data = $this->request->getJSON();
    $user=$data->userid;
    $type=$data->carttype;



    $cityName=$data->city;
      $city = $cityName == "mysuru" ? "" : $cityName . "_";
 $cartModel = new Cart_Model();
        $items = array(
             'UserId' =>$user,
            'ProductId' => $data->productid,
            'ProductName' => $data->productname,
            'Price' => $data->price,
            'QuantityPurchased' => $data->qty,
            'DealerId' => $data->dealerid,
            'DealerPriceId' =>$data->dealerpriceid,
            'AddToCartTime' => date('Y-m-d h:i:s'),
        );
       $id= $cartModel->add($items, $type, $city); 
     return $this->response->setJSON(success($type, 200));  

     }
     function getspecification ($id){
        $result = new stdClass();
        $response = new stdClass();
       $productModel = new ProductModel();
        $productSpecification = $productModel->ProductSpecification($id);
        return $this->response->setJSON(success($productSpecification, 200));  
     }
     function getproductdetail($id,$city){

         $result = new stdClass();
         $response = new stdClass();
        $productModel = new ProductModel();
        $productdetail = $productModel->productdetail($id);
        $slabs   =   $productModel->getgstslab();
        $city = strtolower($city) == "mysuru" ? "" : $city . "_";
        $productdetails = $productModel->pricedetail($id, $city);
         $item = array(
                    "ProductId" => $productdetail->ProductId,
                    "ProductName" => $productdetail->ProductName,
                    "DepartmentId" => $productdetail->DepartmentId,
                    "MainCategoryId" => $productdetail->MainCategoryId,
                    "SubcategoryId" => $productdetail->SubCategoryId,
                    "BrandId" => $productdetail->BrandId,
                    "BrandName"=>$productdetail->BrandName,
                    "ProductCode" => $productdetail->ProductCode,
                    "ConCode"=>$productdetail->ConCode,
                    "thumb_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 1, $productdetail->Image1),
                    "medium_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'medium', 1, $productdetail->Image1),
                    "large_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'large', 1, $productdetail->Image1),
                    "zoom_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 1, $productdetail->Image1)
                );
            //    $respone->detail=$item;
          
            $response->images = array(
            "image1" => array(
                "thumb_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 1, $productdetail->Image1),
                "medium_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'medium', 1, $productdetail->Image1),
                "large_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'large', 1, $productdetail->Image1),
                "zoom_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 1, $productdetail->Image1)
            ),
            "image2" => array(
                "thumb_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 2, $productdetail->Image2),
                "medium_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'medium', 2, $productdetail->Image2),
                "large_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'large', 2, $productdetail->Image2),
                "zoom_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 2, $productdetail->Image2)
            ),
            "image3" => array(
                "thumb_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 3, $productdetail->Image3),
                "medium_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'medium', 3, $productdetail->Image3),
                "large_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'large', 3, $productdetail->Image3),
                "zoom_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 3, $productdetail->Image3)
            ),
            "image4" => array(
                "thumb_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 4, $productdetail->Image4),
                "medium_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'medium', 4, $productdetail->Image4),
                "large_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'large', 4, $productdetail->Image4),
                "zoom_image" => productImageUrl($productdetail->DepartmentId, $productdetail->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 4, $productdetail->Image4)
            ),
        );
              $response->slabs=$slabs;
              $response->detail=$item;
              $response->price=$productdetails;
          //    $response->sellerscount=$ProductSellersc;
    return $this->response->setJSON(success($response, 200)); 
     }
	public function dealerproducts($dealerid,$city){
 $productModel = new ProductModel();
          $response = new stdClass();
$productArray=[];
         
          
             $products = $productModel->dealerproducts($dealerid,$city);
   
             
        foreach ($products as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "DealerpriceId" => $product->DealerPriceId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $product->SubCategoryId,
                "mrp"=>$product->MRP,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }    
     
           return $this->response->setJSON(success($productArray, 200));  

    }
}
