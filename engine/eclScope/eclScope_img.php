<?php

class eclScope_img
{ // class eclScope_img

static function get ($render, $arguments)
{ // function get
global $store;
$document = $render->document;
$number = '0';
if (isset ($arguments[0]))
$number = $arguments[0];
$extras = $render->getVar ('extras');
if (!isset ($extras['img_' . $number]))
return false;

$domainId = $render->getVar ('domain_id');
$domainName = $store->domain->getName ($domainId);

$scope = array ('data' => []);
$data = &$scope['data'];
$data = $extras['img_' . $number];

$data['url'] = $render->getVar ('url');

if (isset ($data['files']['img']))
$data['url_img'] = $document->urlFiles ($data['files']['img'], $domainName);
// Version 3 compatibility
elseif (isset ($data['img']))
$data['url_img'] = $document->urlFiles ($data['img'], $domainName);

if (isset ($data['files']['img_mini']))
$data['url_img_mini'] = $document->urlFiles ($data['files']['img_mini'], $domainName);
// Version 3 compatibility
elseif (isset ($data['img_mini']))
$data['url_img_mini'] = $document->urlFiles ($data['img_mini'], $domainName);

if (!isset ($data['local']['description']))
{ // provides a description
$data['local']['description'] = $render->getVar ('description');
if ($data['local']['description'] == '')
$data['local']['description'] = $render->getVar ('title');
if ($data['local']['description'] == '')
$data['local']['description'] = $render->getVar ('caption');
} // provides a description

return $scope;
} // function get

} // class eclScope_img

?>