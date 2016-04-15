Brightfield.panel.KitPanel = function (config) {
    config = config || {};

    this.grid = new Brightfield.grid.Kit();

    Ext.apply(config, {
        cls: 'br-kittab-panel',
        layout: 'fit',
        items: [this.grid],
        anchor: '100%'
    });
    Brightfield.panel.KitPanel.superclass.constructor.call(this, config);
    Ext.getCmp('modx-content').on('resize', this.doResize, this);
};
Ext.extend(Brightfield.panel.KitPanel, MODx.Panel, {
    doResize: function() {
        this.grid.store.reload();
    },
});
Ext.reg('br-kit', Brightfield.panel.KitPanel);