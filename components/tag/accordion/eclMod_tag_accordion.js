
class eclMod_tag_accordion extends eclMod {
    label = '';
    style = '';

    expanded = false;

    connectedCallback() {
        this.api('label');
        this.api('style');
        this.track('expanded');
    }

    get _expanded_() {
        return this.expanded ? 'true' : 'false';
    }
    handleClick() {
        this.expanded = !this.expanded;
    }

}
