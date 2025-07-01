<?php

class eclApp_systemUsers extends eclApp
{
    public static $name = APPLICATION_USERS_NAME;
    public static $map = ['userAdmin', 'userProfile', 'userGuest'];
    public static $content = 'systemUsers_main';
}