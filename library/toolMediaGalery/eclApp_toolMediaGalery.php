<?php

class eclApp_toolMediaGalery
{ // class eclApp_toolMediaGalery

const name = 'media';
const menuType = 'section';
const dataFrom = 'toolMediaGalery_content';
const map = 'toolMediaGalery_images toolMediaGalery_audios toolMediaGalery_videos';
const isDomain = true;

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_toolImageGalery_list ($document);
} // function dispatch

} // class eclApp_toolMediaGalery

?>