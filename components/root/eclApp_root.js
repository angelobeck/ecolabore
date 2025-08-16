
class eclApp_root extends eclApp {

    static constructorHelper(me) {
        me.map =[...getMap('root'), 'systemUsers', 'systemNotFound'];
        me.groups.push(new eclGroup_root());
    }

}
