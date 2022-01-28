<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use Assert\Assert;

final class XLSXColumnSetup
{
    public float $width;

    public int $groupLevel;

    public int $columnId;

    public function __construct(int $columnId)
    {
        Assert::that($columnId)
              ->between(
                  0,
                  XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT,
                  'colId should be in [0, ' . XLSXConstraints::MAX_COLUMNS_IN_SHEET_COUNT . ']'
              );

        $this->columnId = $columnId;
        $this->setWidth(0);
        $this->setGroupLevel(0);
    }

    public function setWidth(float $width): void
    {
        Assert::that($width)->greaterOrEqualThan(0, 'width should be greater or equal than zero. Zero means default');
        $this->width = $width;
    }

    public function setGroupLevel(int $groupLevel): void
    {
        Assert::that($groupLevel)->between(0, 8, 'groupLevel should be 0 and 8. Zero means default');
        $this->groupLevel = $groupLevel;
    }
}
