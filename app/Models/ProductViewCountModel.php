<?php namespace App\Models;

use CodeIgniter\Model;

class ProductViewCountModel extends Model
{
    protected $table         = 'productviewcount';
    protected $returnType    = 'App\Entities\ProductViewCount';

    function index($city)
    {
        $db = db_connect();
        return $db->table($city.$this->table. ' pvc')->join('products p', 'p.ProductId=pvc.ProductId')->orderBy('ViewCount','desc')->limit(30,0)->get()->getResult();
    }

}