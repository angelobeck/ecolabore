<?php

class eclFilter_adminSystem_update_viewLastVerification
{ // class eclFilter_adminSystem_update_viewLastVerification

static function create ($fieldName, $control, $formulary)
{ // function create
global $io;
$control['type'] = 'view';
$value = $io->systemConstants->get ('SYSTEM_ENGINE_UPDATE_CHECK');
if ($value === false)
return;

$value = $io->webservice->json2array ($value);
if (!isset ($value['request_date']))
return;

$control['content'] = $formulary->document->textMerge ($value['request_date']);
$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_update_viewLastVerification

?>