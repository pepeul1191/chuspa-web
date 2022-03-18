<?php

# website
$f3->route('GET  /', '\App\Controllers\HomeController->index');
$f3->route('GET  /nosotros', '\App\Controllers\HomeController->index');
$f3->route('GET  /servicios', '\App\Controllers\HomeController->index');
$f3->route('GET  /proyectos', '\App\Controllers\HomeController->index');
$f3->route('GET  /contacto', '\App\Controllers\HomeController->index');
$f3->route('POST /mail', '\App\Controllers\MailController->mail');
# api
$f3->route('GET  /api/service/list', '\App\Controllers\Admin\ApiController->serviceList');
$f3->route('GET  /api/product_type/list', '\App\Controllers\Admin\ApiController->typesWithProducts');
$f3->route('GET  /api/service/product', '\App\Controllers\Admin\ApiController->products');
#### login
$f3->route('GET  /login', '\App\Controllers\LoginController->index');
$f3->route('GET  /login/sign-in', '\App\Controllers\LoginController->index');
$f3->route('GET  /login/reset-password', '\App\Controllers\LoginController->index');
$f3->route('POST /login', '\App\Controllers\LoginController->access');
$f3->route('GET  /log-out', '\App\Controllers\LoginController->logout');
### admin
$f3->route('GET  /user/info', '\App\Controllers\AdminController->info');
$f3->route('GET  /admin', '\App\Controllers\AdminController->index');
$f3->route('GET  /admin/product-type', '\App\Controllers\AdminController->index');
$f3->route('GET  /admin/service', '\App\Controllers\AdminController->index');
$f3->route('GET  /admin/product', '\App\Controllers\AdminController->index');
$f3->route('GET  /admin/product/add', '\App\Controllers\AdminController->index');
$f3->route('GET  /admin/product/edit/@num', '\App\Controllers\AdminController->index');
### admin - product-type
$f3->route('GET  /admin/product-type/list', '\App\Controllers\Admin\ProductTypeController->list');
$f3->route('POST /admin/product-type/save', '\App\Controllers\Admin\ProductTypeController->save');
### admin - service
$f3->route('GET  /admin/service/list', '\App\Controllers\Admin\ServiceController->list');
$f3->route('POST /admin/service/save', '\App\Controllers\Admin\ServiceController->save');
### admin - product
$f3->route('GET  /admin/product/list', '\App\Controllers\Admin\ProductController->list');
$f3->route('GET  /admin/product/product-type', '\App\Controllers\Admin\ProductController->productType');
$f3->route('POST /admin/product/detail/save', '\App\Controllers\Admin\ProductController->save');
$f3->route('POST /admin/product/delete', '\App\Controllers\Admin\ProductController->delete');
$f3->route('POST /admin/product/type/save', '\App\Controllers\Admin\ProductController->productTypeSave');
$f3->route('GET  /admin/product/get', '\App\Controllers\Admin\ProductController->get');
### admin - product_image
$f3->route('GET  /admin/product/image/list', '\App\Controllers\Admin\ProductImageController->list');
$f3->route('POST /admin/product/image/save', '\App\Controllers\Admin\ProductImageController->save');
#### rest - file
$f3->route('POST /upload', '\App\Controllers\FileController->upload');
# error handler
$f3->route('GET  /error/access/@code', '\App\Controllers\ErrorController->access');
$f3->set('ONERROR', include_once 'on_error.php');
