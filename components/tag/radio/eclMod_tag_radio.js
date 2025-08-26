
class eclMod_tag_radio extends eclMod {
    legend = '';
    name = '';
    options = [];
    value = '';

    index = -1;
    indexMonitor = -1;

    connectedCallback() {
        this.api('legend');
        this.api('name');
        this.api('options');
        this.api('value');
        this.track('index');
        this.track('indexMonitor');
    }

    get _options_() {
        if (!Array.isArray(this.options))
            return [];

        if(this.index === -1) {
for(let i = 0; i < this.options.length; i++) {
    const option = this.options[i];
    if(option.value === this.value) {
        this.index = i;
        this.indexMonitor = i;
    }
}
        }

        return this.options.map((item, index) => {
            var tabindex = '-1';
            if(this.index === -1 && index === 0)
tabindex = '0';
            else if (this.index === index)
                tabindex = '0';
            else if (this.indexMonitor === index)
                tabindex = '0';

            var checked = false;
            if (this.index === index)
                checked = true;

            return {
                index: index,
                label: item.label || '',
                checked: checked,
                tabindex: tabindex
            };
        });
    }

    handleClick(event) {
        var index = parseInt(event.currentTarget.dataset.index);
        var value = this.options[index].value || '';
        this.index = index;
        this.dispatchEvent(new CustomEvent('change', {
            detail: {
                name: this.name,
                index: index,
                value: value
            }
        }));
    }

    renderedCallback() {
        if (this.indexMonitor !== this.index) {
            this.indexMonitor = this.index;
            this.radiogroupElement.children[this.index].focus();
        }
    }

    handleKeyDown(event) {
        if (event.altKey || event.ctrlKey || event.shiftKey || event.metaKey)
            return;

        if(this.index === -1)
            this.index = 0;

        switch (event.key) {
            case "ArrowUp":
            case "ArrowLeft":
                if (this.index > 0)
                    this.index--;
                else
                    this.index = this.options.length - 1;
                break;

            case "ArrowDown":
            case "ArrowRight":
                this.index++;
                if (this.index === this.options.length)
                    this.index = 0;
                break;
        }
    }

}
