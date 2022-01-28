<?php
declare(strict_types=1);

namespace YAXLSX\Sheet;

use Assert\Assert;
use YAXLSX\Core\XLSXConstraints;
use YAXLSX\Core\XLSXTools;

final class XLSXCellCoordinates
{
    public int $columnId;

    public int $rowId;

    public function __construct(int $columnId, int $rowId)
    {
        Assert::that($columnId)
              ->greaterOrEqualThan(0)
              ->lessOrEqualThan(
                  XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT,
                  'columnId should be in [0, ' . XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT . ']'
              );

        Assert::that($rowId)
              ->greaterOrEqualThan(0)
              ->lessOrEqualThan(
                  XLSXConstraints::MAX_ROWS_IN_SHEET_COUNT,
                  'rowId should be in [0, ' . XLSXConstraints::MAX_ROWS_IN_SHEET_COUNT . ']'
              );

        $this->columnId = $columnId;
        $this->rowId = $rowId;
    }

    public function asExcelCell(): string
    {
        return XLSXTools::excelNotation($this);
    }

    public function asExcelCellFixed(): string
    {
        return XLSXTools::excelNotation($this, true);
    }
}
