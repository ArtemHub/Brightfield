id: 7
source: 1
name: Brightfield
properties: 'a:0:{}'
static: 1
static_file: core/components/brightfield/elements/plugins/plugin.brightfield.php

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