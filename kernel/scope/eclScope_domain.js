
class eclScope_domain extends eclScope {
    static getScope(page, value, argument) {
        var data = page.domain.data;
        if (data.text && data.text[argument])
            return data.text[argument];
        if (data[argument])
            return data[argument];
        else
            return '';
    }

}
