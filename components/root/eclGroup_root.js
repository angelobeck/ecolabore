
class eclGroup_root extends eclGroup {

    check(page, level) {
        if (!page.session.user || !page.session.user.name)
        return false;
        if (level === 1)
            return true;
        if (page.session.user.groups && page.session.user.groups['-root'] && page.session.user.groups['-root'] === 4)
            return true;
        else
            return false;
    }

}
