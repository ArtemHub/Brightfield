Ext.namespace('Brightfield');

Brightfield.controller = Ext.extend(Ext.util.Observable, {
    constructor: function(config) {
        config = config || {};
        this.app = config.app || null;
        this.topic = '/order/';
        this.register = 'mgr';

        Brightfield.controller.superclass.constructor.call(this, config);
        this.init();
    },

    init: function() {
        Ext.getCmp('modx-content').on('resize', this.refresh, this);
        this.app.on('create', this.create, this);
    },

    getCt: function(ct) {
        switch(ct) {
            case 'grid':
                return this.app.findByType('br-grid-order')[0];
                break;
        }
    },

    refresh: function() {
        this.app.doLayout();
        this.getCt('grid').getView().refresh();
    },

    create: function(btn) {
        btn.disable();
        this._query({
            action: 'mgr/order/create'
        }, function(r) {
            if(r.id) {
                url = '?a=mgr/order/update&namespace=brightfield&id='+r.id;
                location.href = url
            }
        }, function(r) {
            console.log(r)
        });
    },

    _query: function(params, success, failure) {
        MODx.Ajax.request({
            url: this.app.url,
            params: params,
            listeners: {
                success: {
                    fn: success, scope: this
                },
                failure: {
                    fn: failure, scope: this
                }
            }
        });
    },
});