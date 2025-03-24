<?php

class eclApp_systemUsers extends eclApp
{
    public static $name = SYSTEM_USERS_URI;
    public static $map = ['userAdmin', 'userProfile', 'userGuest'];
    public static $content = 'systemUsers_main';
}