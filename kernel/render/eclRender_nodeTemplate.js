
class eclRender_nodeTemplate extends eclRender_node {
    loopChildren = [];
    parentElement;
    endingElement;
    value = 'template';

    create(parentElement, insertBeforeMe) {
        this.parentElement = parentElement;
        this.endingElement = document.createComment(" template ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        if (this.dinamicAttributes["for:each"]) {
            this.createLoop(parentElement, this.endingElement);
        } else {
            this.createChildren(this.children, parentElement, this.endingElement);
        }
    }

    refresh() {
        if (this.dinamicAttributes["for:each"]) {
            this.refreshLoop(this.parentElement, this.endingElement);
        } else {
            this.refreshChildren(this.children);
        }
    }

    remove() {
        if (this.dinamicAttributes["for:each"]) {
            while (this.loopChildren.length > 0) {
                this.removeChildren(this.loopChildren.pop());
            }
        } else {
            this.removeChildren(this.children);
        }
        var parentElement = this.endingElement.parentElement;
        parentElement.removeChild(this.endingElement);
        this.endingElement = false;
    }

}
