<?php
/**
 * Get a list of Items
 */
class BrightfieldPackSortProcessor extends modObjectGetListProcessor {
    public $objectType = 'brKit';
    public $classKey = 'brKit';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';


    public function process() {
        $taget = json_decode($this->getProperty('target'), true);
        $source = json_decode($this->getProperty('source'), true);

        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        return $this->outputArray($data['results'],$data['total']);
    }

    public function getData() {
        $data = array();
        $data['total'] = 4;

        $data['results'] = array(
            array(
                'k_id' => '14',
                'k_title' => 'Аксессуары',
            ),
            array(
                'k_id' => '4',
                'k_title' => 'Тубусы и обтюраторы',
            ),
            array(
                'k_id' => '99',
                'k_title' => 'Эндоскопы',
            ),
        );
        return $data;
    }
}
return 'BrightfieldPackSortProcessor';