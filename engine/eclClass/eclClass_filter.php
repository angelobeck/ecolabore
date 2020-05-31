<?php

class eclClass_filter
{ // class eclClass_filter

static function create ($fieldName, $control, $formulary)
{ // function create
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

static function view ($fieldName, $control, $formulary)
{ // function view
} // function view

static function scope ($render, $arguments)
{ // function scope
return [];
} // function scope

static function target ($document, $value, $arguments)
{ // function target
global $store;
} // function target

static function column ($document, $data, $name, $field, $url)
{ // function column
return ['caption' => $document->textMerge ('-')];
} // function column

} // class eclClass_filter

?>