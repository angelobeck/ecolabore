
class eclScope_value extends eclScope {

    static getScope(page, value, argument) {
        var scope = page.selectLanguage(value);
        return scope.value ?? '';
    }

}

