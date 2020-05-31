<?php

class eclApp_sectionCart
{ // class eclApp_sectionCart

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('sectionCart_order');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch

if (isset ($document->session['cart_' . $document->domain->name]['order']))
return self::action_view_order ($document);

if (!$document->access (1))
return self::action_please_connect ($document);

return self::action_list_client_orders ($document);
} // function dispatch

static function action_view_order ($document)
{ // function action_view_order

$formulary = $document->createFormulary ('sectionCart_payFlow', $document->session['cart_' . $document->domain->name]['order'], 'pay');

if ($formulary->save())
$document->session['cart_' . $document->domain->name]['order'] = $formulary->data;

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function action_view_order

static function action_please_connect ($document)
{ // function action_please_connect
$document->mod->panel->main = array ('content', 'login', 'user_subscribe');
$document->dataMerge ('sectionCart_contentPleaseConnect');
} // function action_please_connect

static function action_list_client_orders ($document)
{ // function action_list_client_orders
$document->dataMerge ('sectionCart_contentNoOrders');
} // function action_list_client_orders

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionCart

?>