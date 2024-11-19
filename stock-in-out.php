<?php

require_once('../tools/functions.php');
require_once('../classes/stocks.class.php');

$errors = [];

$stocks = new Stocks();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  foreach ($_POST as $key => $value) {
    $_POST[$key] = clean_input($value);
  }
  foreach ($_GET as $key => $value) {
    $_GET[$key] = clean_input($value);
  }

  extract($_POST);
  extract($_GET);

  if (empty($quantity)) {
    $errors["quantity"] = "Quantity is required";
  } else if (!is_numeric($quantity)) {
    $errors["quantity"] = "Quantity should be a number";
  } else if ($quantity < 1) {
    $errors["quantity"] = "Quantity must be greater than 0";
  } else if ($status == "out" && $quantity > $stocks->getAvailableStocks($product_id)) {
    $remaining = $stocks->getAvailableStocks($product_id) ?? 0;
    $errors["quantity"] = "Quantity must be less than the Available Stocks: $remaining";
  }

  if (empty($status)) {
    $errors["status"] = "Status is required";
  }

  if (empty($reason) && $status == "out") {
    $errors["reason"] = "Reason is required";
  }

  if (count(array_keys($errors))) {
    echo json_encode([
      'status' => 'error',
      'errors' => $errors
    ]);
    exit;
  }

  $stocks->product_id = $product_id;
  $stocks->quantity = $quantity;
  $stocks->status = $status;
  $stocks->reason = $reason;

  if ($stocks->add()) {
    echo json_encode([
      'status' => 'success'
    ]);
  }
}

?>