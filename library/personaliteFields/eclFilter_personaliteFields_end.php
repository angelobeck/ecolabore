<?php

class eclFilter_personaliteFields_end
{ // class eclFilter_personaliteFields_end

static function create ($fieldName, $control, $formulary)
{ // function create
$local['name'] = $fieldName;
$local['type'] = 'end';
$formulary->appendChild ($local);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_personaliteFields_end

?>