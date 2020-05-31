<?php

class eclFilter_sectionFormulary_fields
{ // class eclFilter_sectionFormulary_fields

static function create ($fieldName, $control, $formulary)
{ // function create
$fields['children'] = self::getFields ($formulary->document->application);
$formulary->insertControlChildren ($fields);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
$fields['children'] = self::getFields ($formulary->document->application);
$formulary->insertControlChildren ($fields);
} // function save

static function getFields ($me)
{ // function getFields
if (!isset ($me->data['extras']['formulary']))
return [];

$index = 0;
$formulary = $me->data['extras']['formulary'];
$sorted = [];
$fields = [];
foreach ($formulary as $name => $field)
{ // sort fields
if (isset ($field[$index]))
$sorted[$index][] = $name;
else
$sorted[100000][] = $name;
} // sort fields
ksort ($sorted);

foreach ($sorted as $group)
{ // each group
foreach ($group as $name)
{ // each field
$field = $formulary[$name];
list ($filter) = explode ('_', $name, 2);
if (!isset ($field['caption']))
continue;
$flags = array (
'field_name' => $name, 
'filter' => 'personaliteFields_' . $filter
);
if (isset ($field['type']))
$flags['type'] = $field['type'];

$fields[] = array (
'flags' => $flags, 
'text' => array ('caption' => $field['caption']), 
'local' => $field
);
} // each field
} // each group

return $fields;
} // function getFields

} // class eclFilter_sectionFormulary_fields

?>