<?php

class eclApp_adminDatabase_query
{ // class eclApp_adminDatabase_query

const name = 'query';
const menuType = 'section';
const dataFrom = 'adminDatabase_query_content';

static function dispatch ($document)
{ // function dispatch
$document->mod->formulary = $document->createFormulary ('adminDatabase_query_edit');
} // function dispatch

} // class eclApp_adminDatabase_query

?>