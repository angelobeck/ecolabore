<?php

class eclMod_modComments
{ // class eclMod_modComments

public $document;
public $enable = false;
public $children = [];

public function __construct ($document)
{ // function __construct
global $store;
$this->document = $document;
} // function __construct

public function enable ()
{ // function enable
global $store;
$document = $this->document;
$me = $document->application;

$this->enable = true;

// Conditions
if (!$document->user->userId)
return;

if (!$me->userId and !isset ($me->parent->data['flags']['comments_access']))
return;

if (!$me->userId and !$document->access ($me->parent->data['flags']['comments_access']))
return;

$formulary = $document->createFormulary ('modComments_add_form', [], 'add_comment');

if ($formulary->command ('save') and $formulary->save ())
{ // save comment
$section = &$store->domainContent->openById ($me->domainId, $me->id);
$section['coments_last_update'] = TIME;

$data = $formulary->data;
$data['mode'] = MODE_COMMENT;
$data['parent_id'] = $me->id;
$data['owner_id'] = $document->user->userId;
$store->domainExtras->insert ($me->domainId, $data);

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_GROUP, 
'parent_id' => $me->id, 
'owner_id' => $document->user->userId, 
);
$groups = $store->domainExtras->search ($where);
if ($groups)
{ // group found
} // group ofound
else
{ // create group
$group = array (
'domain_id' => $me->domainId, 
'mode' => MODE_GROUP, 
'parent_id' => $me->id, 
'owner_id' => $document->user->userId
);
$store->domainExtras->insert ($me->domainId, $group);
} // create group

$formulary->data = [];
} // save comment

$this->children = $formulary->create ()->children;
} // function enable

public function setModule ($mod, $arguments)
{ // function setModule
global $store;

if (!$this->enable)
return;

$document = $this->document;
$me = $document->application;
$render = $document->render;

$comments = $store->domainExtras->children ($me->domainId, MODE_COMMENT, $me->id);
if (!$comments)
return;

$row = $mod->appendChild ();
foreach ($comments as $data)
{ // each comment
$row->appendChild ($data);
} // each comment
$row->sort ();

$mod->data = $render->block ('modules/comments');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'comments');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modComments_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modComments

?>