<?php

class eclEngine_page
{
    public eclEngine_application $domain;
    public eclEngine_application $application;
    public eclEngine_application $user;

    public eclEngine_dialogs $dialogs;
    public eclEngine_endpoints $endpoints;
    public eclEngine_menus $menus;
    public eclEngine_components $components;
    public eclEngine_views $views;

    public string $protocol = SERVER_PROTOCOL;
    public string $host = SERVER_HOST;
    public array $path = [];
    public string $lang = DEFAULT_LANGUAGE;
    public string $defaultLang = DEFAULT_LANGUAGE;
    public string $charset = DEFAULT_CHARSET;
    public array $actions = [];
    public array $flags = [];
    public array $received = [];
    public array $session = [];
    public bool $sessionStarted = false;

    public string $contentType = 'text/html';
    public string|array $buffer = '';

    public function sessionStart()
    {
        global $io, $store;

        if ($this->sessionStarted) {
            return;
        }

        $this->sessionStarted = true;
        $sessionId = '';
        $sessionKey = '';
        if (isset($this->received['sessionId']) and isset($this->received['sessionKey'])) {
            $sessionId = $this->received['sessionId'];
            $sessionKey = $this->received['sessionKey'];
        } else if (isset($_COOKIE['ecl-session'])) {
            $parts = explode('-', $_COOKIE['ecl-session']);
            if (count($parts) == 2) {
                [$sessionId, $sessionKey] = $parts;
            }
        }

        if ($this->findSession($sessionId, $sessionKey)) {
            $io->session->setCookie('ecl-session', $sessionId . '-' . $sessionKey);
        } else {
            $io->session->setCookie('ecl-session', 'invalid');
        }

        if (isset($this->actions['logout'])) {
            $io->session->setCookie('ecl-session', 'invalid');
            ecl_log('logout');
            $this->session = [];
            $openedSession = $store->session->open($sessionId, $sessionKey);
            if (isset($openedSession['id'])) {
                $store->session->delete($openedSession['id']);
            }
        }
    }

    private function findSession(string $sessionId, string $sessionKey): bool
    {
        global $store;

        if ($sessionId == '' || $sessionKey == '')
            return false;

        $openedSession = &$store->session->open($sessionId, $sessionKey);
        if (!isset($openedSession['session']['user']['name']))
            return false;

        if ($openedSession['session']['user']['name'] === ADMIN_NAME) {
            $this->session = &$openedSession['session'];
            return true;
        }

        $user = $store->user->open($openedSession['session']['user']['name']);
        if (!$user)
            return false;

        if (isset($user['status']) and $user['status'] === 'blocked')
            return false;

        $this->session = &$openedSession['session'];
        return true;
    }

    public function route(): void
    {
        global $applications;
        $path = $this->path;
        if (SERVER_HOSTING_MODE == 'single')
            array_unshift($path, DEFAULT_DOMAIN_NAME);
        if (!count($path)) {
            $path[] = DEFAULT_DOMAIN_NAME;
        }
        $domain = $applications->child($path[0]);
        if (!$domain) {
            $domain = $applications->child('-not-found');
        }
        if (!count($domain->map)) {
            $this->domain = $domain;
            $this->application = $domain;
        } else {
            array_shift($path);
            if (!count($path)) {
                $path[] = '-home';
            }
            $application = $this->routeSubfolders($domain, $path);
            if (!$application) {
                $application = $applications->child('-not-found');
            }
            $this->domain = $domain;
            $this->application = $application;
        }
    }

    private function routeSubfolders(eclEngine_application $application, array $path): eclEngine_application|bool
    {
        if (!$path) {
            return $application;
        } else if ($application->ignoreSubfolders) {
            return $application;
        }

        $name = array_shift($path);
        $child = $application->child($name);
        if (!$child) {
            $default = $application->child('-default');
            if (is_object($default)) {
                $default->path = [...$default->parent->path, $name];
                $default->name = $name;
                $result = $this->routeSubfolders($default, $path);
                if ($result)
                    return $result;
            }
        } else {
            $result = $this->routeSubfolders($child, $path);
            if ($result)
                return $result;
        }

        return $application->child('-not-found');
    }

