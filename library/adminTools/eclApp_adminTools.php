<?php

class eclApp_adminTools
{ // class eclApp_adminTools

const name = 'tools';
const menuType = 'section';
const dataFrom = 'adminTools_content';
const map = 'adminTools_components adminTools_security adminTools_phpInfo';

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_adminTools

?>