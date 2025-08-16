
class eclStore_staticContent {
    open(name) {
        if (staticContents[name]) {
            return this.addPrefix(staticContents[name], name);
        } else {
            return {};
        }
    }

    addPrefix(data, name) {
        var result = {};
        var lastIndex = name.lastIndexOf('_');
        var prefix = name.substring(0, lastIndex + 1);
        var keys = Object.keys(data);
        for (let i = 0; i < keys.length; i++) {
            const key = keys[i];
            if (key === 'children' || key === 'actions') {
                result[key] = [];
                for (let j = 0; j < data[key].length; j++) {
                    const child = data[key][j];
                    if (child.startsWith('~'))
                        result[key].push(prefix + child.substring(1));
                    else
                        result[key][j] = child;
                }
            } else {
                result[key] = this.escapeString(data[key]);
            }
        }
        return result;
    }

    escapeString(data) {
        if (typeof (data) === 'string')
            return data.replace(/[#]q/g, '"')
                .replace(/[#]0/g, "\0")
                .replace(/[#]n/g, "\n")
                .replace(/[#]r/g, "\r")
                .replace(/[#]t/g, "\t")
                .replace(/[#]b/g, "\\")
                .replace(/[#]c/g, "#");

        if (typeof (data) === 'object') {
            let result = {};
            var keys = Object.keys(data);
            for (let i = 0; i < keys.length; i++) {
                const key = keys[i];
                result[key] = this.escapeString(data[key]);
            }
            return result;
        }

        return data;
    }

}
