<?php

class eclMod_modLanguages
{ // class eclMod_modLanguages

public $action = true;
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
$me = $document->application;

// Condições
if (!isset ($me->data['text']['caption']))
return;

if ($document->printableLayout)
return;

if ($document->domain->domainId and !isset ($document->data['id']))
return;

// Preparação
if ($document->access (4))
$access = 1;
else
$access = 1;

$languages = [];
if (isset ($document->domain->data['flags']['languages']))
{ // set domain languages
foreach (explode (CRLF, $document->domain->data['flags']['languages']) as $lang)
{ // each enabled language
$languages[$lang] = $access;
} // each enabled language
} // set domain languages

foreach ($me->data['text']['caption'] as $lang => $value)
{ // each document language
$languages[$lang] = 2;
} // each document language

// Itens da lista
$row = $mod->appendChild ();
foreach ($languages as $lang => $access)
{ // each valid languages

if ($lang == $document->lang)
$active = 1;
else
$active = 0;

if ($active or $access)
{ // valid language

if ($access == 1)
$virtual = 1;
else
$virtual = 0;

$label = $store->control->read ('labels/lang/' . $lang);
if (!isset ($label['text']['caption']))
continue;

$caption = [];
if (isset ($label['text']['caption'][$lang]))
$caption[$lang] = $label['text']['caption'][$lang];
else
$caption = $label['text']['caption'];
$local = array (
'caption' => $caption, 
'virtual' => $virtual, 
'icon' => $document->urlFiles ('modLanguages/' . $lang . '.png', true, '-shared'), 
'LANG' => strtoupper ($lang), 
'lang' => $lang
);
$row->appendChild ($local)
->active ($active)
->url (true, $lang, $this->action);
} // valid language
} // each valid languages

if (count ($row->children) == 1 and $active)
return;

// Configurações
$mod->data = $render->block ('modules/languages');

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4, $document->domain->groups))
{ // reference
$pathway = array ($document->domain->name, '-personalite', 'modules', 'languages');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modLanguages_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // reference

$mod->enabled = true;
} // function setModule

} // class eclMod_modLanguages

?>