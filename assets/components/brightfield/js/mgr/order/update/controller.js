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
        var importer = this.getCt('importer'),
            tabpanel = this.getCt('tabpanel'),
            form = this.getCt('form');

        //Importer Events
        importer.on('import', this.import, this);

        //TabPanel Events
        tabpanel.on('beforeRefresh', this._showMask, this);
        tabpanel.on('afterRefresh', this._hideMask, this);
        tabpanel.on('change', this.updateOrderForm, this);
        tabpanel.on('removeTab', this.removeTab, this);

        //Form
        form.on('selectClient', this.setClientdata, this);

        this.app.on('save', this.save, this);
        this.app.on('exit', this.exit, this);
        this.app.on('generateCP', this.generateCP, this);
        this.app.on('generateOrder', this.generateOrder, this);
        this.app.on('openWindow', this.openWindow, this);
    },

    getCt: function(ct) {
        switch(ct) {
            case 'form':
                return this.app.getComponent(1);
                break;
            case 'importer':
                return this.app.getComponent(2);
                break;
            case 'tabpanel':
                return this.app.getComponent(3);
                break;
        }
    },

    save: function(btn) {
        var f = this.getCt('form').getForm(),
            params = f.getValues();
        params['action'] = 'mgr/order/update';

        this._showMask();

        this._query(params, function (r) {
            this._hideMask();
            this.updateOrderForm(r.object)
        }, function (r) {
            this._hideMask();
        }, this);
    },

    openWindow: function(btn, index) {
        this.app.windows[index].show();
    },

    exit: function(btn) {
        url = '?a=mgr/order/index&namespace=brightfield';
        location.href = url;
    },

    generateCP: function(btn) {
        var ct = this;
        btn.disable();

        ct._showMask();

        var iframe = Ext.get('file-downloader');
        if(iframe) {
            iframe.remove();
        }

        this._query({
            action: 'mgr/generator/cp',
            id: this.app.record.id,
            images: true,
        }, function (r) {
             var dh = Ext.DomHelper;
             var test = dh.append(Ext.getBody(), {
                 tag: 'iframe',
                 id: 'file-downloader',
                 frameBorder: 0,
                 width: 0,
                 height: 0,
                 css: 'display:none;visibility:hidden;height:0px;',
                 src: r.object.url
             });
            setTimeout(function() {
                ct._hideMask();
                btn.enable();
            }, 1000);
        }, function (r) {
            console.log(r)
        }, this);
    },

    generateOrder: function(btn) {
        var ct = this;
        btn.disable();

        ct._showMask();

        var iframe = Ext.get('file-downloader');
        if(iframe) {
            iframe.remove();
        }

        this._query({
            action: 'mgr/generator/order',
            id: this.app.record.id,
            images: true,
        }, function (r) {
            var dh = Ext.DomHelper;
            var test = dh.append(Ext.getBody(), {
                tag: 'iframe',
                id: 'file-downloader',
                frameBorder: 0,
                width: 0,
                height: 0,
                css: 'display:none;visibility:hidden;height:0px;',
                src: r.object.url
            });
            setTimeout(function() {
                ct._hideMask();
                btn.enable();
            }, 1000);
        }, function (r) {
            console.log(r)
        }, this);
    },

    updateOrderForm: function(record) {
        var f = this.getCt('form');
        console.log(record)
        if(record.price && record.cost) {
            record.price = parseFloat(record.price).toFixed(2).replace(/\.00/,'');
            record.cost = parseFloat(record.cost).toFixed(2).replace(/\.00/,'');
        }
        f.getForm().setValues(record);
    },

    setClientdata: function(id) {
        var ct = this;
        Ext.MessageBox.confirm(
            'Загрузить данные?',
            'Загрузить данные выбранного пользователя в форму?',
            function(result) {
                if(result == 'yes') {
                    ct._query({
                        internalKey: id,
                        action: 'mgr/user/get'
                    }, function (r) {
                        ct.updateOrderForm(r.object);
                    }, function (r) {
                        console.log(r)
                    }, ct);
                }
            }
        );
    },

    removeTab: function(kit_id) {
        this._query({
            order_id: this.app.record.id,
            kit_id: kit_id,
            action: 'mgr/order/kit/remove'
        }, function (r) {
            this.getCt('tabpanel').reload();
            this.updateOrderForm(r.result);
        }, function (r) {
            console.log(r)
        }, this);
    },

    import: function(values, type) {
        var action = null, classKey = null;

        switch (type) {
            case 'kit':
                action = 'mgr/order/kit/add';
                break;
            case 'product':
                action = 'mgr/order/product/add';
                break;
        }

        this._query({
            action: action,
            order_id: this.app.record.id,
            list: true,
            id: values,
            topic: this.topic,
            register: this.register,
        }, function (r) {
            if(r.message.length) {
                this._alert(r.message);
            }
            this.getCt('tabpanel').reload();
            if(type == 'product') {
                this.updateOrderForm(r.result);
            }
        }, function (r) {
            console.log(r)
        }, this);
    },

    _alert: function(msg, title) {
        Ext.MessageBox.minWidth = 360;
        MODx.msg.alert(title, msg);
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

    _showMask: function() {
        this.app.mask.show();
    },

    _hideMask: function() {
        this.app.mask.hide();
    },
});