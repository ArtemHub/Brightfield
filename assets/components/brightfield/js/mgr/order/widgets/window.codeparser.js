Brightfield.window.CodeParser = function (config) {
    config = config || {};

    Ext.apply(config, {
        url: Brightfield.config.connectorUrl,
        title: 'Добавить товар',
        baseParams: {
            action: 'mgr/pack/codeparser'
        },
        modal: true,
        labelAlign: 'top',
        width: 600,
        autoHeight: true,
        fields: [{
            xtype: 'textarea',
            anchor: '100%',
            name: 'codelist',
            allowBlank: false
        },{
            xtype: 'hidden',
            name: 'pack_id'
        },{
            xtype: 'hidden',
            name: 'topic'
        },{
            xtype: 'hidden',
            name: 'register'
        }],
        closeAction: 'close'
    });
    Brightfield.window.CodeParser.superclass.constructor.call(this, config);
};
Ext.extend(Brightfield.window.CodeParser, MODx.Window, {
    resizeWindow: function(){
        var viewHeight = Ext.getBody().getViewSize().height;
        var el = this.fp.getForm().el;

        if(!el) {
            return;
        }

        if(viewHeight < this.originalHeight){
            el.setStyle('overflow-y', 'scroll');
            el.setHeight(viewHeight - this.toolsHeight);
        }else{
            el.setStyle('overflow-y', 'auto');
            el.setHeight('auto');
        }
    }
});