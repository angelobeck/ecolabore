<?php

class eclGroup_root extends eclGroup
{

    public function check(eclEngine_page $page, int $level): bool
    {
        if (!isset($page->session['user']['name']))
            return false;
        else if ($level === 1)
            return true;
        else if ($page->session['user']['name'] === ADMIN_NAME)
            return true;
        else if ($level === 4)
            return false;

        foreach (explode(',', ADMIN_HELPERS) as $helperName) {
            $helperName = trim($helperName);
            if ($page->session['user']['name'] === $helperName) {
                return true;
            }
        }

        return false;
    }

}
