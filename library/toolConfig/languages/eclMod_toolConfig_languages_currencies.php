<?php

class eclMod_toolConfig_languages_currencies
{ // class eclMod_toolConfig_languages_currencies

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
$names = $store->control->scandir ('t', 'labels/currency');
foreach ($names as $currency)
{ // each currency
$data = $store->control->read ('labels/currency/' . $currency);
$mod->appendChild ($data, array ('name' => $currency));
} // each currency

$mod->enabled = true;
} // function setModule

} // class eclMod_toolConfig_languages_currencies

?>