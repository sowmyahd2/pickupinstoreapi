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
        $city = $cityName== "mysore" ? "" : $cityName."_";
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
}
