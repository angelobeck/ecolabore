<?php

class eclFilter_sectionFormulary_fieldsView
{ // class eclFilter_sectionFormulary_fieldsView

static function create ($fieldName, $control, $formulary)
{ // function create
$me = $formulary->document->application;
if ($me->applicationName == 'sectionFormulary_record')
$me = $me->parent->parent;

$fields['children'] = self::getFields ($me);
$formulary->insertControlChildren ($fields);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function getFields ($me)
{ // function getFields
if (!isset ($me->data['extras']['formulary']))
return [];

$fields = [];
foreach ($me->data['extras']['formulary'] as $name => $field)
{ // each field
if (!isset ($field['caption']))
continue;

list ($filter) = explode ('_', $name, 2);
$flags = array (
'field_name' => $name, 
'filter' => 'personaliteFields_' . $filter, 
'view' => true
);
$fields[] = array (
'flags' => $flags, 
'text' => array ('caption' => $field['caption']), 
'local' => $field
);
} // each field

return $fields;
} // function getFields

} // class eclFilter_sectionFormulary_fieldsView

?>