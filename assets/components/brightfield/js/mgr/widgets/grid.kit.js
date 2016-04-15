Brightfield.grid.Kit = function (config) {
    config = config || {};

    this.topic = '/kit/';
    this.register = 'mgr';

    this.kit_id = Brightfield.config.kit_id;

    this.textfield = new Ext.form.TextField({
        width: '300px',
        name: 'new_pack',
        value: ''
    });
    this.button = new Ext.Button({
        text: 'Добавить',
        handler: this._packAdd
    });

    this.collapseToggle = new Ext.Button({
        text: '<i class="icon icon-level-down"></i>',
        handler: function() {
            if(this.collapseToggle.getText() == '<i class="icon icon-level-down"></i>') {
                this.collapseToggle.setText('<i class="icon icon-level-up"></i>');
            }
            else {
                this.collapseToggle.setText('<i class="icon icon-level-down"></i>');
            }
            this.view.toggleAllGroups();
        }, scope: this
    });

    Ext.applyIf(config, {
        url: Brightfield.config.connectorUrl,
        baseParams: {
            action: 'mgr/pack/getlist',
            kit_id: this.kit_id
        },
        fields: ['pack_id','pack_title','prod_id','prod_title','prod_desc','prod_code','prod_code_shinda','prod_thumb'],
        grouping: true,
        hideHeaders: true,
        groupBy: 'pack_id',
        width: 'auto',
        columns: [{
            dataIndex: 'pack_id',
            hidden: true,
            sortable: true,
        }, {
            dataIndex: 'prod_thumb',
            sortable: false,
            width: 60,
            renderer: function(value) {
                return value ? '<img width="40" src="'+ value +'" alt="">' : '';
            }
        }, {
            dataIndex: 'prod_title',
            sortable: false,
            width: 150
        }, {
            dataIndex: 'prod_desc',
            sortable: false,
            width: 250
        }, {
            dataIndex: 'prod_code',
            sortable: false,
            width: 100
        }, {
            dataIndex: 'prod_code_shinda',
            sortable: false,
            width: 100
        }, {
            sortable: false,
            width: 40,
            renderer: function(value, metadata, record) {
                return value ? '<i action="removeProduct" class="icon icon-trash br-kittab-grid-action-remove"></i>' : '';
            }
        }],
        sm: new Ext.grid.CheckboxSelectionModel(),
        tbar: [
            this.textfield, this.button, '->', this.collapseToggle
        ],
        view: new Brightfield.grid.KitGroupingView(),
        paging: false,
        remoteSort: true,
        autoHeight: true,
        ddGroup: 'br-dd-kit',
        enableDragDrop: true
    });
    Brightfield.grid.Kit.superclass.constructor.call(this, config);
    this.view.on('up', this._packUp, this);
    this.view.on('down', this._packDown, this);
    this.view.on('add', this._openWindow, this);
    this.view.on('remove', this._packRemove, this);
    this.view.on('update', this._packUpdate, this);
};
Ext.extend(Brightfield.grid.Kit, MODx.grid.Grid, {
    _packUp: function(f, v, el) {
        this.onChange();
    },
    _packDown: function(f, v, el) {
        this.onChange();
    },
    _packAdd: function() {
        var title = this.textfield.getValue();
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/pack/create',
                kitid: this.kit_id,
                title: title
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.textfield.setValue('');
                        this.onChange();
                    }, scope: this
                },
                failure: {
                    fn: function(r) {
                        console.log(r)
                    }, scope: this
                }
            }
        });
    },
    _packRemove: function(f, v, el) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/pack/remove',
                id: v,
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.onChange();
                    }, scope: this
                },
                failure: {
                    fn: function(r) {
                        console.log(r)
                    }, scope: this
                }
            }
        });
    },
    _packUpdate: function(t, v, el) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/pack/update',
                id: v,
                title: t
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.onChange();
                    }, scope: this
                },
                failure: {
                    fn: function(r) {
                        console.log(r)
                    }, scope: this
                }
            }
        });
    },
    _openWindow: function(f, v, el) {
        if(this.window) {
            delete this.window;
        }
        this.window = new Brightfield.window.CodeParser({
            listeners: {
                beforeSubmit: function() {
                    var cons = new MODx.Console({
                        register: this.register
                        ,topic:  this.topic
                        ,show_filename: 0
                        ,listeners: {
                            'shutdown': {fn:function() {
                                this.onChange();
                            },scope:this}
                        }
                    });
                    cons.show();
                }, scope: this
            }
        });
        this.window.setValues({
            pack_id: v,
            register: this.register,
            topic: this.topic,
        });
        this.window.show();
    },
    onChange: function() {
        this.store.reload();
    },
    onClick: function (e) {
       var elem = e.getTarget();
        if (elem.nodeName == 'I') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (typeof this[action] === 'function') {
                    return this[action](row.data);
                }
            }
        }
        return this.processEvent('click', e);
    },
    removeProduct: function(data) {
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/pack/rmproduct',
                pack_id: data.pack_id,
                prod_id: data.prod_id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.onChange();
                    }, scope: this
                },
                failure: {
                    fn: function(r) {
                        console.log(r)
                    }, scope: this
                }
            }
        });
    }
});
Ext.reg('br-grid-packages', Brightfield.grid.Kit);