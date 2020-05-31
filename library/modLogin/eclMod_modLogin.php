<?php

class eclMod_modLogin
{ // class eclMod_modLogin

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

if ($document->access (1) or (isset ($document->data['flags']['modLogin_enabled']) and !$document->data['flags']['modLogin_enabled']))
return;

$mod->data = $render->block ('modules/login');

static $instances = 0;
$instances++;
$formulary = $document->createFormulary ('modLogin_login', [], 'login' . $instances . 'fields');
if ($instances == 1)
$formulary->action = '_login';
else
$formulary->action = '_login-' . $instances;
$formulary->pathway = $document->pathway;

$mod->childrenMerge ($formulary->create ());

$mod->enabled = true;
} // function setModule

} // class eclMod_modLogin

?>