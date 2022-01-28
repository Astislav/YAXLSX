<?php
declare(strict_types=1);

namespace YAXLSX\tests\Sheet;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXConstraints;
use YAXLSX\Sheet\XLSXCellCoordinates;

class XLSXCellCoordinatesTest extends TestCase
{
    /** @test */
    public function it_correctly_converts_coordinates_to_string(): void
    {
        $actual = new XLSXCellCoordinates(30, 12);
        $this->assertEquals('AE13', $actual->asExcelCell());
    }

    /** @test */
    public function it_fails_for_big_column_id(): void
    {
        $this->expectExceptionMessage('columnId should be in [0, ' . XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT . ']');
        new XLSXCellCoordinates(XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT + 1, 0);
    }

    /** @test */
    public function it_fails_for_big_row_id(): void
    {
        $this->expectExceptionMessage('rowId should be in [0, ' . XLSXConstraints::MAX_ROWS_IN_SHEET_COUNT . ']');
        new XLSXCellCoordinates(0, XLSXConstraints::MAX_ROWS_IN_SHEET_COUNT + 1);
    }
}
