<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\SubCategoryModel;
use App\Models\MainCategoryModel;
use CodeIgniter\API\ResponseTrait;

use stdClass;


helper('response');
helper('cityonnet');
class Category extends BaseController
{
    public function subcategory($id,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new MainCategoryModel();
        $response = new stdClass();
        $categories = $departmentModel->getDubCategoryByMainCategoryId($id);
        $brands = $categoryModel->getMainCategoryBrandsById($id,$city);
        $localBrands = $categoryModel->getMainCategoryLocalBrandsById($id,$city);
        $response->category=$categories;
        $response->brands=$brands;
       
        return $this->response->setJSON(success($response, 200));
    }
public function subcategorybrand($id,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new MainCategoryModel();
        $response = new stdClass();
        
        $brands = $categoryModel->getMainCategoryBrandsById($id,$city);
      
        
        $response->brands=$brands;
        
        return $this->response->setJSON(success($response, 200));
    }
     function getcategoryFilter($id,$cityName,$filterid){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new SubCategoryModel();
        $response = new stdClass();
       

       
            $res =  $categoryModel->dealerSpecification($filterid,$id,$city);
         
           
        
        return $this->response->setJSON(success($res, 200));
    }
    function categoryFilter($id,$cityName){
        $city = $cityName== "mysuru" ? "" : $cityName."_";
        $departmentModel = new DepartmentModel();
        $categoryModel = new SubCategoryModel();
        $response = new stdClass();
        $brands = $categoryModel->getMainCategoryBrandsById($id,$city);
     
        $filters = $categoryModel->filter($id,$city);
        $response->brands=$brands;
    
        foreach($filters as $filter){
            $res =  $categoryModel->dealerSpecification($filter->SpecificationId,$id,$city);
            $specificationName = $filter->SpecificationName;
            $response->$specificationName = $res;
        }
        return $this->response->setJSON(success($response, 200));
    }

    function getcategorylist($id){
		
		$catfilter=[];
		$catfilter1=[];
	    $categoryModel = new MainCategoryModel();
		$response = new stdClass();
		$maincateory=$categoryModel->getmaincaterory($id);
		$subcategoryModel = new SubCategoryModel();
        foreach($maincateory as $m){
        		 $subcateory=$subcategoryModel->getsubcaterory($m->MainCategoryId);
        		 $catfilter=["main"=>$m->MainCategoryName,"mainId"=>$m->MainCategoryId,
        		 "sub"=>$subcateory];
                 $catfilter1[]=$catfilter;
        	///array_push($catfilter1,);
        	} 

		
		 return $this->response->setJSON(success($catfilter1, 200));
    }

    function getsubcategorylist($id){
	
        $subcategoryModel = new SubCategoryModel();
        $response = new stdClass();
        $subcateory=$subcategoryModel->getsubcaterory($id);
        return $this->response->setJSON(success($subcateory, 200));
	}


	}
