<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth_Model;
use App\Models\BrandModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Authenticate extends BaseController
{
    use ResponseTrait;
    function index()
    {
           $usermodel = new UserModel();
        $data = $this->request->getJSON();
        $email = $data->email;
        $password = $data->password;
        $number=$data->number;
        $date = date('Y-m-d H:i:s');
        $authModel = new Auth_Model();
        if($email!="" && $password!=""){
            $user = $authModel->login($email, $password);  
        }
      else{
$user=$usermodel->getuserdetail($number);
      }
        if ($user) {
            $key = JWT_KEY;
            $payload = array(
                "iss" => "https://cityonnet-api.herokuapp.com/",
                "aud" => "https://cityonnet-api.herokuapp.com/",
                "iat" => time(),
                "userId" => $user->UserId
            );
            $jwt = JWT::encode($payload, $key);
            $resp = array (
                "UserId" => $user->UserId,
                "Username" => $user->UserName,
                "EmailId" => $user->EmailId,
                "Mobile" => $user->Mobile,
                "Gender" => $user->Gender,
                "Address" => $user->Address,
                "AccessToken" => $jwt
            );
            return $this->response->setJSON(success($resp, 200), 200);
        } else {
            return $this->response->setJSON(success("", 403, "invalid credentials"));
        }
    }
    function logindetail($number)
    {
        
      
         $usermodel = new UserModel();
   
   
  
  
        $date = date('Y-m-d H:i:s');
        $authModel = new Auth_Model();
        $user=$usermodel->getuserdetail($number);
        if ($user) {
            
            
            $resp = array (
                "UserId" => $user->UserId,
                "Username" => $user->UserName,
                "EmailId" => $user->EmailId,
                "Mobile" => $user->Mobile,
                "Gender" => $user->Gender,
                "Address" => $user->Address,
               
            );
            return $this->response->setJSON(success($resp, 200), 200);
        } else {
            return $this->response->setJSON(success("", 403, "invalid credentials"));
        }
    }
}
