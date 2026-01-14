
class eclRender_nodeRender extends eclRender_node {
    endingElement;
    value = 'render';
    template;

    create(parentElement, insertBeforeMe) {
        this.endingElement = document.createComment(" parse ");
        parentElement.insertBefore(this.endingElement, insertBeforeMe);
        this.template = this.findMyTemplate();
        var tokenizer = new eclRender_tokenizer();
        var parser = new eclRender_parser();

        var tokens = tokenizer.tokenize(this.template);
        parser.parse(this, tokens, this.component.module);
        this.createChildren(this.children, parentElement, this.endingElement);
    }

    refresh() {
        var template = this.findMyTemplate();
        if (this.template == template) {
            this.refreshChildren(this.children);
        } else {
            this.removeChildren(this.children);
            this.template = template;
            var tokenizer = new eclRender_tokenizer();
            var parser = new eclRender_parser();

            var tokens = tokenizer.tokenize(this.template);
            parser.parse(this, tokens, this.component.module);
            this.createChildren(this.children, this.endingElement.parentElement, this.endingElement);
        }
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
