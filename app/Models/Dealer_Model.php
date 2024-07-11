<?php

namespace App\Models;

use CodeIgniter\Model;

class Dealer_Model extends Model
{
  
  public function getdealerdetail($email,$password)
  {
    $db = db_connect();
     return $db->table('dealercity_map')->select('DealerId,City,shop_url') ->where('EmailId', $email)->where('Password',$password)->get()->getRow();
  }
  public function get_product_size($dpid,$mid,$subid){
    $db = db_connect();
    $query=$db->table('product_sizes')
   ->select('ProductSize,ProductSizeId')
   ->where('DepartmentId', $dpid)
   ->where('MainCategoryId', $mid)
  ->where('SubCategoryId', $subid)
  ->get()->getResult();
    return $query;
  }
  public function updateaddress($a,$city,$dealerid){
     $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $a=implode(",",$a);
      $db      = \Config\Database::connect();
    $builder = $db->table($city.'dealeraccounts'); 
      $builder->set('Promotype', $a);
      $builder->where('DealerId', $dealerid);
      $cancel=$builder->update();
    
    
  }

  public function gethomeordertotal($id, $city)
{
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
  
  $data=$db->table($city.'hmainorder pso')
  ->select("")
  ->join($city .'hconorder ps', 'ps.H_MainOrderId=pso.H_MainOrderId')
  ->where('ps.DealerId', $id)
  ->groupBy('pso.H_MainOrderId')
  ->orderBy('pso.H_MainOrderId', 'Desc')
  ->get()->getResult();
  return count($data);
}
public function getpickupordertotal($id, $city)
{
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
  
  $data=$db->table($city.'ps_orders pso')
  ->select("")
  ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
 
  ->where('ps.DealerId', $id)
  ->groupBy('ps.PickatStoreId')
  ->orderBy('ps.PickatStoreId', 'Desc')
  ->get()->getResult();
  return count($data);
}
  public function getdealerdetailnumber($number){
    $db = db_connect();

    return $db->table('dealercity_map')->select('DealerId,City,shop_url') ->where('MobileNumber', $number)->get()->getRow();

  }
    public function getcityid($cityname)
    {
        $db = db_connect();
         return $db->table('citytable c')->select('*')->where('CityName', trim($cityname))->get()->getRow();
    }
   public function getstateid($stateid)
    {
        $db = db_connect();
         return $db->table('statetable c')->select('*')->where('StateId', $stateid)->get()->getRow();
    }
    public function smsverify($dealerid,$cityid,$city)
    {
       $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
        $db2 = db_connect("pos");
  return $db2->table($city.'dealer_pos_map')->select('*')->where('CityId', $cityid)->where('DealerId',$dealerid)->get()->getRow();

    }
    function getBrands($term)
    {
        $db = db_connect();
        return $db->table("brands b")
            ->select("b.BrandName,b.BrandId")
            ->like('b.BrandName', $term, 'after')
            ->get()->getResult();
    }
    public function posdata($city,$dealerid){
      $db2 = db_connect("pos");
      return $db2->table('poscontacts_new pc')->select('*')->join($city.'dealeruser_pos_map pm','pc.PosContactId=pm.PosContactId')->where('DealerId', $dealerid)->groupBy('pc.PhoneNum')->get()->getResult();
    
    }
      public function poscontact($number)
    {
        $db2 = db_connect("pos");
  return $db2->table('poscontacts_new')->select('*')->where('PhoneNum', $number)->get()->getRow();

    }
    public function inserttopos($a,$city){
       $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
 $db2 = db_connect("pos");
    $user = $db2->table($city.'dealer_pos_map');
    
    $user->insert($a);
    return $db2->insertID();

    }
    public function psordercancel($orderid,$pickid,$reasonid,$comments,$city){
      $db      = \Config\Database::connect();
      $builder = $db->table($city.'ps_orderdetails'); 
      $builder->set('PS_OrderStatusId', 3);
      $builder->where('PS_OrderDetailsId', $orderid);
      $cancel=$builder->update();
      if($cancel){
        $builder = $db->table($city.'ps_oc_reasonsmap');
        $items = array(
        'PickAtStoreId' => $pickid,
        'PS_OrderDetailsId' => $orderid,
        'PS_OC_ReasonsId' =>$reasonid,
        'UserComment' => $comments,
  
      );
      $builder->insert($items);
      }
      return false;
    }
    public function updatepos($a,$dealerid,$cityid,$city){
 $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $this->db= db_connect("pos");
  
      $this->db->table($city.'dealer_pos_map')->where('DealerId', $dealerid)->where('CityId',$cityid)->update($a);
  
      
     
    }
       public function insertdealerpos($a){
      $db2 = db_connect("pos");
      $user = $db2->table('poscontacts_new');
      $user->insert($a);
    return $db2->insertID();

    }
    public function updatedealerpos($a,$id){
       $this->db = db_connect("pos");
 $this->db->table('poscontacts_new')->where('PosContactId', $id)->update($a);

     
    }
    public function insert_pos_dealer_cust_map($arr,$id,$city){

      $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
       $db2 = db_connect("pos");
       $count=$db2->table($city.'pos_dealer_cust_map')->select('*')->where('PosContactId', $id)->get()->getRow();
       if($count){
        $count=$count->Num_of_Visits+1; 
    $this->db= db_connect("pos");
  $a = array('Num_of_Visits' => $count );
      $this->db->table($city.'pos_dealer_cust_map')->where('PosContactId', $id)->update($a);
 

       

       }
       else{
         $db2 = db_connect('pos');
      $user = $db2->table($city.'pos_dealer_cust_map');
      $user->insert($arr);
    return $db2->insertID();
       }  
    }

