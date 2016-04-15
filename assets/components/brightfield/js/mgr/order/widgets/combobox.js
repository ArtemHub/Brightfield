Ext.namespace('Brightfield.combo');
Brightfield.combo.OrderStatus = function (config) {
    config = config || {};

    this.kit_id = config.kit_id || false;

    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [
                ['Отменен', 0],
                ['Новый', 1],
                ['В обработке', 2],
                ['На утверждении', 3],
                ['Отправлен', 4],
                ['Выполнен', 5]
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    Brightfield.combo.OrderStatus.superclass.constructor.call(this, config);
};
Ext.extend(Brightfield.combo.OrderStatus, MODx.combo.ComboBox);
Ext.reg('br-order-status', Brightfield.combo.OrderStatus);