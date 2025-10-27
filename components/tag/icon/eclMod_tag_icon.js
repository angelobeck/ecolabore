
class eclMod_tag_icon extends eclMod {
    svg = '';
    svgElement;
    rendered = false;

    connectedCallback() {
        this.api('svg');
    }

    renderedCallback() {
        if (!this.rendered) {
            this.rendered = true;
            this.svgElement.innerHTML = this.svg;
        }
    }

}
