Ext.namespace('Brightfield.grid');

Brightfield.grid.Order = Ext.extend(MODx.grid.Grid, {
    constructor: function(config) {
        config.url = config.url || '';

        config = Ext.apply({
            url: config.url,
            baseParams: {
                action: 'mgr/order/getlist'
            },
            fields: ['id','num','manager_username','manager_fullname','client_username','status','cost','phone','createdon','updatedon','price','discount', 'actions'],
            columns: [{
                header: 'id',
                dataIndex: 'id',
                sortable: true,
                width: 60
            }, {
                header: 'Номер',
                dataIndex: 'num',
                sortable: true,
                width: 80
            }, {
                header: 'Статус',
                dataIndex: 'status',
                sortable: true,
                width: 140,
                editable: true,
                editor: {
                    xtype: 'br-order-status'
                },
                renderer: function(val) {
                    if(val == 0) {
                        return 'Отменен';
                    }
                    else if(val == 1) {
                        return 'Новый';
                    }
                    else if(val == 2) {
                        return 'В обработке';
                    }
                    else if(val == 3) {
                        return 'На утверждении';
                    }
                    else if(val == 4) {
                        return 'Отправлен';
                    }
                    else if(val == 5) {
                        return 'Выполнен';
                    }
                }
            }, {
                header: 'Менеджер',
                dataIndex: 'manager_fullname',
                sortable: false,
                width: 140,
                renderer: function(val, metaData, record) {
                     return (val) ? val : record.get('manager_username');
                }
            }, {
                header: 'Стоимость',
                dataIndex: 'cost',
                sortable: false,
                width: 100
            }, {
                header: 'Телефон',
                dataIndex: 'client_username',
                sortable: false,
                width: 100,
            }, {
                header: 'Дата создания',
                dataIndex: 'createdon',
                sortable: true,
                width: 100
            }, {
                header: 'Дата изменения',
                dataIndex: 'updatedon',
                sortable: true,
                width: 100
            }, {
                header: 'Действия',
                dataIndex: 'actions',
                sortable: false,
                width: 100,
                renderer: this.renderActions
            }],
            width: '100%',
            sm: new Ext.grid.CheckboxSelectionModel(),
            viewConfig: {
                forceFit: true,
                autoFill: true,
                //enableRowBody: true,
                scrollOffset: 0,
                /*
                 getRowClass: function (rec, ri, p) {
                 return !rec.data.active
                 ? 'br-grid-row-disabled'
                 : '';
                 }
                 */
            },
            paging: true,
            remoteSort: true,
            autoHeight: true
        }, config);

        Brightfield.grid.Order.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        Brightfield.grid.Order.superclass.initComponent.call(this);
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

    updateOrder: function(btn, e, row) {
        url = '?a=mgr/order/update&namespace=brightfield&id='+row.id;
        location.href = url;
    },

    removeOrder: function(btn, e, row) {
        var grid = this;
        MODx.Ajax.request({
            url: this.url,
            params: {
                action: 'mgr/order/remove',
                id: row.id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        grid.store.reload();
                    }, scope: this
                },
                failure: {
                    fn: function(r) {
                        console.log(r)
                    }, scope: this
                }
            },
        });
    }
});
Ext.reg('br-grid-order', Brightfield.grid.Order);