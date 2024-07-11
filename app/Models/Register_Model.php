<?php 
namespace App\Models;
use CodeIgniter\Model;

class Register_Model extends Model
{
    protected $table = 'useraccounts';
    protected $primaryKey = 'UserId';
    protected $allowedFields = ['UserName', 'EmailId','Password','Mobile'];
}