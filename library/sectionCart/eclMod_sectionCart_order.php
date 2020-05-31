<?php

class eclMod_sectionCart_order
{ // class eclMod_sectionCart_order

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;
$mod->data['local']['list'] = 'table_simple';

$row = $mod->appendChild ();

$row->appendChild ('sectionCart_listCode');
$row->appendChild ('sectionCart_listDescription');
$row->appendChild ('sectionCart_listPrice');
$row->appendChild ('sectionCart_listQuantity');
$row->appendChild ('sectionCart_listValue');
$row->appendChild ('sectionCart_listAction');

$order = $document->session['cart_' . $document->domain->name]['order'];
$remove = 0;
if (isset ($document->actions['product'][2]) and $document->actions['product'][1] == 'remove' and intval ($document->actions['product'][2]))
$remove = $document->actions['product'][2];
$total = 0;

foreach ($order as $key => $product)
{ // each product
if ($remove and $product['id'] == $remove)
{ // remove product
unset ($document->session['order'][$key]);
continue;
} // remove product

$data = $store->domainContent->openById ($product['domain_id'], $product['id']);
if (!isset ($data['local']['product_price']))
{ // remove product
unset ($document->session['order'][$key]);
continue;
} // remove product

$price = $data['local']['product_price'];
$total += $price * $product['quantity'];

$row = $mod->appendChild ();
if (isset ($data['local']['product_code']))
$row->appendChild (array (
'caption' => $document->textMerge ($data['local']['code'])
));
else
$row->appendChild (array (
'caption' => $document->textMerge ('-')
));

$row->appendChild (array (
'caption' => $data['text']['title']
));

$row->appendChild (array (
'caption' => $document->textMerge ($price)
));

$row->appendChild (array (
'caption' => $document->textMerge ($product['quantity'])
));

$row->appendChild (array (
'caption' => $document->textMerge ($product['quantity'] * $price)
));

$row->appendChild ('labels/action/remove')
->url (true, true, '_product-remove-' . $product['id']);
} // each product

// total
$row = $mod->appendChild ();
$row->appendChild ('sectionCart_listTotal', array (
'rowspan' => 4
));

$row->appendChild (array (
'caption' => $document->textMerge ($total)
));
$mod->enabled = true;
} // function setModule

} // class eclMod_sectionCart_order

?>