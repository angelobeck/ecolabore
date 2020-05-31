<?php

class eclFilter_financialAccount_choosePaymentMethod
{ // class eclFilter_financialAccount_choosePaymentMethod

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->domain->child ('-tools')->child ('financial')->child ('resources');

$control['local']['type'] = 'radio';
$control['local']['name'] = $fieldName;
$item = $formulary->appendChild ($control);

foreach ($me->children () as $account)
{ // each account
foreach ($account->children () as $method)
{ // each method
if ($method->menuType != 'method')
continue;

$item->appendChild ($method->data, array (
'value' => $account->name . '/' . $method->name, 
'name' => $fieldName
));
} // each method
} // each account
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
if (!isset ($formulary->received[$fieldName][0]))
return $formulary->setErrorMsg ($control, $fieldName);

$parts = explode ('/', $formulary->received[$fieldName]);
if (count ($parts) < 2)
return $formulary->setErrorMsg ($control, $fieldName);

$formulary->data['account'] = $parts[0];
$formulary->data['method'] = $parts[1];
} // function save

} // class eclFilter_financialAccount_choosePaymentMethod

?>