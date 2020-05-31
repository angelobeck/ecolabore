<?php

class eclApp_adminAlerts
{ // class eclApp_adminAlerts

const name = 'alerts';
const menuType = 'section';
const dataFrom = 'adminAlerts_main';
const access = 4;

static function constructor_helper ($me)
{ // function constructor_helper
$me->getMap ();
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

if (!count ($me->children ()))
$document->dataMerge ('adminAlerts_mainEmpty');
else
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_adminAlerts

?>