<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\Reference;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\Reference\XLSXFormula;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Sheet\XLSXCellCoordinates;

class XLSXFormulaTest extends TestCase
{
    /** @test */
    public function it_returns_valid_xml_for_single_cell()
    {
        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Важный Лист');
        $cell = new XLSXCellCoordinates(0, 0);

        $ref = new XLSXFormula($sheet, $cell);

        $actual = $ref->asXml();
        $expected = '<c:f>\'Важный Лист\'!$A$1</c:f>';

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_valid_xml_for_two_cells()
    {
        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Важный Лист');
        $from = new XLSXCellCoordinates(0, 0);
        $to = new XLSXCellCoordinates(30, 12);

        $ref = new XLSXFormula($sheet, $from, $to);

        $actual = $ref->asXml();
        $expected = '<c:f>\'Важный Лист\'!$A$1:$AE$13</c:f>';

        $this->assertEquals($expected, $actual);
    }
}
