<?php

/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 19.12.2016
 * Time: 12:34
 */
require_once "Classes/PHPExcel.php";

class Do_work
{
    private $Excel_file, $inputFileName;
    private $sheet, $columns_count, $rows_count;

    public function __construct()
    {
        $this->inputFileName = "Osen10112016.xls";
        # открыть файл только на чтение
        try {
            $this->Excel_file = PHPExcel_IOFactory::load($this->inputFileName);

            // выбрать активный лист; нумерация с нуля
            $this->Excel_file->setActiveSheetIndex(0);

            // получаем этот лист для работы
            $this->sheet = $this->Excel_file->getActiveSheet();

            //getHighestColumn() возвращает символьное представление последнего занятого столбца
            // columnIndexFromString  позволяет определить индекс столбца по его символьному представлению
            $this->columns_count = PHPExcel_Cell::columnIndexFromString($this->sheet->getHighestColumn());

            // узнаем количество строк
            $this->rows_count = $this->sheet->getHighestRow();

        } catch (Exception $e) {
            //pathinfo - функция , возвращающая информацию о пути к файлу
            // параметр PATHINFO_BASENAME- Возвращает последний компонент имени из указанного пути
            die('Error loading file "' . pathinfo($this->inputFileName, PATHINFO_BASENAME) . '":  ' . $e->getMessage());
        }

    }

    public function ADD_DAY()
    {
        $db = new Database();
        for ($row = 10; $row < 107; $row++) {
            for ($column = 0; $column < 1; $column++) {

                $str=$this->pars_merge_sells($row,$column);
                var_dump($str);
                echo " Что такое<br>";
                $db->Add_day($str);
            }
        }


    }

    private function pars_merge_sells($row, $column)
    {

        //найдем все объединенные ячейки на листе. Это массив
        $mergedCells = $this->sheet->getMergeCells();


        global $cell;
        //echo "I am here<br>";
        $cell = $this->sheet->getCellByColumnAndRow($column, $row);

        foreach ($mergedCells as $currMergedRange) {
            //если текущая ячейка принадлежит какой-то из объединенных ячеек
            if ($cell->isInRange($currMergedRange)) {
                //если да, то достанем диапазон ячеек в который попала текущая ячейка
                $currMergedCellsArray = PHPExcel_Cell::splitRange($currMergedRange);
                // присвоить текущей ячейке значение первой ячейки из выбранного диапазона
                $cell = $this->sheet->getCell($currMergedCellsArray[0][0]);
                //var_dump( $cell);
                unset($currMergedRange);
                break;
            }

        }
        return ($cell->getFormattedValue());

    }
}