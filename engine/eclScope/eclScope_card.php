<?php

class eclScope_card
{ // class eclScope_card

static function get ($render, $arguments)
{ // function get
if (isset ($render->me->data['extras']['card']['local']))
return $render->me->data['extras']['card'];

return;
} // function get

} // class eclScope_card

?>