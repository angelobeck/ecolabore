<?php

class eclEngine_formulary
{
    public eclEngine_page $page;
    public array $data = [];
    public array $flags = [];
    public string $prefix = 'edit';
    public array $children = [];
    public array $received = [];
    public array $error = [];
    public array $control = [];

    private string $sessionKey;

    private int $errorStatus = 0;
    private array $errorMessage = [];

    public function __construct(eclEngine_page $page, string|array $control, array $data, string $prefix)
    {
        global $store;
        $this->page = $page;
        $this->received = $page->received;
        $this->data = $data;
        $this->prefix = $prefix;
        $this->sessionKey = implode('/', $page->application->path) . '/_' . $prefix;
        if (is_string($control)) {
            $control = $store->staticContent->open($control);
        }
        $this->control = $control;
    }

    public function save(int $posteriori = 0): bool
    {
        global $store;
        $this->error = [];
        if (isset($this->control['children'])) {
            foreach ($this->control['children'] as $control) {
                if (is_string($control)) {
                    $control = $this->cloneControl($store->staticContent->open($control));
                }

                if (!isset($control['filter']))
                    continue;
                if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $control['filter']))
                    continue;
                if (!isset($control['name']))
                    continue;
                if (isset($control['condition']) && !$this->condition($control['condition']))
                    continue;
                if (isset($control['flags']) and $posteriori == 1)
                    continue;
                if (!isset($control['posteriori']) and $posteriori == 2)
                    continue;
                if (isset($control['view']))
                    continue;

                $name = $this->prefix . '_' . $control['name'];
                $filter = 'eclFilter_' . $control['filter'];
                if (!isset($control['target']))
                    $control['target'] = $control['name'];

                $filter::save($this, $control, $name);
            }
        }

        return $this->errorStatus === 0;
    }

    public function create(): eclMod
    {
        global $store;
        if (!$this->errorStatus) {
            $this->children = [];
            if (isset($this->control['children'])) {
                foreach ($this->control['children'] as $name) {
                    if (is_string($name))
                        $control = $this->cloneControl($store->staticContent->open($name));
                    else
                        $control = $name;


                    if (!isset($control['filter']))
                        continue;
                    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $control['filter']))
                        continue;
                    if (!isset($control['name']))
                        continue;
                    if (isset($control['condition']) && !$this->condition($control['condition']))
                        continue;

                    $name = $this->prefix . '_' . $control['name'];
                    $filter = 'eclFilter_' . $control['filter'];
                    if (!isset($control['target']))
                        $control['target'] = $control['name'];

                    if (isset($control['view']))
                        $filter::view($this, $control, $name);
                    else
                        $filter::create($this, $control, $name);
                }
            }
        }
        $form = new eclMod_form($this->page);
        $form->children = $this->children;
        return $form;
    }

    public function alert(): eclMod
    {
        $alert = new eclMod_form_alert($this->page);
        $alert->data = $this->errorMessage;
        return $alert;
    }

    public function error(): bool
    {
        return $this->errorStatus > 0;
    }

    public function errorClear()
    {
        $this->errorStatus = 0;
        $this->errorMessage = [];
    }
    private function condition(string $condition): bool
    {
        return isset($this->flags[$condition]) && $this->flags[$condition];
    }

    public function submited(): bool
    {
        static $received;
        if (!isset($received)) {
            $received = false;
            $start = strlen($this->prefix);
            foreach (array_keys($this->page->received) as $key) {
                if (substr($key, 0, $start) === $this->prefix) {
                    $received = true;
                    return true;
                }
            }
        }
        return $received;
    }

    public function removeLanguage(string $lang)
    {
        if (!isset($this->data['text']))
            return;
        foreach (array_keys($this->data['text']) as $key) {
            if (isset($this->data['text'][$key][$lang]))
                unset($this->data['text'][$key][$lang]);
        }
    }

    public function getField(string $target): mixed
    {
        if (!strlen($target))
            return false;

        $path = explode('.', $target);
        $length = count($path);
        $found = [$this->data];
        for ($i = 0; $i < $length; ) {
            $field = $path[$i];
            if (!isset($found[$i][$field]))
                return false;
            $return = $found[$i][$field];
            $i++;
            if ($length == $i)
                return $return;
            $found[$i] = $return;
        }
        return $return;
    }

    public function setField(string $target, mixed $value = false): void
    {
        if (!strlen($target))
            return;

        $path = explode('.', $target);
        $length = count($path);
        do { // its not a loop
            if ($length == 1) {
                $this->data[$path[0]] = $value;
                break;
            }
            if (!isset($this->data[$path[0]]))
                $this->data[$path[0]] = [];

            if ($length == 2) {
                $this->data[$path[0]][$path[1]] = $value;
                break;
            }
            if (!isset($this->data[$path[0]][$path[1]]))
                $this->data[$path[0]][$path[1]] = [];

            if ($length == 3) {
                $this->data[$path[0]][$path[1]][$path[2]] = $value;
                break;
            }

            if (!isset($this->data[$path[0]][$path[1]][$path[2]]))
                $this->data[$path[0]][$path[1]][$path[2]] = [];

            if ($length == 4) {
                $this->data[$path[0]][$path[1]][$path[2]][$path[3]] = $value;
                break;
            }
            if (!isset($this->data[$path[0]][$path[1]][$path[2]][$path[3]]))
                $this->data[$path[0]][$path[1]][$path[2]][$path[3]] = [];

            if ($length == 5) {
                $this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]] = $value;
                break;
            }
            if (!isset($this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]]))
                $this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]] = [];

            if ($length == 6) {
                $this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]][$path[5]] = $value;
                break;
            }

            break;
        }
        while ('Ill never be evaluated');

        if ($value === false) {
            switch ($length) {
                case 6:
                    if (!$this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]][$path[5]])
                        unset($this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]][$path[5]]);

                case 5:
                    if (!$this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]])
                        unset($this->data[$path[0]][$path[1]][$path[2]][$path[3]][$path[4]]);

                case 4:
                    if (!$this->data[$path[0]][$path[1]][$path[2]][$path[3]])
                        unset($this->data[$path[0]][$path[1]][$path[2]][$path[3]]);

                case 3:
                    if (!$this->data[$path[0]][$path[1]][$path[2]])
                        unset($this->data[$path[0]][$path[1]][$path[2]]);

                case 2:
                    if (!$this->data[$path[0]][$path[1]])
                        unset($this->data[$path[0]][$path[1]]);

                case 1:
                    if (!$this->data[$path[0]])
                        unset($this->data[$path[0]]);
            }
        }
    }

    public function appendChild(mixed $data): eclEngine_child
    {
        $child = new eclEngine_child($this->page, $data);
        $this->children[] = $child;
        return $child;
    }

    public function setErrorMessage(array $control, string $name, array|string $message): void
    {
        global $store;

        if ($this->errorStatus > 0)
            return;

        if (is_string($message)) {
            $this->errorMessage = $this->cloneControl($store->staticContent->open($message));
        } else {
            $this->errorMessage = $this->cloneControl($message);
        }

        $filter = 'eclFilter_' . $control['filter'];
        $filter::create($this, $control, $name);

        $next = $this->cloneControl($store->staticContent->open('form_command_next'));
        eclFilter_form_command::create($this, $next, 'save');

        $back = $this->cloneControl($store->staticContent->open('form_link_back'));
        eclFilter_form_link::create($this, $back, 'back');

        $this->errorStatus = 2;
    }

    private function cloneControl(array $control): array
    {
        $cloned = [];
        foreach ($control as $key => $value) {
            if ($key === 'flags' or $key === 'text') {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        $cloned[$subKey] = $subValue;
                    }
                }
            } else {
                $cloned[$key] = $value;
            }
        }
        return $cloned;
    }

    public function sessionStore()
    {
        $this->page->session[$this->sessionKey] = [
            'data' => $this->data,
            'received' => $this->received,
            'expires' => TIME + 604800
        ];
    }

    public function sessionRestore()
    {
        if (isset($this->page->session[$this->sessionKey]['data'])) {
            $data = $this->page->session[$this->sessionKey]['data'];
            foreach ($data as $key => $value) {
                if ($key === 'text' || $key === 'flags' && is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        $this->data[$key][$subKey] = $subValue;
                    }
                } else {
                    $this->data[$key] = $value;
                }
            }
        }
        if (isset($this->page->session[$this->sessionKey]['received'])) {
            $this->received = array_replace($this->page->session[$this->sessionKey]['received'], $this->received);
        }
    }

    public function sessionClear()
    {
        unset($this->page->session[$this->sessionKey]);
    }

}
