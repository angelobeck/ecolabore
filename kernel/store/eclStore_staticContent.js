
class eclStore_staticContent {
    open(name) {
        if (staticContents[name]) {
            return staticContents[name];
        } else {
            return {};
        }
    }
}
