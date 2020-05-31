<?php

class eclScope_audio
{ // class eclScope_audio

static function get ($render, $arguments)
{ // function get
global $store, $system;
if ($render->me === false)
return false;
$document = $render->document;
$me = $render->me;
$number = $render->getVar ('mod.number');
if (!$number)
$number = '0';
$extras = $me->data['extras'];
$target = 'audio_' . $number;

if (!isset ($extras[$target]))
{ // no media
if (!$me->id or !$me->domainId)
return false;
if (!$document->access (4, $me->groups))
return;

$data['local']['editable'] = 1;
$pathway = $me->pathway;
$domain = array_shift ($pathway);
array_unshift ($pathway, $domain, '-personalite', 'extras', $target);
$url = $document->url ($pathway);
$data['local']['url'] = "javaScript:gadgets.humperstilshen.actionPopupOpen(" . TIC . $url . TIC . ", 300, 200)";
return $data;
} // no media

$data['local'] = $extras[$target];
if (isset ($data['local']['files']['audio']))
{ // url
$data['local']['url_download'] = $document->urlFiles ($data['local']['files']['audio'], true, '-downloads');
$data['local']['url_play'] = $document->urlFiles ($data['local']['files']['audio'], true, '-play');
} // url
//!start version 3 compatibility
if (isset ($data['local']['audio']))
{ // Version 3 compatibility
$data['local']['url_download'] = $document->urlFiles ($data['local']['audio'], true, '-downloads');
$data['local']['url_play'] = $document->urlFiles ($data['local']['audio'], true, '-play');
} // Version 3 compatibility
//!end version 3 compatibility

if (isset ($data['local']['play']))
$data['local']['play'] = strval ($data['local']['play']);
//!start version 3 compatibility
elseif (isset ($data['local']['plays']))
$data['local']['play'] = strval ($data['local']['plays']);
//!end version 3 compatibility

return $data;
} // function get

} // class eclScope_audio

?>