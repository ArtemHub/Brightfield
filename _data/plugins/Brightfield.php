id: 7
source: 1
name: Brightfield
plugincode: "if ($modx->event->name == 'OnManagerPageBeforeRender') {\n    if(!$controller->scriptProperties['id']) {\n        return;\n    }\n    $resource = $modx->getObject('modResource', $controller->scriptProperties['id']);\n    if($resource->template != 3) {\n        return;\n    }\n\n    $controller->br = $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());\n    $controller->addLexiconTopic('brightfield:default');\n\n    $controller->addJavascript($br->config['jsUrl'].'mgr/brightfield.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.view.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/window.codeparser.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/panel.kit.js');\n\n    $controller->addCss($br->config['cssUrl'].'mgr/brightfield.css');\n\n    $scripts = array(\n        'head' => '\n\t\t\tBrightfield.config = '.$modx->toJSON($br->config).';\n\t\t\tBrightfield.config.kit_id = \"'.$controller->scriptProperties['id'].'\";\n\t\t\tBrightfield.config.connector_url = \"'.$br->config['connectorUrl'].'\";\n\t\t'\n        ,'tab' => '\n            Ext.ComponentMgr.onAvailable(\\'modx-resource-tabs\\', function() {\n                this.items.unshift({\n                    title: _(\\'brightfield_kit\\')\n                    ,cls: \\'modx-resource-tab\\'\n                    ,layout: \\'form\\'\n                    ,autoScroll: false\n                    ,forceLayout: true\n                    ,deferredRender: false\n                    ,labelWidth: 200\n                    ,bodyCssClass: \\'main-wrapper\\'\n                    ,autoHeight: true\n                    ,defaults: {\n                        border: false\n                        ,msgTarget: \\'under\\'\n                    }\n                    ,items: [{\n                        xtype: \\'br-kit\\'\n                    }]\n                });\n            });\n            '\n    );\n\n    $controller->addHtml('<script type=\"text/javascript\">'.implode(\"\\n\", $scripts).'</script>');\n}\nif($modx->event->name == 'OnDocFormPrerender') {\n    $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());\n\n    if ($mode !== 'upd') {\n        return;\n    }\n    if($resource->class_key == 'msCategory' || $resource->class_key == 'msProduct') {\n        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/widgets.js');\n        $modx->regClientCSS($br->config['cssUrl'].'mgr/brightfield.css');\n    }\n    if($resource->class_key == 'msProduct') {\n        $currency_rate['eur'] = floatval($modx->getOption('currency_rate_eur'));\n        $currency_rate['usd'] = floatval($modx->getOption('currency_rate_usd'));\n\n        if (!$modx->getObject('msProduct', $id)) {\n            return;\n        }\n        $modx->regClientStartupScript(\"<script type=\\\"text/javascript\\\">\\n\n            Ext.onReady(function() { if(MODx.loadRTE) MODx.loadRTE('content_commercial'); });\n            Brightfield.currency_rate = \".$modx->toJSON($currency_rate).\";\n        \\n</script>\", true);\n        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/tab.product.options.js');\n    }\n}\nif ($modx->event->name == 'OnBeforeDocFormSave') {\n    if($resource->get('class_key') !== 'msProduct') {\n        return;\n    }\n    if ($mode !== 'upd') {\n        return;\n    }\n    if(!$resource->get('article')) {\n        $modx->event->output('Вы не указали Артикул!');\n        return;\n    }\n    if(!$resource->get('article_shinda')) {\n        $modx->event->output('Вы не указали Артикул SHINDA!');\n        return;\n    }\n\n    $q = $modx->newQuery('msProductData', array('article' => $resource->get('article') ));\n    $q->where(array('id:!=' => $resource->get('id')));\n    if($modx->getCount('msProductData', $q)) {\n        $modx->event->output('Продукт с таким артикулом уже существует');\n        return;\n    }\n\n    $q = $modx->newQuery('msProductData', array('article_shinda' => $resource->get('article_shinda') ));\n    $q->where(array('id:!=' => $resource->get('id')));\n    if($modx->getCount('msProductData', $q)) {\n        $modx->event->output('Продукт с таким артикулом SHINDA уже существует');\n        return;\n    }\n\n    if(!$resource->get('paramDiameter')) {\n        $resource->set('diameter', null);\n        $resource->set('diameter_measure', null);\n    }\n    if(!$resource->get('paramLength')) {\n        $resource->set('length', null);\n        $resource->set('length_measure', null);\n    }\n    if(!$resource->get('paramAngleViewing')) {\n        $resource->set('angle_viewing', null);\n    }\n    if(!$resource->get('paramAngleBend')) {\n        $resource->set('angle_bend', null);\n    }\n}"
properties: 'a:0:{}'
static: 1
static_file: core/components/brightfield/elements/plugins/plugin.brightfield.php
content: "if ($modx->event->name == 'OnManagerPageBeforeRender') {\n    if(!$controller->scriptProperties['id']) {\n        return;\n    }\n    $resource = $modx->getObject('modResource', $controller->scriptProperties['id']);\n    if($resource->template != 3) {\n        return;\n    }\n\n    $controller->br = $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());\n    $controller->addLexiconTopic('brightfield:default');\n\n    $controller->addJavascript($br->config['jsUrl'].'mgr/brightfield.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.view.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/window.codeparser.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.js');\n    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/panel.kit.js');\n\n    $controller->addCss($br->config['cssUrl'].'mgr/brightfield.css');\n\n    $scripts = array(\n        'head' => '\n\t\t\tBrightfield.config = '.$modx->toJSON($br->config).';\n\t\t\tBrightfield.config.kit_id = \"'.$controller->scriptProperties['id'].'\";\n\t\t\tBrightfield.config.connector_url = \"'.$br->config['connectorUrl'].'\";\n\t\t'\n        ,'tab' => '\n            Ext.ComponentMgr.onAvailable(\\'modx-resource-tabs\\', function() {\n                this.items.unshift({\n                    title: _(\\'brightfield_kit\\')\n                    ,cls: \\'modx-resource-tab\\'\n                    ,layout: \\'form\\'\n                    ,autoScroll: false\n                    ,forceLayout: true\n                    ,deferredRender: false\n                    ,labelWidth: 200\n                    ,bodyCssClass: \\'main-wrapper\\'\n                    ,autoHeight: true\n                    ,defaults: {\n                        border: false\n                        ,msgTarget: \\'under\\'\n                    }\n                    ,items: [{\n                        xtype: \\'br-kit\\'\n                    }]\n                });\n            });\n            '\n    );\n\n    $controller->addHtml('<script type=\"text/javascript\">'.implode(\"\\n\", $scripts).'</script>');\n}\nif($modx->event->name == 'OnDocFormPrerender') {\n    $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());\n\n    if ($mode !== 'upd') {\n        return;\n    }\n    if($resource->class_key == 'msCategory' || $resource->class_key == 'msProduct') {\n        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/widgets.js');\n        $modx->regClientCSS($br->config['cssUrl'].'mgr/brightfield.css');\n    }\n    if($resource->class_key == 'msProduct') {\n        $currency_rate['eur'] = floatval($modx->getOption('currency_rate_eur'));\n        $currency_rate['usd'] = floatval($modx->getOption('currency_rate_usd'));\n\n        if (!$modx->getObject('msProduct', $id)) {\n            return;\n        }\n        $modx->regClientStartupScript(\"<script type=\\\"text/javascript\\\">\\n\n            Ext.onReady(function() { if(MODx.loadRTE) MODx.loadRTE('content_commercial'); });\n            Brightfield.currency_rate = \".$modx->toJSON($currency_rate).\";\n        \\n</script>\", true);\n        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/tab.product.options.js');\n    }\n}\nif ($modx->event->name == 'OnBeforeDocFormSave') {\n    if($resource->get('class_key') !== 'msProduct') {\n        return;\n    }\n    if ($mode !== 'upd') {\n        return;\n    }\n    if(!$resource->get('article')) {\n        $modx->event->output('Вы не указали Артикул!');\n        return;\n    }\n    if(!$resource->get('article_shinda')) {\n        $modx->event->output('Вы не указали Артикул SHINDA!');\n        return;\n    }\n\n    $q = $modx->newQuery('msProductData', array('article' => $resource->get('article') ));\n    $q->where(array('id:!=' => $resource->get('id')));\n    if($modx->getCount('msProductData', $q)) {\n        $modx->event->output('Продукт с таким артикулом уже существует');\n        return;\n    }\n\n    $q = $modx->newQuery('msProductData', array('article_shinda' => $resource->get('article_shinda') ));\n    $q->where(array('id:!=' => $resource->get('id')));\n    if($modx->getCount('msProductData', $q)) {\n        $modx->event->output('Продукт с таким артикулом SHINDA уже существует');\n        return;\n    }\n\n    if(!$resource->get('paramDiameter')) {\n        $resource->set('diameter', null);\n        $resource->set('diameter_measure', null);\n    }\n    if(!$resource->get('paramLength')) {\n        $resource->set('length', null);\n        $resource->set('length_measure', null);\n    }\n    if(!$resource->get('paramAngleViewing')) {\n        $resource->set('angle_viewing', null);\n    }\n    if(!$resource->get('paramAngleBend')) {\n        $resource->set('angle_bend', null);\n    }\n}"

