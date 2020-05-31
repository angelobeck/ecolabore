<?php

class eclMod_financialCharges_list
{ // class eclMod_financialCharges_list

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$me = $document->application;

$mod->data = $store->control->read ('financialCharges_list');
$mod->data = array_replace_recursive ($store->control->read ('modules/list_card'), $mod->data);
$mod->data['local']['list'] = 'table';

// Headers
$row = $mod->appendChild ();
$row->appendChild ('financialCharges_listOwner');
$row->appendChild ('financialCharges_listDescription');
$row->appendChild ('financialCharges_listDue');

$where = array (
'domain_id' => $me->domainId, 
'mode' => MODE_CONTRACT, 
'spotlight' => 101
);

$contracts = $store->domainContent->search ($where, 4, 0, 0, 'event_start');

foreach ($contracts as $contract)
{ // each contract
$row = $mod->appendChild ();

// Owner
$owner = $store->domainContent->openById ($me->domainId, $contract['subscription_id']);
$row->appendChild ($owner);

// Contract subject
$subject = $store->domainContent->openById ($me->domainId, $contract['parent_id']);
$row->appendChild ($subject)
->url ($store->domainContent->pathway ($me->domainId, $contract['id']))
->popUpOpen ();

// date
$local['text']['caption'] = $document->textMerge (date ('Y-m-d', $contract['event_start']));
$row->appendChild ($local);
} // each contract

$mod->enabled = true;
} // function setModule

} // class eclMod_financialCharges_list

?>