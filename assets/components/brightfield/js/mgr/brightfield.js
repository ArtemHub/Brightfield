var Brightfield = function (config) {
    config = config || {};
    Brightfield.superclass.constructor.call(this, config);
};
Ext.extend(Brightfield, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}, fields: {}
});
Ext.reg('brightfield', Brightfield);

Brightfield = new Brightfield();