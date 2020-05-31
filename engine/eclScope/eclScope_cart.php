<?php

class eclScope_cart
{ // class eclScope_cart

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;

if (!$render->getVar ('value'))
return;

if (!$store->domainContent->findMarker ($document->domain->domainId, 12))
return;

$data['local'] = $render->data;
$data['local']['url'] = $document->url (true, true, '_preload-cart');

if (isset ($document->domain->data['flags']['modCart_type']))
{ // different cart mode
if ($document->domain->data['flags']['modCart_type'] == 'bag')
$data['local']['bag'] = 1;
elseif ($document->domain->data['flags']['modCart_type'] == 'order')
$data['local']['order'] = 1;
} // different cart mode
return $data;
} // function get

} // class eclScope_cart

?>