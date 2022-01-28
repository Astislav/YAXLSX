<?php
declare(strict_types=1);

namespace YAXLSX\tests\Core;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXTools;
use YAXLSX\Sheet\XLSXCellCoordinates;

class XLSXToolsTest extends TestCase
{
    /** @test */
    public function it_correctly_replaces_invalid_chars(): void
    {
        $invalidChars = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x0b\x0c\x0e\x0f\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1a\x1b\x1c\x1d\x1e\x1f\x7f";
        $validChars = '                              ';

        $actual = XLSXTools::filterChars($invalidChars);
        $this->assertEquals($validChars, $actual);
    }

    /** @test */
    public function it_correctly_calculates_fixed_excel_notation(): void
    {
        $actual = XLSXTools::excelNotation(new XLSXCellCoordinates(30, 12), true);
        $this->assertEquals('$AE$13', $actual);
    }

    /** @test */
    public function it_correctly_calculates_excel_notation_for_left_top_corner(): void
    {
        $actual = XLSXTools::excelNotation(new XLSXCellCoordinates(0, 0));
        $this->assertEquals('A1', $actual);
    }
}
