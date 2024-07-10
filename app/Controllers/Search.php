<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Search_Model;
use CodeIgniter\API\ResponseTrait;
use stdClass;

helper('response');
helper('cityonnet');
class Search extends BaseController
{
    use ResponseTrait;

    function autocomplete(){
        $data = $this->request->getJSON();
        $term = $data->term;
        $productss = [];
        $cityName = $data->city;
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $id = $data->departmentId;
        $search = new Search_Model();
        $category = $search->getMainCategory($term,$id);
        $response = new stdClass();
       $response->category = $category;
        $response->brands = $search->getBrands($term);
        $response->stores = $search->getshop($term,$id,$city);
         //  $products = $search->getproducts($term,$id,$city);
             
              
        return $this->response->setJSON(success($response, 200));
    }
    
    
    
}