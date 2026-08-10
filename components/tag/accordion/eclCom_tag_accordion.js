
class eclCom_tag_accordion extends eclCom {
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

    
    handleKeydown(event) {
        if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey)
            return;

        switch (event.key) {
            case ' ':
            case 'Enter':
                this.handleClick();
        }
    }

}
