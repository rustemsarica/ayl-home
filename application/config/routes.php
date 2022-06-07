<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    | -------------------------------------------------------------------------
    | URI ROUTING
    | -------------------------------------------------------------------------
    | This file lets you re-map URI requests to specific controller functions.
    |
    | Typically there is a one-to-one relationship between a URL string
    | and its corresponding controller class/method. The segments in a
    | URL normally follow this pattern:
    |
    |	example.com/class/method/id/
    |
    | In some instances, however, you may want to remap this relationship
    | so that a different class/function is called than the one
    | corresponding to the URL.
    |
    | Please see the user guide for complete details:
    |
    |	https://codeigniter.com/user_guide/general/routing.html
    |
    | -------------------------------------------------------------------------
    | RESERVED ROUTES
    | -------------------------------------------------------------------------
    |
    | There are three reserved routes:
    |
    |	$route['default_controller'] = 'welcome';
    |
    | This route indicates which controller class should be loaded if the
    | URI contains no data. In the above example, the "welcome" class
    | would be loaded.
    |
    |	$route['404_override'] = 'errors/page_missing';
    |
    | This route will tell the Router which controller/method to use if those
    | provided in the URL cannot be matched to a valid route.
    |
    |	$route['translate_uri_dashes'] = FALSE;
    |
    | This is not exactly a route, but allows you to automatically route
    | controller and method names that contain dashes. '-' isn't a valid
    | class or method name character, so it requires translation.
    | When you set this option to TRUE, it will replace ALL dashes in the
    | controller and method URI segments.
    |
    | Examples:	my-controller/index	-> my_controller/index
    |		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home_controller';
$route['404_override'] = 'home_controller/error_404';
$route['translate_uri_dashes'] = FALSE;
$route['error-404'] = 'home_controller/error_404';

$route['login']['GET'] = 'auth_controller/login';
$route['login-post']['POST'] = 'auth_controller/login_post';
$route['register']['GET'] = 'auth_controller/register';
$route['register-post']['POST'] = 'auth_controller/register_post';
$route['logout']['GET'] = 'auth_controller/logout';

$route['orders']['GET'] = 'home_controller/orders';
$route['orders/completed']['GET'] = 'home_controller/completed_orders';
$route['orders/shipped']['GET'] = 'home_controller/shipped_orders';
$route['order-shipping-key-post']['POST'] = 'admin_controller/order_shipping_key_post';
$route['delete-order']['POST'] = 'order_controller/delete_order';

$route['products']['GET'] = 'product_controller/products';
$route['products/add-product']['GET'] = 'product_controller/add_product';
$route['add-product-post']['POST'] = 'product_controller/add_product_post';
$route['products/edit-product/(:num)']['GET'] = 'product_controller/edit_product/$1';
$route['edit-product-post']['POST'] = 'product_controller/edit_product_post';
$route['delete-product-post']['POST'] = 'product_controller/delete_product_post';

$route['earnings']['GET'] = 'home_controller/earnings';

$route['payouts']['GET'] = 'home_controller/payouts';
$route['payouts-request-post']['POST'] = 'home_controller/payouts_request_post';
$route['payout-approve']['POST'] = 'home_controller/payout_approve';
$route['payout-cancel']['POST'] = 'home_controller/payout_cancel';

$route['users']['GET'] = 'admin_controller/users';
$route['delete-user']['POST'] = 'admin_controller/delete_user';
$route['users/approve']['GET'] = 'admin_controller/approve_users';
$route['approve-user-post']['POST'] = 'admin_controller/approve_user_post';

$route['settings']['GET'] = 'admin_controller/settings';
$route['settings-post']['POST'] = 'admin_controller/settings_post';

$route['cart']['GET'] = 'cart_controller/cart';
$route['add-to-cart']['POST'] = 'cart_controller/add_to_cart';
$route['remove-from-cart']['POST'] = 'cart_controller/remove_from_cart';
$route['update-cart-product-quantity']['POST'] = 'cart_controller/update_cart_product_quantity';

$route['create-order-post']['POST'] = 'order_controller/create_order_post';
$route['order-approve-post']['POST'] = 'order_controller/order_approve_post';
$route['date-of-orders']['POST'] = 'order_controller/date_of_orders';

$route['get-towns']['POST'] = 'home_controller/get_towns';

$route['profile']['GET'] = 'home_controller/profile';
$route['profile-post']['POST'] = 'home_controller/profile_post';
$route['change-password']['GET'] = 'home_controller/change_password';
$route['change-password-post']['POST'] = 'auth_controller/change_password_post';