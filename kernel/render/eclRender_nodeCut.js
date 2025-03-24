
class eclRender_nodeCut extends eclRender_node {
value = 'cut';
    
    create(parentElement, insertBeforeMe) {
        var name = this.findName();
        var target = this.findTarget();

        if (!page.cuts[target])
            page.cuts[target] = {
                names: {}
            };
        page.cuts[target].names[name] = this.children;
    }

    refresh() {
    }

    remove() {
        var name = this.findName();
        var target = this.findTarget();

        if (!page.cuts[target])
            page.cuts[target] = {
                names: {}
            };
        page.cuts[target].names[name] = [];
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
