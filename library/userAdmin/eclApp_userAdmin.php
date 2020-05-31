<?php

class eclApp_userAdmin
{ // class eclApp_userAdmin

static function is_child ($me, $name)
{ // function is_child
global $store;

if (!defined ('ADMIN_IDENTIFIER'))
return false;
if ($name != ADMIN_IDENTIFIER)
return false;
if ($store->user->getId ($name))
return false;

return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $io, $store;
$me->name = ADMIN_IDENTIFIER;
$me->map = array ('userAdmin_index', 'userHome_welcome');
$me->data = &$io->session->cache;
$me->data['text']['caption'] = array ('en' => array (1 => ADMIN_CAPTION));
$me->data['text']['title'] = array ('en' => array (1 => ADMIN_CAPTION));
$me->data['name'] = ADMIN_IDENTIFIER;
$me->data['local']['mail'] = ADMIN_MAIL;
$me->data['local']['gender'] = ADMIN_GENDER;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->application = $document->application->child ('-index');
$document->application->dispatch ($document);
} // function dispatch

} // class eclApp_userAdmin

?>