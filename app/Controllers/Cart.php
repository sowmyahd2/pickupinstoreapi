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
        $token = getBearerToken();
        if ($token !== null) {
            $user = JWT::decode($token, JWT_KEY,array('HS256'));
            if ($user) {
                $data = $this->request->getJSON();
                $cityName = $data->city;
                $qty = $data->qty;
                $city = $cityName == "mysore" ? "" : $cityName . "_";
                $dealerPriceId = $data->dealerPriceId;
                $type = $data->type;

                $cartModel = new Cart_Model();
                $product = $cartModel->getProductDetali($dealerPriceId, $city);
                $items = array(
                    'UserId' => $user->userId,
                    'ProductId' => $product->ProductId,
                    'ProductName' => $product->ProductName,
                    'Price' => $product->SellingPrice,
                    'QuantityPurchased' => $qty,
                    'DealerId' => $product->DealerId,
                    'DealerPriceId' => $dealerPriceId,
                    'AddToCartTime' => date('Y-m-d h:i:s'),
                );
                $cartModel->add($items, $type, $city);
                $cart = $cartModel->view($type,$city,$user->userId);
                $productArray = [];
                foreach($cart as $product){
                    $item = array(
                        "ProductId" => $product->ProductId,
                        "ProductName" => $product->ProductName,
                        "DepartmentId" => $product->DepartmentId,
                        "MainCategoryId" => $product->MainCategoryId,
                        "SubCategoryId" => $product->SubCategoryId,
                        "Qty" => $product->QuantityPurchased,
                        "ProductCode" => $product->ProductCode,
                        "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                        "SpecificationName" =>$product->SpecificationName,
                        "SellingPrice" => $product->SellingPrice,
                        "MRP" => $product->MRP,
                        "SpecificationValue" => $product->SpecificationValue,
                        "ShopName" => $product->ShopName,
                        "DealerId" => $product->DealerId,
                        "SubTotal" => $product->QuantityPurchased * $product->StorePrice,
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
                return $this->response->setJSON(success($productArray, 200));
            } else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        } else {
            return $this->response->setJSON(success("", 403, "unauthorized"));
        }
    }
    function addtocart(){
        $data = $this->request->getJSON();
        $userId = $data->userId;
    
        if ($userId) {
            $userId = $data->userId;
            $cityName = $data->city;
            $qty = $data->qty;
           
            $city = $cityName == "mysore" ? "" : $cityName . "_";
            $dealerPriceId = $data->dealerPriceId;
            $type = $data->type;

            $cartModel = new Cart_Model();
            $product = $cartModel->getProductDetali($dealerPriceId, $city);
            $items = array(
                'UserId' => $userId,
                'ProductId' => $product->ProductId,
                'ProductName' => $product->ProductName,
                'Price' => $product->SellingPrice,
                'QuantityPurchased' => $qty,
                'DealerId' => $product->DealerId,
                'DealerPriceId' => $dealerPriceId,
                'AddToCartTime' => date('Y-m-d h:i:s'),
            );
            $cartModel->add($items, $type, $city);
            $cart = $cartModel->view($type,$city,$userId);
            $productArray = [];
            foreach($cart as $product){
                $item = array(
                    "ProductId" => $product->ProductId,
                    "ProductName" => $product->ProductName,
                    "DepartmentId" => $product->DepartmentId,
                    "MainCategoryId" => $product->MainCategoryId,
                    "SubCategoryId" => $product->SubCategoryId,
                    "Qty" => $product->QuantityPurchased,
                    "ProductCode" => $product->ProductCode,
                    "thumb_image" => productImageUrl($product->DepartmentId, $product->MainCategoryId, $product->SubCategoryId, 'thumbs', 1, $product->Image1),
                    "SpecificationName" =>$product->SpecificationName,
                    "SellingPrice" => $product->SellingPrice,
                    "MRP" => $product->MRP,
                    "SpecificationValue" => $product->SpecificationValue,
                    "ShopName" => $product->ShopName,
                    "DealerId" => $product->DealerId,
                    "SubTotal" => $product->QuantityPurchased * $product->StorePrice,
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
            return $this->response->setJSON(success($productArray, 200));
        } else {
            return $this->response->setJSON(success("", 403, "unauthorized"));
        }
    }
    function cartview($type,$cityName,$userid){
      
            $user = $userid;
            if ($user) {
                $cartModel = new Cart_Model();
                $city = $cityName == "mysore" ? "" : $cityName . "_";
                $cart = $cartModel->view($type,$city,$user);
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
                        "ShopName" => $product->ShopName,
                        "ShopAddress" => $product->Adress,
                        "ShopLogo" => $product->ShopLogo,
                        "MobileNumber" => $product->MobileNumber
                    );
                    array_push($productArray, $item);
                }
                return $this->response->setJSON(success($productArray, 200));
            }else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
       
    }

    
    function view($type,$cityName){
        $token = getBearerToken();
        if ($token !== null) {
            $user = JWT::decode($token, JWT_KEY,array('HS256'));
            if ($user) {
                $cartModel = new Cart_Model();
                $city = $cityName == "mysore" ? "" : $cityName . "_";
                $cart = $cartModel->view($type,$city,$user->userId);
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
                        "ShopName" => $product->ShopName,
                        "ShopAddress" => $product->Adress,
                        "ShopLogo" => $product->ShopLogo,
                        "MobileNumber" => $product->MobileNumber
                    );
                    array_push($productArray, $item);
                }
                return $this->response->setJSON(success($productArray, 200));
            }else {
                return $this->response->setJSON(success("", 403, "unauthorized"));
            }
        }else {
            return $this->response->setJSON(success("", 403, "unauthorized"));
        }
    }
    public function removeproduct($userid,$type,$cityName,$pid){
        $cartModel = new Cart_Model();
        $city = $cityName == "mysore" ? "" : $cityName . "_";
        $cart = $cartModel->removeproduct($userid,$type,$pid,$city);
        var_dump($cart);
   
    }
}
