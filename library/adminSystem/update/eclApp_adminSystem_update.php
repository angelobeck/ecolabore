<?php

class eclApp_adminSystem_update
{ // class eclApp_adminSystem_update

const name = 'update';
const menuType = 'section';
const dataFrom = 'adminSystem_update_content';

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('adminSystem_update_edit');

if ($formulary->command ('check'))
{ // check
$data = $io->webservice->request (SYSTEM_ENGINE_UPDATE_URL);
if (isset ($data['EcolaboreEngine']))
{ // update found
$data = $data['EcolaboreEngine'];
$data['request_date'] = date ('Y-m-d', TIME);
$io->systemConstants->set ('SYSTEM_ENGINE_UPDATE_CHECK', $io->webservice->array2json ($data));
} // update found
} // check

if ($formulary->command ('update') and defined ('SYSTEM_ENGINE_UPDATE_CHECK'))
{ // formulary update
if (SYSTEM_TIME_LIMIT)
set_time_limit (0);
$data = $io->webservice->json2array (SYSTEM_ENGINE_UPDATE_CHECK);
if (isset ($data['url']))
{ // update
$io->systemConstants->drop ('SYSTEM_ENGINE_UPDATE_CHECK');
$io->close ();
$document->reload = $document->url ();
$document->render ();

$string = file_get_contents ($data['url']);
$fileName = PATH_ROOT . SYSTEM_SCRIPT_NAME;
@unlink ($fileName);
file_put_contents ($fileName, $string);
print $document->buffer;
exit;
} // update
} // formulary saved

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminSystem_update

?>