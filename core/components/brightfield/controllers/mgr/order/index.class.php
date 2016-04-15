<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.class.php';

class BrightfieldMgrOrderIndexManagerController extends BrightfieldMainController {
    public $br;
    public $record;

    public function checkPermissions() {
        return $this->modx->hasPermission('br_edit_orders');
    }

    public function process(array $scriptProperties = array()) {
        //$this->id = $neparsirani->getPrimaryKey();
    }

    public function getPageTitle() {
        return $this->modx->lexicon('modextra');
    }

    public function loadCustomCssJs() {
        $currency_rate = array(
            'eur' => floatval($this->modx->getOption('currency_rate_eur')),
            'usd' => floatval($this->modx->getOption('currency_rate_usd'))
        );

        $this->addCss($this->br->config['cssUrl'] . 'mgr/bootstrap.buttons.css');

        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/combobox.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/header.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/widgets/grid.order.js');

        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/list/app.js');
        $this->addJavascript($this->br->config['jsUrl'] . 'mgr/order/list/controller.js');


        $this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
            Brightfield.config = ' . $this->modx->toJSON($this->br->config) . ';
            Brightfield.config.site_id = "' . $this->modx->site_id . '";
            Brightfield.config.panelRenderTo = "order-ct-div";
			Brightfield.config.connector_url = "' . $this->br->config['connectorUrl'] . '";
			Brightfield.currency_rate = '.$this->modx->toJSON($currency_rate).';

			MODx.load({
			    xtype: "modx-component",
			    components: [{
                    xtype: "br-order-list-page",
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
return 'BrightfieldMgrOrderIndexManagerController';