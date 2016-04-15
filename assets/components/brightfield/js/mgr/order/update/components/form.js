Ext.namespace('Brightfield.Order');

Brightfield.Order.Form = Ext.extend(MODx.FormPanel, {
    constructor: function(config) {
        config = config || {};
        config.record = config.record || {};

        config = Ext.apply({
            items: [{
                xtype: 'modx-tabs',
                cls: 'order-tabpanel',
                deferredRender: false,
                items: [{
                    title: 'Общие сведения',
                    layout: 'column',
                    defaults: {
                        layout: 'form',
                        labelAlign: 'top',
                    },
                    items: this.getDefaultTabFields(config.record)
                },{
                    title: 'Контактная информация',
                    layout: 'column',
                    defaults: {
                        layout: 'form',
                        labelAlign: 'top',
                    },
                    items: this.getContactTabFields(config.record)
                }]
            }]
        }, config);

        Brightfield.Order.Form.superclass.constructor.call(this, config);
        this.addEvents('selectClient');
    },

    initComponent: function() {
        Brightfield.Order.Form.superclass.initComponent.call(this);
    },

    getDefaultTabFields: function(record) {
        return [{
            columnWidth: .3,
            defaults: {
                anchor: '100%'
            },
            items: [{
                xtype: 'hidden',
                name: 'id',
                value: record.id
            },{
                xtype: 'displayfield',
                fieldLabel: 'Общая стоимость (грн)',
                name: 'price',
                value: parseFloat(record.price).toFixed(2).replace(/\.00/,'') || '0.00',
            }, {
                xtype: 'displayfield',
                fieldLabel: 'Конечная цена (грн)',
                name: 'cost',
                value: parseFloat(record.cost).toFixed(2).replace(/\.00/,'') || '0.00'
            }]
        }, {
            columnWidth: .3,
            defaults: {
                anchor: '100%',
            },
            items: [{
                xtype: 'displayfield',
                fieldLabel: 'Дата создания',
                name: 'createdon',
                value: record.createdon || ''
            }, {
                xtype: 'displayfield',
                fieldLabel: 'Дата редактирования',
                name: 'updatedon',
                value: record.updatedon || ''
            }]
        }, {
            columnWidth: .4,
            defaults: {
                anchor: '100%'
            },
            items: [{
                xtype: 'br-order-status',
                fieldLabel: 'Статус заказа',
                name: 'status',
                value: record.status || 0,
                hiddenName: 'status',
            }, {
                xtype: 'numberfield',
                fieldLabel: 'Общая скидка (%)',
                decimalPrecision: '2',
                anchor: '26%',
                maxValue: 100,
                minValue: 0,
                listeners: {
                    blur: function(f) {
                        if(f.isValid()) {
                            this.discountChange(f.getValue());
                        }
                    }, scope: this
                },
                name: 'discount',
                value: record.discount || ''
            }]
        }]
    },

    getContactTabFields: function(record) {
        return [{
            columnWidth: .25,
            defaults: {
                anchor: '100%'
            },
            items: [{
                xtype: 'modx-combo-user',
                anchor: '100%',
                fieldLabel: 'Контактный телефон',
                name: 'client_id',
                hiddenName: 'client_id',
                value: record.client_id || '',
                baseParams: {
                    action: 'security/user/getlist',
                    usergroup: 3
                },
                listeners: {
                    beforeselect: function(combo, record, index) {
                        this.fireEvent('selectClient', record.get('id'));
                    }, scope: this
                }
            }, {
                xtype: 'textfield',
                fieldLabel: 'Имя',
                name: 'client_name',
                value: record.client_name || '',
            }]
        }, {
            columnWidth: .25,
            defaults: {
                anchor: '100%',
            },
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Город',
                name: 'city',
                value: record.city || '',
            }, {
                xtype: 'textfield',
                fieldLabel: 'Адрес',
                name: 'address',
                value: record.address || '',
            }]
        }, {
            columnWidth: .5,
            defaults: {
                anchor: '100%',
            },
            items: [{
                xtype: 'textfield',
                name: 'company',
                fieldLabel: 'Организация',
                value: record.company || '',
            },{
                xtype: 'textarea',
                fieldLabel: 'Комментарий',
                height: 60,
                name: 'comment',
                value: record.comment || '',
            }]
        }]
    },

    discountChange: function(discount) {
        var price = parseFloat(this.getField('price').getValue());
        var cost = parseFloat(this.getField('cost').getValue());

        if(!discount) {
            this.getField('cost').setValue(price);
        }
        else {
            var cost = (price - (price/100 * discount)).toFixed(2).replace(/\.00/,'');
            this.getField('cost').setValue(cost);
        }
    }
});
Ext.reg('br-order-form', Brightfield.Order.Form);