<?php

class eclMod_sectionSubdomains_list
{ // class eclMod_sectionSubdomains_list

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
foreach ($store->domain->childrenNames () as $name)
{ // each domain
$domainId = $store->domain->getId ($name);
$status = $store->domain->getStatus ($name);
if ($status != 2)
continue;

$data = $store->domainContent->open ($domainId, '-register');
$row->appendChild ($data)
->swapTitle ()
->url (array ($name));
} // each domain

if (!$row->children)
return;

if (isset ($me->data['extras']['list']))
$mod->data = $me->data['extras']['list'];
else
$mod->data = $document->render->block ('modules/list');
$mod->data['name'] = 'section_' . $me->name;

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'list');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionSubdomains_list

?>