    public function insert_pos_user_cust_map($arr,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db2 = db_connect('pos');
      $user = $db2->table($city.'dealeruser_pos_map');
      $user->insert($arr);
      return $db2->insertID();
    }
    public function userdetail($email){

         $db = db_connect();
         return $db->table('useraccounts')->select('EmailId') ->where('EmailId', $email)->get()->getRow();
    }
    public function dealerdetail($dealerid,$city)
    {
        $db = db_connect();
         return $db->table('dealercity_map')->select('shop_url') ->where('DealerId', $dealerid)->where('City',$city)->get()->getRow();
    }
    public function checkemail($email)
    {
        $db = db_connect();
         return $db->table('dealercity_map')->select('*') ->where('EmailId', $email)->get()->getRow();
    }
  public function getdepartname($dpid)
  {
       $db=db_connect();
       return $db->table('department')->select('DepartmentName')->where('DepartmentId',$dpid)->get()->getRow();
  }
  public function getmaincatname($mainid)
   {
         $db=db_connect();
         return $db->table('maincategory')->select('MainCategoryName')->where('MainCategoryId',$mainid)->get()->getRow();
   }
   public function getsubcatname($subid)
   {
         $db=db_connect();
         return $db->table('subcategory')->select('SubCategoryName')->where('SubCategoryId',$subid)->get()->getRow();
   }
  public function getdealerdetails($dealerid,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    
    $db = db_connect();
     return $db->table($city.'dealeraccounts da')->select('*')->join("citytable c","c.CityName=da.CityName")->join("statetable s","c.StateId=s.StateId")->where('Dealerid', $dealerid)->get()->getRow();
  }
     public function updatedate($dealerid,$type,$dayid,$cityname,$str){
      $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";  
      $str=date( "h:i A", strtotime( $str ) );
      
      $db = db_connect(); 
     $time=$db->table($city.'dealershoptimings da')->select('*')->where('Dealerid', $dealerid)->where('DayId', $dayid)->get()->getRow();
    $t=$time->ShopTimings;
    $ti=explode("-",$t);
  
    if($type=="ot"){
      $newtiime=$str."-".$ti[1];
    }
    else{
      $newtiime=$ti[0]."-".$str;
    }
    $a=[
      "ShopTimings"=>$newtiime
    ];
    $this->db->table($city.'dealershoptimings da')->where('DealerId', $dealerid)->where('DayId', $dayid)->update($a);
    }
     
