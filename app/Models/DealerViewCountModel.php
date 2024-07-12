<?php namespace App\Models;

use CodeIgniter\Model;

class DealerViewCountModel extends Model
{
    protected $table         = 'dealer_viewcount';
    protected $returnType    = 'App\Entities\DealerViewCount';

    function index($city)
    {
        $db = db_connect();
        return $db->table($city.$this->table. ' dvc')->join($city.'dealeraccounts da', 'da.DealerId=dvc.DealerId')->orderBy('Viewcount','desc')->limit(9,0)->get()->getResult();
    }

}