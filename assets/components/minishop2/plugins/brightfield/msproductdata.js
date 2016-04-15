miniShop2.plugin.brightfield = {
    getColumns: function() {
        return {
            article_shinda: {
                header: 'SHINDA',
                width: 50,
                sortable: false,
                editor: {
                    xtype:'textfield',
                    name: 'article_shinda'
                }
            },
            currency: {
                header: 'Валюта',
                width: 50,
                sortable: false,
                editor: {
                    xtype:'brightfield-currency',
                    name: 'currency'
                }
                ,renderer: function(value) {
                    return value.toUpperCase();
                }
            }
        }
    }
};