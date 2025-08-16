<?php

class eclEngine_formulary
{
    public eclEngine_page $page;
    public array $data;
    private array $originalData;
    public array $flags = [];
    public string $prefix = 'edit';
    public array $children = [];
    public array $received = [];
    public array $error = [];
    public array $control = [];

    private string $sessionKey;
    private string $currentName;
    private array $currentControl;

    public function __construct(eclEngine_page $page, string|array $control, array &$data, string $prefix)
    {
        global $store;
        $this->page = $page;
        $this->received = $page->received;
        $this->data = $data;
        $this->originalData = &$data;
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
                } else {
                    $control = $this->cloneControl($control);
                }

                if (!isset($control['filter']))
                    continue;
                if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $control['filter']))
                    continue;
                if (isset($control['condition']) && !$this->condition($control['condition']))
                    continue;
                if (isset($control['posteriori']) and $posteriori == 1)
                    continue;
                if (!isset($control['posteriori']) and $posteriori == 2)
                    continue;
                if (isset($control['view']))
                    continue;

                $name = $this->prefix . '_' . ($control['name'] ?? '');
                $filter = 'eclFilter_' . $control['filter'];
                $this->currentName = $name;
                $this->currentControl = $control;

                $filter::save($this, $control, $name);
            }
        }

        return !$this->error;
    }

    public function sanitize(array $input): bool
    {
        global $store;
        $this->error = [];
        $this->received = $input;
        if (isset($this->control['children'])) {
            foreach ($this->control['children'] as $control) {
                if (is_string($control)) {
                    $control = $this->cloneControl($store->staticContent->open($control));
                } else {
                    $control = $this->cloneControl($control);
                }

                if (!isset($control['filter']))
                    continue;
                if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $control['filter']))
                    continue;
                if (isset($control['condition']) && !$this->condition($control['condition']))
                    continue;
                if (isset($control['view']))
                    continue;

                $this->currentName = '';
                $this->currentControl = $control;
                $filter = 'eclFilter_' . $control['filter'];

                $error = $filter::sanitize($this, $control);
                if (!$this->error and $error)
                    $this->error = $error;
            }
        }

        if (!$this->error) {
            foreach ($this->data as $key => $value) {
                $this->originalData[$key] = $value;
            }
        }

        return !$this->error;
    }

    public function create(): eclMod
    {
        global $store;
        $this->children = [];
        if (isset($this->control['children'])) {
            foreach ($this->control['children'] as $control) {
                if (is_string($control))
                    $control = $this->cloneControl($store->staticContent->open($control));
                else
                    $control = $this->cloneControl($control);


                if (!isset($control['filter']))
                    continue;
                if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $control['filter']))
                    continue;
                if (isset($control['condition']) && !$this->condition($control['condition']))
                    continue;

                $name = $this->prefix . '_' . ($control['name'] || '');
                $filter = 'eclFilter_' . $control['filter'];

                if (isset($control['view']) || isset($this->flags['view']))
                    $filter::view($this, $control, $name);
                else
                    $filter::create($this, $control, $name);
            }
        }
        $form = new eclMod_form($this->page);
        $form->children = $this->children;
        return $form;
    }

    public function view(): eclMod
    {
        $this->flags['view'] = true;
        return $this->create();
    }

    private function condition(string $condition): bool
    {
        return isset($this->flags[$condition]) && $this->flags[$condition];
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
            return null;

        $path = explode('.', $target);
        $length = count($path);
        $found = [$this->data];
        for ($i = 0; $i < $length; ) {
            $field = $path[$i];
            if (!isset($found[$i][$field]))
                return null;
            $return = $found[$i][$field];
            $i++;
            if ($length == $i)
                return $return;
            $found[$i] = $return;
        }
        return $return;
    }

    public function getReceived(string $target): mixed
    {
        if (!strlen($target))
            return null;

        $path = explode('.', $target);
        $length = count($path);
        $found = [$this->received];
        for ($i = 0; $i < $length; ) {
            $field = $path[$i];
            if (!isset($found[$i][$field]))
                return null;
            $return = $found[$i][$field];
            $i++;
            if ($length == $i)
                return $return;
            $found[$i] = $return;
        }
        return $return;
    }

    public function setField(string $target, mixed $value = null): void
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
        while (false);

        if ($value === null) {
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

    public function setErrorMessage(string $message, string $value = ''): void
    {
        global $store;

        if ($this->error)
            return;

        $this->error = $this->cloneControl($store->staticContent->open($message));
        $this->error['field_id'] = $this->currentName;
        $this->error['value'] = $value;
        $this->error['control'] = $this->currentControl;
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
