<?php

class eclEngine_page
{
    public eclEngine_application $domain;
    public eclEngine_application $application;
    public eclEngine_application $user;

    public eclEngine_dialogs $dialogs;
    public eclEngine_endpoints $endpoints;
    public eclEngine_menus $menus;
    public eclEngine_modules $modules;
    public eclEngine_views $views;

    public string $host = '#';
    public array $path = [];
    public string $lang = SYSTEM_DEFAULT_LANGUAGE;
    public string $defaultLang = SYSTEM_DEFAULT_LANGUAGE;
    public string $charset = 'UTF-8';
    public array $actions = [];
    public array $flags = [];
    public array $received = [];
    public array $session = [];

    public string $contentType = 'text/html';
    public string|array $buffer = '';

    public function sessionStart(): void
    {
        global $applications, $store;
        if (isset($this->flags['sid'][2])) {
            list(, $name, $key) = $this->flags['sid'];
            $openedSession = &$store->session->open($name, $key);
            if ($openedSession) {
                $this->session = &$openedSession['session'];
            } else {
                unset($this->flags['sid']);
            }
        }

        if (!isset($this->flags['sid'][2])) {
            $createdSession = &$store->session->create();
            $this->flags['sid'] = ['sid', $createdSession['name'], $createdSession['key']];
            $this->session = &$createdSession['session'];
        }

        $this->user = $applications->child(SYSTEM_USERS_URI)->child('-guest');
        if (isset($this->actions['logout'])) {
            $this->session = [];
        } else if (isset($this->session['user_name'])) {
            $user = $applications->child(SYSTEM_USERS_URI)->child($this->session['user_name']);
            if ($user) {
                $this->user = $user;
            }
        }

    }

    public function route(): void
    {
        global $applications;
        $path = $this->path;
        if (!count($path)) {
            $path[] = SYSTEM_DEFAULT_DOMAIN_NAME;
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
        if ($child) {
            $child = $this->routeSubfolders($child, $path);
        }
        if ($child) {
            return $child;
        }

        $child = $application->child('-not-found');
        if ($child) {
            return $this->routeSubfolders($child, $path);
        }
        return false;
    }

    public function dispatch(): void
    {
        $this->modules = new eclEngine_modules($this);

        if ($this->domain->applicationName !== $this->application->applicationName) {
            $domainHelper = 'eclApp_' . $this->domain->applicationName;
            $domainHelper::dispatch($this);
        }
        $helper = 'eclApp_' . $this->application->applicationName;
        $helper::dispatch($this);
        $helper::view_main($this);
    }

    public function render(): void
    {
        if (is_array($this->buffer)) {
            $this->buffer = eclIo_convert::array2json($this->buffer);
            $this->contentType = 'text/json';
            return;
        }

        if (strlen($this->buffer) > 0) {
            return;
        }

        $render = new eclEngine_render($this);
        $this->buffer = $render->render($this->modules->html);
    }

    public function access(int $requiredAccess, int $currentAccess): bool
    {
        return true;
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

        return 'http://localhost/ecolabore/index.php?url=' . implode('/', $path);
    }

    public function createFormulary(string|array $control, array $data = [], string $prefix = 'edit'): eclEngine_formulary
    {
        $formulary = new eclEngine_formulary($this, $control, $data, $prefix);
        return $formulary;
    }

}
