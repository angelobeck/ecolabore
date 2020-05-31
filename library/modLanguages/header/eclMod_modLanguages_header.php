<?php

class eclMod_modLanguages_header
{ // class eclMod_modLanguages_header

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

if (!isset ($me->data['text']['caption']))
return;

if ($document->printableLayout)
return;

if (!isset ($me->data['text']['caption'][$document->lang]))
{ // no index, go canonical
if (isset ($me->data['text']['caption'][$document->defaultLang]))
$mod->data['local']['url_canonical'] = $document->url (true, $document->defaultLang);
else
{ // first language
$lang = key ($me->data['text']['caption']);
$mod->data['local']['url_canonical'] = $document->url (true, $lang);
} // first language
} // no index, go canonical

if ($me->name == '' and !isset ($me->parent->data['flags']['modRss_disable']))
{ // rss
if ($document->lang == $document->defaultLang)
$mod->data['local']['url_rss'] = $document->url (array ($document->domain->name, 'rss.xml'), false, false);
else
$mod->data['local']['url_rss'] = $document->url (array ($document->domain->name, 'rss-' . $document->lang . '.xml'), false, false);
$mod->data['local']['rss'] = $me->parent->data['text']['title'];
} // rss

foreach ($me->data['text']['caption'] as $lang => $text)
{ // each lang
if ($lang == $document->lang)
continue;

$mod->appendChild (array (
'lang' => $lang, 
'url' => $document->url (true, $lang)
));
} // each lang

$mod->enabled = true;
} // function setModule

} // class eclMod_modLanguages_header

?>