
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
            if (key === 'children' || key === 'commands') {
                result[key] = [];
                for (let j = 0; j < data[key].length; j++) {
                    const child = data[key][j];
                    if (child.startsWith('~'))
                        result[key].push(prefix + child.substring(1));
                    else
                        result[key][j] = child;
                }
            } else {
                result[key] = data[key];
            }
        }
        return result;
    }

}
