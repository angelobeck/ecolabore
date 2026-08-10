
class eclRender_nodePaste extends eclRender_node {
    value = 'paste';
    parentElement;
    insertBeforeMe

    create(parentElement, insertBeforeMe) {
        this.parentElement = parentElement;
        this.insertBeforeMe = insertBeforeMe;
        var target = this.findTarget();
        var name = this.findName();

        if (!page.cuts[target]) {
            page.cuts[target] = {
                names: {}
            };
        }

        if (!page.cuts[target].names[name]) {
            page.cuts[target].names[name] = {
                children: [],
                pasteNodes: []
            };
        }

        this.children = page.cuts[target].names[name].children;
        this.createChildren(this.children, parentElement, insertBeforeMe);

        page.cuts[target].names[name].pasteNodes.push(this);
    }

    refresh() {
        this.refreshChildren(this.children);
    }

    remove() {
        var target = this.findTarget();
        var name = this.findName();

        this.removeChildren(this.children);

        if(!page.cuts[target] || !page.cuts[target].names[name])
            return;

        var pasteNodes = page.cuts[target].names[name].pasteNodes;
        for(let i = 0; i < pasteNodes.length; i++) {
            if(pasteNodes[i] === this) {
                pasteNodes.slice(i, i + 1);
                break;
            }
        }
    }

    findName() {
        if (this.staticAttributes['name'])
            return this.staticAttributes['name'];
        else if (this.dinamicAttributes['name']) {
            let value = this.mediator.getProperty(this.dinamicAttributes['name']);
            if (value && typeof (value) === "string")
                return value;
        }
        return 'body';
    }

    findTarget() {
        if (this.staticAttributes['target'])
            return this.staticAttributes['target'];
        else if (this.dinamicAttributes['target']) {
            let value = this.mediator.getProperty(this.dinamicAttributes['target']);
            if (value && typeof (value) === "string")
                return value;
        }
        return 'document';
    }

}
