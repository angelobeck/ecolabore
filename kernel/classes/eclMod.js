
class eclMod {
    children = [];
    data;

    constructor(data = {}) {
        this.data = data;
    }

    connectedCallback() { }

    refreshCallback() {}

    renderedCallback() { }

    disconnectedCallback() { }

    appendChild(fromData) {
        var child = new eclEngine_child(this.page, fromData);
        this.children.push(child);
        return child;
    }

}
