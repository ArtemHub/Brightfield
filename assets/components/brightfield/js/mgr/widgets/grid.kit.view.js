Brightfield.grid.KitGroupingView = function(config) {
    config = config || {};

    this.icons = {
        edit: '<i class="icon icon-pencil-square-o action-edit br-kittab-gridview-edit"></i>',
        add: '<i class="icon icon-plus-circle action-add br-kittab-gridview-add"></i>',
        up: '<i class="icon icon-caret-up action-up br-kittab-gridview-up"></i>',
        down: '<i class="icon icon-caret-down action-down br-kittab-gridview-down"></i>',
        remove: '<i class="icon icon-times action-remove br-kittab-gridview-remove"></i>'
    };

    this.tpl = this.renderTpl();

    Ext.applyIf(config, {
        emptyText: '- empty -',
        forceFit: true,
        autoFill: true,
        showPreview: true,
        enableRowBody: true,
        startCollapsed: true,
        scrollOffset: 0,
        groupTextTpl: this.tpl,
        showGroupName: false
    });
    Brightfield.grid.KitGroupingView.superclass.constructor.call(this, config);
    this.addEvents('edit', 'up', 'down', 'update', 'remove');
};
Ext.extend(Brightfield.grid.KitGroupingView, Ext.grid.GroupingView, {
    renderTpl: function() {
        var tpl = '';
        for (var key in this.icons) {
            if(key == 'edit') {
                tpl+= '<div class="br-kittab-gridview-title"><span>{[values.rs[0].data["pack_title"]]}</span> '+this.icons[key]+'</div>';
            }
            else {
                tpl+= this.icons[key];
            }
        }
        return tpl;
    },
    processEvent: function(name, e){
        Ext.grid.GroupingView.superclass.processEvent.call(this, name, e);
        var hd = e.getTarget('.x-grid-group-hd', this.mainBody);
        if(hd){
            // group value is at the end of the string
            var field = this.getGroupField(),
                prefix = this.getPrefix(field),
                groupValue = hd.id.substring(prefix.length),
                emptyRe = new RegExp('gp-' + Ext.escapeRe(field) + '--hd');

            // remove trailing '-hd'
            groupValue = groupValue.substr(0, groupValue.length - 3);

            // also need to check for empty groups
            if(groupValue || emptyRe.test(hd.id)){
                this.grid.fireEvent('group' + name, this.grid, field, groupValue, e);
            }
            if(name == 'mousedown' && e.button == 0){
                var action = false;
                if(e.getTarget('.action-edit')) {
                    action = 'edit';
                }
                else if(e.getTarget('.action-up')) {
                    action = 'up';
                }
                else if(e.getTarget('.action-down')) {
                    action = 'down';
                }
                else if(e.getTarget('.action-add')) {
                    action = 'add';
                }
                else if(e.getTarget('.action-remove')) {
                    action = 'remove';
                }
                else if(e.getTarget('.x-form-text')) {
                    action = true;
                };

                if(action) {
                    this._action(action, {
                        v: groupValue,
                        f: field,
                        el: hd
                    });
                }
                else {
                    this.toggleGroup(hd.parentNode);
                }
            }
        }
    },
    _action: function(action, param) {
        switch(action) {
            case 'edit' :
                this._edit(param.f, param.v, param.el);
                break;

            case 'up' :
                this._up(param.f, param.v, param.el);
                break;

            case 'down' :
                this._down(param.f, param.v, param.el);
                break;

            case 'add' :
                this._add(param.f, param.v, param.el);
                break;

            case 'remove' :
                this._remove(param.f, param.v, param.el);
                break;
        }
    },
    _up: function(f, v, el) {
        this.fireEvent('up', f, v, el);
    },
    _down: function(f, v, el) {
        this.fireEvent('down', f, v, el);
    },
    _add: function(f, v, el) {
        this.fireEvent('add', f, v, el);
    },
    _remove: function(f, v, el) {
        this.fireEvent('remove', f, v, el);
    },
    _edit: function(f, v, el) {
        if(this.textfield) {
            return;
        }
        var container = Ext.query('div.br-kittab-gridview-title', el)[0],
            text = Ext.query('span', el)[0].innerHTML;

        container.innerHTML = '';

        this.textfield = new Ext.form.TextField({
            xtype: 'textfield',
            renderTo: container,
            value: text,
            enableKeyEvents: true,
            listeners: {
                keydown: function(field, e) {
                    if(e.getCharCode() == '13') {
                        this._update(container, this.textfield.getValue(), text, f, v, el);
                    }
                },
                blur: function() {
                    this._update(container, this.textfield.getValue(), text, f, v, el);
                }, scope: this
            }
        });
    },
    _update: function(container, text, r_text, f, v, el) {
        if(text == '' || text == r_text) {
            text = r_text;
        }
        else {
            this.fireEvent('update', text, v, el);
        }
        this.textfield.destroy();
        delete this.textfield;
        container.innerHTML = '<span>' + text + '</span> ' + this.icons.edit;
    },
});