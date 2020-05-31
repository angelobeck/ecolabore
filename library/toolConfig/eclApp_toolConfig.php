<?php

class eclApp_toolConfig
{ // class eclApp_toolConfig

const name = 'config';
const menuType = 'section';
const dataFrom = 'toolConfig_content';
const map = 'toolConfig_about toolConfig_languages toolConfig_market toolConfig_contentPrivacyPolicy';
const isDomain = true;

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_toolConfig

?>