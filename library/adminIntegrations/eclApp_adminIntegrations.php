<?php

class eclApp_adminIntegrations
{ // class eclApp_adminIntegrations

const name = 'integrations';
const menuType = 'section';
const dataFrom = 'adminIntegrations_content';
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
$document->dataMerge ('adminIntegrations_contentEmpty');
else
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_adminIntegrations

?>