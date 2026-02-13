
class eclMod_modList extends eclMod {

    get _children_() {
        this.children = [];
        var children = page.application.children();
        for (const child of children) {
            if(!page.access(child.access, child.groups))
                continue;
            if ((child.data.flags && child.data.flags.modList_show) || child.data.id) {
                this.appendChild(child.data)
                    .url(child.path);
            }
        }
        return this.children;
    }

    get _showModule_() {
        return page.application.children().length > 0;
    }
}
