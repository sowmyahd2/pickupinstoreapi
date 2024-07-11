<?php namespace App\Models;

use CodeIgniter\Model;

class ProductFeedModel extends Model
{
function userdetail($email,$password){
       
       
       
        $db1 = db_connect("prod");
        $user = $db1->table('productfeed_accounts u')
		->where('EmailId',$email)
		->where('Password',$password)
        ->where('AccountStatus',1)
	   ->get()->getRow();
      if($user){
                return $user;
      }
      return false;
    }
}