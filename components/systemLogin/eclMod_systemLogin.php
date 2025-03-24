<?php

class eclMod_systemLogin extends eclMod
{
    public string $action;
    public bool $isConnected = false;
    public string $name = '';

    public function connectedCallback(): void
    {
        global $io, $store, $applications;
        $this->action = $this->page->url();
        if (isset($this->page->session['user_name'])) {
            $this->isConnected = true;
            $this->action = $this->page->url($this->page->application->parent->path);
            $this->name = $this->page->session['user_name'];
            return;
        }

        $received = $this->page->received;
        if (isset($received['name'][0]) and isset($received['password'][0])) {
            $row = $this->selectUser($received['name']);
            if ($row) {
                $passwordCheck = eclIo_database::passwordCheck($received['password'], $row['password']);
                if ($passwordCheck) {
                    $this->isConnected = true;
                    $this->action = $this->page->url($this->page->application->parent->path);
                    $this->page->session['user_id'] = $row['id'];
                    $this->page->session['user_name'] = $row['name'];
                    $this->page->user = $applications->child(SYSTEM_USERS_URI)->child($row['name']);
                    $this->name = $this->page->session['user_name'];
                }
            }
        }
    }

    private function selectUser(string $name): array
    {
        global $io, $store;
        $numbers = eclIo_convert::extractNumbers($name);

        if (strlen($numbers) >= 12) {
            $rows = $io->database->select($store->user, ['phone' => $numbers]);
            if ($rows)
                return $rows[0];

            return [];
        }

        if (strlen($numbers) == 11) {
            $rows = $io->database->select($store->user, ['document' => $numbers]);
            if ($rows)
                return $rows[0];

            return [];
        }

        if (preg_match('/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+$/', $name)) {
            $rows = $io->database->select($store->user, ['mail' => $name]);
            if ($rows)
                return $rows[0];

            return [];
        }

        if (preg_match('/^[a-z0-9_-]+$/', $name)) {
            $rows = $io->database->select($store->user, ['name' => $name]);
            if ($rows)
                return $rows[0];
        }

        return [];
    }

}
