<?php

class eclMod_modCart_scope
{ // class eclMod_modCart_scope

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;

if (!$render->getVar ('value'))
return;

if (!$store->domainContent->findMarker ($document->domain->domainId, 12))
return;

$mod->data = $store->control->read ('modCart_scope_module');

$row = $mod->appendChild ($render->data);
$row->data['url'] = $document->url (true, true, '_preload-cart');

$mod->enabled = true;
} // function setModule

} // class eclMod_modCart_scope

?>