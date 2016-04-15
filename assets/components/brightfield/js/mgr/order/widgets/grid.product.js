Ext.namespace('Brightfield.grid');

Brightfield.grid.Product = function (config) {
    config = config || {};

    this.order_id = config.order_id || false;

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/order/product/getlist',
            order_id: this.order_id
        },
        autosave: true,
        save_action: 'mgr/order/product/update',
        saveParams: {
            kit_id: 0,
            order_id: this.order_id
        },
        fields: ['prod_id','prod_title','prod_desc','prod_code','prod_code_shinda','prod_thumb','price','cost','count','discount','cost','actions'],
        width: 'auto',
        columns: [{
            header: 'id',
            dataIndex: 'prod_id',
            hidden: true,
            sortable: true,
            menuDisabled: true
        }, {
            header: 'Изображение',
            dataIndex: 'prod_thumb',
            sortable: false,
            width: 60,
            menuDisabled: true,
            renderer: function(value) {
                return value ? '<img width="40" src="'+ value +'" alt="">' : '';
            }
        }, {
            header: 'Название',
            dataIndex: 'prod_title',
            sortable: false,
            menuDisabled: true,
            width: 150
        }, {
            header: 'Описание',
            dataIndex: 'prod_desc',
            sortable: false,
            menuDisabled: true,
            width: 250
        }, {
            header: 'Код',
            dataIndex: 'prod_code',
            sortable: false,
            menuDisabled: true,
            width: 80
        }, {
            header: 'Код Shinda',
            dataIndex: 'prod_code_shinda',
            sortable: false,
            menuDisabled: true,
            width: 80
        }, {
            header: 'Цена (шт)',
            dataIndex: 'price',
            sortable: false,
            menuDisabled: true,
            width: 80,
            renderer: function(value, metadata, record, rowIndex, colIndex) {
                return (value != 0) ? parseFloat(value).toFixed(2).replace(/\.00/,'')+' грн' : '0.00 грн';
            }
        }, {
            header: 'Количество',
            dataIndex: 'count',
            sortable: false,
            menuDisabled: true,
            width: 80,
            editable: true,
            editor: {
                xtype: 'numberfield',
                maxValue: 1000,
                minValue: 1,
            }
        }, {
            header: 'Скидка',
            dataIndex: 'discount',
            sortable: false,
            menuDisabled: true,
            width: 80,
            editable: true,
            editor: {
                xtype: 'numberfield',
                decimalPrecision: '2',
                maxValue: 100,
                minValue: 0,
            }
        }, {
            header: 'Всего',
            dataIndex: 'cost',
            sortable: false,
            menuDisabled: true,
            width: 80,
            renderer: function(value, metadata, record, rowIndex, colIndex) {
                return (value != 0) ? parseFloat(value).toFixed(2).replace(/\.00/,'')+' грн' : '0.00 грн';
            }
        }, {
            header: 'Действие',
            dataIndex: 'actions',
            sortable: false,
            width: 80,
            menuDisabled: true,
            renderer: this.renderActions
        }],
        sm: new Ext.grid.CheckboxSelectionModel(),
        viewConfig: {
            forceFit: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
        },
        remoteSort: true,
        autoHeight: true,
        save_callback: this.afterSave
    });
    Brightfield.grid.Product.superclass.constructor.call(this, config);
    this.addEvents('onChange');
};
Ext.extend(Brightfield.grid.Product, MODx.grid.Grid, {
    afterSave: function (r) {
        this.store.reload();
        this.fireEvent('onChange',r.result)
    },

    renderActions: function(val ,metaData, row) {
        var res = [];
        var cls, icon, title, action, item = '';
        for (var i in row.data.actions) {
            if (!row.data.actions.hasOwnProperty(i)) {
                continue;
            }
            var a = row.data.actions[i];
            if (!a['button']) {
                continue;
            }

            cls = a['cls'] ? a['cls'] : '';
            icon = a['icon'] ? a['icon'] : '';
            action = a['action'] ? a['action'] : '';
            title = a['title'] ? a['title'] : '';

            item = String.format(
                '<li class="{0}"><button class="btn btn-default {1}" action="{2}" title="{3}"></button></li>',
                cls, icon, action, title
            );

            res.push(item);
        }

        return String.format(
            '<ul class="br-row-actions">{0}</ul>',
            res.join('')
        );
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (typeof this[action] === 'function') {
                    row = row.data;
                    return this[action](this, e, row);
                }
            }
        }
        return this.processEvent('click', e);
    },

    removeProduct: function(btn, e, row) {
        MODx.Ajax.request({
            url: this.url,
            params: {
                action: 'mgr/order/product/remove',
                order_id: btn.order_id,
                product_id: row.prod_id,
                kit_id: 0
            },
            listeners: {
                success: {
                    fn: function (r) {
                        btn.store.reload();
                        this.fireEvent('onChange', r.result);
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
});
Ext.reg('br-order-grid-product', Brightfield.grid.Product);