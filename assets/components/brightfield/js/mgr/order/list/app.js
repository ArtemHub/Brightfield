Ext.namespace('Brightfield.Order');

Brightfield.Order.ListPage = Ext.extend(Ext.Container, {
    constructor: function(config) {
        config = Ext.apply({
            autoEl: 'div',
            layout: 'anchor',
            cls: 'br-order-manager',
        }, config);

        Brightfield.Order.ListPage.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        this.items = [{
            xtype: 'br-order-header',
            title: 'Менеджер заказов',
            currency: Brightfield.currency_rate,
            buttons: this._getButtons()
        }, {
            xtype: 'modx-tabs',
            cls: 'order-tabpanel',
            items: [{
                title: 'Заказы',
                items: [{
                    xtype: 'br-grid-order',
                    url: this.url
                }]
            }]
        }]

        Brightfield.Order.ListPage.superclass.initComponent.call(this);
        this.addEvents('create');
    },

    afterRender: function() {
        Brightfield.Order.ListPage.superclass.afterRender.call(this);
        var app = this;
        this.controller = new Brightfield.controller({
            app: app
        });
    },

    _getButtons: function() {
        return [{
            xtype: 'button',
            text: 'Создать заказ',
            cls: 'x-btn x-btn-small x-btn-icon-small-left primary-button',
            handler: function(btn) {
                this.fireEvent('create', btn);
            }, scope: this
        }]
    }
});
Ext.reg('br-order-list-page', Brightfield.Order.ListPage);