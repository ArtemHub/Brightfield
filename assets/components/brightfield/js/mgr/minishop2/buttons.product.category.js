Ext.ComponentMgr.onAvailable('modx-action-buttons', function() {
    //modx-actionbuttons

    this.on('beforerender', function() {
        this.insert(0, {
            xtype: 'button',
            text: 'Экспорт',
            handler: function() {
                var btn = this;
                var iframe = Ext.get('file-downloader');
                if(iframe) {
                    iframe.remove();
                }

                if(!mask) {
                    var mask = new Ext.LoadMask(Ext.getBody(), {
                        msg: 'Загрузка ...'
                    });
                }
                mask.show();
                btn.disable();

                MODx.Ajax.request({
                    url: '/assets/components/brightfield/connector.php',
                    params: {
                        action: 'mgr/generator/export',
                    },
                    headers: {
                        'accept-encoding': 'true'
                    },
                    listeners: {
                        success: {
                            fn: function (r) {
                                window.open(r.object.url);
                                setTimeout(function() {
                                    mask.hide();
                                    btn.enable();
                                }, 1000);
                            }
                        }
                    }
                });
            }
        });
        this.insert(1, {
            xtype: 'button',
            text: 'Импорт',
            handler: function() {
                if(!window) {
                    var console = MODx.load({
                        xtype: 'modx-console',
                        register: 'mgr',
                        topic: '/import/',
                        show_filename: 0,
                    });

                    var window = new MODx.Window({
                        url: '/assets/components/brightfield/connector.php',
                        baseParams: {
                            register: 'mgr',
                            topic: '/import/',
                            action: 'mgr/generator/import',
                        },
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
                        }],
                        listeners: {
                            beforeSubmit: function() {
                                console.show(Ext.getBody());
                            }
                        }
                    });
                }

                window.buttons[1].disable();
                window.show();
            }
        });
    });
});