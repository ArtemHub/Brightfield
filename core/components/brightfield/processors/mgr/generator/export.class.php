<?php

require_once MODX_CORE_PATH.'components/brightfield/vendor/phpexcel/PHPExcel.php';
require_once MODX_CORE_PATH.'components/brightfield/vendor/phpexcel/PHPExcel/Writer/Excel5.php';

class BrightfieldGeneratorExportProcessor extends modObjectProcessor {
    public $products = array();
    public $permission = 'br_export';

    public function process() {
        $this->products = $this->getProducts();

        $xls = new PHPExcel();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('База товаров');
        $sheet = $this->setTitleColumns($sheet);
        $sheet = $this->setData($sheet);


        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $file = MODX_ASSETS_PATH.'xls/tmp.xls';
        $objWriter->save($file);

        return $this->success();
    }

    public function setData(PHPExcel_Worksheet $sheet) {
        $total = count($this->products);
        for($i = 0; $i < $total; ++$i) {
            $j = $i+2;
            $sheet->setCellValue("A".$j, $this->products[$i]['pagetitle']);
            $sheet->setCellValue("B".$j, $this->products[$i]['article']);
            $sheet->setCellValue("C".$j, $this->products[$i]['article_shinda']);
            $sheet->setCellValue("D".$j, $this->products[$i]['price']);
            $sheet->setCellValue("E".$j, $this->products[$i]['currency']);
            $sheet->setCellValue("F".$j, $this->products[$i]['coefficient']);

            $sheet->getStyle('A'.$j.':F'.$j)->getFont()->applyFromArray(array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => false,
                'color' => array(
                    'rgb' => '444444'
                )
            ));
            $sheet->getStyle('A'.$j.':F'.$j)->getAlignment()->applyFromArray(array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ));
            $sheet->getRowDimension($j)->setRowHeight(16);
        }

        return $sheet;
    }

    public function getProducts() {
        $result = array();
        $c = $this->modx->newQuery('msProduct');
        $c->leftJoin('msProductData', 'ProductData');
        $c->select($this->modx->getSelectColumns('msProduct','msProduct'));
        $c->select($this->modx->getSelectColumns('msProductData','ProductData'));
        $c->sortby('ProductData.article_shinda', 'ASC');

        $products = $this->modx->getCollection('msProduct');
        foreach($products as $product) {
            $result[] = $product->toArray();
        }
        return $result;
    }

    public function setTitleColumns(PHPExcel_Worksheet $sheet) {
        $sheet->setCellValue("A1", 'Название');
        $sheet->setCellValue("B1", 'Код');
        $sheet->setCellValue("C1", 'Код Shinda');
        $sheet->setCellValue("D1", 'Цена');
        $sheet->setCellValue("E1", 'Валюта');
        $sheet->setCellValue("F1", 'Коэффициент');

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(6);
        $sheet->getColumnDimension('F')->setWidth(10);

        $sheet->getRowDimension(1)->setRowHeight(20);

        $sheet->getStyle('A1:F1')->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                'rgb' => 'CCCCCC'
            )
        ));

        $sheet->getStyle('A1:F1')->getFont()->applyFromArray(array(
            'name' => 'Arial',
            'size' => 10,
            'bold' => true,
            'color' => array(
                'rgb' => '444444'
            )
        ));

        return $sheet;
    }
}
return 'BrightfieldGeneratorExportProcessor';