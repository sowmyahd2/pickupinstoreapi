<?php

namespace APP\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth_Model;
use App\Models\BrandModel;
use App\Models\Cart_Model;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use stdClass;

helper('response');
helper('cityonnet');
class Cart extends BaseController
{
    use ResponseTrait;
    function index()
    {
        
            $user=2910;
            if ($user) {
                $data = $this->request->getJSON();
                
                die();

                foreach($data as $product){
                        $cityName = $product->city;
                        $qty = $product->qty;
                        $city = $cityName == "mysuru" ? "" : $cityName . "_";
                        $type = $product->cartType;
                        $cartModel = new Cart_Model();
                        $items = array(
                            //'UserId' => $user->userId,
                            'UserId' =>$user,
                            'ProductId' => $product->productId,
                            'ProductName' => $product->productName,
                            'Price' => $product->price,
                            'QuantityPurchased' => $product->qty,
                            'DealerId' => $product->DealerId,
                            'DealerPriceId' =>$product->DealerPriceId,
                            'AddToCartTime' => date('Y-m-d h:i:s'),
                        );
                        $cartModel->add($items, $type, $city); 

                }
               return $this->response->setJSON(success('cart details inserted successfuly', 200));
            } else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
       
    }

function addtocart(){

    $data = $this->request->getJSON();
    $cart=$data->cartterm;
    $user=2910;
    if ($user) {
    foreach ($cart as $cartitem) {
        $cityName = $cartitem->city;
        $qty = $cartitem->qty;
        $city = $cityName == "mysuru" ? "" : $cityName . "_";
        $type = $cartitem->cartType;
        $cartModel = new Cart_Model();
        $items = array(
             'UserId' =>$user,
            'ProductId' => $cartitem->productId,
            'ProductName' => $cartitem->productName,
            'Price' => $cartitem->price,
            'QuantityPurchased' => $cartitem->qty,
            'DealerId' => $cartitem->DealerId,
            'DealerPriceId' =>$cartitem->DealerPriceId,
            'AddToCartTime' => date('Y-m-d h:i:s'),
        );
        $cartModel->add($items, $type, $city); 
    }
        return $this->response->setJSON(success('cart details inserted successfulyy', 200));
            
            
    }else {
        return $this->response->setJSON(success("", 403, "unauthorized"));
    }
             
}


function view($type,$cityName,$userid){
         $user=$userid;

         $result = new stdClass();
            if ($user) {
                $cartModel = new Cart_Model();
                $city = $cityName == "mysuru" ? "" : $cityName . "_";
              
                $cart = $cartModel->view($type,$city, $user);
             
                if($type==3){
                $result->reserverdays= $cartModel->getreserverdays($user,$city);
                 $result->recipient= $cartModel->getreciepeintdetail($user,$city);
                   
                }
                  $result->sellerdetail = $cartModel->getsellerdetail($type, $userid,$city);
                  
                $productArray = [];
                foreach($cart as $product){
                    $item = array(
                        "ProductId" => $product->ProductId,
                        "DealerPriceId" => $product->DealerPriceId,
                        "ProductName" => $product->ProductName,
                        "DepartmentId" => $product->DepartmentId,
                        "MainCategoryId" => $product->MainCategoryId,
                        "SubcategoryId" => $product->SubCategoryId,
                        "Qty" => $product->QuantityPurchased,
                        "SubTotal" => $product->QuantityPurchased * $product->StorePrice,
                        "ProductCode" => $product->ProductCode,
                        "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                        "SpecificationName" =>$product->SpecificationName,
                        "SellingPrice" => $product->SellingPrice,
                        "MRP" => $product->MRP,
                        "SpecificationValue" => $product->SpecificationValue,
                        "ShopName" => $product->ShopName,
                        "DealerId" => $product->DealerId,
                        "ReserveDays" => $product->ReserveDays,
                        "StorePrice" => $product->StorePrice,
                        "FreeShipmentStatus" => $product->FreeShipmentStatus,
                        "LocalShipmentCost" => $product->LocalShipmentCost,
                        "ZoneShipmentCost" => $product->ZoneShipmentCost,
                        "NationalShipmentCost" => $product->NationalShipmentCost,
                        "LocalMinOrderPrice" => $product->LocalMinOrderPrice,
                        "ZoneMinOrderPrice" => $product->ZoneMinOrderPrice,
                        "NationalMinOrderPrice" => $product->NationalMinOrderPrice,
                    );
                    array_push($productArray, $item);
                }
                 $result->cartproducts=$productArray;
                return $this->response->setJSON(success($result, 200));
            }else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        
    }
    function pickupcartview($type,$cityName,$userid){
         $user=$userid;

         $result = new stdClass();
            if ($user) {
                $cartModel = new Cart_Model();
                $city = $cityName == "mysuru" ? "" : $cityName . "_";
              
                $cart = $cartModel->pickupcartview($type,$city, $user);
             
            
               
                  
                $productArray = [];
                foreach($cart as $product){
                    $item = array(
                        "ProductId" => $product->ProductId,
                        "DealerPriceId" => $product->DealerPriceId,
                        "ProductName" => $product->ProductName,
                        "DepartmentId" => $product->DepartmentId,
                       "Qty" => $product->QuantityPurchased,
                        "SubTotal" => $product->QuantityPurchased * $product->StorePrice,
                        "ProductCode" => $product->ProductCode,
                        "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                          "ShopName" => $product->ShopName,
                        "DealerId" => $product->DealerId,
                        "ReserveDays" => $product->ReserveDays,
                        "StorePrice" => $product->StorePrice,
                       
                    );
                    array_push($productArray, $item);
                }
                 $result->cartproducts=$productArray;
                return $this->response->setJSON(success($result, 200));
            }else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        
    }
}
