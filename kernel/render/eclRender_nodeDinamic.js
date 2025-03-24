
class eclRender_nodeDinamic extends eclRender_node {

    create(parentElement, insertBeforeMe) {
        var value = this.component.getProperty(this.value, true);
        this.element = document.createTextNode(value);
        parentElement.insertBefore(this.element, insertBeforeMe);
    }

    refresh() {
        var value = this.component.getProperty(this.value, true);
        this.element.data = value;
    }

    remove() {
        if (this.element) {
            var parentElement = this.element.parentElement;
            parentElement.removeChild(this.element);
            this.element = false;
        }
    }

}
