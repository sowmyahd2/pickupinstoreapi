<?php namespace App\Models;

use CodeIgniter\Model;

class DealerViewCountModel extends Model
{
    protected $table         = 'dealer_viewcount';
    protected $returnType    = 'App\Entities\DealerViewCount';

    function index($city,$pincode)
    {
        $db = db_connect();
        if($pincode!=0){
            return $db->table($city.$this->table. ' dvc')->join($city.'dealeraccounts da', 'da.DealerId=dvc.DealerId')->where("da.PinCode",$pincode)->orderBy('Viewcount','desc')->limit(30,0)->get()->getResult();
        }
        else{
            return $db->table($city.$this->table. ' dvc')->join($city.'dealeraccounts da', 'da.DealerId=dvc.DealerId')->orderBy('Viewcount','desc')->limit(30,0)->get()->getResult();
        }
       
    }

}