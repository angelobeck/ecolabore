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
        else
            return false;
    }

}
