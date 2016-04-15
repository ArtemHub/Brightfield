Ext.namespace('Brightfield.minishop2');

Brightfield.minishop2.currency = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        autoSelect: true,
        store: new Ext.data.SimpleStore({
            fields: ['v','d'],
            data: [
                ['uah','UAH'],
                ['usd','USD'],
                ['eur','EUR']
            ]
        }),
        mode: 'local',
        displayField: 'd',
        valueField: 'v',
        hiddenName: 'currency',
        triggerAction: 'all',
        editable: false,
        selectOnFocus: false,
        preventRender: true,
        forceSelection: true,
        enableKeyEvents: true
    });

    Brightfield.minishop2.currency.superclass.constructor.call(this,config);
};
Ext.extend(Brightfield.minishop2.currency, MODx.combo.ComboBox);
Ext.reg('brightfield-currency',Brightfield.minishop2.currency);


Brightfield.minishop2.measure = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        autoSelect: true,
        store: new Ext.data.ArrayStore({
            id: 0,
            fields: ['val','measure'],
            data: [
                ['mm','mm'],
                ['fr','Fr'],
                ['cm','cm'],
                ['m','m'],
                ['deg','deg'],
            ]
        }),
        mode: 'local',
        displayField: 'measure',
        valueField: 'val',
    });

    Brightfield.minishop2.measure.superclass.constructor.call(this,config);
};
Ext.extend(Brightfield.minishop2.measure, MODx.combo.ComboBox);
Ext.reg('brightfield-measure',Brightfield.minishop2.measure);


Brightfield.minishop2.diameter = function(config) {
    config = config || {};
    config.value = (config.value !== null) ? config.value : '';
    config.measure = config.measure || '';
    config.checked = (config.value !== '');

    Ext.applyIf(config, {
        labelStyle: 'display: none',
        disableMeasure: false,
        defaultAutoCreate : {
            tag: 'div',
            class: 'diameter-field-wrap',
        },
        diameterFieldTpl: new Ext.XTemplate(
            '<div class="label-ct"></div>',
            '<div class="wrapper"><div class="input-ct"></div><div class="combo-ct"></div><div class="checkbox-ct"></div></div>'
        )
    });

    Brightfield.minishop2.diameter.superclass.constructor.call(this,config);
    this.on('afterrender', this.onAfterRender, this);
};
Ext.extend(Brightfield.minishop2.diameter, Ext.form.Field, {
    onAfterRender: function() {
        this.checkbox.on('check', this.enableFields, this);
        this.input.on('keyup', this.onChange, this);
        this.input.on('change', this.onChange, this);
        if(!this.disableMeasure) {
            this.combo.on('select', this.onChange, this);
        }
    },

    onChange: function() {
        console.log('change');
        MODx.fireResourceFormChange();
    },

    enableFields: function(checkbox, checked) {
        this.onChange();
        if(checked) {
            this.label.enable();
            this.input.enable();
            if(!this.disableMeasure) {
                this.combo.enable();
            }
        }
        else {
            this.label.disable();

            this.input.setValue(null);
            this.input.disable();

            if(!this.disableMeasure) {
                this.combo.disable();
                this.combo.setValue('mm')
            }
        }
    },

    onRender: function(ct, position) {
        Brightfield.minishop2.diameter.superclass.onRender.call(this, ct, position);
        this.el.update(this.diameterFieldTpl.apply(this));

        var disabled = (this.checked) ? false : true;

        var obj = Ext.query('.label-ct', this.el.dom)[0];
        this['label'] = Ext.create({
            xtype: 'label',
            renderTo: obj,
            text: this.fieldLabel,
            cls: 'diameter-field-label',
            disabled: disabled,
        });

        obj = Ext.query('.checkbox-ct', this.el.dom)[0];
        this['checkbox'] = Ext.create({
            xtype: 'checkbox',
            renderTo: obj,
            cls: 'diameter-button',
            checked: this.checked,
            inputValue: '1',
            uncheckedValue: '0',
            name: this.name || '',
        });

        obj = Ext.query('.input-ct', this.el.dom)[0];
        this['input'] = Ext.create({
            xtype: 'numberfield',
            enableKeyEvents: true,
            decimalPrecision: 1,
            renderTo: obj,
            width: '100%',
            disabled: disabled,
            value: this.value,
            name: this.fieldName || ''
        });

        if(!this.disableMeasure) {
            obj = Ext.query('.combo-ct', this.el.dom)[0];
            this['combo'] = Ext.create({
                xtype: 'brightfield-measure',
                renderTo: obj,
                width: 62,
                minListWidth: 60,
                value: this.measure || 'mm',
                disabled: disabled,
                hiddenName: this.measureName || ''
            });
        }
    }
});
Ext.reg('brightfield-diameter', Brightfield.minishop2.diameter);



