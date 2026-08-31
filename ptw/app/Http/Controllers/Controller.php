<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Build a SpreadsheetML (.xls) download response, matching the style used by
     * the Permits list export. $rows is a list of rows, each row a list of cells;
     * a cell is [type, value] or [type, value, styleId] where type is
     * "String" | "Number".
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, array{0:string,1:mixed,2?:string}>>  $rows
     */
    protected function spreadsheetDownload(string $sheetName, array $headers, array $rows, string $filenamePrefix): Response
    {
        $filename = $filenamePrefix . '_' . date('Y-m-d_His') . '.xls';

        $html  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $html .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $html .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
        $html .= '<Styles>';
        $html .= '<Style ss:ID="header"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#1A3A6B" ss:Pattern="Solid"/></Style>';
        $html .= '<Style ss:ID="even"><Interior ss:Color="#F8F9FA" ss:Pattern="Solid"/></Style>';
        $html .= '<Style ss:ID="ok"><Font ss:Bold="1" ss:Color="#28A745"/></Style>';
        $html .= '<Style ss:ID="nok"><Font ss:Bold="1" ss:Color="#DC3545"/></Style>';
        $html .= '</Styles>' . "\n";
        $html .= '<Worksheet ss:Name="' . htmlspecialchars($sheetName) . '"><Table>' . "\n";

        $html .= '<Row>';
        foreach ($headers as $h) {
            $html .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . htmlspecialchars($h) . '</Data></Cell>';
        }
        $html .= '</Row>' . "\n";

        $rowNum = 2;
        foreach ($rows as $cells) {
            $rowStyle = ($rowNum % 2 === 0) ? ' ss:StyleID="even"' : '';
            $html .= '<Row' . $rowStyle . '>';
            foreach ($cells as $cell) {
                $type  = $cell[0];
                $value = htmlspecialchars((string) ($cell[1] ?? ''));
                $style = isset($cell[2]) && $cell[2] !== '' ? ' ss:StyleID="' . $cell[2] . '"' : '';
                $html .= '<Cell' . $style . '><Data ss:Type="' . $type . '">' . $value . '</Data></Cell>';
            }
            $html .= '</Row>' . "\n";
            $rowNum++;
        }

        $html .= '</Table></Worksheet></Workbook>';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
