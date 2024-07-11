<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Register_Model;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;


helper('response');
helper('cityonnet');
class Register extends BaseController
{
    use ResponseTrait;
    function index()
    {
       $data = $this->request->getvar();
       $username= $data['name'];
       $email= $data['email'];
       $mob= $data['mobile'];
       $pass= $data['password'];
       $pass=hash('sha256',$pass);
       $regModel = new Register_Model();
       $user = $regModel->insuserdetail($username,$email,$pass,$mob);
       
       
        }
        public function create() {
        $model = new Register_Model();
        
        $data = [
            'UserName' => $this->request->getVar('name'),
            'EmailId'  => $this->request->getVar('email'),
            'Password'  => hash('sha256',$this->request->getVar('Password')),
            'Mobile'  => $this->request->getVar('mobile'),


        ];
        $model->insert($data);
         $resp = array (
                
                "status" => 'success',
                'EmailId'  => $this->request->getVar('email'),
            );
            return $this->response->setJSON(success($resp, 200), 200);

    }
    
}
