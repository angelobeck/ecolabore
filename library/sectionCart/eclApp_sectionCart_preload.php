<?php

class eclApp_sectionCart_preload
{ // class eclApp_sectionCart_preload

static function is_child ($me, $name)
{ // function is_child
if ($name == 'cart')
return true;
return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

if ($document->actions ('cart', 'timeout'))
{ // cart timeout
unset ($document->session['cart_' . $document->domain->name]);
if (isset ($document->domain->data['flags']['modCart_type']) and $document->domain->data['flags']['modCart_type'] == 'bag')
$bag = 1;
else
$bag = '';

$document->mod->humperstilshen->dialog ('sectionCart_alertTimeout', array ('alert' => 1, 'bag' => $bag));
return;
} // cart timeout

$received = $document->received;
if (!isset ($received['id']) or !intval ($received['id']))
return;

$data = $store->domainContent->openById ($document->domain->domainId, intval ($received['id']));
if (!$data or !$data['value'] or !isset ($data['local']['product_price']))
return;

if (isset ($received['quantity']) and intval ($received['quantity']))
$quantity = $received['quantity'];
else
$quantity = 1;

preg_match ('/([0-9]+)[,][0]?([0-9]+)/', $data['local']['product_price'], $match);
$value = floatval ($match[1]) + (floatval ($match[2]) / 100);

$cart = 'cart_' . $document->domain->name;
if (!isset ($document->session[$cart]))
$document->session[$cart] = [];
if (!isset ($document->session[$cart]['start_time']))
$document->session[$cart]['start_time'] = TIME;
if (!isset ($document->session[$cart]['order']))
$document->session[$cart]['order'] = [];

$document->session[$cart]['order'][] = array (
'domain_id' => $data['domain_id'], 
'id' => $data['id'], 
'caption' => $data['text']['caption'], 
'value' => $value, 
'quantity' => $quantity
);
} // function dispatch

} // class eclApp_sectionCart_preload

?>