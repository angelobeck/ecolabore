<?php

class eclMod_sectionFolder_list
{ // class eclMod_sectionFolder_list

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

if (isset ($me->data['extras']['list']))
$mod->data = $me->data['extras']['list'];
else
$mod->data = $document->render->block ('modules/list');
$mod->data['name'] = 'section_' . $me->name;

$row = $mod->appendChild ();
foreach ($me->menuChildren ($document) as $child)
{ // each child

$row->appendChild ($child->data)
->virtual ($child->access)
->set ('editable', $document->contentEditable)
->swapTitle ()
->url ($child->pathway);
} // each child

if (!$row->children)
return;

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

} // class eclMod_sectionFolder_list

?>