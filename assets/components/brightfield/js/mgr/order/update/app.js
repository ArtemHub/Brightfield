Ext.namespace('Brightfield.Order');

Brightfield.Order.UpdatePage = Ext.extend(Ext.Container, {
    constructor: function(config) {
        config.record = Brightfield.record || {};

        config = Ext.apply({
            autoEl: 'div',
            layout: 'anchor',
            cls: 'br-order-panel',
        }, config);

        Brightfield.Order.UpdatePage.superclass.constructor.call(this, config);

        this.mask = new Ext.LoadMask(this.el, {
            msg: 'Сохранение ...'
        });
    },

    initComponent: function() {
        this.windows = this._getWindows();

        this.items = [{
            xtype: 'br-order-header',
            title: '# ' + this.record.num,
            currency: Brightfield.currency_rate,
            buttons: this._getButtons()
        }, {
            xtype: 'br-order-form',
            record: this.record
        }, {
            xtype: 'br-order-importer',
            url: this.url,
        }, {
            xtype: 'br-order-tabpanel',
            url: this.url,
            order_id: this.record.id,
        }]

        Brightfield.Order.UpdatePage.superclass.initComponent.call(this);
        this.addEvents('save','generateCP','exit');
    },

    afterRender: function() {
        Brightfield.Order.UpdatePage.superclass.afterRender.call(this);
        var app = this;
        this.controller = new Brightfield.controller({
            app: app
        });
    },

    _getButtons: function() {
        return [{
            xtype: 'button',
            text: 'Сохранить',
            disabled: false,
            cls: 'x-btn x-btn-small x-btn-icon-small-left primary-button',
            handler: function(btn) {
                this.fireEvent('save', btn);
            }, scope: this
        },{
            xtype: 'button',
            text: 'Скачать КП',
            cls: 'x-btn x-btn-small x-btn-icon-small-left primary-button',
            handler: function(btn) {
                this.fireEvent('generateCP', btn);
            }, scope: this
        },{
            xtype: 'button',
            text: 'Скачать заказ',
            cls: 'x-btn x-btn-small x-btn-icon-small-left primary-button',
            handler: function(btn) {
                this.fireEvent('generateOrder', btn);
            }, scope: this
        },{
            xtype: 'button',
            text: '<i class="icon icon-user-plus" aria-hidden="true"></i>',
            cls: 'x-btn x-btn-small x-btn-icon-small-left',
            handler: function(btn) {
                this.fireEvent('openWindow', btn, 0);
            }, scope: this
        },{
            xtype: 'button',
            text: 'Заказы',
            cls: 'x-btn x-btn-small x-btn-icon-small-left',
            handler: function(btn) {
                this.fireEvent('exit', btn);
            }, scope: this
        }]
    },

    _getWindows: function() {
        var url = this.url;
        return [
            new Brightfield.window.CreateUser({
                url: url
            })
        ]
    }
});
Ext.reg('br-order-update-page', Brightfield.Order.UpdatePage);