<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$mySpreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

// delete the default active sheet
$mySpreadsheet->removeSheetByIndex(0);

// Create "Sheet 1" tab as the first worksheet.
// https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
$worksheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 1");
$mySpreadsheet->addSheet($worksheet1, 0);

// Create "Sheet 2" tab as the second worksheet.
$worksheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 2");
$mySpreadsheet->addSheet($worksheet2, 1);

// sheet 1 contains the birthdays of famous people.
$sheet1Data = [
    ["First Name", "Last Name", "Date of Birth"],
    ['Britney',  "Spears", "02-12-1981"],
    ['Michael',  "Jackson", "29-08-1958"],
    ['Christina',  "Aguilera", "18-12-1980"],
];

// Sheet 2 contains list of ferrari cars and when they were manufactured.
$sheet2Data = [
    ["Model", "Production Year Start", "Production Year End"],
    ["308 GTB",  1975, 1985],
    ["360 Spider",  1999, 2004],
    ["488 GTB",  2015, 2020],
];


$worksheet1->fromArray($sheet1Data);
$worksheet2->fromArray($sheet2Data);


// Change the widths of the columns to be appropriately large for the content in them.
// https://stackoverflow.com/questions/62203260/php-spreadsheet-cant-find-the-function-to-auto-size-column-width
$worksheets = [$worksheet1, $worksheet2];

foreach ($worksheets as $worksheet)
{
    foreach ($worksheet->getColumnIterator() as $column)
    {
        $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        $worksheet->mergeCells();
    }
}

// Save to file.
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($mySpreadsheet);
$writer->save('output.xlsx');

?>
<a style="display: none" href="output.xlsx">
    <button>Plik</button>
</a>
<script>
    const downloadBtn = document.querySelector('a');
    downloadBtn.click();
</script>