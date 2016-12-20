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
    private $PHPExcel_file, $inputFileName;
    private $sheet, $columns_count,$rows_count ;

    public function __construct()
    {
        $this->inputFileName = "Osen10112016.xls";
        # открыть файл только на чтение
        try {
            $this->PHPExcel_file = PHPExcel_IOFactory::load($this->inputFileName);

            // выбрать активный лист; нумерация с нуля
            $this->PHPExcel_file->setActiveSheetIndex(0);

            // получаем этот лист для работы
            $this->sheet = $this->PHPExcel_file->getActiveSheet();

            // узнаем максимальное название столбца, т.e. количество столбцов
            $this->columns_count = $this->sheet->getHighestColumn();
            //var_dump($this->columns_count);

            // узнаем количество строк
            $this->rows_count = $this->sheet->getHighestRow();
            //var_dump($this->rows_count);
        } catch (Exception $e) {
            //pathinfo - функция , возвращающая информацию о пути к файлу
            // параметр PATHINFO_BASENAME- Возвращает последний компонент имени из указанного пути
            die('Error loading file "' . pathinfo($this->inputFileName, PATHINFO_BASENAME) . '":  ' . $e->getMessage());
        }

    }

}