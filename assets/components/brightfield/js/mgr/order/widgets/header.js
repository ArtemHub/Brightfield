Ext.namespace('Brightfield.Order');

Brightfield.Order.Header = Ext.extend(Ext.BoxComponent, {
    constructor: function(config) {
        config = config || {};

        config.title = config.title || '';
        config.currency = config.currency || {};

        config.buttons = config.buttons || [];

        config = Ext.apply({
            autoEl: {
                tag: 'div',
                cls: 'br-order-header'
            }
        }, config);

        Brightfield.Order.Header.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        Brightfield.Order.Header.superclass.initComponent.call(this);
        this.addEvents('actionSave', 'actionGenerate');
    },

    onRender: function(ct, position) {
        Brightfield.Order.Header.superclass.onRender.call(this, ct, position);
        var dh = Ext.DomHelper,
            tpl = dh.createTemplate({tag: 'div', cls: 'currency {0}', html: '<span>{1}</span> {2}'});

        var el = dh.append(this.el, {
            tag: 'div', cls: 'wrapper', children: [
                {tag: 'div', cls: 'title-ct'},
                {tag: 'div', cls: 'currency-rate-ct', html: 'Курс валют:'},
                {tag: 'div', cls: 'buttons-ct'},
            ]
        });
        for(var key in this.currency) {
            tpl.append(Ext.query('.currency-rate-ct', el)[0], [key, key.toUpperCase(), this.currency[key]]);
        }

        if(this.buttons.length) {
            for(var key in this.buttons) {
                var node = Ext.query('.buttons-ct', el)[0];

                this.buttons[key].renderTo = node;
                this.buttons[key] = MODx.load(this.buttons[key]);
            }
        }
        this.setTitle(this.title);
    },

    setTitle: function(text) {
        Ext.query('.title-ct', this.el.dom)[0].innerHTML = '<h1>' + text + '</h1>';
    },
});
Ext.reg('br-order-header', Brightfield.Order.Header);