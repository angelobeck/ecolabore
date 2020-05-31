<?php

class eclFilter_adminSystem_update_upload
{ // class eclFilter_adminSystem_update_upload

static function create ($fieldName, $control, $formulary)
{ // function create
$control['type'] = 'file';
$control['name'] = $fieldName;
$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($_FILES[$fieldName]['size']) or !$_FILES[$fieldName]['size'])
return $formulary->setRequiredMsg ($control, $fieldName);

$formulary->data['file_field'] = $fieldName;
} // function save

} // class eclFilter_adminSystem_update_upload

?>