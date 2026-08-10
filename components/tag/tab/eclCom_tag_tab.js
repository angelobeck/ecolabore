
class eclCom_tag_tab extends eclCom {
    index;
    label;
    selected = false;
    expanded = false;

    connectedCallback() {
        this.api('label');
        this.api('selected');
        this.track('expanded');

        this.node.parent.component.component.subscribe(this);
    }


}
