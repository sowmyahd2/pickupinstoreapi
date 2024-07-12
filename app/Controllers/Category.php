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
class Category extends BaseController
{
    public function subcategory($id,$cityName){
        $city = $cityName== "mysore" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new MainCategoryModel();
        $response = new stdClass();
        $categories = $departmentModel->getDubCategoryByMainCategoryId($id);
        $brands = $categoryModel->getMainCategoryBrandsById($id,$city);
        $localBrands = $categoryModel->getMainCategoryLocalBrandsById($id,$city);
        $response->category=$categories;
        $response->brands=$brands;
        $response->localBrands=$localBrands;
        return $this->response->setJSON(success($response, 200));
    }

    function categoryFilter($id,$cityName){
        $city = $cityName== "mysore" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new SubCategoryModel();
        $response = new stdClass();
        $brands = $categoryModel->getMainCategoryBrandsById($id,$city);
        $localBrands = $categoryModel->getMainCategoryLocalBrandsById($id,$city);
        $filters = $categoryModel->filter($id,$city);
        $response->brands=$brands;
        $response->localBrands=$localBrands;
        foreach($filters as $filter){
            $res =  $categoryModel->dealerSpecification($filter->SpecificationId,$id,$city);
            $specificationName = $filter->SpecificationName;
            $response->$specificationName = $res;
        }
        return $this->response->setJSON(success($response, 200));
    }
}