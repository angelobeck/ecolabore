<?php

class eclScope_eventSubscribe
{ // class eclScope_eventSubscribe

static function get ($render, $arguments)
{ // function get
$document = $render->document;
$me = $render->me;
print_a ($me->data);
if (!isset ($me->data['local']['event_subscription_enable']))
return;

$local = [];
$local['url'] = $document->url ($me->pathway);


$data['data'] = $local;
return $data;
} // function get

} // class eclScope_eventSubscribe

?>