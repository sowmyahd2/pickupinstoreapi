<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
<<<<<<< HEAD
$routes->group('api/v1', function($routes) {
    $routes->get('departments', 'Department::index');
   
  
});
/*
=======
$routes->get('/v1/department', 'Department::index');
$routes->get('/v1/brands', 'Brands::index');
$routes->get('/v1/stores', 'Stores::index');
$routes->get('/v1/products/mostview/(:segment)', 'Products::mostView/$1');
$routes->get('/v1/stores/mostview/(:segment)/(:segment)', 'Stores::mostView/$1/$2');

$routes->get('/v1/city', 'City::index');
$routes->get('/v1/getcityname/(:segment)/(:segment)', 'City::getcityname/$1/$2');
$routes->get('/v1/stores/catstore/(:segment)/(:segment)', 'Stores::catstore/$1/$2');
$routes->get('/v1/products/department/(:segment)/(:segment)', 'Products::departmentProducts/$1/$2');
$routes->get('/v1/products/brandcategory/(:segment)/(:segment)', 'Products::brandCategoryProducts/$1/$2');
$routes->get('/v1/department/category/(:segment)', 'Department::category/$1');
$routes->get('/v1/department/categorylist/(:segment)', 'Department::categorylist/$1');
$routes->get('/v1/department/subcategorylist/(:segment)', 'Department::subcategorylist/$1');
$routes->get('/v1/department/browseby/(:segment)/(:segment)', 'Department::browseby/$1/$2');
$routes->get('/v1/maincategory/browseby/(:segment)/(:segment)', 'Department::mainCategoryBrowseBy/$1/$2');
$routes->get('/v1/subcategory/browseby/(:segment)/(:segment)', 'Department::subCategoryBrowseBy/$1/$2');
$routes->get('/v1/products/maincategory/(:segment)/(:segment)', 'Products::maincategory/$1/$2');
$routes->get('/v1/products/subcategory/(:segment)/(:segment)', 'Products::subCategory/$1/$2');
$routes->get('/v1/Category/subcategory/(:segment)/(:segment)', 'Category::subcategory/$1/$2');
$routes->get('/v1/subcategory/filterlist/(:segment)/(:segment)', 'Category::categoryFilter/$1/$2');
$routes->get('/v1/brands/offers/(:segment)/(:segment)', 'Brands::offers/$1/$2');
$routes->get('/v1/brands/newArrivalbrands/(:segment)/(:segment)', 'Brands::newArrivalbrands/$1/$2');
$routes->get('/v1/brands/newArrivalsBysubBrand/(:segment)/(:segment)/(:segment)', 'Brands::newArrivalsBysubBrand/$1/$2/$3');

$routes->get('/v1/brands/getcatbrand/(:segment)/(:segment)', 'Brands::getcatbrand/$1/$2');
$routes->get('/v1/brands/offers/products/(:segment)/(:segment)/(:segment)', 'Brands::offerProducts/$1/$2/$3');
$routes->get('/v1/brands/newarrivals/products/(:segment)/(:segment)/(:segment)', 'Brands::newArrivalsByBrand/$1/$2/$3');

$routes->get('/v1/brands/departmentBrowseby/(:segment)/(:segment)/(:segment)', 'Brands::departmentBrowseby/$1/$2/$3');
$routes->get('/v1/brands/categoryBrowseby/(:segment)/(:segment)/(:segment)', 'Brands::categoryBrowseby/$1/$2/$3');

$routes->get('/v1/products/(:segment)/(:segment)/(:segment)', 'Products::index/$1/$2/$3');
$routes->get('/v1/newarrival/(:segment)/(:segment)', 'NewArrival::index/$1/$2');
$routes->get('/v1/brands/maincategory/(:segment)/(:segment)/(:segment)', 'Products::brandMainCategory/$1/$2/$3');
$routes->get('/v1/brands/subcategory/(:segment)/(:segment)/(:segment)', 'Products::brandSubCategory/$1/$2/$3');

$routes->get('/v1/store/categoryproducts/(:segment)/(:segment)', 'Stores::storeProducts/$1/$2');
$routes->get('/v1/store/maincategoryproducts/(:segment)/(:segment)/(:segment)', 'Stores::storeCategoryProducts/$1/$2/$3');

$routes->post('/v1/search/autocomplete','Search::autocomplete');
$routes->get('/v1/user/profile/(:segment)','Useraccount::index/$1');
$routes->get('/v1/user/profile/(:segment)','Useraccount::index/$1');

$routes->get('/v1/user/location/(:segment)','Useraccount::getlocality/$1');
$routes->get('/v1/user/address/(:segment)','Useraccount::getuseraddress/$1');
$routes->get('/v1/user/addadress/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)/(:segment)','Useraccount::addbillingaddress/$1/$2/$3/$4/$5/$6');
$routes->get('/v1/user/changepass/(:segment)/(:segment)','Useraccount::changepass/$1/$2');
$routes->post('/v1/user/sendotp/','Useraccount::sendotp');
$routes->post('/v1/user/register/','Useraccount::register');
$routes->post('/v1/user/mobilelogin','Authenticate::index');
$routes->post('/v1/auth','Authenticate::index');
$routes->post('/v1/producfeedlogin','Productfeed::index');
$routes->post('/v1/cart','Cart::addtocart');
$routes->get('/v1/cart/(:segment)/(:segment)/(:segment)','Cart::cartview/$1/$2/$3');
$routes->get('/v1/pickupinshoporders/(:segment)/(:segment)','Useraccount::pickupinshoporders/$1/$2');         
$routes->get('/v1/cart/removeproduct/(:segment)/(:segment)/(:segment)/(:segment)','Cart::removeproduct/$1/$2/$3/$4');
$routes->get('/v1/checkout/(:segment)/(:segment)','Checkout::index/$1/$2');
$routes->get('/v1/homeordercheckout/(:segment)/(:segment)/(:segment)','Checkout::homeordercheckout/$1/$2/$3');
$routes->post('/v1/checkout/addrecipient','Checkout::addrecipient');
$routes->post('/v1/pickordercheckout/(:segment)/(:segment)/(:segment)/(:segment)','Checkout::pickordercheckout/$1/$2/$3/$4');

$routes->get('/v1/pickcartcount/(:segment)/(:segment)','Checkout::pickcartcount/$1/$2');
$routes->get('/v1/homecartcount/(:segment)/(:segment)','Checkout::homecartcount/$1/$2');
$routes->get('/v1/inactiveaddress/(:segment)','Useraccount::inactiveaddress/$1');
$routes->get('/v1/pickupordertail/(:segment)/(:segment)','Useraccount::pickorderdetail/$1/$2');
$routes->get('/v1/homedeliveryorders/(:segment)/(:segment)','Useraccount::homedeliveryorders/$1/$2');
$routes->get('/v1/homedeliveryorderdetail/(:segment)/(:segment)','Useraccount::homedeliveryordersdetail/$1/$2');
$routes->get('/v1/cancelpickorder/(:segment)/(:segment)/(:segment)/(:segment)','Useraccount::cancelpickorder/$1/$2/$3/$4');
$routes->get('/v1/addtowishlist/(:segment)/(:segment)/(:segment)','Useraccount::addtowishlist/$1/$2/$3');
$routes->get('/v1/wishlist/(:segment)/(:segment)','Useraccount::wishlist/$1/$2');
$routes->get('/v1/removewishlist/(:segment)/(:segment)/(:segment)','Useraccount::deletewhishlist/$1/$2/$3');
$routes->get('/v1/globaldata','Globaldata::index');
/**
>>>>>>> sowmya-dev
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
