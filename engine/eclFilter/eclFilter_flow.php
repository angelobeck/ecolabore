<?php

/*
* Valid control flags
* control_filter = flow
* 
* Note that the control needs children
*/

class eclFilter_flow
{ // class eclFilter_flow

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;

if (!isset ($control['children']))
return;

$pageLength = count ($control['children']);

$page = 0;
if (isset ($formulary->received[$formulary->prefix . 'page']))
$page = $formulary->received[$formulary->prefix . 'page'];

if (!$formulary->errorMsg)
{ // no errors
if ($formulary->command ('next') or $formulary->command ('save') or $formulary->command ('finish'))
$page ++;
elseif ($page and $formulary->command ('previous'))
$page --;
} // no errors
if ($page >= $pageLength)
$page = $pageLength - 1;

$pageControl = $store->control->read ($control['children'][$page]);

if (!isset ($pageControl['children']))
$pageControl['children'] = [];

if (!$page)
$pageControl['children'] = array_merge (['_start'], $pageControl['children'], ['_next', '_end']);
elseif ($page + 1 == $pageLength)
$pageControl['children'] = array_merge (['_start'], $pageControl['children'], ['_previousFinish', '_end']);
else
$pageControl['children'] = array_merge (['_start'], $pageControl['children'], ['_previousNext', '_end']);

$formulary->hidden[$formulary->prefix . 'page'] = $page;
$formulary->control = $pageControl;
$formulary->index = 0;
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
if ($formulary->command ('previous'))
return;

if (!isset ($control['children']))
return;

$pageLength = count ($control['children']);

$page = 0;
if (isset ($formulary->received[$formulary->prefix . 'page']))
$page = $formulary->received[$formulary->prefix . 'page'];
if ($page >= $pageLength)
$page = $pageLength - 1;

$child = $store->control->read ($control['children'][$page]);

$formulary->hidden['page'] = $page;
$formulary->control = $child;
$formulary->index = 0;
} // function save

} // class eclFilter_flow

?>