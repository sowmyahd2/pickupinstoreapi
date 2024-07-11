<?php


function productImageUrl($departmentId, $mainCategoryId, $subCategoryId, $size, $number, $path)
{
    $productUrl = "https://d1mmws0cero5pr.cloudfront.net/";
    if ($subCategoryId == 0) {
        return $productUrl . "D" . $departmentId . "/M" . $mainCategoryId . "/" . $size . "/" . "image" . $number . "/" . $path;
    } else {
        return $productUrl . "D" . $departmentId . "/M" . $mainCategoryId . "/S" . $subCategoryId . "/" . $size . "/" . "image" . $number . "/" . $path;
    }
}

function storeLogo($image)
{
    $storeLogobaseUrl = 'https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/';
    return $storeLogobaseUrl . $image;
}

function departmentIcon($image)
{
    $departmentBaseUrl = "https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/department_icons/";
    return $departmentBaseUrl . $image;
}

function brandLogo($image)
{
    $brandImage = "https://s3-ap-southeast-1.amazonaws.com/cityonnet-virtualmall/";
    return $brandImage . str_replace("jpg", "png", $image);
}

function categoryBanner($id)
{
    $banner = "https://cityonnet-virtualmall.s3-ap-southeast-1.amazonaws.com/category_banner/";
    return $banner . $id . '.jpg';
}

/** 
 * Get header Authorization
 * */
function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}
/**
 * get access token from header
 * */
function getBearerToken()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
/**
 *  send sms
 */
function sendSms($number,$message){

	$service_url="http://alerts.solutionsinfini.com";
	$sender='CITIES';
	$api_key="Ab429e3d02a1a3721410a2b187ea5da2a";


	$service="http://alerts.solutionsinfini.com/api/v3/index.php?method=sms.xml";
	$xmldata =  '<?xml version="1.0" encoding="UTF-8"?><api><sender>CITIES</sender><message>'.$message.'</message><sms><to>91'.$number.'</to></sms></api>';
	$api_url =$service.'&api_key='.$api_key.'&format=xml&xml='.urlencode($xmldata);

	$response = file_get_contents($api_url);
	if($response)
	{
		return $response;
	}

}