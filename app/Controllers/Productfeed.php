<?php 

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use App\Models\ProductFeedModel;
use App\Models\ProductViewCountModel;
use App\Models\StoreModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Productfeed extends BaseController
{
	use ResponseTrait;

	public function index(){
		 $productfeedmodel = new ProductFeedModel();
		 $data = $this->request->getJSON();
        $email = $data->email;
        $password = $data->password;
         if($email!="" && $password!=""){
            $user = $productfeedmodel->userdetail($email, sha1($password));  
             if ($user) {
            $key = JWT_KEY;
            $payload = array(
                "iss" => "https://cityonnet-api.herokuapp.com/",
                "aud" => "https://cityonnet-api.herokuapp.com/",
                "iat" => time(),
                "userId" => $user->ProdFeed_AccountId
            );
            $jwt = JWT::encode($payload, $key);
            $resp = array (
                "UserId" => $user->ProdFeed_AccountId,
                "Username" => $user->FirstName,
                "EmailId" => $user->EmailId,
                "Mobile" => $user->MobileNumber,
                "AccessToken" => $jwt
            );
            return $this->response->setJSON(success($resp, 200), 200);
        } else {
            return $this->response->setJSON(success("", 403, "invalid credentials"));
        }
        }
	}

}

?>