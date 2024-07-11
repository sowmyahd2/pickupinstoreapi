<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\GlobaldataModel;
use CodeIgniter\API\ResponseTrait;
use stdClass;
helper('response');
class Globaldata extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $globalModel = new GlobaldataModel();
        $dealerdata = $globalModel->findAll(10);
        $dealerArray = [];
        foreach ($dealerdata as $dealerdata) {
            array_push($dealerArray, array(
                "DealerId"  => $dealerdata->DealerId,
                "Shopname"  => $dealerdata->Shop_Name,
                "address"    => $dealerdata->Address,
                "shoptime"  => $dealerdata->ShopTiming,
                "website"    => $dealerdata->ShopWebsite,
                "city"       => $dealerdata->City,
                "pincode"    => $dealerdata->PinCode,
                "country"    => $dealerdata->Country,
                "number"     => $dealerdata->PhoneNumber,
                "service"    => $dealerdata->Service_option,  
                "servicetype" => $dealerdata->Service_type,
                "orderfrom"=> $dealerdata->Order_from,
                "DepartmentGroupId" => $dealerdata->Content
            ));
        }
        return $this->response->setJSON(success($dealerArray, 200));
    }
}
?>