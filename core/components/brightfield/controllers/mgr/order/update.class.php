<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.class.php';

class BrightfieldMgrOrderUpdateManagerController extends BrightfieldMainController {
    public $br;
    public $record;

    public function checkPermissions() {
        return $this->modx->hasPermission('br_edit_orders');
    }

    public function process(array $scriptProperties = array()) {
        if(!$id = $scriptProperties['id']) {
            die('id not set');
        };
        $this->record = $this->modx->getObject('brOrder', $id)->toArray();
        if(empty($this->record)) {
            die('order not found');
        }
    }

    public function getPageTitle() {
        return 'Brightfield ';
    }

    public function loadCustomCssJs() {
        $currency_rate = array(
            'eur' => floatval($this->modx->getOption('currency_rate_eur')),
            'usd' => floatval($this->modx->getOption('currency_rate_usd'))
        );

        $this->addCss($this->br->config['cssUrl'] . 'mgr/bootstrap.buttons.css');

        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/combobox.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/grid.kit.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/grid.product.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/header.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/window.user.js');

        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/update/app.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/update/controller.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/update/components/form.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/update/components/importer.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/update/components/tabpanel.js');



        $this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
            Brightfield.config = ' . $this->modx->toJSON($this->br->config) . ';
            Brightfield.config.site_id = "' . $this->modx->site_id . '";
            Brightfield.record = ' . $this->modx->toJSON($this->record) . ';
            Brightfield.config.panelRenderTo = "order-ct-div";
			Brightfield.config.connector_url = "' . $this->br->config['connectorUrl'] . '";
			Brightfield.currency_rate = '.$this->modx->toJSON($currency_rate).';

			MODx.load({
			    xtype: "modx-component",
			    components: [{
                    xtype: "br-order-update-page",
                    url: Brightfield.config.connector_url,
                    renderTo: Brightfield.config.panelRenderTo
                }]
			});
		});
		</script>');
    }

    public function getTemplateFile() {
        return $this->br->config['templatesPath'] . 'mgr/order.create.tpl';
    }
}
return 'BrightfieldMgrOrderUpdateManagerController';