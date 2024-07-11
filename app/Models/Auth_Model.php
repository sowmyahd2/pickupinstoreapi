<?php namespace App\Models;

use CodeIgniter\Model;

class Auth_Model extends Model
{
function login($email,$password)
    {
        $pass=hash('sha256', $password);
       
        $db = db_connect();
        $user = $db->table('useraccounts')
		->where('EmailId',$email)
        ->where('Password',$pass)
        ->where('SignUpComplete',1)
	   ->get()->getRow();
      if($user){
                return $user;
      }
      return false;
    }
}