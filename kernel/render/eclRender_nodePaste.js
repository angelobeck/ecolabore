
class eclRender_nodePaste extends eclRender_node {
    value = 'paste';

    create(parentElement, insertBeforeMe) {
        var target = this.findTarget();
        var name = this.findName();

if(page.cuts[target] && page.cuts[target].names[name]) {
    this.children = page.cuts[target].names[name];
        this.createChildren(this.children, parentElement, insertBeforeMe);
}
    }

    refresh() {
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
    }

    findName() {
        if (this.staticAttributes['name'])
            return this.staticAttributes['name'];
        else if (this.dinamicAttributes['name']) {
            let value = this.component.getProperty(this.dinamicAttributes['name']);
            if (value && typeof (value) === "string")
                return value;
        }
        return 'body';
    }

    findTarget() {
        if (this.staticAttributes['target'])
            return this.staticAttributes['target'];
        else if (this.dinamicAttributes['target']) {
            let value = this.component.getProperty(this.dinamicAttributes['target']);
            if (value && typeof (value) === "string")
                return value;
        }
        return 'document';
    }

}
