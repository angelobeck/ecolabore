
class eclMod_tag_icon extends eclMod {
    svg = '';
    divElement;
    svgElement;
    rendered = false;
    raw = '';

    connectedCallback() {
        this.api('svg');
        this.track('raw');
    }

    renderedCallback() {
        if (!this.rendered) {
            this.rendered = true;
            this.divElement.innerHTML = this.svg;
            this.raw = this.divElement.innerHTML;
        }
    }

}
