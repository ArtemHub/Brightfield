Ext.namespace('Brightfield.minishop2');


Brightfield.minishop2.productOptions = function(config) {
    config = config || {};

    if(!config.record) {
        console.log('SET RECORD DATA!');
        return false;
    }
    this.record = config.record;

    this.listenerEvents = {
        change: {
            fn: MODx.fireResourceFormChange
        },
        select: {
            fn: MODx.fireResourceFormChange
        },
        keydown: {
            fn: MODx.fireResourceFormChange
        },
        check: {
            fn: MODx.fireResourceFormChange
        },
        uncheck: {
            fn: MODx.fireResourceFormChange
        }
    };

    Ext.applyIf(config, {
        title: 'Опции',
        hideMode: 'offsets',
        items: [{
            layout: 'column',
            border: false,
            bodyCssClass: 'tab-panel-wrapper ',
            style: 'padding: 15px;',
            items: [{
                columnWidth: 1,
                border: false,
                layout: 'form',
                labelAlign: 'top',
                preventRender: true,
                items: [{
                    layout: 'column',
                    items: [{
                        columnWidth: .5,
                        layout: 'form',
                        labelAlign: 'top',
                        items: [
                            this.getArticleFields(),
                            this.getTitleFields(), {
                                xtype: 'br-pricefield',
                                record: this.record,
                                rate: Brightfield.currency_rate,
                                trigger: {
                                    fn: MODx.fireResourceFormChange
                                }
                            }
                        ],
                    },{
                        columnWidth: .5,
                        items: [
                            this.getParameterFields()
                        ]
                    }]
                }]
            }]
        },{
            style: {
                paddingTop: '30px'
            },
            layout: 'form',
            labelAlign: 'top',
            items: [{
                xtype: 'textarea'
                ,fieldLabel: 'Описание для Коммерческого предложения'
                ,description: ''
                ,height: 200
                ,anchor: '100%'
                ,id: 'content_commercial'
                ,name: 'content_commercial'
                ,value: this.record['content_commercial'] || ''
                ,listeners: {
                    change: {
                        fn: MODx.fireResourceFormChange
                    }
                }
            }]
        }]
    });

    Brightfield.minishop2.productOptions.superclass.constructor.call(this,config);
};
Ext.extend(Brightfield.minishop2.productOptions, MODx.Panel, {
    getArticleFields: function() {
        return [{
            layout: 'fit',
            items: [{
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Код товара',
                        name: 'article',
                        allowBlank: false,
                        anchor: '99%',
                        listeners: this.listenerEvents,
                        value: this.record['article']
                    }]
                },{
                    columnWidth: .5,
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Код товара (SHINDA)',
                        name: 'article_shinda',
                        allowBlank: false,
                        anchor: '99%',
                        listeners: this.listenerEvents,
                        value: this.record['article_shinda']
                    }]
                }]
            }]
        }];
    }
    ,getTitleFields: function() {
        return [{
            layout: 'form',
            style: {
                marginTop: '20px'
            },
            labelAlign: 'top',
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Название (МОЗ)',
                name: 'title_moz',
                anchor: '99%',
                listeners: this.listenerEvents,
                value: this.record['title_moz']
            },{
                xtype: 'textfield',
                fieldLabel: 'Название (ENG)',
                name: 'title_english',
                anchor: '99%',
                listeners: this.listenerEvents,
                value: this.record['title_english']
            }]
        }];
    }
    ,getParameterFields: function() {
        return[{
                xtype: 'brightfield-diameter',
                fieldLabel: 'Диаметр ∅',
                value: this.record['diameter'],
                measure: this.record['diameter_measure'],
                name: 'paramDiameter',
                fieldName: 'diameter',
                measureName: 'diameter_measure',
            },{
                xtype: 'brightfield-diameter',
                fieldLabel: 'Длина',
                value: this.record['length'],
                measure: this.record['length_measure'],
                name: 'paramLength',
                fieldName: 'length',
                measureName: 'length_measure',
                style: {
                    paddingTop: '10px'
                }
            },{
                xtype: 'brightfield-diameter',
                fieldLabel: 'Угол обзора',
                value: this.record['angle_viewing'],
                name: 'paramAngleViewing',
                disableMeasure: true,
                fieldName: 'angle_viewing',
                style: {
                    paddingTop: '10px'
                }
            },{
                xtype: 'brightfield-diameter',
                fieldLabel: 'Угол наклона',
                value: this.record['angle_bend'],
                name: 'paramAngleBend',
                disableMeasure: true,
                fieldName: 'angle_bend',
                style: {
                    paddingTop: '10px'
                }
        }]
    }
});
Ext.reg('brightfield-product-options', Brightfield.minishop2.productOptions);


Ext.ComponentMgr.onAvailable('minishop2-product-settings-panel', function() {
    this.on('beforerender', function() {

        var listeners = {
            change: {
                fn: MODx.fireResourceFormChange
            },
            select: {
                fn: MODx.fireResourceFormChange
            },
            keydown: {
                fn: MODx.fireResourceFormChange
            },
            check: {
                fn: MODx.fireResourceFormChange
            },
            uncheck: {
                fn: MODx.fireResourceFormChange
            }
        };
        var main = Ext.getCmp('minishop2-product-settings');
        if (!!main) {
            var record = main.initialConfig.items[0].record;
        }

        this.insert(0, {
            xtype: 'brightfield-product-options',
            record: record
        });
    });
});