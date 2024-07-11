<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth_Model;
use App\Models\BrandModel;
use App\Models\Cart_Model;
use App\Models\CheckoutModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Checkout extends BaseController
{
    use ResponseTrait;
    function index($type,$cityName)
    {
        $token = getBearerToken();
        if ($token !== null) {
            $user = JWT::decode($token, JWT_KEY,array('HS256'));
            $userId = $user->userId;
            $data = $this->request->getJSON();
            $date = $data->startDate;
            $city = $cityName == "mysore" ? "" : $cityName . "_";
            if ($user) {
                $data = $this->request->getJSON();
                $cityName = $data->city;
                $city = $cityName == "mysore" ? "" : $cityName . "_";
                $checkoutModel = new CheckoutModel();
                $checkoutModel->placeOrder($type,$city,$userId,$date);
                return $this->response->setJSON(success("", 200, "order placed successfully"));        
            } else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        } else {
            return $this->response->setJSON(success("", 403, "unauthorized"));
        }
    }
    function pickcartcount($userId,$cityName){
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        if ($userId) {
      $checkoutModel = new CheckoutModel();
         $count=$checkoutModel->pickcartcount($userId,$city);
         return $this->response->setJSON(success($count, 200));
        } else {
            return $this->response->setJSON(success($userId, 403, "unauthorized"));
        }

    }
    function homecartcount($userId,$cityName){
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        if ($userId) {
      $checkoutModel = new CheckoutModel();
         $count=$checkoutModel->homecartcount($userId,$city);
         return $this->response->setJSON(success($count, 200));
        } else {
            return $this->response->setJSON(success($userId, 403, "unauthorized"));
        }

    }
    function homeordercheckout($type,$cityName,$userId){
       
         $data = $this->request->getJSON();
            $city = $cityName == "mysore" ? "" : $cityName . "_";
            if ($userId) {
          $checkoutModel = new CheckoutModel();
             $checkoutModel->placeOrder($type,$city,$userId,$date="");
                return $this->response->setJSON(success("", 200, "order placed successfully"));        
            } else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        
    }
    function pickordercheckout($type,$cityName,$userId,$date){
       
        $data = $this->request->getJSON();
           $city = $cityName == "mysore" ? "" : $cityName . "_";
           if ($userId) {
         $checkoutModel = new CheckoutModel();
            $checkoutModel->placeOrder($type,$city,$userId,$date);
               return $this->response->setJSON(success("", 200, "order placed successfully"));        
           } else {
               return $this->response->setJSON(success("", 403, "unauthorized"));
           }
       
   }
    function addrecipient(){
         $data = $this->request->getJSON();
           $result = new stdClass();
           
         $name=$data->name;
         $number=$data->number;
         $userid=$data->userid;
         $city=$data->city;
         $city = $city == "mysore" ? "" : $city . "_";
         $checkoutModel = new CheckoutModel();
              $data = array(
          'Name' => $name,
    'MobileNumber'=>$number,
    'UserId'=>$userid,

    );
              
     $result->insertid = $checkoutModel->insertrecipient($data, $city);
     $result->name=$name;
        $result->number=$number;
     return $this->response->setJSON(success($result, 200));
    }
    
}