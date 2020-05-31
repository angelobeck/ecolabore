<?php

class eclFilter_userJoin_termsView
{ // class eclFilter_userJoin_termsView

static function create ($fieldName, $control, $formulary)
{ // function create
global $store;
$control['type'] = 'descriptive';
if ($domainId = $store->domain->getId (SYSTEM_DEFAULT_DOMAIN_NAME) and $id = $store->domainContent->findMarker ($domainId, 5))
{ // terms found
$data = $store->domainContent->openById ($domainId, $id);
$formulary->appendChild ($control, $data);
} // terms found
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
} // function save

} // class eclFilter_userJoin_termsView

?>