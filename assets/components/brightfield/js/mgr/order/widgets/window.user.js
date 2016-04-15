Ext.namespace('Brightfield.window');

Brightfield.window.CreateUser = Ext.extend(MODx.Window, {
    constructor: function(config) {
        config = config || {};

        config = Ext.apply({
            title: 'Регистрация нового пользователя',
            action: 'mgr/user/create',
            autoHeight: true,
            modal: true,
            fields: this._setFields(),
        }, config);

        Brightfield.window.CreateUser.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        Brightfield.window.CreateUser.superclass.initComponent.call(this);
    },

    _setFields: function() {
        return [{
            xtype: 'textfield',
            fieldLabel: 'Имя',
            name: 'fullname',
            allowBlank: false,
            anchor: '100%',
        },{
            xtype: 'textfield',
            allowBlank: false,
            name: 'phone',
            fieldLabel: 'Телефон (формат 380XXXXXXXXX):',
            anchor: '100%',
            regex: /^\d{12}$/i,
            maskRe : /^\d$/,
            emptyText: '380',
            listeners: {
                focus: function(obj) {
                    var v = obj.getValue();
                    if(v == '') {
                        obj.setValue('380');
                    }
                }
            }
        }, {
            xtype: 'textfield',
            fieldLabel: 'E-mail',
            name: 'email',
            vtype: 'email',
            anchor: '100%',
        }, {
            xtype: 'textfield',
            fieldLabel: 'Город',
            name: 'city',
            anchor: '100%',
        }, {
            xtype: 'textfield',
            fieldLabel: 'Организация',
            name: 'company',
            anchor: '100%',
        }]
    }
});