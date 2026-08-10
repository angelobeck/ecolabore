
class eclRender_nodeCut extends eclRender_node {
    value = 'cut';

    create(parentElement, insertBeforeMe) {
        var name = this.findName();
        var target = this.findTarget();

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

        page.cuts[target].names[name].children = this.cloneChildren(this.children);

        const pasteNodes = page.cuts[target].names[name].pasteNodes;

        for (let i = 0; i < pasteNodes.length; i++) {
            const node = pasteNodes[i];
            node.removeChildren(node.children);
            node.children = this.cloneChildren(this.children);
            node.createChildren(node.children, node.parentElement, node.insertBeforeMe);
        }
    }

    refresh() {
        var name = this.findName();
        var target = this.findTarget();

        if (!page.cuts[target] || !page.cuts[target].names[name])
            return;

        const pasteNodes = page.cuts[target].names[name].pasteNodes;

        for (let i = 0; i < pasteNodes.length; i++) {
            const node = pasteNodes[i];
            node.refresh();
        }
    }

    remove() {
        var name = this.findName();
        var target = this.findTarget();

        if (!page.cuts[target] || !page.cuts[target].names[name])
            return;

        page.cuts[target].names[name].children = [];

        const pasteNodes = page.cuts[target].names[name].pasteNodes;

        for (let i = 0; i < pasteNodes.length; i++) {
            const node = pasteNodes[i];
            node.removeChildren(node.children);
            node.children = [];
        }

        if (pasteNodes.length == 0) {
            delete page.cuts[target].names[name];
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
