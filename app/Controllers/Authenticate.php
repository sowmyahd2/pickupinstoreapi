<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth_Model;
use App\Models\BrandModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;


helper('response');
helper('cityonnet');
class Authenticate extends BaseController
{
    use ResponseTrait;
    function index()
    {
        $data = $this->request->getJSON(); 
        $email= $data->email;
        $password= $data->password;


        $date = date('Y-m-d H:i:s');
        $authModel = new Auth_Model();
        $user = $authModel->login($email, $password);
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
                "status" => 'success',
                "AccessToken" => $jwt
            );
            return $this->response->setJSON(success($resp, 200), 200);
        } else {
            return $this->response->setJSON(success("", 403, "invalid credentials"));
        }
    }


    public function emailverify(){

      $data = $this->request->getvar();
      $email= $data['email'];
       $authModel = new Auth_Model();
      $user = $authModel->emaillogin($email);
      if ($user) {
         $resp = array (
                
                "EmailId" => $user->EmailId,
                "status" => 'success',
                
            );
        return $this->response->setJSON(success($resp, 200), 200);
      } 
      else {
       return $this->response->setJSON(success("", 403, "invalid credentials"));
      }
	}

}


