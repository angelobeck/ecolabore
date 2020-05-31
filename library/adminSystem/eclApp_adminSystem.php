<?php

class eclApp_adminSystem
{ // class eclApp_adminSystem

const name = 'system';
const menuType = 'section';
const dataFrom = 'adminSystem_content';
const map = 'adminSystem_setup adminSystem_admin adminSystem_hosting adminSystem_server adminSystem_update adminSystem_eval adminSystem_log adminSystem_extract adminSystem_pack';
const access = 4;

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_adminSystem

?>