Brightfield.minishop2.PriceField = Ext.extend(Ext.BoxComponent, {
    constructor: function(config) {
        config = config || {};

        config.rate = config.rate || {};
        config.title = config.title || '';
        config.currency = config.currency || {};
        config.fields = {};
        config.record = config.record || {};

        config = Ext.apply({
            autoEl: {
                tag: 'div',
                cls: 'br-pricefield'
            },
            fieldLabel: 'Цена / Коэффициент / Валюта',
            items: [{
                xtype: 'numberfield',
                decimalPrecision: 2,
                minValue: 0,
                name: 'price',
                value: config.record.price || 0,
                listeners: {
                    blur: function (f) {
                        var v = f.getValue();
                        if(v != f.startValue && f.isValid()){
                            this.fireEvent('change');
                        }
                    }, scope: this
                }
            },{
                xtype: 'numberfield',
                decimalPrecision: 2,
                minValue: 0,
                name: 'coefficient',
                value: config.record.coefficient || 0,
                listeners: {
                    blur: function (f) {
                        var v = f.getValue();
                        if(v != f.startValue && f.isValid()){
                            this.fireEvent('change');
                        }
                    }, scope: this
                }
            },{
                xtype: 'brightfield-currency',
                name: 'currency',
                hiddenName: 'currency',
                value: config.record.currency || 0,
                listeners: {
                    select: function (f, record) {
                        this.fireEvent('change');
                        this._changeRateText(this.rate[f.getValue()]);
                    }, scope: this
                }
            },{
                xtype: 'displayfield',
                name: 'rate',
            },{
                xtype: 'displayfield',
                name: 'cost',
            }]
        }, config);

        Brightfield.minishop2.PriceField.superclass.constructor.call(this, config);
    },

    initComponent: function() {
        Brightfield.minishop2.PriceField.superclass.initComponent.call(this);
        this.addEvents('change');
        this.on('change', this.onChange, this);
    },

    onRender: function(ct, position) {
        Brightfield.minishop2.PriceField.superclass.onRender.call(this, ct, position);
        var dh = Ext.DomHelper,
            tpl = dh.createTemplate({tag: 'div', cls: '{0}-ct'});

        var wrapper = dh.append(this.el, {
            tag: 'div', cls: 'wrapper'
        });
        for(var key in this.items) {
            if(typeof this.items[key] === 'object') {
                var name = this.items[key].name;
                var el = tpl.append(wrapper, [name]);

                this.items[key].renderTo = el;
                this.fields[name] = MODx.load(this.items[key]);
            }
        }
        this.reCountCost();
        this._changeRateText(this.rate[this.fields.currency.getValue()]);
    },

    onChange: function() {
        this.reCountCost();
        this.trigger.fn();
    },

    reCountCost: function() {
        var price = parseFloat(this.fields.price.getValue()),
            coefficient = parseFloat(this.fields.coefficient.getValue()),
            rate = parseFloat(this.rate[this.fields.currency.getValue()]),
            cost = 0;

        if(coefficient) {
            price = this._calcPriceByCoefficient(price, coefficient);
        }
        cost = (!rate) ? price : this._calcPriceByRate(price, rate);

        cost = Ext.util.Format.number(cost, '0000.00').replace(/\.00/,'');
        this.fields.cost.setValue(cost + ' грн');
    },

    _calcPriceByCoefficient: function(price, coefficient) {
        var result = price*coefficient;
        return 1 * result.toFixed(2);
    },

    _calcPriceByRate: function(price, rate) {
        var result = ((price*100) * (rate*100)) / 10000;
        return 1 * result.toFixed(2);
    },

    _changeRateText: function(rate) {
        this.fields.rate.setValue(rate || '');
    },
});
Ext.reg('br-pricefield', Brightfield.minishop2.PriceField);