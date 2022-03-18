<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Filters\ApiFilter;

class ApiController extends BaseController
{
  function __construct()
  {
    parent::__construct();
    parent::loadHelper('orm');
  }

  function beforeroute($f3) 
  {
    parent::beforeroute($f3);
    ApiFilter::before($f3);
  }
  
  function typesWithProducts($f3)
  {
    // data
    $resp = [];
    $status = 200;
    // logic
    try {
      $rs = \Model::factory('App\\Models\\VWProductTypeProduct', 'app')
        ->select('product_type_id', 'id')
        ->select('product_type_name', 'name')
        ->group_by('product_type_id')
        ->order_by_asc('id')
        ->find_array();
      $resp = json_encode($rs);
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }

  private function addProductTypes($record)
  {
    try {
      $rs = \Model::factory('App\\Models\\VWProductTypeProduct', 'app')
        ->select('product_type_id', 'id')
        ->select('product_type_name', 'name')
        ->where('product_id', $record['id'])
        ->find_array();
      $record['product_types'] = $rs;
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    return $record;
  }

  private function addProductImages($record)
  {
    try {
      $rs = \Model::factory('App\\Models\\ProductImage', 'app')
        ->select('description')
        ->select('url')
        ->where('product_id', $record['id'])
        ->find_array();
      $record['images'] = $rs;
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    return $record;
  }

  function products($f3)
  {
    // data
    $resp = [];
    $status = 200;
    // logic
    try {
      $rs = [];
      $rs = \Model::factory('App\\Models\\Product', 'app')
        ->find_array();
      $rs = array_map(array($this, 'addProductTypes'), $rs);
      $rs = array_map(array($this, 'addProductImages'), $rs);
      $resp = json_encode($rs);
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }
}