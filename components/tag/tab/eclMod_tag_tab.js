
class eclMod_tag_tab extends eclMod {
    index;
    label;
    selected = false;
    expanded = false;

    connectedCallback() {
        this.api('label');
        this.api('selected');
        this.track('expanded');

        this.node.parent.component.module.subscribe(this);
    }


}
