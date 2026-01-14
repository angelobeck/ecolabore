
class eclRender_nodeEscape extends eclRender_node {

    create(parentElement, insertBeforeMe) {
        var value = this.staticAttributes.value;
        value = value.replace(/\#q/g, '"');
        this.element = document.createTextNode(value);
        parentElement.insertBefore(this.element, insertBeforeMe);
    }

    refresh() {
    }

    remove() {
        if (this.element) {
            var parentElement = this.element.parentElement;
            parentElement.removeChild(this.element);
            this.element = false;
        }
    }

}
