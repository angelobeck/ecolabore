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
            $cookie[] = 'Domain=' . ($pos === false ? $host : substr($host, 0, $pos));
        } // set host

        $cookie[] = 'Path=/';
        $cookie[] = 'SameSite=Strict';
        $cookie[] = 'HttpOnly';

        // SERVER_PROTOCOL e configurado como 'https:' (com dois-pontos)
        if (rtrim($io->request->protocol, ':') == 'https')
            $cookie[] = 'Secure';

        self::replaceCookieHeader($name, 'Set-Cookie: ' . implode('; ', $cookie));
    }

    /**
     * header('Set-Cookie: ...') com replace=true apaga TODOS os Set-Cookie
     * ja enviados, inclusive os emitidos por setcookie() (ex.: cookies
     * assinados do CloudFront no fluxo do audiobook). Aqui substituimos
     * apenas o cookie de mesmo nome e preservamos os demais.
     */
    public static function replaceCookieHeader(string $name, string $header): void
    {
        if (headers_sent())
            return;

        $kept = [];
        $found = false;
        foreach (headers_list() as $line) {
            if (stripos($line, 'Set-Cookie:') !== 0)
                continue;
            $pair = ltrim(substr($line, strlen('Set-Cookie:')));
            if (strpos($pair, $name . '=') === 0) {
                $found = true;
                continue;
            }
            $kept[] = $line;
        }

        if ($found) {
            header_remove('Set-Cookie');
            foreach ($kept as $line)
                header($line, false);
        }

        header($header, false);
    }

}