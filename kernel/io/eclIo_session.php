<?php

class eclIo_session
{

public function setCookie(string $name, string $value)
    {
        global $io;

        $cookie = [];

        $cookie[] = $name . '=' . $value;

        if ($value == 'invalid')
            $cookie[] = 'Expires=Thu, 01 Jan 1970 00:00:00 GMT';
        elseif ($name == 'ECL-Persistent')
            $cookie[] = 'Max-Age=2592000'; // 30 days

        if (SERVER_HOSTING_MODE == 'single') { // set host
            $host = $io->request->host;
            $pos = strpos($host, '/');
            $cookie[] = 'Domain=' . substr($host, 0, $pos);
        } // set host

        $cookie[] = 'Path=/';
        $cookie[] = 'SameSite=Strict';
        $cookie[] = 'HttpOnly';

        if ($io->request->protocol == 'https')
            $cookie[] = 'Secure';

        header('Set-Cookie: ' . implode('; ', $cookie));
    }

}