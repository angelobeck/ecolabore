<?php

class eclIo_request
{
    public string $host = SERVER_HOST;
    public array $path = [];
    public string $lang = DEFAULT_LANGUAGE;
    public array $actions = [];
    public array $flags = [];
    public array $received = [];

    public function __construct()
    {
        $path = [];
        if (isset($_GET['url'])) {
            $parts = explode('/', $_GET['url']);
            foreach ($parts as $folder) {
                if (preg_match('/^[a-zA-Z0-9._+-]+$/', $folder)) {
                    $path[] = $folder;
                }
            }
        }

        if ($path && end($path)[0] == '_') {
            $actionsGroups = explode('_', array_pop($path));
            foreach ($actionsGroups as $group) {
                $parts = explode('-', $group);
                switch ($parts[0]) {
                    case 'sid':
                    case 'js':
                    case 'html':
                        $this->flags[$parts[0]] = $parts;
                        break;

                    default:
                        $this->actions[$parts[0]] = $parts;
                }
            }
        }

        if ($path && end($path)[0] == '-' && strlen(end($path)) == 3) {
            $this->lang = substr(array_pop($path), 1);
        }

        $this->path = $path;

        $this->received = $_POST;
    }

}