    public function dispatch(): void
    {
        global $io, $store;

        $this->components = new eclEngine_components($this);
        $this->endpoints = new eclEngine_endpoints($this);

        if ($this->domain->applicationName !== $this->application->applicationName) {
            $domainHelper = 'eclApp_' . $this->domain->applicationName;
            $domainHelper::dispatch($this);
        }
        $helper = 'eclApp_' . $this->application->applicationName;
        $helper::dispatch($this);

        if (!$this->access($this->application->access)) {
            return;
        }

        if (isset($this->actions['endpoint'][1])) {
            $endpoint = $this->actions['endpoint'][1];
            if ($endpoint === 'file') {
                $this->buffer = $this->endpoints->file->dispatch([]);
            } else {
                if (isset($this->received['content']) and is_array($this->received['content']))
                    $this->buffer = $this->endpoints->$endpoint->dispatch($this->received['content']);
                else
                    $this->buffer = $this->endpoints->$endpoint->dispatch([]);
            }
            return;
        }

        $helper::view_main($this);
    }

    public function render(): void
    {
        if (is_array($this->buffer)) {
            $this->buffer = eclIo_convert::array2json($this->buffer);
            $this->contentType = 'application/json';
            return;
        }

        if (strlen($this->buffer) > 0) {
            return;
        }

        $render = new eclEngine_render($this);
        $this->buffer = $render->render($this->components->html);
    }

    public function access(int $level, array $groups = []): bool
    {
        if ($level === 0)
            return true;

        if (!$this->sessionStarted)
            $this->sessionStart();

        if (!isset($this->session['user']))
            return false;

        if ($level === 1)
            return true;

        if (!$groups)
            $groups = $this->application->groups;

        foreach ($groups as $group) {
            if ($group->check($this, $level))
                return true;
        }

        return false;
    }

    public function action(string $name): bool
    {
        $args = func_get_args();
        $argsCount = count($args);
        if (!isset($this->actions[$args[0]])) {
            return false;
        }
        $action = $this->actions[$args[0]];
        if (count($action) < $argsCount) {
            return false;
        }
        for ($i = 1; $i < $argsCount; $i++) {
            if ($action[$i] != $args[$i]) {
                return false;
            }
        }
        return true;
    }

    public function selectLanguage(string|array|int|float $text): array
    {
        $result = [
            'value' => ''
        ];

        foreach (func_get_args() as $arg) {
            if (is_string($arg)) {
                $result['value'] .= $arg;
            } else if (is_numeric($arg)) {
                $result['value'] .= strval($arg);
            } else if (is_array($arg) && isset($arg[$this->lang])) {
                $result['value'] .= mb_convert_encoding($arg[$this->lang]['value'], $this->charset, ($arg[$this->lang]['charset'] ?? 'UTF-8'));
            } else if (is_array($arg)) {
                foreach ($arg as $lang => $value) {
                    $result['value'] .= mb_convert_encoding($value['value'], $this->charset, ($value['charset'] ?? 'UTF-8'));
                    $result['lang'] = $lang;
                }
            }
        }
        if (!isset($result['lang'])) {
            $result['lang'] = $this->lang;
        }
        return $result;
    }

    public function url(array|bool $path = true, string|bool $lang = true, string|bool $action = true): string
    {
        if ($path === true or !$path) {
            $path = $this->application->path;
        }
        if ($lang === true && $this->lang !== $this->defaultLang) {
            $path[] = '-' . $this->lang;
        } else if (is_string($lang) && $lang !== $this->defaultLang) {
            $path[] = '-' . $lang;
        }

        $actions = [];
        if (is_string($action) and $action !== '') {
            $actions[] = $action;
        }
        foreach ($this->flags as $flag) {
            $actions[] = implode('-', $flag);
        }
        if ($actions) {
            $path[] = '_' . implode('_', $actions);
        }

        $url = SERVER_PROTOCOL . '//' . SERVER_HOST;
        if (!SERVER_REWRITE_ENGINE)
            $url .= SERVER_SCRIPT_NAME . '?url=';
        if (SERVER_HOSTING_MODE == 'single' && $path[0] === DEFAULT_DOMAIN_NAME)
            array_shift($path);

        $url .= implode('/', $path);
        return $url;
    }

    public function createFormulary(string|array $control, array $data = [], string $prefix = 'edit'): eclEngine_formulary
    {
        $formulary = new eclEngine_formulary($this, $control, $data, $prefix);
        return $formulary;
    }

}
