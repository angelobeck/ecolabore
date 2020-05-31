<?php

class eclMod_modVideo
{ // class eclMod_modVideo

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
if (isset ($me->data['extras']['video_' . $number]))
{ // module exists
$video = $me->data['extras']['video_' . $number];
$mod->data = $video;
$mod->enabled = true;
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;

if (isset ($video))
{ // modify
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'video_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modVideo_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // modify
} // personalite

$mod->data['local']['number'] = $number;
$mod->data['local']['list'] = 'details';
$mod->data['local']['details'] = 'media_video';
if (isset ($video))
$local['extras'] = array ('video_' . $number => $video);
$local['pathway'] = $me->pathway;
$mod->appendChild ($local);
} // function setModule

} // class eclMod_modVideo

?>