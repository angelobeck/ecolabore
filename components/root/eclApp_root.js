
class eclApp_root extends eclApp {

    static constructorHelper(me) {
        me.map =[...getMap('root'), 'systemUsers', 'systemNotFound'];
    }

}
