Ext.namespace('Brightfield.Order');

Brightfield.Order.TabPanel = Ext.extend(MODx.VerticalTabs, {
    constructor: function (config) {
        config = config || {};
        config.order_id = config.order_id || null;

        config = Ext.apply({
            hidden: true,
            deferredRender: false,
            action: 'mgr/order/getTabs'
        }, config);

        Brightfield.Order.TabPanel.superclass.constructor.call(this, config);
    },

    initComponent: function () {
        this.store = new Ext.data.JsonStore({
            url: this.url,
            baseParams: {
                action: this.action,
                order_id: this.order_id
            },
            fields: ['id', 'title','xtype'],
            root: 'results',
            totalProperty: 'total',
            remoteSort: false,
            autoDestroy: true,
        });

        Brightfield.Order.TabPanel.superclass.initComponent.call(this);
        this.addEvents('beforeRefresh', 'afterRefresh', 'change', 'removeTab');
        this.store.on('load', this.refresh, this);
    },

    onRender: function (ct, position) {
        Brightfield.Order.TabPanel.superclass.onRender.call(this, ct, position);
        this.reload();
    },

    reload: function() {
        this.fireEvent('beforeRefresh');
        this.store.load();
    },

    refresh: function() {
        var records = this.store.getRange(),
            tb = this;

        tb.items.each(function(item, index){
            tb.remove(item)
        });

        if(this.store.getCount() == 0) {
            tb.hide();
            this.fireEvent('afterRefresh');
            return;
        }

        Ext.each(records, function(rec) {
            tb.add(new Ext.Panel({
                title: rec.get('title'),
                items: [{
                    xtype: rec.get('xtype'),
                    boxMinHeight: 500,
                    url: tb.url,
                    order_id: tb.order_id,
                    kit_id: rec.get('id'),
                    tbar: ['->',{
                        xtype: 'button',
                        text: 'Удалить',
                        handler: function() {
                            this.fireEvent('removeTab', rec.get('id'));
                        }, scope: tb
                    }],
                    listeners: {
                        onChange: function(r) {
                            this.fireEvent('change', r);
                        }, scope: tb
                    }
                }],
                kit_id: rec.get('id'),
            }));
        });
        this.doLayout();
        this.setActiveTab(0)
        this.show();
        this.fireEvent('afterRefresh');
    },
});
Ext.reg('br-order-tabpanel', Brightfield.Order.TabPanel);