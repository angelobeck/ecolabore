
class eclCom_comList extends eclCom {

    get _children_() {
        this.children = [];
        var children = page.application.children();
        for (const child of children) {
            if(!page.access(child.access, child.groups))
                continue;
            if ((child.data.flags && child.data.flags.comList_show) || child.data.id) {
                this.appendChild(child.data)
                    .url(child.path);
            }
        }
        return this.children;
    }

    get _showComponent_() {
        return page.application.children().length > 0;
    }
}
