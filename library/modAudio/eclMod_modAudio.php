<?php

class eclMod_modAudio
{ // class eclMod_modAudio

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

// Procure a aplicaзгo
$me = $document->application->findModuleTarget ($document, $arguments);
if (!$me)
return;

$number = $arguments[1];

// Procure o mуdulo dentro da aplicaзгo
if (isset ($me->data['extras']['audio_' . $number]))
{ // module exists
$audio = $me->data['extras']['audio_' . $number];
$mod->data = $audio;
$mod->enabled = true;
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;

if (isset ($audio))
{ // modify
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'audio_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modAudio_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // modify
} // personalite

$mod->data['local']['number'] = $number;
$mod->data['local']['list'] = 'details';
$mod->data['local']['details'] = 'media_audio';
$mod->appendChild ($me);
} // function setModule

} // class eclMod_modAudio

?>