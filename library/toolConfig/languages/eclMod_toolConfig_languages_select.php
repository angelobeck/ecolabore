<?php

class eclMod_toolConfig_languages_select
{ // class eclMod_toolConfig_languages_select

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

$names = $store->control->scandir ('t', 'labels/lang');
foreach ($names as $lang)
{ // each lang
$data = $store->control->read ('labels/lang/' . $lang);
$mod->appendChild ($data, array ('name' => $lang));
} // each lang

$mod->enabled = true;
} // function setModule

} // class eclMod_toolConfig_languages_select

?>