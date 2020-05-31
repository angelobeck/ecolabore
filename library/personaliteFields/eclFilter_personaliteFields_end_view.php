<?php

class eclFilter_personaliteFields_end_view
{ // class eclFilter_personaliteFields_end_view

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;
$local['type'] = 'view_end';
$formulary->appendChild ($local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_personaliteFields_end_view

?>