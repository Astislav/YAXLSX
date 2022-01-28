<?php
declare(strict_types=1);

namespace YAXLSX\tests\Core;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXTools;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Sheet\XLSXCellCoordinates;
use function file_exists;
use function microtime;
use function sprintf;
use function sys_get_temp_dir;
use function unlink;

final class XLSXPerformanceTest extends TestCase
{
    /**
     * @test
     * @group slow
     */
    public function document_performance(): void
    {
        $temp = sys_get_temp_dir() . '/';
        $testFile = $temp . 'performance_test.xlsx';
        if (file_exists($testFile)) {
            unlink($testFile);
        }

        $writer = new XLSXWriter($temp);
        $styleManager = $writer->styleManager();
        $floatStyle = $styleManager->fromFormatParams('#,##0.00');
        $textStyle = $styleManager->fromFont(new XLSXFont('Comic Sans MS', 8, XLSXColor::newRed(), true));
        $sheet = $writer->newSheet('Лист');

        $count = 10 * 1000;
        $start = microtime(true);

        for ($i = 1; $i <= $count; $i++) {
            $row = $sheet->newRow();
            $a = $row->addNumber($i, $floatStyle)->asExcelCell();
            $row->addInlineString("Координаты ячейки слева - $a", $textStyle);
        }

        $durationFill = microtime(true) - $start;
        $rate = $count / $durationFill;
        echo sprintf("document creation: writing rate is %01.2f rows/second\n", $rate);

        $start = microtime(true);
        $writer->saveToFile($testFile);
        $durationSave = microtime(true) - $start;
        $rate = $count / $durationSave;
        echo sprintf("document creation: zipping rate is %01.2f rows/second\n", $rate);

        $this->assertFileExists($testFile);
    }

    /**
     * @test
     * @group slow
     */
    public function it_calculates_backed_excel_notations_faster(): void
    {
        $count = 1000000;

        $backedCoordinates = new XLSXCellCoordinates(
            XLSXTools::BACKED_ALPHABET_LENGTH - 1,
            0
        );

        $notBackedCoordinates = new XLSXCellCoordinates(
            XLSXTools::BACKED_ALPHABET_LENGTH,
            0
        );

        $start = microtime(true);
        for ($i = 1; $i <= $count; $i++) {
            XLSXTools::excelNotation($backedCoordinates);
        }

        $backedDuration = microtime(true) - $start;
        $backedRate = $count / $backedDuration;
        echo $count . '/' . $backedDuration . ' = ' . $backedRate . " backed notations/second\n";

        $start = microtime(true);
        for ($i = 1; $i <= $count; $i++) {
            XLSXTools::excelNotation($notBackedCoordinates);
        }

        $notBackedDuration = microtime(true) - $start;
        $notBackedRate = $count / $notBackedDuration;
        echo $count . '/' . $notBackedDuration . ' = ' . $notBackedRate . " notations/second\n";

        echo sprintf("backed is %01.2f %% faster!\n", (1 - $backedDuration / $notBackedDuration) * 100);

        $this->assertGreaterThan($notBackedRate, $backedRate);
    }
}
