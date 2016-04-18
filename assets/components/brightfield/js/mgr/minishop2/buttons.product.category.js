Ext.ComponentMgr.onAvailable('modx-action-buttons', function() {
    //modx-actionbuttons

    this.on('beforerender', function() {
        this.insert(0, {
            xtype: 'button',
            text: 'Экспорт',
            handler: function() {
                console.log('export')
            }
        });
        this.insert(1, {
            xtype: 'button',
            text: 'Импорт',
            handler: function() {
                if(!window) {
                    var window = new MODx.Window({
                        fileUpload: true,
                        modal: true,
                        saveBtnText: 'Имортировать',
                        fields: [{
                            xtype: 'fileuploadfield',
                            cls: '',
                            buttonText: '<i class="icon icon-upload"></i>',
                            name: 'file',
                            listeners: {
                                fileselected: function() {
                                    window.buttons[1].enable();
                                }, scope: this
                            }
                        }]
                    });
                }

                window.buttons[1].disable();
                window.show();
            }
        });
    });
});