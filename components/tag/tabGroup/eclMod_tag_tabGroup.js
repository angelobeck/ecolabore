
class eclMod_tag_tabGroup extends eclMod {
    registeredTabs = [];
    index = -1;
    length = 0;

    connectedCallback() {
        this.track('index');
        this.track('length');
    }

    subscribe(tab) {
        this.index = 0;
        this.registeredTabs.push(tab);
        this.length = this.registeredTabs.length;
    }

    get _tablist_() {
        return this.registeredTabs.map((tab, index) => {
            return {
                label: tab.label,
                index: index,
                selected: index === this.index
            }
        });
    }

    handleClick(event) {

    }

    handleKeydown(event) {

    }

}
