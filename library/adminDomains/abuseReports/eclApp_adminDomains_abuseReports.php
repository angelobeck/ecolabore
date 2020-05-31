<?php

class eclApp_adminDomains_abuseReports
{ // class eclApp_adminDomains_abuseReports

const name = '-abuse-reports';
const menuType = 'section';
const dataFrom = 'adminDomains_abuseReports_content';

static function constructor_helper ($me)
{ // function constructor_helper
global $io, $store;
$me->map = ['adminDomains_abuseReports_ticket'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

$document->mod->list = new eclMod_adminDomains_abuseReports_list ($document);
} // function dispatch

} // class eclApp_adminDomains

?>