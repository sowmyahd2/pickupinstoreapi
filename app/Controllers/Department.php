<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use CodeIgniter\API\ResponseTrait;
use MainCategoryModel;
use stdClass;
use SubCategoryModel;

helper('response');
helper('cityonnet');
class Department extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->findAll();
        $departmentArray = [];
        foreach ($departments as $department) {
            array_push($departmentArray, array(
                "DepartmentId" => $department->DepartmentId,
                "DepartmentName" => $department->DepartmentName,
                "Icons" => departmentIcon($department->Icons),
                "VerticalId"=> $department->VerticalId,
                "DepartmentGroupId" => $department->DepartmentGroupId
            ));
        }
        return $this->response->setJSON(success($departmentArray, 200));
    }

    public function category($departmentId)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getCategoryByDepartmentId($departmentId);
        $categoryArray = new stdClass();
        foreach($categories as $category){
            $mainCategoryName = $category->MainCategoryName;
            $mainCategories =$departmentModel->getDubCategoryByMainCategoryId($category->MainCategoryId);
            $categoryArray->$mainCategoryName = $mainCategories;
        }
        return $this->response->setJSON(success($categoryArray, 200));
    }
 public function categorylist($departmentId)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getCategoryByDepartmentId($departmentId);
        $categoryArray = new stdClass();
        foreach($categories as $category){
            $mainCategoryName = $category->MainCategoryName;
            $mainCategories =$departmentModel->getDubCategoryByMainCategoryId($category->MainCategoryId);
            $categoryArray->$mainCategoryName = $mainCategories;
        }
        return $this->response->setJSON(success($categories, 200));
    }
 public function subcategorylist($MainCategoryId)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getDubCategoryByMainCategoryId($MainCategoryId);
        $categoryArray = new stdClass();
       
        return $this->response->setJSON(success($categories, 200));
    }
    public function browseby($departmentId, $cityName){
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $shop = $departmentModel->browsebyShop($departmentId,$city);
        $brand = $departmentModel->browsebyBrand($departmentId,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
    public function mainCategoryBrowseBy($id, $cityName){
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $maincategoryModel = new MainCategoryModel();
        $shop = $maincategoryModel->browsebyShop($id,$city);
        $brand = $maincategoryModel->browsebyBrand($id,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
    public function subCategoryBrowseBy($id, $cityName){
        $city = $cityName == "mysore" ? "" : $cityName."_";
        $subcategoryModel = new SubCategoryModel();
        $shop = $subcategoryModel->browsebyShop($id,$city);
        $brand = $subcategoryModel->browsebyBrand($id,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
    public function getdeeplink(){
        $response = [
            'screen_id' => 1,
            // ... other data ...
        ];

        // Return the response as JSON
        return $this->respond($response, 200);
}
}