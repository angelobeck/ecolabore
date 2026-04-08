
class eclMod_tag_icon extends eclMod {
    svg = '';
    divElement;
    rendered = false;
    svgMonitor = '';

    connectedCallback() {
        this.api('svg');
    }

    refreshCallback() {
        if (this.rendered && this.svgMonitor != this.svg) {
            this.divElement.innerHTML = this.svg;
            this.svgMonitor = this.svg;
        }
    }

    renderedCallback() {
        if (!this.rendered) {
            this.rendered = true;
            this.divElement.innerHTML = this.svg;
            this.svgMonitor = this.svg;
        }
    }

}
