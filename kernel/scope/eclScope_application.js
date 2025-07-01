
class eclScope_application extends eclScope {
    static getScope(page, value, argument) {
        var data = page.application.data;
        if (data.text && data.text[argument])
            return data.text[argument];
        if (data[argument])
            return data[argument];
        if (page.application[argument])
            return page.application[argument];
        else
            return '';
    }

}
