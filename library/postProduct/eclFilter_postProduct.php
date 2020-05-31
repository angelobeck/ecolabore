<?php

class eclFilter_postProduct
{ // class eclFilter_postProduct

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application;

$formulary->appendChild ('postProduct_editProductPrice', array (
'name' => $fieldName . '_price', 
'value' => $formulary->getField ('local/product_price')
));

$formulary->appendChild ('postProduct_editProductAvailable', array (
'name' => $fieldName . '_available', 
'value' => $formulary->getField ('local/product_available')
));

$formulary->appendChild ('postProduct_editProductOrder', array (
'name' => $fieldName . '_order_enabled', 
'value' => $formulary->getField ('local/product_order')
));

if (!$me->id)
return;

$order = 0;
$stock = 0;
$reserved = 0;
$sould = 0;
if (isset ($formulary->data['id']))
{ // search products in stock
$children = $store->domainExtras->children ($me->domainId, MODE_PRODUCT, $me->id);
foreach ($children as $data)
{ // each product in stock
if ($data['status'] < 110)
$order ++;
elseif ($data['status'] == 110 or $data['status'] == 111)
$stock ++;
elseif ($data['status'] == 113)
$reserved ++;
else
$sould ++;
} // each product in stock
} // search products in stock

$formulary->appendChild ('postProduct_editOrder', array (
'name' => $fieldName . '_order', 
'value' => $order
));

$formulary->appendChild ('postProduct_editStock', array (
'name' => $fieldName . '_stock', 
'value' => $stock
));

$formulary->appendChild ('postProduct_editReserved', array (
'name' => $fieldName . '_reserved', 
'content' => $document->textMerge ($reserved)
));

$formulary->appendChild ('postProduct_editSould', array (
'name' => $fieldName . '_sould', 
'content' => $document->textMerge ($sould)
));

} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$received = $formulary->received;
$document = $formulary->document;
$me = $document->application;

if (isset ($received[$fieldName . '_price']) and preg_match ('/([0-9]+)([,]([0-9]{1,2}))?/', $received[$fieldName . '_price'], $match))
{ // save value
if (!isset ($match[3]))
$match[3] = '00';
else
$match[3] = str_pad ($match[3], 2, '0');

$formulary->setField ('local/product_price', $match[1] . ',' . $match[3]);
$formulary->setField ('value', intval ($match[1] . $match[3]));
} // save value
else
{ // clear value
$formulary->setField ('local/product_price', false);
$formulary->setField ('value', 0);
} // clear value

if (isset ($received[$fieldName . '_available']) and $received[$fieldName . '_available'])
$formulary->setField ('local/product_available', 1);
else
$formulary->setField ('local/product_available', false);

if (isset ($received[$fieldName . '_order_enabled']) and $received[$fieldName . '_order_enabled'])
$formulary->setField ('local/product_order', 1);
else
$formulary->setField ('local/product_order', false);


if (!$me->id)
return;

$order = 0;
$stock = 0;
if (isset ($formulary->data['id']))
{ // search products in stock
$children = $store->domainExtras->children ($me->domainId, MODE_PRODUCT, $me->id);
foreach ($children as $data)
{ // each product in stock
if ($data['status'] < 110)
$order ++;
elseif ($data['status'] == 110 or $data['status'] == 111)
$stock ++;
} // each product in stock
} // search products in stock

if (isset ($received[$fieldName . '_order'][0]))
{ // order
$receivedOrder = intval ($received[$fieldName . '_order']);
if ($receivedOrder > $order)
{ // order more products
for ($i = $order; $i <= $receivedOrder; $i++ )
{ // each new order
$data = array (
'mode' => MODE_PRODUCT,
'parent_id' => $me->id,
'status' => 100
);
$store->domainExtras->insert ($me->domainId, $data);
} // each new order
} // order more products
} // received order


if (isset ($received[$fieldName . '_stock'][0]))
{ // stock
$receivedStock = intval ($received[$fieldName . '_stock']);
if ($receivedStock > $stock)
{ // stock more products
for ($i = $stock; $i <= $receivedStock; $i++ )
{ // each new stock
$data = array (
'mode' => MODE_PRODUCT,
'parent_id' => $me->id,
'status' => 110
);
$store->domainExtras->insert ($me->domainId, $data);
} // each new stock
} // stock more products
} // received stock

} // function save

} // class eclFilter_postProduct

?>