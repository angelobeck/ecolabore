<?php

class eclApp_adminTools_phpInfo
{ // class eclApp_adminTools_phpInfo

const name = 'php-info';
const menuType = 'section';
const dataFrom = 'adminTools_phpInfo_content';
const access = 4;

static function dispatch ($document)
{ // function dispatch
phpinfo();
exit;
} // function dispatch

} // class eclApp_adminTools_security

?>