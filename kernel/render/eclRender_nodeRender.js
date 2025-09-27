
class eclRender_nodeRender extends eclRender_node {
    endingElement;
    value = 'render';

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" parse ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        var template = this.findMyTemplate();
        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var tokens = tokenizer.tokenize(template);
        parser.parse(this, tokens, this.component.module);
        this.createChildren(this.children, parentElement, this.endingElement);
    }

    refresh(cancelRefreshCallback = false) {
        this.refreshChildren(this.children);
    }

    remove() {
        this.removeChildren(this.children);
        if (this.endingElement) {
            let parentElement = this.endingElement.parentElement;
            parentElement.removeChild(this.endingElement);
            this.endingElement = false;
        }
    }

    findMyTemplate() {
        if (this.staticAttributes.value)
            return this.staticAttributes.value;
        else if (this.dinamicAttributes.value)
            return this.component.getProperty(this.dinamicAttributes.value);
        else
            return '';
    }

}