-----

if ($modx->event->name == 'OnManagerPageBeforeRender') {
    if(!$controller->scriptProperties['id']) {
        return;
    }
    $resource = $modx->getObject('modResource', $controller->scriptProperties['id']);
    if($resource->template != 3) {
        return;
    }

    $controller->br = $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());
    $controller->addLexiconTopic('brightfield:default');

    $controller->addJavascript($br->config['jsUrl'].'mgr/brightfield.js');
    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.view.js');
    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/window.codeparser.js');
    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/grid.kit.js');
    $controller->addJavascript($br->config['jsUrl'].'mgr/widgets/panel.kit.js');

    $controller->addCss($br->config['cssUrl'].'mgr/brightfield.css');

    $scripts = array(
        'head' => '
			Brightfield.config = '.$modx->toJSON($br->config).';
			Brightfield.config.kit_id = "'.$controller->scriptProperties['id'].'";
			Brightfield.config.connector_url = "'.$br->config['connectorUrl'].'";
		'
        ,'tab' => '
            Ext.ComponentMgr.onAvailable(\'modx-resource-tabs\', function() {
                this.items.unshift({
                    title: _(\'brightfield_kit\')
                    ,cls: \'modx-resource-tab\'
                    ,layout: \'form\'
                    ,autoScroll: false
                    ,forceLayout: true
                    ,deferredRender: false
                    ,labelWidth: 200
                    ,bodyCssClass: \'main-wrapper\'
                    ,autoHeight: true
                    ,defaults: {
                        border: false
                        ,msgTarget: \'under\'
                    }
                    ,items: [{
                        xtype: \'br-kit\'
                    }]
                });
            });
            '
    );

    $controller->addHtml('<script type="text/javascript">'.implode("\n", $scripts).'</script>');
}
if($modx->event->name == 'OnDocFormPrerender') {
    $br = $modx->getService('br','Brightfield', MODX_CORE_PATH . 'components/brightfield/model/brightfield/', array());

    if ($mode !== 'upd') {
        return;
    }
    if($resource->class_key == 'msCategory' || $resource->class_key == 'msProduct') {
        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/widgets.js');
        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/buttons.product.category.js');
        $modx->regClientCSS($br->config['cssUrl'].'mgr/brightfield.css');
    }
    if($resource->class_key == 'msProduct') {
        $currency_rate['eur'] = floatval($modx->getOption('currency_rate_eur'));
        $currency_rate['usd'] = floatval($modx->getOption('currency_rate_usd'));

        if (!$modx->getObject('msProduct', $id)) {
            return;
        }
        $modx->regClientStartupScript("<script type=\"text/javascript\">\n
            Ext.onReady(function() { if(MODx.loadRTE) MODx.loadRTE('content_commercial'); });
            Brightfield.currency_rate = ".$modx->toJSON($currency_rate).";
        \n</script>", true);
        $modx->regClientStartupScript($br->config['jsUrl'].'mgr/minishop2/tab.product.options.js');
    }
}
if ($modx->event->name == 'OnBeforeDocFormSave') {
    if($resource->get('class_key') !== 'msProduct') {
        return;
    }
    if ($mode !== 'upd') {
        return;
    }
    if(!$resource->get('article')) {
        $modx->event->output('Вы не указали Артикул!');
        return;
    }
    if(!$resource->get('article_shinda')) {
        $modx->event->output('Вы не указали Артикул SHINDA!');
        return;
    }

    $q = $modx->newQuery('msProductData', array('article' => $resource->get('article') ));
    $q->where(array('id:!=' => $resource->get('id')));
    if($modx->getCount('msProductData', $q)) {
        $modx->event->output('Продукт с таким артикулом уже существует');
        return;
    }

    $q = $modx->newQuery('msProductData', array('article_shinda' => $resource->get('article_shinda') ));
    $q->where(array('id:!=' => $resource->get('id')));
    if($modx->getCount('msProductData', $q)) {
        $modx->event->output('Продукт с таким артикулом SHINDA уже существует');
        return;
    }

    if(!$resource->get('paramDiameter')) {
        $resource->set('diameter', null);
        $resource->set('diameter_measure', null);
    }
    if(!$resource->get('paramLength')) {
        $resource->set('length', null);
        $resource->set('length_measure', null);
    }
    if(!$resource->get('paramAngleViewing')) {
        $resource->set('angle_viewing', null);
    }
    if(!$resource->get('paramAngleBend')) {
        $resource->set('angle_bend', null);
    }
}