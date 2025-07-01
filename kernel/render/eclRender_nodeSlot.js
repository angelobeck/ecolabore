
class eclRender_nodeSlot extends eclRender_node {

    create(parentElement, insertBeforeMe) {
        if(this.component.slot.length === 0)  {
            return;
        }
        this.children = this.cloneChildren(this.component.slot);
        this.createChildren(this.children, parentElement, insertBeforeMe);
    }

    refresh() {
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
    }

}
