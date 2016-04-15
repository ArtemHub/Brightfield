Ext.namespace('Brightfield.Order');

Brightfield.Order.Importer = Ext.extend(Ext.Container, {
    constructor: function(config) {
        config = config || {};
        config.action = {
            kit: 'mgr/kit/getlist',
            product: 'mgr/product/getlist'
        };

        config = Ext.apply({
            cls: 'modx-form br-panel-default br-order-cpanel',
            layout: 'column',
            defaults: {
                layout: 'anchor'
            }
        }, config);

        Brightfield.Order.Importer.superclass.constructor.call(this, config);
        this.addEvents({
            'import': true
        });
    },

    initComponent: function() {

        this.superboxselect = new Ext.ux.form.SuperBoxSelect({
            anchor: '100%',
            forceSelection: true,
            msgTarget: 'under',
            resizable: true,
            grow: true,
            extraItemCls: 'x-tag',
            expandBtnCls: 'x-form-trigger',
            clearBtnCls: 'x-form-trigger',
            valueField: 'id',
            forceSameValueQuery: true,
            displayField: 'value',
            store: new Ext.data.JsonStore({
                url: this.url
                ,baseParams: {
                    action: this.action.kit,
                    shinda: true
                }
                ,fields: ['id','value']
                ,root: 'results'
                ,totalProperty: 'total'
                ,remoteSort: false
                ,autoDestroy: true
            })
        });

        this.radiogroup = new Ext.form.RadioGroup({
            width: 140,
            columns: [54, 50],
            items: [{
                //anchor: '100%',
                boxLabel: 'Набор',
                inputValue: 'kit',
                name: 'type',
                checked: true,
            },{
                boxLabel: 'Товар',
                inputValue: 'product',
                name: 'type'
            }]
        });

        this.checkbox = new Ext.form.Checkbox({
            width: 100,
            boxLabel: 'Код Shinda',
            checked: true,
        });

        this.button = new Ext.Button({
            text: 'Добавить',
            cls: 'br-dutton-add',
            handler: this._import,
            scope: this
        });

        this.items = [{
            columnWidth: 1,
            items: [
                this.superboxselect, {
                    layout: 'column',
                    defaults: {
                        layout: 'anchor'
                    },
                    items: [{
                        width: 90,
                        style: {
                          paddingTop: '10px'
                        },
                        items: [this.checkbox]
                    },{
                        width: 140,
                        items: [this.radiogroup]
                    }, {
                        columnWidth: 1,
                        items: []
                    }]
                },
            ]
        }, {
            width: 100,
            items: [this.button]
        }];

        Brightfield.Order.Importer.superclass.initComponent.call(this);
        this.radiogroup.on('change', this._setActionBaseParam, this);
        this.checkbox.on('check', this._setCodeBaseParam, this);
    },

    _import: function(btn, e) {
        var v = this.superboxselect.getValue(),
            type = this.radiogroup.getValue().getRawValue();

        this.fireEvent('import', v, type);
        this.superboxselect.clearValue();
    },

    _setCodeBaseParam: function(obj, checked) {
        checked = (checked) ? 1: 0;
        this.superboxselect.store.setBaseParam('shinda', checked);
    },

    _setActionBaseParam: function(obj, rb) {
        var type = obj.getValue().getRawValue(),
            action = null;

        switch(type) {
            case 'kit':
                action = this.action.kit;
                break;
            case 'product':
                action = this.action.product;
                break;
        }

        this.superboxselect.clearValue();
        this.superboxselect.store.setBaseParam('action', action);
    },
});
Ext.reg('br-order-importer', Brightfield.Order.Importer);