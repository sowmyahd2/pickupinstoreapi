<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\BrandModel;
use App\Models\CityModel;
use CodeIgniter\API\ResponseTrait;

helper('response');
class City extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $data = $this->request->getJSON();
        $cityModel = new CityModel();
        $city = $cityModel->where('MainCityStatus',1)->findAll();
        return $this->response->setJSON(success($city, 200));
    }
    public function getcityname($cityname,$pincode)
    {
        $data = $this->request->getJSON();
        $cityModel = new CityModel();
        if($pincode!=0){
            $city = $cityModel->where('NewCityName',$cityname)->
 where('Pincode',$pincode)->select("areatable.Pincode,citytable.CityName")->join('areatable','citytable.CityId=areatable.CityId')->first();
       
        }
        else{
            $city = $cityModel->where('NewCityName',$cityname)->select("areatable.Pincode,citytable.CityName")->join('areatable','citytable.CityId=areatable.CityId')->first();
       
        }
        return $this->response->setJSON(success($city, 200));
    }
    
}
