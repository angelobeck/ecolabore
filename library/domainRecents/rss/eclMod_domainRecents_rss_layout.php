<?php

class eclMod_domainRecents_rss_layout
{ // class eclMod_domainRecents_rss_layout

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->domain;
$mod->enabled = true;
$mod->data = $store->control->read ('domainRecents_rss_layout');
$mod->data['local']['url_self'] = $document->url (true, false, false);
$mod->data['local']['pubDate'] = date ('r');

$me = $document->domain->child ('-recents');

if (!isset ($me->data['links']['pages']))
exit ('<xml></xml>');

$data = [];
$sorted = [];
foreach ($me->data['links']['pages'] as $name)
{ // each page
$data = $store->domainContent->open ($me->domainId, $name);
if (!$data)
continue;

$sorted[$data['event_start']][] = $data;
} // each page
krsort ($sorted);

$max = 20;
if (isset ($document->domain->data['flags']['modRss_max']))
$max = $document->domain->data['flags']['modRss_max'];

$index = 0;
foreach ($sorted as $group)
{ // each group
foreach ($group as $data)
{ // each data
$post = $me->findChild ($data['name']);
if (!$post or !$document->access ($post->access, $post->groups))
continue;

$index++;
if ($index >= $max)
break 2;

$mod->appendChild ($data)
->set ('pubDate', date ('r', $data['created']))
->url ($post->pathway);
} // each data
} // each group
} // function setModule

} // class eclMod_domainRecents_rss_layout

?>