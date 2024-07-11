<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\ProductModel;
use App\Models\ProductViewCountModel;
use App\Models\StoreModel;
use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class Products extends BaseController
{
    use ResponseTrait;
    public function index($id, $cityName,$pincode)
    {
        $result = new stdClass();
        $city = $cityName == "mysore" ? "" : $cityName . "_";
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
        $productArray = [];
   
        $similarproducts=$productModel->similarproducts($city,$productdetail->MainCategoryId, $productdetail->SubCategoryId);
     
        foreach ($similarproducts as $product) {
            $item = array(
                "ProductId" => $product->ProductId,
                "ProductName" => $product->ProductName,
                "DepartmentId" => $product->DepartmentId,
                "MainCategoryId" => $product->MainCategoryId,
                "SubcategoryId" => $productdetail->SubCategoryId,
                "BrandId" => $product->BrandId,
                "ProductCode" => $product->ProductCode,
                "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $productdetail->SubCategoryId, 'thumbs', 1, $product->Image1),
                "medium_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $productdetail->SubCategoryId, 'medium', 1, $product->Image1),
                "large_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $productdetail->SubCategoryId, 'large', 1, $product->Image1),
                "zoom_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $productdetail->SubCategoryId, 'zoom', 1, $product->Image1)
            );
            array_push($productArray, $item);
        }
      
        $productSpecification = $productModel->ProductSpecification($id);
        $ProductSellers = $productModel->ProductSellers($id, $city,$pincode);
        $SellingSizes = $productModel->SellingSizes($id, $city);
        $reviews = $productModel->get_reviews($id, $city);

        /*         $productdetailobject->productdetail = $productdetail;
        $productSpecificationobject->specification = $productSpecification;
        $ProductSellersobject->sellers = $ProductSellers;
        $SellingSizesobject->availablesize = $SellingSizes;
        $reviewobject->reviews = $reviews;
        $shareobject->share = "https://www.cityonnet.com/product_detail/index/".$id; */

        /*         array_push($result, $productdetailobject);
        array_push($result, $productSpecificationobject);
        array_push($result, $ProductSellersobject);
        array_push($result, $SellingSizesobject);
        array_push($result, $reviewobject);
        array_push($result, $similarobject);
        array_push($result, $shareobject);
        array_push($result, $ratingobject); */
        $product = $productModel->detail($id, $city);
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
        $result->similarproducts=$productArray;
        $result->specification = $productSpecification;
        $result->sellers = $ProductSellers;
        $result->availableSize = $SellingSizes;
        $result->reviews = $reviews;
        $result->share = "https://www.cityonnet.com/product_detail/index/" . $id;

        return $this->response->setJSON(success($result, 200));
    }
    public function mostView($cityName)
    {
        $data = $this->request->getJSON();
        $city = $cityName == "mysore" ? "" : $cityName . "_";
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

    public function departmentProducts($cityName, $id)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getCategoryByDepartmentId($id);
        $productModel = new ProductModel();
        $city = $cityName == "mysore" ? "" : $cityName."_";
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

    public function mainCategory($cityName, $id)
    {
        $productModel = new ProductModel();
        $limit = 24;
        $offset = 0;
        $brandIds = [];
        $sort = "desc";
        $min = 0;
        $max = 999999;
        if(isset($_GET['limit'])){
            $limit = $this->request->getVar('limit');
        }
        if(isset($_GET['offset'])){
            $offset = $this->request->getVar('offset');
        }
        if(isset($_GET['brandIds']) &&  strlen(trim($this->request->getVar('brandIds')) > 0) ){
            $brandIds = $this->request->getVar('brandIds');
            $brandIds = explode(',', $brandIds);
        }       
        if(isset($_GET['sort'])){
            $sort = $this->request->getVar('sort');
        }     
        if(isset($_GET['price'])){
            $price = explode(',',$this->request->getVar('price'));
            $min = $price[0];
            $max = $price[1] == 0 ? 99999 : $price[1] ;
            
        }        
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $products = $productModel->maincategoryProducts($id, $city,$limit,$offset,$brandIds,$min,$max,$sort);
        
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
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $limit = 24;
        $offset = 0;
        $brandIds = [];
        $sort = "desc";
        $min = 0;
        $max = 999999;
        if(isset($_GET['limit'])){
            $limit = $this->request->getVar('limit');
        }
        if(isset($_GET['sort'])){
            $sort = $this->request->getVar('sort');
        }
        if(isset($_GET['offset'])){
            $offset = $this->request->getVar('offset');
        }
        if(isset($_GET['brandIds'])){
            $brandIds = $this->request->getVar('brandIds');
            $brandIds = explode(',', $brandIds);
        } 
        if(isset($_GET['price'])){
            $price = explode(',',$this->request->getVar('price'));
            $min = $price[0];
            $max = $price[1] == 0 ? 99999 : $price[1] ;
            
        }     
        $departmentModel = new DepartmentModel();
        $products = $productModel->maincategoryProductsByBrandId($departmentId, $city, $brandId,$limit,$offset,$brandIds, $min, $max,$sort);
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
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $limit = 24;
        $offset = 0;
        $brandIds = [];
        $sort = "desc";
        $min = 0;
        $max = 999999;
        if(isset($_GET['limit'])){
            $limit = $this->request->getVar('limit');
        }
        if(isset($_GET['offset'])){
            $offset = $this->request->getVar('offset');
        }
        if(isset($_GET['sort'])){
            $sort = $this->request->getVar('sort');
        }
        if(isset($_GET['brandIds']) &&  strlen(trim($this->request->getVar('brandIds')) > 0) ){
            $brandIds = $this->request->getVar('brandIds');
            $brandIds = explode(',', $brandIds);
        }       
        if(isset($_GET['sort'])){
            $sort = $this->request->getVar('sort');
        }     
        if(isset($_GET['price'])){
            $price = explode(',',$this->request->getVar('price'));
            $min = $price[0];
            $max = $price[1] == 0 ? 99999 : $price[1] ;
            
        }  
        $departmentModel = new DepartmentModel();
        $products = $productModel->subCategoryProductsByBrandId($subCategoryId, $city, $brandId, $limit, $offset, $brandIds, $min,  $max,$sort);
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


    public function subCategory($cityName, $id)
    {
        $productModel = new ProductModel();
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $limit = 24;
        $offset = 0;
        $brandIds = [];
        $min =0;
        $max = 0;
        $sort="desc";
        if(isset($_GET['limit'])){
            $limit = $this->request->getVar('limit');
        }
        if(isset($_GET['offset'])){
            $offset = $this->request->getVar('offset');
        }
        if(isset($_GET['sort'])){
            $sort= $this->request->getVar('sort');
        }
        if(isset($_GET['brandIds']) &&  strlen(trim($this->request->getVar('brandIds')) > 0) ){
            $brandIds = $this->request->getVar('brandIds');
            $brandIds = explode(',', $brandIds);
        }      
        if(isset($_GET['price'])){
            $price = explode(',',$this->request->getVar('price'));
            $min = $price[0];
            $max = $price[1] == 0 ? 99999 : $price[1] ;
            
        }  
        $products = $productModel->subcategoryProducts($id, $city, $limit, $offset, $brandIds, $min, $max,$sort);
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
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $departments = $departmentModel->getDepartmentByBrandId($id, $city);
        $productModel = new ProductModel();
        $departmentArray = new stdClass();
        $categoryObj = new stdClass();
   
        $respone = new stdClass();
        foreach ($departments as $department) {
            $categoryArray = [];
            $mainCategoryName = $department->DepartmentName.'_'.$department->DepartmentId;
            $products = $productModel->brandDepartment($department->DepartmentId, $city, $id);
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
}
