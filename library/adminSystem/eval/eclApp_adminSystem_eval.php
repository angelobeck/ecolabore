<?php

class eclApp_adminSystem_eval
{ // class eclApp_adminSystem_eval

const name = 'eval';
const menuType = 'section';
const dataFrom = 'adminSystem_eval_content';

static function dispatch ($document)
{ // function dispatch
$document->mod->formulary = $document->createFormulary ('adminSystem_eval_edit');
} // function dispatch

} // class eclApp_adminSystem_eval

?>