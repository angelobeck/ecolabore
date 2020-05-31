<?php

class eclFilter_personaliteFields_separator_view
{ // class eclFilter_personaliteFields_separator_view

static function create ($fieldName, $control, $formulary)
{ // function create
$local['type'] = 'separator';
$formulary->appendChild ($local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function column ($data, $name, $field, $url, $document)
{ // function column
return [];
} // function column

} // class eclFilter_personaliteFields_separator_view

?>