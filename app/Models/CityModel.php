<?php namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table         = 'citytable';
    protected $returnType    = 'App\Entities\City';
}