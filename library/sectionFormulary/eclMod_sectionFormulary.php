<?php

class eclMod_sectionFormulary
{ // class eclMod_sectionFormulary

public $document;
public $formulary;

public function __construct ($document, $formulary)
{ // function __construct
$this->document = $document;
$this->formulary = $formulary;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;

$mod->children = $this->formulary->create ();

// Procure o mуdulo dentro da aplicaзгo
if (isset ($me->data['extras']['formulary']))
$mod->data = $me->data['extras']['formulary'];
else
{ // from preset
if (isset ($me->data['flags']['modFormulary_preset']))
$preset = $me->data['flags']['modFormulary_preset'];
else
$preset = 'edit';

$mod->data = $render->block ('modules/formulary_' . $preset);
if (!$mod->data)
$mod->data = $render->block ('modules/formulary');
} // from preset

$mod->data['name'] = 'section_' . $me->name;

if ($document->templateEditable and $document->access (4))
{ // personalite reference
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'formulary');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modList_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_sectionFormulary

?>