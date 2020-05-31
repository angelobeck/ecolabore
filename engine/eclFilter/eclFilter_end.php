<?php

class eclFilter_end
{ // class eclFilter_end

static function create ($fieldName, $control, $formulary)
{ // function create
$item = $formulary->appendChild ();
$item->data['type'] = 'end';

foreach ($formulary->hidden as $name => $value)
{ // each hidden field
$item->appendChild (false, array (
'name' => $name, 
'value' => $value
));
} // each hidden field
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_end

?>