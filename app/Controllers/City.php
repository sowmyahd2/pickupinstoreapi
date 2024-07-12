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
     public function testi(){

 if($img = $this->request->getFile('fileName'))
        {
            if ($img->isValid() && ! $img->hasMoved())
            {
                $newName = $img->getRandomName();
                $img->move('./public/uploads/', $newName);

                // You can continue here to write a code to save the name to database
                // db_connect() or model format

            }
        }



return $this->response->setJSON(success($img, 200));

    }
}
