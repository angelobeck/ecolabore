
class eclRender_node {
    component;
    parent;
    value;
    staticAttributes = [];
    dinamicAttributes = [];
    children = [];
    closingTag = true;
    status;

    constructor(parent, value) {
        this.parent = parent;
        this.value = value;
        if (parent !== undefined) {
            this.component = parent.component;
        }
    }

    createChildren(children, parentElement, insertBeforeMe) {
        if (!Array.isArray(children)) {
            return;
        }
        var index;
        var node;
        var scope = false;
        for (index = 0; index < children.length; index++) {
            node = children[index];
            scope = this.component.getScope(node);
            if (scope) {
                node.component.scopes.unshift(scope);
            }

            if (node.dinamicAttributes["if:true"] || node.dinamicAttributes["if:false"]) {
                node.conditionEndingComment = document.createComment(" if ");
                parentElement.insertBefore(node.conditionEndingComment, insertBeforeMe);
                node.status = this.checkConditionStatus(node);
                if (node.status) {
                    node.create(parentElement, node.conditionEndingComment);
                }
            } else {
                node.create(parentElement, insertBeforeMe);
            }
            if (scope) {
                node.component.scopes.shift();
            }
        }
    }

    refreshChildren(children) {
        if (!Array.isArray(children)) {
            return;
        }
        var index;
        var node;
        var scope = false;
        for (index = 0; index < children.length; index++) {
            node = children[index];
            scope = this.component.getScope(node);
            if (scope) {
                node.component.scopes.unshift(scope);
            }

            if (node.dinamicAttributes["if:true"] || node.dinamicAttributes["if:false"]) {
                let status = this.checkConditionStatus(node);
                if (!node.status && status) {
                    node.create(node.conditionEndingComment.parentElement, node.conditionEndingComment);
                } else if (node.status && !status) {
                    node.remove();
                } else if (node.status) {
                    node.refresh();
                }
                node.status = status;
            } else {
                node.refresh();
            }
            if (scope) {
                node.component.scopes.shift();
            }

        }
    }

    removeChildren(children) {
        if (!Array.isArray(children)) {
            return;
        }
        var index;
        var node;
        for (index = 0; index < children.length; index++) {
            node = children[index];
            if (node.dinamicAttributes["if:true"] || node.dinamicAttributes["if:false"]) {
                if (node.status) {
                    node.remove();
                }
                const parentElement = node.conditionEndingComment.parentElement;
                parentElement.removeChild(node.conditionEndingComment);
                node.conditionEndingComment = false;
            } else {
                node.remove();
            }
        }
    }

    createLoop(element, insertBeforeMe) {
        var loopChildren;
        var target;
        const loopIterator = this.component.getProperty(this.dinamicAttributes["for:each"]);
        if (!Array.isArray(loopIterator)) {
            return;
        }
        if (this.dinamicAttributes["for:item"]) {
            target = this.dinamicAttributes["for:item"];
        } else if (this.staticAttributes["for:item"]) {
            target = this.staticAttributes["for:item"];
        } else {
            target = "item";
        }
        this.loopChildren = [];
        this.component.scopes.unshift({});
        for (let iteratorIndex = 0; iteratorIndex < loopIterator.length; iteratorIndex++) {
            this.component.scopes[0][target] = loopIterator[iteratorIndex];
            loopChildren = this.cloneChildren(this.children);
            this.loopChildren.push(loopChildren);
            this.createChildren(loopChildren, element, insertBeforeMe);
        }
        this.component.scopes.shift();
    }

    refreshLoop(element, insertBeforeMe) {
        var loopChildren;
        var target;
        const loopIterator = this.component.getProperty(this.dinamicAttributes["for:each"]);
        if (!Array.isArray(loopIterator)) {
            loopIterator = [];
        }

        if (this.dinamicAttributes["for:item"]) {
            target = this.dinamicAttributes["for:item"];
        } else if (this.staticAttributes["for:item"]) {
            target = this.staticAttributes["for:item"];
        } else {
            target = "item";
        }

        while (this.loopChildren.length > loopIterator.length) {
            loopChildren = this.loopChildren.pop();
            while (loopChildren.length > 0) {
                loopChildren.pop().remove();
            }
        }

        this.component.scopes.unshift({});
        for (let iteratorIndex = 0; iteratorIndex < loopIterator.length; iteratorIndex++) {
            this.component.scopes[0][target] = loopIterator[iteratorIndex];
            if (iteratorIndex < this.loopChildren.length) {
                loopChildren = this.loopChildren[iteratorIndex];
                this.refreshChildren(loopChildren);
            } else {
                loopChildren = this.cloneChildren(this.children);
                this.loopChildren.push(loopChildren);
                this.createChildren(loopChildren, element, insertBeforeMe);
            }
        }
        this.component.scopes.shift();
    }

    checkConditionStatus(node) {
        var value;
        if (node.dinamicAttributes["if:true"]) {
            value = node.component.getProperty(node.dinamicAttributes["if:true"]);
            if (node.staticAttributes["if:compare"]) {
                return value.toString() == node.staticAttributes["if:compare"];
            } else if (node.dinamicAttributes["if:compare"]) {
                return value == node.component.getProperty(node.dinamicAttributes["if:compare"]);
            } else if (
                value === undefined ||
                value === null ||
                value === false ||
                value === "false" ||
                value === 0 ||
                value === "" ||
                (Array.isArray(value) && value.length === 0) ||
                (typeof (value) === "object" && Object.keys(value).length === 0)
            ) {
                return false;
            } else {
                return true;
            }
        } else if (node.dinamicAttributes["if:false"]) {
            value = node.component.getProperty(node.dinamicAttributes["if:false"]);
            if (node.staticAttributes["if:compare"]) {
                return value.toString() != node.staticAttributes["if:compare"];
            } else if (node.dinamicAttributes["if:compare"]) {
                return value != node.component.getProperty(node.dinamicAttributes["if:compare"]);
            } else if (
                value === undefined ||
                value === null ||
                value === false ||
                value === "false" ||
                value === 0 ||
                value === "" ||
                (Array.isArray(value) && value.length === 0) ||
                (typeof (value) === "object" && Object.keys(value).length === 0)
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    cloneChildren(children) {
        var clonedChild;
        var result = [];
        for (let i = 0; i < children.length; i++) {
            let child = children[i];
            const type = child.constructor.name;
            eval("clonedChild = new " + type + "(this, child.value);");
            for (let name in child) {
                if (name === "children") {
                    clonedChild.children = this.cloneChildren(child.children);
                } else {
                    clonedChild[name] = child[name];
                }
            }
            result.push(clonedChild);
        }
        return result;
    }

}
