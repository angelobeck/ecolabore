<?php

class eclApp_system
{ // class eclApp_system

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function constructor_helper ($me)
{ // function constructor_helper
global $io, $store;

$me->data = ECOLABORE_DATA;

// Set default layout
$me->data['flags'] = array (
'modLayout_base' => 'responsive', 
'modLayout_from' => 'domain', 
'modLayout_name' => 'default'
);

$me->getMap ();
$me->isDomain = true;
$me->groups[] = new eclGroup_system ();
} // function constructor_helper

} // class eclApp_system

?>