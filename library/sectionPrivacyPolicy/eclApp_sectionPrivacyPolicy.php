<?php

class eclApp_sectionPrivacyPolicy
{ // class eclApp_sectionPrivacyPolicy

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionPrivacyPolicy

?>