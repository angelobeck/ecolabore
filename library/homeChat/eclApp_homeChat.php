<?php

class eclApp_homeChat
{ // class eclApp_homeChat

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('homeChat_group', 'homeChat_user');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();
$document->mod->list = new eclMod_homeChat_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_homeChat

?>