<?php

$columns = [
  'id',
  'product_name',
  'product_url',
  'price',
  'category'
];

/* $products = [
  [1, 'product 1', 'https://example.com/product-1', '9.99', 'category 1'],
  [2, 'product 2', 'https://example.com/product-2', '19.99', 'category 2'],
  [3, 'product 3', 'https://example.com/product-3', '29.99', 'category 3'],
  [4, 'product 4', 'https://example.com/product-4', '39.99', 'category 4'],
]; */

$products = [];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="products.csv"');


for ($x = 0; $x <= 1000_000; $x++) {
//for ($x = 0; $x <= 10; $x++) {
  $products[] = [
    $x, "product $x", "https://example.com/product- $x", 10.00 + $x, "category $x",
  ];
}

echo implode(',', $columns) . PHP_EOL;
foreach ($products as $product) {
  echo implode(',', $product) . PHP_EOL;
}