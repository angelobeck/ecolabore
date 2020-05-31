<?php

class eclApp_financial
{ // class eclApp_financial

const name = 'financial';
const menuType = 'section';
const dataFrom = 'financial_content';
const map = 'financialProjects financialIncoming financialOutgoing financialResources financialCharges';
const access = 4;
const isDomain = true;

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_financial

?>