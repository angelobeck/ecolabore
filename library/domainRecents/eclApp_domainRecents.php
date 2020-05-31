<?php

class eclApp_domainRecents
{ // class eclApp_domainRecents

static function is_child ($me, $name)
{ // function is_child
if ($name == '-recents')
return true;
if (isset ($me->data['flags']['modRss_disable']))
return false;
if ($name == 'rss.xml')
return true;
if (substr ($name, 0, 4) == 'rss-' and substr ($name,  - 4) == '.xml')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-recents');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-recents');
if (!$me->data)
{ // create content
$data['mode'] = MODE_DOMAIN;
$data['name'] = '-recents';
$data['flags']['modList_preset'] = 'recents';
$store->domainContent->insert ($me->domainId, $data);
} // create content
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-recents');
$me->id = $me->data['id'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;

if (substr ($me->name, 0, 3) == 'rss')
return self::rss ($document);
} // function dispatch

static function rss ($document)
{ // function rss
global $io;
$me = $document->application;
$io->log->silent = true;
if (preg_match ('/^rss-([a-z]+)\.xml$/', $me->name, $match))
$document->lang = $match[1];
$document->data['flags']['modLayout_base'] = 'empty';
$document->mod->layout = new eclMod_domainRecents_rss_layout ($document);
} // function rss

} // class eclApp_domainRecents

?>