<?php

class eclFilter_postEventSubscribe
{ // class eclFilter_postEventSubscribe

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;
$me = $document->application;

$formulary->appendChild ('postEventSubscribe_editEnableSubscription', array (
'name' => $fieldName . '_enable', 
'value' => $formulary->getField ('local/event_subscription_enable')
));

} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
$received = $formulary->received;
$document = $formulary->document;
$me = $document->application;

if (isset ($received[$fieldName . '_enable'][0]))
{ // save value
$formulary->setField ('local/event_subscription_enable', 1);
} // save value
else
{ // clear value
$formulary->setField ('local/event_subscription_available', false);
} // clear value


} // function save

} // class eclFilter_postEventSubscribe

?>