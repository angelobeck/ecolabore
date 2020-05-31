<?php

class eclApp_adminTools_components
{ // class eclApp_adminTools_components

const name = 'components';
const menuType = 'section';
const dataFrom = 'adminTools_components_content';
const access = 4;

static function dispatch ($document)
{ // function dispatch
if (!is_dir (PATH_COMPONENTS))
{ // create folder
$formulary = $document->createFormulary ('adminTools_components_folder', [], 'createFolder');
if (!$formulary->command ('create') or !$formulary->save ())
{ // view form
$document->mod->formulary = $formulary;
return;
} // view form
} // create folder

$formulary = $document->createFormulary ('adminTools_components_edit');

$formulary->command ('save') and $formulary->save ();

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminTools_components

?>