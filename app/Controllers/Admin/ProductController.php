<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Filters\SessionTrueApiFilter;
use App\Libraries\RandomLib;

class ProductController extends BaseController
{
  function __construct()
  {
    parent::__construct();
    parent::loadHelper('orm');
  }

  function beforeroute($f3) 
  {
    parent::beforeroute($f3);
    SessionTrueApiFilter::before($f3);
    //CsrfApiFilter::before($f3);
  }

  function list($f3)
  {
    // data
    $resp = [];
    $status = 200;
    // logic
    try {
      $stmt = \Model::factory('App\\Models\\Product', 'app')
        ->select('id')
        ->select('color')
        ->select('name');
      // filter user
      if(
        $f3->get('GET.name') != null
      ){
        $stmt = $stmt->where_like('name', '%' . $f3->get('GET.name') . '%');
      }
      // pages with final statement
      $pages = ceil(
        $stmt->count()
        / $f3->get('GET.step')
      );
      // pagination
      if(
        $f3->get('GET.step') != null && 
        $f3->get('GET.page') != null
      ){
        $offset = ($f3->get('GET.page') - 1) * $f3->get('GET.step');
        $stmt = $stmt->offset($offset)->limit($f3->get('GET.step'));
      }
      $rs = $stmt->find_array();
      $resp = json_encode(array(
        'list' => $rs,
        'pages' => $pages,
      ));
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }

  function save($f3)
  {
    // data
    $resp = [];
    $status = 200;
    $payload = json_decode(file_get_contents('php://input'), true);
    $createdId = [];
    // logic
    \ORM::get_db('app')->beginTransaction();
    try {
      if($payload['id'] == 'E'){
        $n = \Model::factory('App\\Models\\Product', 'app')->create();
        $n->name = $payload['name'];
        $n->description = $payload['description'];
        $n->url = $payload['url'];
        $n->color = $payload['color'];
        $n->save();
        // response data
        $resp = $n->id;
      }else{
        $e = \Model::factory('App\\Models\\Product', 'app')->find_one($payload['id']);
        $e->name = $payload['name'];
        $e->description = $payload['description'];
        $e->url = $payload['url'];
        $e->color = $payload['color'];
        $e->save();
        $resp = '';
      }
      // commit
      \ORM::get_db('app')->commit();
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }

  function productType($f3)
  {
    // data
    $resp = [];
    $status = 200;
    $product_id = $f3->get('GET.product_id');
    // logic
    try {
      $pdo = \ORM::get_db('app');
      $query = '
        SELECT T.id AS id, T.name AS name, (CASE WHEN (P.exist = 1) THEN 1 ELSE 0 END) AS exist FROM
        (
          SELECT id, name, 0 AS exist FROM product_types
        ) T 
        LEFT JOIN 
        (
          SELECT PT.id, PT.name, 1 AS exist FROM 
          product_types PT INNER JOIN product_types_products PTP ON
          PT.id = PTP.product_type_id
          WHERE PTP.product_id = %d
        ) P 
        ON P.id = T.id
      ';
      $rs = array();
      foreach($pdo->query(sprintf($query, $product_id)) as $row) {
        array_push($rs, array(
          'id' => $row['id'],
          'name' => $row['name'],
          'exist' => $row['exist'],
        ));
      }
      if($rs == false){
        $resp = 'Parcipante no tiene tipos de producto asociado';
        $status = 404;
      }else{
        $resp = json_encode($rs);
      }
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }

  function productTypeSave($f3)
  {
    // data
    $resp = '';
    $status = 200;
    $payload = json_decode(file_get_contents('php://input'));
    $data = $payload->{'data'};
    $product_id = $payload->{'id'};
    // logic
    \ORM::get_db('app')->beginTransaction();
    try {
      if(count($data) > 0){
				foreach ($data as &$record) {
          $product_type_id = $record->{'id'};
          $exist = $record->{'exist'};
          $e = \Model::factory('App\\Models\\ProductTypeProduct', 'app')
            ->where('product_type_id', $product_type_id)
            ->where('product_id', $product_id)
            ->find_one();
          if($exist == 0){
            if($e != false){
              $e->delete();
            }
          }else{
            if($e == false){
              $n = \Model::factory('App\\Models\\ProductTypeProduct', 'app')->create();
              $n->product_type_id = $product_type_id;
              $n->product_id = $product_id;
              $n->save();
            }
          }
        }
      }
      // commit
      \ORM::get_db('app')->commit();
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }

  function get($f3)
  {
    // data
    $resp = [];
    $status = 200;
    $id = $f3->get('GET.id');
    // logic
    try {
      $r = \Model::factory('App\\Models\\Product', 'app')->find_one($id);
      $resp = json_encode(array(
        'id' => $r->{'id'},
        'name' => $r->{'name'},
        'description' => $r->{'description'},
        'url' => $r->{'url'},
        'color' => $r->{'color'},
      ));
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }
  
  function delete($f3)
  {
    // data
    $resp = [];
    $status = 200;
    $payload = json_decode(file_get_contents('php://input'), true);
    $createdIds = [];
    $deletes = $payload['deletes'];
    // logic
    \ORM::get_db('app')->beginTransaction();
    try {
      // deletes
      if(count($deletes) > 0){
				foreach ($deletes as &$delete) {
          // delete ProductTypeProduct
          $typesProduct = \Model::factory('App\\Models\\ProductTypeProduct', 'app')
            ->where('product_id', $delete['id'])
            ->find_many();
          foreach ($typesProduct as &$tp) {
            $tp->delete();
          }
          // delete Product
			    $d = \Model::factory('App\\Models\\Product', 'app')->find_one($delete['id']);
			    $d->delete();
				}
      }
      // commit
      \ORM::get_db('app')->commit();
      // response data
      $resp = json_encode($createdIds);
    }catch (\Exception $e) {
      $status = 500;
      $resp = json_encode(['ups', $e->getMessage()]);
    }
    // resp
    http_response_code($status);
    echo $resp;
  }
}