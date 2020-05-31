<?php

class eclFilter_adminSystem_update_viewCurrentRelease
{ // class eclFilter_adminSystem_update_viewCurrentRelease

static function create ($fieldName, $control, $formulary)
{ // function create
$control['type'] = 'view';
$control['content'] = $formulary->document->textMerge (SYSTEM_RELEASE);
$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_update_viewCurrentRelease

?>