<?php

class eclFilter_userJoin_privacyPolicyAgree
{ // class eclFilter_userJoin_privacyPolicyAgree

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$document = $formulary->document;

if ($domainId = $store->domain->getId (SYSTEM_DEFAULT_DOMAIN_NAME) and $id = $store->domainContent->findMarker ($domainId, 7))
{ // privacy policy found
$pathway = $store->domainContent->pathway ($domainId, $id);

$formulary->appendChild ('userJoin_editPrivacyPolicyView', [
'type' => 'descriptive',
'url' => $document->url ($pathway)
]);

$formulary->appendChild ('userJoin_editPrivacyPolicyAgree', [
'type' => 'checkbox',
'name' => $fieldName,
'value' => $formulary->data['privacyAgree'] ?? 0
]);

} // privacy policy found
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
if ($domainId = $store->domain->getId (SYSTEM_DEFAULT_DOMAIN_NAME) and $id = $store->domainContent->findMarker ($domainId, 7))
{ // policy found
if (isset ($formulary->received[$fieldName][0]))
{ // checked
$formulary->data['privacyAgree'] = 1;
unset ($formulary->data['notAgree']);
} // checked
else
{ // not checked
unset ($formulary->data['privacyAgree']);
$formulary->data['notAgree'] = 1;
} // not checked
} // policy found
} // function save

} // class eclFilter_userJoin_privacyPolicyAgree

?>