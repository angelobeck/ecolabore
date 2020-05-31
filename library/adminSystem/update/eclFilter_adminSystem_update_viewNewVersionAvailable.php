<?php

class eclFilter_adminSystem_update_viewNewVersionAvailable
{ // class eclFilter_adminSystem_update_viewNewVersionAvailable

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store;
$control['type'] = 'view';
$value = $io->systemConstants->get ('SYSTEM_ENGINE_UPDATE_CHECK');
if ($value === false)
return;

$value = $io->webservice->json2array ($value);
if (!isset ($value['release']))
return;

list ($y, $m, $d) = explode ('-', $value['release']);
$lastRelease = mktime (0, 0, 0, $m, $d, $y);
list ($y, $m, $d) = explode ('-', SYSTEM_RELEASE);
$systemRelease = mktime (0, 0, 0, $m, $d, $y);

if ($systemRelease >= $lastRelease)
{ // system updated
$formulary->appendChild ('adminSystem_update_viewSystemUpdated');
return;
} // system updated

$control['content'] = $formulary->document->textMerge ($value['release']);
$formulary->appendChild ($control);

if (!isset ($value['info']))
return;

$control = array ('type' => 'view');
$label = $store->control->read ('adminSystem_update_viewReleaseNotes');
$control['content'] = $label['text']['caption'];
$control['url'] = $value['info'];
$formulary->appendChild ($control);

$submit = $formulary->appendChild (array ('type' => 'submit'));

$control = array ('name' => $formulary->prefix . 'command_update');
$submit->appendChild ('adminSystem_update_editUpdateNow', $control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_update_viewNewVersionAvailable

?>