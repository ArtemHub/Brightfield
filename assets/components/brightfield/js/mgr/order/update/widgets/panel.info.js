Ext.namespace('Brightfield.Order.widgets.widgets');

Brightfield.Order.widgets.Info = Ext.extend(Ext.BoxComponent, {
    constructor: function(config) {
        config = config || {};
        config.items = config.items || [];

        config = Ext.apply({
            autoEl: {
                tag: 'div',
                cls: 'order-panel-info'
            }
        }, config);

        Brightfield.Order.widgets.Info.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        Brightfield.Order.widgets.Info.superclass.initComponent.call(this);
    },

    onRender: function(ct, position) {
        Brightfield.Order.widgets.Info.superclass.onRender.call(this, ct, position);
        var dh = Ext.DomHelper,
            tpl = dh.createTemplate({tag: 'div', cls: '{0}-ct', html: '<div class="field"></div>{1}'});;

        for(var key in this.items) {
            tpl.append(this.el.dom, [this.items[key].name, this.items[key].fieldLabel]);
        }

        if(this.items.length) {
            for(var key in this.items) {
                if(typeof this.items[key] === 'object') {
                    this.items[key].renderTo = Ext.query('.'+this.items[key].name+'-ct .field', this.el.dom)[0];
                    this.items[key] = Ext.create(this.items[key]);
                }
            }
        }
    },
});
Ext.reg('br-order-panel-info', Brightfield.Order.widgets.Info);