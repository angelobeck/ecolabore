<?php

class eclScope_address
{ // class eclScope_address

static function get ($render, $arguments)
{ // function get
$data['data'] = $render->getVar ('address');
return $data;
} // function get

} // class eclScope_address

?>