  public function getdealershoptime($dealerid,$city){
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
      
      $db = db_connect();
       return $db->table($city.'dealershoptimings da')->select('*,SUBSTRING(Days, 1, 3) AS Days')->join('weekdays w','w.DayId=da.DayId')->where('Dealerid', $dealerid)->groupBy('da.DayId')->get()->getResult();
    }
  public function getdealerdetaisl($dealerid,$city){
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
      
      $db = db_connect();
       return $db->table($city.'dealeraccounts da')->select('*')->where('Dealerid', $dealerid)->get()->getRow();
    }
    public function emailverify($email,$city){
   $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_"; 
    
    $db = db_connect();
     return $db->table($city.'dealeraccounts da')->select('*') ->where('EmailId', $email)->get()->getRow();
  }
public function insertdealerdata($data,$city){
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db = db_connect();
    $user = $db->table('dealeraccounts');
    
    $user->insert($data);
    return $db->insertID();
}
public function addtodealershoptime($city,$id){
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db = db_connect();
   $time=$db->table($city.'dealershoptimings da')->select('*')->where('Dealerid', $id)->get()->getRow();
   if(!$time){
    for ($x = 1; $x <= 7; $x++) {
      $data1=[
        'DealerId'=>$id,
     'DayId'=>$x,
     'ShopTimings'=>'07:00 AM-10:00 PM'
     
         ];
         $user = $db->table($city.'dealershoptimings');
         
         $user->insert($data1);
    }
   }
  
   
    
    
}
public function addtodealerbankdetail($data,$city,$id){
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db = db_connect();
   $time=$db->table($city.'dealer_bankdetails da')->select('*')->where('Dealerid', $id)->get()->getRow();
   if(!$time){
    $user = $db->table($city.'dealer_bankdetails');
    
    $user->insert($data);
    return $db->insertID();
   }
   else{
    $this->db->table($city.'dealer_bankdetails')->where('DealerId', $id)->update($data);
    return $id;
   }
}
public function changedealermap($dealerid,$arr,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
$db = db_connect();

  $this->db->table('dealercity_map')->where('DealerId', $dealerid)->where("City",$cityname)->update($arr);
  $this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($arr);

}
public function changeemail($dealerid,$arr,$arr1,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
$db = db_connect();

  $this->db->table('dealercity_map')->where('DealerId', $dealerid)->where("City",$cityname)->update($arr);
  $this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($arr1);

}

public function changedetail($dealerid,$a,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
$db = db_connect();
$this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($a);

}
public function inserttodealermap($cityname,$mobile,$email,$password,$dealerid,$shopurl,$regurl){
 

$db = db_connect();


 $c= $db->table('dealercity_map')->select('*')->where('DealerId',$dealerid)->where('City',$cityname)->get()->getRow();
  $data=[
"EmailId"=>$email,
"Password"=>$password,
"City"=>ucfirst($cityname),
"ActiveSatus"=>1,
"reg_url"=>$regurl,
"shop_url"=>$shopurl,
"DealerId"=>$dealerid,
"MobileNumber"=>$mobile,

  ];
 if(!$c){


   $user = $db->table('dealercity_map');
    
    $user->insert($data);
     return $db->insertID();
 }
 else{
  $this->db->table('dealercity_map')->where('DealerId', $dealerid)->where('City',$cityname)->update($data);
     return $dealerid;
 }
}
public function inserttouseraccount($email,$pass,$mobile,$address,$username,$cityid,$stateid){
  $db = db_connect();
  $c= $db->table('useraccounts')->select('*')->where('EmailId', $email)->get()->getRow();
   if(!$c){

  $data=[
"EmailId"=>$email,
"Password"=>$pass,
"CityId"=>$cityid,
"StateId"=>$stateid,
"UserName"=>$username,
"Address"=>$address,
"SignUpComplete"=>1

  ];
   $user = $db->table('useraccounts');
    
    $user->insert($data);
     return $db->insertID();
 }
}

public function addbrand($brandname){

$db = db_connect();


 $c= $db->table('brands')->select('*')->where('BrandName', $brandname)->where("BrandType",1)->get()->getRow();
 if(!$c){

  $data=[
"BrandName"=>$brandname,
"BrandType"=>1,
"Status"=>1,


  ];
   $user = $db->table('brands');
    
    $user->insert($data);
     return $db->insertID();
 }
 else{
  return $c->BrandId;
 }
}
public function insertproduct($brandid,$pname,$pcode,$dpartid,$maincatid,$subid){
$db = db_connect();


 $c= $db->table('products')->select('*')->where('ProductName', $pname)->where('ProductCode',$pcode)->where('BrandId',$brandid)->get()->getRow();
 if(!$c){

  $data=[
     "DepartmentId"    => $dpartid,
     "MainCategoryId"  => $maincatid,
     "SubCategoryId"   => $subid,
     "BrandId"         => $brandid,
     "ProductName"     => $pname,
     "ProductCode"     => $pcode,
     "Arrivalstatus"    =>  1,
     "BrandType"        =>1,
     "Origin"          =>"India"



  ];
   $user = $db->table('products');
    
    $user->insert($data);


     $pid= $db->insertID();
     $a=array(
      "Image1"   =>$pid."_image1.jpg",
      "Image2"   =>$pid."_image2.jpg",
      "Image3"   =>$pid."_image3.jpg",
      "Image4"   =>$pid."_image4.jpg",
      "Image5"   =>$pid."_image5.jpg",
      "Image6"   =>$pid."_image6.jpg",
      "Image7"   =>$pid."_image7.jpg",
      "Image8"   =>$pid."_image8.jpg",
      );
        $this->db->table('products')->where('ProductId', $pid)->update($a);
     return $pid;
 }
 else{
  return $c->ProductId;
 }  
}
public function getsubscriptionplans($city){
  
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    $plans = ['Annual', 'Trial','Balance Towards Annual Subscription','Half Yearly'];
$db = db_connect();
 return $db->table($city.'subscriptionplans')->select('*')->where('ActiveStatus', 1)->wherein("SubscriptionPlanType",$plans)->get()->getResult();

}
public function getsubscriptionplandetail($city,$id){
  
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    
$db = db_connect();
 return $db->table($city.'subscriptionplans')->select('*')->where('ActiveStatus', 1)->where("SubscriptionPlanId",$id)->get()->getRow();

}
public function getsubscriptiondetail($planid){
  $city="mysore";
$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    
$db = db_connect();
 return $db->table($city.'subscriptionplans')->select('*')->where('SubscriptionPlanId', $planid)->get()->getRow();

}
  public function getdealerdepartment($dealerid,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    
    $db = db_connect();
     return $db->table($city.'dealerdepartment dd')->select('*')->join("department d","d.DepartmentId=dd.DepartmentId") ->where('DealerId', $dealerid)->groupBy('d.DepartmentId')->get()->getResult();
  }
public function maincategory($departid){
  
    
    $db = db_connect();
     return $db->table('maincategory')->select('*')->where('DepartmentId', $departid)->get()->getResult();
  }
   public function subcategory($departid){
  
    
    $db = db_connect();
     return $db->table('subcategory s')->select('*')->join('maincategory m','s.MainCategoryId=m.MainCategoryId')->where('s.MainCategoryId', $departid)->get()->getResult();
  } 
    public function insertDealerdepartment($dealerid,$city,$departval){
      
      $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    $db = db_connect();
    $builder = $db->table($city.'dealerdepartment');
      $items = array(
        'ProductId' => $pid,
        'AddedFrom' => "AndroidApp",
        'UserId' =>$userid,
      );
      $builder->insert($items);
     return $db->table($city.'dealerdepartment')->select('*') ->where('DealerId', $dealerid)->get()->getRow();
  }
   public function insertproductspection($key,$value,$pid){
      
      
    $db = db_connect();
    $builder = $db->table('productspecification');


$c=$db->table('productspecification')->select('*') ->where('ProductId', $pid)->where("SpecificationId",$key)->get()->getRow();
if(!$c){
$speci=
             [
                 "SpecificationId"      =>  $key,
                 "SpecificationValue"   =>  $value,
                 "ProductId"            =>  $pid
             ];

   $builder->insert($speci);
return $db->insertID();
}

else{

  return $c->ProductSpecificationId;
}
  }
public function dashboard($id){


 $this->db2 = db_connect("pos");

//$builder = $db1->table('dealer_pos_map');
 $sql = "SELECT COUNT(Dealer_POS_MapId) as 'count'  FROM dealer_pos_map WHERE DealerId=".$id."  GROUP BY MONTH(Date)";
return $this->db2->query($sql)->getResult();
}
public function getgstslab(){
$db = db_connect();
     return $db->table('gst_slabs')->select('*')->where('ActiveStatus', 1)->get()->getResult();


}
public function getbankdetail($dealerid,$cityname){
  $db = db_connect();
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
 return $db->table($city.'dealer_bankdetails')->select('*') ->where('DealerId', $dealerid)->get()->getRow();
 
  
  
  }
  public function updatebankdetail($data,$cityname,$dealerid){
    $db = db_connect();
    $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
    $detail=$db->table($city.'dealer_bankdetails')->select('*') ->where('DealerId', $dealerid)->get()->getRow();
  
    if($detail!=""){
     
      $this->db->table($city.'dealer_bankdetails')->where('DealerId', $dealerid)->update($data);
      $id= $dealerid;
     
    }
   
    else{
      $user = $db->table($city.'dealer_bankdetails');
      $user->insert($data);
      $id=$db->insertID();
   
    }
   return $id;
    
  }
  public function getgstindetail($city,$dealerid){
   
   $db = db_connect();
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
 return $db->table($city.'dealers_gst_mapping')->select('*') ->where('DealerId', $dealerid)->get()->getRow();
  }
    public function updategst($data,$cityname,$dealerid){
    $db = db_connect();
    $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
    $detail=$db->table($city.'dealers_gst_mapping')->select('*') ->where('DealerId', $dealerid)->get()->getRow();
    
     if($detail){
     
      $this->db->table($city.'dealers_gst_mapping')->where('DealerId', $dealerid)->update($data);
      $id= $dealerid;
     
    }
   
    else{
      $user = $db->table($city.'dealers_gst_mapping');
      $user->insert($data);
      $id=$db->insertID();
   
    }
   return $id;
    
  }
public function getmandatoryspecification($dpid,$mid,$sid){
  $db = db_connect();

  $sql = "SELECT   GROUP_CONCAT(DISTINCT SpecificationId SEPARATOR ',') AS mad from specificationsmapping   WHERE DepartmentId=".$dpid." and MainCategoryId=".$mid." and SubCategoryId=".$sid." and Mandatory_Status=1 GROUP BY DepartmentId";
  return $this->db->query($sql)->getRow();
  
}
public function addbrandedproduct($size,$city,$dealerid,$qty,$mrp,$shiping){
  $cat=[];
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
  $db = db_connect();
  for($i=0;$i<count($category);$i++){
    $c=$db->table($city.'dealerdepartment')->select('*') ->where('DealerId', $dealerid)->where("DepartmentId",$category[$i])->get()->getRow();
    if(!$c){
    $items = array(
            'DealerId' => $dealerid,
            'VerticalId'=>1,
            'DepartmentId' =>$category[$i],
            'CityId'=>245,
            'ActiveStatus'=>1,
    
          );
    
       $builder->insert($items);
    
    }
       
       
    }
}
public function addcategories($category,$dealerid,$city){
  
  $cat=[];
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
  $db = db_connect();
  $builder = $db->table($city.'dealerdepartment');
   
    
    $builder->where('DealerId', $dealerid);
$builder->delete();
for($i=0;$i<count($category);$i++){
$c=$db->table($city.'dealerdepartment')->select('*') ->where('DealerId', $dealerid)->where("DepartmentId",$category[$i])->get()->getRow();
if(!$c){
$items = array(
        'DealerId' => $dealerid,
        'VerticalId'=>1,
        'DepartmentId' =>$category[$i],
        'CityId'=>245,
        'ActiveStatus'=>1,

      );

   $builder->insert($items);

}
   
   
}


}
public function addproduct($product,$city)
{
  
  
   $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_"; 
    $db = db_connect();
    $builder = $db->table($city.'dealerprice');
    $builder->insert($product);
    return $db->insertID();


}
public function getproductprice($dealerid,$cityname,$pid,$dpid)
{
  $db = db_connect();
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
  return $db->table($city.'dealerprice dp')->select('*')->join($city.'dealerproductspecification dps','dp.DealerPriceId=dps.DealerPriceId','left')->join($city.'dp_gst_map gst','dp.DealerPriceId=gst.DealerPriceId','left')->join('gst_slabs slab','gst.GST_SlabId=slab.GST_SlabId','left')->where('dp.DealerId',$dealerid)->where('ProductId',$pid)->where('dp.DealerPriceId',$dpid)->get()->getRow();
 
}
public function getproductprices($dealerid,$cityname,$pid)
{
  $db = db_connect();
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
  return $db->table($city.'dealerprice')->select('*')->where('DealerId',$dealerid)->where('ProductId',$pid)->get()->getResult();
}  
public function editedproducts($dealerid,$cityname){
   $db = db_connect();

     
  return $db->table($cityname.'dealerprice dp')->select('*')->join('products p', 'dp.ProductId=p.ProductId')->where('DealerId',$dealerid)->limit(30,0)->orderBy('LastUpdate',"desc")->get()->getResult(); 
}
public function addproductpecification($product,$cityname,$dpid,$specid){
    $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";  
    $db = db_connect();
       $c=$db->table($city.'dealerproductspecification')->select('*') ->where('DealerPriceId', $dpid)->where('SpecificationValue', $specid)->get()->getRow();
if(!$c){
    $builder = $db->table($city.'dealerproductspecification');
    $builder->insert($product);
    return $db->insertID();
}
  else{
    return $c->DealerProductSpecificationId;
  }
}
public function addtogstmap($city,$slabs,$dealerpriceid){
    $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";  
    $db = db_connect();
    $builder = $db->table($city.'dp_gst_map');
    $c=$db->table($city.'dp_gst_map')->select('*') ->where('DealerPriceId', $dealerpriceid)->get()->getRow();
if(!$c){
  $product=[
     "DealerPriceId"=>$dealerpriceid,
     "GST_SlabId"=>$slabs,
     "ActiveStatus"=>1
    ];
   $builder->insert($product);
return $db->insertID();
}

else{
  $product=[
    "GST_SlabId"=>$slabs,
    "ActiveStatus"=>1
   ];
  $this->db->table($city.'dp_gst_map')->where('DealerPriceId', $dealerpriceid)->update($product);
  return $c->DealerPriceId;
}

}
public function getaddress($pincode){

   $db = db_connect();
        return $db->table('areatable a')
        ->select('StateName,CityName')
        ->where('a.PinCode',$pincode)
        ->join('citytable c', 'a.CityId=c.CityId')
        ->join('statetable s', 'c.StateId=c.StateId')
          ->get()->getRow();  
}
public function getexeid($exeid){
 
   $db3 = db_connect("market");
   $s=$db3->table('accounts')->select('*')->where('ExecutiveRefId', $exeid)->get()->getRow();
   return $s;
}
public function insertintosubscriptionplan($city,$sub){


 

$city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db3 = db_connect("market");
    $user = $db3 ->table('subscriptiondetails');
    
    $user->insert($sub);
    return $db3 ->insertID();

}
public function insertintosubscriptionpayment($city,$arr){
  $city  = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
    $db3   = db_connect("market");
    $user  = $db3 ->table('subscription_paymentdetails');
    
    $user->insert($arr);
    return $db3 ->insertID();



}
public function updateprice($dip,$city,$price){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
$db = db_connect();

  $user = $db->table($city.'dealerprice');
  $user->set('MRP', $price);
  $user->where('DealerPriceId', $dip);
  $user->update();
  

}
public function updateadress($data,$cityname,$dealerid){
    $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
$db = db_connect();

$this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($data);


}
// products

public function getdealerproducts($dealerid,$cityname){
 $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
 $db = db_connect();

        return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,b.BrandName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->where('p.BrandType',0)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
      ->get()->getResult();
}

public function dealercatproducts($dealerid,$maincatid,$subcatid,$cityname){
    $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
      $db = db_connect();
      
  return $db->table($city.'dealerprice dp')
         ->select('p.ProductId,p.BrandId,p.ProductName,b.BrandName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1,dp.DealerPriceId,ds.SpecificationValue')
         ->where('dp.DealerId',$dealerid)
       ->join('products p', 'dp.ProductId=p.ProductId')
       ->join($city.'dealerproductspecification ds', 'dp.DealerPriceId=ds.DealerPriceId',"left")
         ->join('brands b', 'p.BrandId=b.BrandId')
         ->where('p.BrandType',0)
         ->where('p.MainCategoryId',$maincatid)
         ->where('p.SubCategoryId',$subcatid)
         ->groupBy('dp.DealerPriceId')
       ->get()->getResult();
}
public function dealercatnobrandedproducts($dealerid,$maincatid,$subcatid,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
    $db = db_connect();
    
return $db->table($city.'dealerprice dp')
       ->select('p.ProductId,p.BrandId,p.ProductName,b.BrandName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1,dp.DealerPriceId,ds.SpecificationValue')
       ->where('dp.DealerId',$dealerid)
     ->join('products p', 'dp.ProductId=p.ProductId')
     ->join($city.'dealerproductspecification ds', 'dp.DealerPriceId=ds.DealerPriceId',"left")
       ->join('brands b', 'p.BrandId=b.BrandId')
       ->where('p.BrandType',1)
       ->where('p.MainCategoryId',$maincatid)
       ->where('p.SubCategoryId',$subcatid)
       ->groupBy('dp.DealerPriceId')
     ->get()->getResult();
}
public function dealercatnobrandedproduct($dealerid,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
    $db = db_connect();
    
return $db->table($city.'dealerprice dp')
       ->select('p.ProductId,p.BrandId,p.ProductName,b.BrandName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1,dp.DealerPriceId,ds.SpecificationValue')
       ->where('dp.DealerId',$dealerid)
     ->join('products p', 'dp.ProductId=p.ProductId')
     ->join($city.'dealerproductspecification ds', 'dp.DealerPriceId=ds.DealerPriceId',"left")
       ->join('brands b', 'p.BrandId=b.BrandId')
       ->where('p.BrandType',1)
->groupBy('dp.DealerPriceId')
       ->limit(10,0)->get()->getResult(); 
}
public function getproductdetail($dpid,$cityname){
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
    $db = db_connect();
    
return $db->table($city.'dealerprice dp')
       ->select('p.ProductId,p.BrandId,p.ProductName,b.BrandName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1,dp.*,ds.SpecificationValue')
        ->join('products p', 'dp.ProductId=p.ProductId')
     ->join($city.'dealerproductspecification ds', 'dp.DealerPriceId=ds.DealerPriceId',"left")
       ->join('brands b', 'p.BrandId=b.BrandId')
       ->where('dp.DealerPriceId',$dpid)
       ->groupBy('dp.DealerPriceId')
      ->get()->getRow();
}
public function addtodealerccount($data,$cityname,$dealerid,$email){
  $db = db_connect();
  $city = strtolower($cityname)== "mysore" ? "" : strtolower($cityname)."_";
  if($dealerid!=0){
    $this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($data);
    $id= $dealerid;

  }
 
  else{
$s=$db->table($city.'dealeraccounts da')->select('*')->where('EmailId', $email)->get()->getRow();
if($s){
  $this->db->table($city.'dealeraccounts')->where('DealerId', $dealerid)->update($data);
  $id=$s->DealerId;
}
else{
   $user = $db->table($city.'dealeraccounts');
    $user->insert($data);
    $id=$db->insertID();
 
}
   
 
  }

  return $db->table($city.'dealeraccounts da')->select('*')->where('DealerId', $id)->get()->getRow();
  
}
public function getdealer($term,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
 
         return $db->table($city.'dealeraccounts dp')
         ->select('ShopName,DealerId')
         ->like(strtolower('ShopName'), strtolower($term))
       ->get()->getResult();
 }
 public function getproducts($term){
 
  $db = db_connect();
 
         return $db->table('products')
         ->select('ProductId')
         
    ->like(strtolower("ProductName"),strtolower($term))
      ->orLike("ProductId",$term)
        
    
       
       ->get()->getResult();
 }
public function getdealerfilterproducts($dealerid,$city,$filter)
{
 $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
 $db = db_connect();
if($filter==1){

      return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->orderBy('MRP', 'ASC')
        ->get()->getResult();
}
 else if($filter==2){

        return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->orderBy('MRP', 'DESC')
        ->get()->getResult();
  }  
  else{
        return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->orderBy('DealerPriceId')
        ->get()->getResult();
  }
}
public function dealerproducts($dealerid,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_"; 

   $db = db_connect();
    return $db->table($city.'dealerprice dp')
    ->select('*')
    ->where('dp.QuantityAvailable > ',0)
    ->join('products p', 'p.ProductId=dp.ProductId')
    ->where('dp.ActiveStatus',1)
    ->where('p.BrandType',0)
    ->where('dp.DealerId ', $dealerid)
    ->groupBy('dp.ProductId')
    ->orderBy('dp.DealerPriceId', 'desc')
    ->get()->getResult();  
}
public function dealerproduct($dealerid,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_"; 

   $db = db_connect();
    return $db->table($city.'dealerprice dp')
    ->select('*,dp.DealerPriceId')
    ->where('dp.QuantityAvailable > ',0)
    ->join('products p', 'p.ProductId=dp.ProductId')
    ->join($city.'dealerproductspecification dps','dp.DealerPriceId = dps.DealerPriceId','left')
    ->join('brands b', 'p.BrandId=b.BrandId')
    ->where('dp.ActiveStatus',1)
    ->where('p.BrandType',0)
    ->where('dp.DealerId ', $dealerid)
    ->groupBy('dp.DealerPriceId')
    ->orderBy('dp.DealerPriceId', 'desc')
    ->limit(10,0)->get()->getResult(); 
}
public function getnondealerproducts($dealerid,$city){
 $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
 $db = db_connect();

        return $db->table($city.'dealerprice dp')
        ->select('*')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->join($city.'dealerproductspecification dps','dp.DealerPriceId = dps.DealerPriceId','left')
        ->join('brands b', 'p.BrandId=b.BrandId')
        ->where('b.BrandType',1)
        ->where('dp.ActiveStatus',1)
        ->orderBy('MRP', 'ASC')
      ->get()->getResult();
}
public function updatedealerproductstatus($dealerpriceid,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
  $a=array(
    "ActiveStatus"   =>0
    );
      $this->db->table($city.'dealerprice')->where('DealerPriceId', $dealerpriceid)->update($a);
}
public function updateproduct($a,$city,$dpid){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
  
      $this->db->table($city.'dealerprice')->where('DealerPriceId', $dpid)->update($a);
}
public function getnondealerproductsfilter($dealerid,$city,$filter){
 $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
 $db = db_connect();
if($filter==1){
 return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
        ->where('b.BrandType',1)
        ->orderBy('MRP', 'ASC')
        ->get()->getResult();

}
 else if($filter==2){
   return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
        ->where('b.BrandType',1)
        ->orderBy('MRP', 'DESC')
        ->get()->getResult();
 } 
 else{
 return $db->table($city.'dealerprice dp')
        ->select('p.ProductId,p.ProductName,p.DepartmentId,p.MainCategoryId,p.SubCategoryId,MRP,SellingPrice,ProductCode,StorePrice,Image1')
        ->where('dp.DealerId',$dealerid)
        ->join('products p', 'dp.ProductId=p.ProductId')
        ->join('brands b', 'p.BrandId=b.BrandId')
        ->where('b.BrandType',1)
        ->orderBy('DealerPriceId')
        ->get()->getResult();

 }     
}
public function addtogstin($a,$city,$stateid,$dealerid){

  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
   $db = db_connect();
   $query=$db->table($city.'dealers_gst_mapping dp')
        ->select('*')
        ->where('dp.DealerId',$dealerid)
        ->get()->getResult();

  if(count($query) > 0){
 $db = db_connect();

      $this->db->table($city.'dealers_gst_mapping')->where('DealerId', $dealerid)->update($a);

  }

else {
   $db      = \Config\Database::connect();
      $builder = $db->table($city.'dealers_gst_mapping'); 
      
    
    $builder->insert($a);
    return $db->insertID();


  }
}
public function getproductspecification($dpid,$mainid,$subid){
 $db = db_connect();
return $db->table('specifications s')
        ->select('s.SpecificationId,s.SpecificationName,Mandatory_Status')
        ->where('sm.DepartmentId',$dpid)
         ->where('sm.MainCategoryId',$mainid)
          ->where('sm.SubCategoryId',$subid)
        ->join('specificationsmapping sm', 's.SpecificationId=sm.SpecificationId')
        ->get()->getResult();

}
public function checkmandatory($dpid,$mainid,$subid,$specid){
  $db = db_connect();
  return $db->table('specifications s')
  ->select('s.SpecificationId,s.SpecificationName,Mandatory_Status')
  ->where('sm.DepartmentId',$dpid)
   ->where('sm.MainCategoryId',$mainid)
    ->where('sm.SubCategoryId',$subid)
    ->where('s.SpecificationId',$specid)
  ->join('specificationsmapping sm', 's.SpecificationId=sm.SpecificationId')
  ->get()->getRow();
}
public function getcatbrands($mainid,$subid){
 $db = db_connect(); 
 
        if($subid!=0){
        $dt= $db->table('brandcategorymapping bm')
        ->select('*')
        ->join('brands b', 'bm.BrandId=b.BrandId')
        
          ->join('subcategory s', 'bm.SubCategoryId=s.SubCategoryId')
        
       
        ->where('bm.MainCategoryId',$mainid)
        ->where('bm.SubCategoryId',$subid)
        ->orderBy('BrandName')
        ->get()->getResult();
      }
      else{
        $dt= $db->table('brandcategorymapping bm')
        ->select('*')
        ->join('brands b', 'bm.BrandId=b.BrandId')
     
        ->join('maincategory s', 'bm.MainCategoryId=s.MainCategoryId')
        ->where('bm.MainCategoryId',$mainid)
        ->where('bm.SubCategoryId',$subid)
        ->orderBy('BrandName')
        ->get()->getResult();
      }
      return $dt;
}
public function psordercount($id, $city){
  $db = db_connect();
  return $db->table($city.'ps_orderdetails pso')
  ->select("*")
  ->join($city .'pickatstore_new ps', 'ps.PickatStoreId=pso.PickatStoreId')
  ->join($city .'ps_orders pss', 'ps.PS_OrderId=pss.PS_OrderId')
  ->where('ps.DealerId', $id)
  ->get()->getResult();
}
public function hordercount($id, $city){
  $db = db_connect();
  return $db->table($city.'horderdetails pso')
  ->select("*")
  ->join($city .'hconorder ps', 'ps.H_conorderId=pso.H_conorderId')
  ->where('ps.DealerId', $id)
  ->get()->getResult();
}

public function pickuporders($id, $city)
{
  $db = db_connect();
  return $db->table($city.'ps_orders pso')
  ->select("count(PS_OrderDetailsId) as pscount,pso.*,ps.DealerId,ps.PickatStoreId")
  ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
  ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
  ->where('ps.DealerId', $id)
  ->groupBy('pso.PS_OrderId')
  ->orderBy('pso.PS_OrderId', 'Desc')
  ->get()->getResult();
}
public function pickupordersfilter($id, $city,$statusid)
{
  $db = db_connect();
  return $db->table($city.'ps_orders pso')
  ->select("count(PS_OrderDetailsId) as pscount,pso.*,ps.DealerId")
  ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
  ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
  ->where('ps.DealerId', $id)
  ->where('po.PS_OrderStatusId',$statusid)
  ->groupBy('pso.PS_OrderId')
  ->orderBy('pso.PS_OrderId', 'Desc')
  ->get()->getResult();
}

public function homeorders($id, $city,$type)
{
  $db = db_connect();
  if($type!=0){

  return $db->table($city.'hconorder pso')
  ->select("count(H_OrderDetailid) as pscount,po.*,p.*,pso.OrderDate")
  ->join($city .'horderdetails po', 'pso.H_conorderId=po.H_conorderId')
  ->join( 'products p', 'po.ProductId=p.ProductId')
  ->where('pso.DealerId', $id)
  ->where("po.OrderStatus",$type)
  ->groupBy('po.H_OrderDetailid')
  ->orderBy('po.H_OrderDetailid', 'Desc')
  ->get()->getResult();
   
  }
else{


  return $db->table($city.'hconorder pso')
  ->select("count(H_OrderDetailid) as pscount,po.*,p.*,pso.OrderDate")
  ->join($city .'horderdetails po', 'pso.H_conorderId=po.H_conorderId')
  ->join( 'products p', 'po.ProductId=p.ProductId')
  ->where('pso.DealerId', $id)
  ->groupBy('po.H_OrderDetailid')
  ->orderBy('po.H_OrderDetailid', 'Desc')
  ->get()->getResult();
}
}
public function completehorderdetail($id,$city){
  $city = strtolower($city)== "mysore" ? "" : strtolower($city)."_";
  $db = db_connect();
  $a=array(
    "OrderStatus" =>2
    );
      $this->db->table($city.'horderdetails')->where('H_OrderDetailid', $id)->update($a);
}
public function gethorders($id,$city){
  $db = db_connect();
  return $db->table($city.'hmainorder pso')
  ->select("*")
  ->join($city .'hconorder ps', 'ps.H_MainOrderId=pso.H_MainOrderId')
  ->join($city.'horderdetails pos', 'pos.H_conorderId=ps.H_conorderId')
  ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
  ->join( 'products p', 'pos.ProductId=p.ProductId')
  ->join( 'usershippingdetails us', 'pso.ShippingId=us.ShippingId')
  ->where('pos.H_OrderDetailid', $id)
  ->orderBy('pso.H_MainOrderId', 'Desc')
  ->get()->getRow();
}
public function pickupordersdetail($id, $city)
{
  $db = db_connect();
  return $db->table($city.'ps_orders pso')
  ->select("*,pos.PS_Status as status")
  ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
  ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
  ->join('ps_orderstatus pos', 'pos.PS_OrderStatusId=po.PS_OrderStatusId')
  ->join( 'products p', 'po.ProductId=p.ProductId')
  ->join($city . 'dealerprice da', 'p.ProductId=da.ProductId')
  ->where('po.PickatStoreId', $id)
  ->where('ps.PS_OrderStatusId',1)
  ->groupby('po.PS_OrderDetailsId')
  ->orderBy('pso.PS_OrderId', 'Desc')
  ->get()->getResult();
}
public function getordersdetail($id,$city){
  $db = db_connect();
  return $db->table($city.'pickatstore_new ps')
  ->select("*,da.MobileNumber as mobile,da.Adress as address,pr.MobileNumber as mob")
  ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
  ->join($city . 'ps_orders pso', 'pso.PS_OrderId=ps.PS_OrderId')
  ->join('ps_order_recepients pr', 'pso.OrderRecipientId=pr.OrderRecipientId','left')
  ->join('ps_orderstatus pos', 'pos.PS_OrderStatusId=po.PS_OrderStatusId')
  ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
  ->join( 'products p', 'po.ProductId=p.ProductId')
  ->where('po.PS_OrderDetailsId', $id)
  ->orderBy('po.PS_OrderDetailsId', 'Desc')
  ->get()->getRow();
}
public function updatepsorderstatus($status,$orderid,$city){

  $db = db_connect();
  $a=array(
    "PS_OrderStatusId"   =>$status
    );
      $this->db->table($city.'ps_orderdetails')->where('PS_OrderDetailsId', $orderid)->update($a);
}
public function getpscancelstatus(){
  $db = db_connect();
  return $db->table($city.'ps_orders pso')
  ->select("*,pos.PS_Status as status")
  ->join($city .'pickatstore_new ps', 'ps.PS_OrderId=pso.PS_OrderId')
  ->join($city . 'ps_orderdetails po', 'po.PickatStoreId=ps.PickatStoreId')
  ->join('ps_orderstatus pos', 'pos.PS_OrderStatusId=po.PS_OrderStatusId')
  ->join($city . 'dealeraccounts da', 'ps.DealerId=da.DealerId')
  ->join( 'products p', 'po.ProductId=p.ProductId')
  ->where('pso.PS_OrderId', $id)
  ->orderBy('pso.PS_OrderId', 'Desc')
  ->get()->getResult();

}
public function getpsorderstatus()
{
$db = db_connect();
$user = $db->table('ps_oc_reasons')->where('CancellationBy',1)->where('ActiveStatus',1)->get()->getResult();
if($user){
  return $user;
}
return false;
}
public function shopnewarravail($dealerid,$cityname){
  
  $db = db_connect();
  return $db->table("products p")
  ->select("*")
  ->join($cityname."dealerprice dp","p.ProductId=dp.ProductId")
  ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
 ->where("QuantityAvailable >",0)
  ->where("dp.ActiveStatus",1)
  ->where("dp.dealerId",$dealerid)
  ->where("p.Arrivalstatus",1)
  ->orderBy('dp.DealerPriceId', 'desc')

  ->limit(2,0)->get()->getResult();
}
public function shopnewarravails($dealerid,$cityname){
  
  $db = db_connect();
  return $db->table("products p")
  ->select("*")
  ->join($cityname."dealerprice dp","p.ProductId=dp.ProductId")
  ->where('UploadedDate BETWEEN DATE_SUB(NOW(), INTERVAL 270 DAY) AND NOW()')
 ->where("QuantityAvailable >",0)
  ->where("dp.ActiveStatus",1)
  ->where("dp.dealerId",$dealerid)
  ->where("p.Arrivalstatus",1)
  ->orderBy('dp.DealerPriceId', 'desc')

  ->limit(50,0)->get()->getResult();
}
public function storefrontsubproducts($id,$subid,$brandid,$city){
  $db = db_connect();
  $a="SELECT *
  FROM products p join brands b on p.BrandId=b.BrandId  WHERE p.BrandId=$brandid and p.SubcategoryId=$subid and
   ProductId not in ( SELECT `ProductId`
                     FROM ".$city."dealerprice b
                     WHERE p.`ProductId` = b.`ProductId`AND b.DealerId=$id
                   )";
        return $db->query($a)->getResult();

                   
 
}
public function storefrontfiltersubproducts($id,$subid,$brandid,$city,$array){
  $db = db_connect();
  $city = strtolower($city) == "mysore" ? "" : strtolower($city) . "_";
  $a="SELECT *
  FROM products p join brands b on p.BrandId=b.BrandId  WHERE p.BrandId=$brandid and p.SubcategoryId=$subid and p.ProductId=$array and
   ProductId not in ( SELECT `ProductId`
                     FROM ".$city."dealerprice b
                     WHERE p.`ProductId` = b.`ProductId`AND b.DealerId=$id
                   )";
        return $db->query($a)->getRow();

                   
 
}
public function storefrontfiltermainproducts($id,$subid,$brandid,$city,$array){
  $city = strtolower($city) == "mysore" ? "" : strtolower($city) . "_";
  $db = db_connect();
  $a="SELECT *
  FROM products p join brands b on p.BrandId=b.BrandId  WHERE p.BrandId=$brandid and p.MaincategoryId=$subid and p.ProductId=$array and
   ProductId not in ( SELECT `ProductId`
                     FROM ".$city."dealerprice b
                     WHERE p.`ProductId` = b.`ProductId`AND b.DealerId=$id
                   )";
        return $db->query($a)->getRow();

                   
 
}
public function storefrontmainproducts($id,$subid,$brandid,$city){
  $db = db_connect();
  
  $a="SELECT *
  FROM products p  WHERE p.BrandId=$brandid and p.MaincategoryId=$subid and
   ProductId not in ( SELECT `ProductId`
                     FROM ".$city."dealerprice b
                     WHERE p.`ProductId` = b.`ProductId`AND b.DealerId=$id
                   )";
        return $db->query($a)->getResult();

                   
 
}
public function shopofferproducts($dealerid,$cityname){
  $where = "dp.MRP > dp.SellingPrice";
  $db = db_connect();
  return $db->table("products p")
  ->select("*")
  ->join($cityname."dealerprice dp","p.ProductId=dp.ProductId")
  ->where("QuantityAvailable >",0)
  ->where("dp.DealerId",$dealerid)
  ->where($where)
  ->limit(2,0)->get()->getResult();
}
public function shopofferproduct($dealerid,$cityname){
  $where = "dp.MRP > dp.SellingPrice";
  $db = db_connect();
  return $db->table("products p")
  ->select("*")
  ->join($cityname."dealerprice dp","p.ProductId=dp.ProductId")
  ->where("QuantityAvailable >",0)
  ->where("dp.DealerId",$dealerid)
  ->where($where)
  ->limit(30,0)->get()->getResult();
}
public function getdepartbrandmappinglist($city,$id){
   $city = strtolower($city) == "mysore" ? "" : strtolower($city) . "_";
   $db = db_connect();
  return $db->table("brands p")
  ->select("*")
  ->join($city."brandcategorymapping dp","p.BrandId=dp.BrandId")

  ->where("dp.DepartmentId",$id)

  ->limit(30,0)->get()->getResult();

}
}