<?php

class eclMod_domainInfo_listAdministrators
{ // class eclMod_domainInfo_listAdministrators

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
$row = $mod->appendChild ();

$domainId = $me->domainId;
$group = $store->domainGroup->open ($domainId, 1);
foreach ($group as $userId => $status)
{ // each user
if (!$userId or $status != 4)
continue;

$data = $store->userContent->open ($userId, '-register');
if (!$data)
continue;

$row->appendChild ($data)
->url (array (SYSTEM_PROFILES_URI, $store->user->getName ($userId)));
} // each user

$mod->data = $document->render->block ('modules/list_info_administrators');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'list_glossary');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_domainInfo_listAdministrators

?>