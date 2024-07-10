<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use CodeIgniter\API\ResponseTrait;
use MainCategoryModel;
use stdClass;
use SubCategoryModel;
header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Allow-Headers: Origin, Methods, Content-Type");
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
                "DepartmentGroupId" => $department->DepartmentGroupId,
                "DepartmentShortName"=>$department->DepartmentShortName,
            ));
        }
        return $this->response->setJSON(success($departmentArray, 200));
    }
public function handleImageUpload(){
    $input = $request->all();
var_dump($input);
}
    public function getDepartments(){

        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->findAll();
        $departmentArray = [];
        foreach ($departments as $department) {
            array_push($departmentArray, array(
                'label' => $department->DepartmentName,
                'value' => $department->DepartmentId,
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
    public function getsubcategory($departmentId)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getsubCategoryByDepartmentId($departmentId);

     
        return $this->response->setJSON(success($categories, 200));
        
        
    }
    public function getcategory($departmentId)
    {
        $departmentModel = new DepartmentModel();
        $categories = $departmentModel->getCategoryByDepartmentId($departmentId);
        $productArray=[];
          foreach($categories as $category){
  $mainCategories =count($departmentModel->getDubCategoryByMainCategoryId($category->MainCategoryId));
$detail=[
            "MainCategoryId"=>$category->MainCategoryId,
            "MainCategoryName"=>$category->MainCategoryName,
            "subcount"=>$mainCategories,
          
        ];
            array_push($productArray,$detail);
        }
        
     
        return $this->response->setJSON(success($productArray, 200));
    }
    public function browseby($departmentId, $cityName){
        $city = $cityName == "mysuru" ? "" : $cityName;
        $departmentModel = new DepartmentModel();
        $shop = $departmentModel->browsebyShop($departmentId,$city);
        $brand = $departmentModel->browsebyBrand($departmentId,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
    public function mainCategoryBrowseBy($id, $cityName){
        $city = $cityName == "mysuru" ? "" : $cityName;
        $maincategoryModel = new MainCategoryModel();
        $shop = $maincategoryModel->browsebyShop($id,$city);
        $brand = $maincategoryModel->browsebyBrand($id,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
    public function subCategoryBrowseBy($id, $cityName){
        $city = $cityName == "mysuru" ? "" : $cityName;
        $subcategoryModel = new SubCategoryModel();
        $shop = $subcategoryModel->browsebyShop($id,$city);
        $brand = $subcategoryModel->browsebyBrand($id,$city);
        $response = new stdClass();
        $response->store = $shop;
        $response->brand = $brand;
        return $this->response->setJSON(success($response, 200));
    }
}
