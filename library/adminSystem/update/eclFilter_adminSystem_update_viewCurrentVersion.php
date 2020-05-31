<?php

class eclFilter_adminSystem_update_viewCurrentVersion
{ // class eclFilter_adminSystem_update_viewCurrentVersion

static function create ($fieldName, $control, $formulary)
{ // function create
$control['type'] = 'view';
$control['content'] = $formulary->document->textMerge (SYSTEM_VERSION);
$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_adminSystem_update_viewCurrentVersion

?>