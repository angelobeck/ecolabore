<?php

class eclApp_adminTools_security
{ // class eclApp_adminTools_security

const name = 'security-report';
const menuType = 'section';
const dataFrom = 'adminTools_security_content';

static function dispatch ($document)
{ // function dispatch

$formulary = $document->createFormulary ('adminTools_security_view');
$document->mod->formulary = $formulary;

} // function dispatch

} // class eclApp_adminTools_security

?>