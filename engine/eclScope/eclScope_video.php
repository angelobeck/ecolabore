<?php

class eclScope_video
{ // class eclScope_video

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;

$extras = $render->getVar ('extras');
$target = 'video';
$number = $render->getVar ('mod.number');
if (!$number)
$number = '0';

if (!isset ($extras[$target . '_' . $number]))
{ // no media
$pathway = $render->getVar ('pathway');
if (!$pathway)
return;

$me = $document->domain->parent;
foreach ($pathway as $folder)
{ // each folder
$me = $me->child ($folder);
if (!$me)
return;
} // each folder

if (!$document->access (4, $me->groups))
return;

$data['local']['editable'] = 1;
$domain = array_shift ($pathway);
array_unshift ($pathway, $domain, '-personalite', 'extras', $target . '_' . $number);
$url = $document->url ($pathway);
$data['local']['url'] = "javaScript:humperstilshen.popUpOpen(" . TIC . $url . TIC . ", 300, 200)";
return $data;
} // no media

$data['local'] = $extras[$target . '_' . $number];
if (isset ($data['local']['files'][$target]))
{ // url
$data['local']['url_download'] = $document->urlFiles ($data['local']['files'][$target], true, '-downloads');
$data['local']['url_play'] = $document->urlFiles ($data['local']['files'][$target], true, '-play');
} // url
return $data;
} // function get

} // class eclScope_video

?>