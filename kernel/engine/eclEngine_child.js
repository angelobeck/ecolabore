
class eclEngine_child {
    page;
    data = {};
    children = [];

    constructor(page, fromData) {
        this.page = page;
        if (isset(fromData)) {
            if (fromData.constructor.name == 'eclEngine_application')
                this.data = fromData.data;
            else if (typeof (fromData) === 'string')
                this.data = store.staticContent.open(fromData);
            else
                this.data = fromData;
        }
    }

    appendChild(fromData) {
        var child = new eclEngine_child(this.page, fromData);
        this.children.push(child);
        return child;
    }

    swapTitle() {
        if (this.data.text && this.data.text.label) {
            this.data.text.title = this.data.text.label;
        }
        return this;
    }

    url(path = true, lang = true, action = true) {
        this.data.url = page.url(path);
        return this;
    }

    current(value) {
        this.data.current = value;
        return this;
    }

}
