
class eclScope_system extends eclScope {
    static getScope(page, value, argument) {
        var data = root.data;
        if (data.text && data.text[argument])
            return data.text[argument];
        if (data[argument])
            return data[argument];
        else
            return '';
    }

}
