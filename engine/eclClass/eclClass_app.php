<?php

class eclClass_app
{ // class eclClass_app

// const name = 'name'; -> the folder for the application
// const menuType = 'section'; -> 'section', 'hidden', 'post', 'domain'
// const dataFrom = ''; -> the name of the control to generate the content
// const map = 'myModule_create myModule_edit myModule_config'; -> a space separated applications names
// const access = 0; -> 0=all 1=connected 2=subscribed 3=member 4=owner or admin
// const isDomain = false; -> true creates a subgroup of pages to navigate
// const ignoreSubfolders = false; -> true ignore all childrens on the url

static function is_child ($parent, $name)
{ // function is_child
return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($parent)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclClass_app

?>