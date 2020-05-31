<?php

class eclMod_modCart
{ // class eclMod_modCart

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
$render = $document->render;
$cart = 'cart_' . $document->domain->name;

// Condições
if (!isset ($document->session[$cart]))
return;

if ($me->isDomain)
return;

if (!$me->id)
return;

if ($document->printableLayout)
return;

if (isset ($document->domain->data['flags']['modCart_timer']) and $timer = $document->domain->data['flags']['modCart_timer'] and isset ($me->data['marker']) and $me->data['marker'] == 12)
return;

$me = $document->application;

// Configurações
$mod->data = $render->block ('modules/cart');

// Itens da lista
$local = [];

$quantity = 0;
$value = 0;
foreach ($document->session[$cart]['order'] as $product)
{ // each product
$quantity += $product['quantity'];
$value += $product['value'] * $product['quantity'];
} // each product

if ($quantity == 1)
$captionItems = $store->control->read ('labels/field/cart_item');
else
$captionItems = $store->control->read ('labels/field/cart_items');
$local['caption'] = $document->textMerge ($quantity, ' ', $captionItems['text']['caption'], ' (R$' . $value . ')');

if ($me->name != '-cart' and (!isset ($me->data['mode']) or $me->data['mode'] != MODE_SECTION or !isset ($me->data['marker']) or $me->data['marker'] != 12))
{ // go to cart
if ($id = $store->domainContent->findMarker ($document->domain->domainId, 12))
$local['url'] = $document->url ($store->domainContent->pathway ($document->domain->domainId, $id));
else
$local['url'] = $document->url (array ($document->domain->name, '-cart'));
} // go to cart

if (isset ($timer) and $timer)
{ // timer on
if (isset ($document->session[$cart]['start_time']))
{ // set timer
$local['timer_on'] = 1;
$local['timer'] = $timer - (TIME - $document->session[$cart]['start_time']);
$local['url_timeout'] = $document->url (true, true, '_preload-cart_cart-timeout');
if ($local['timer'] < 10)
$local['timeout'] = 1;
} // set timer
} // timer on

$mod->appendChild ($local);


// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'cart');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modCart_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modCart


?>