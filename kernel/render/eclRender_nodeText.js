
class eclRender_nodeText extends eclRender_node {

    create(parentElement, insertBeforeMe) {
        this.element = document.createTextNode(this.value);
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
