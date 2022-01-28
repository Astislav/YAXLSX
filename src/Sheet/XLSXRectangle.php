<?php
declare(strict_types=1);

namespace YAXLSX\Sheet;

use Assert\Assert;

final class XLSXRectangle
{
    private XLSXCellCoordinates $leftTop;

    private XLSXCellCoordinates $rightBottom;

    private ?XLSXSheet $sheet;

    public function __construct(
        XLSXCellCoordinates $leftTop,
        XLSXCellCoordinates $rightBottom,
        ?XLSXSheet $sheet = null
    ) {
        Assert::that($rightBottom->columnId - $leftTop->columnId)->greaterOrEqualThan(
            0,
            'The RightBottom corner is to the left than the LeftTop'
        );

        Assert::that($rightBottom->rowId - $leftTop->rowId)->greaterOrEqualThan(
            0,
            'The RightBottom corner is upper than the LeftTop'
        );

        $this->leftTop = $leftTop;
        $this->rightBottom = $rightBottom;
        $this->sheet = $sheet;
    }

    public static function fromRowAndColIds(
        int $leftColId,
        int $topRowId,
        int $rightColId,
        int $bottomRowId,
        ?XLSXSheet $sheet = null
    ): self {
        return new self(
            new XLSXCellCoordinates($leftColId, $topRowId),
            new XLSXCellCoordinates($rightColId, $bottomRowId),
            $sheet
        );
    }

    public static function fromTopLeftIdAndWidthHeight(
        int $leftColId,
        int $topRowId,
        int $width,
        int $height,
        ?XLSXSheet $sheet = null
    ): self {
        return self::fromRowAndColIds(
            $leftColId,
            $topRowId,
            $leftColId + $width - 1,
            $topRowId + $height - 1,
            $sheet
        );
    }

    public function setLeftTopColRow(int $leftColumnId, int $topRowId): self
    {
        $this->leftTop->columnId = $leftColumnId;
        $this->leftTop->rowId = $topRowId;

        return $this;
    }

    public function setRightBottomColRow(int $rightColumnId, int $bottomRowId): self
    {
        $this->rightBottom->columnId = $rightColumnId;
        $this->rightBottom->rowId = $bottomRowId;

        return $this;
    }

    public function leftTop(): XLSXCellCoordinates
    {
        return $this->leftTop;
    }

    public function rightBottom(): XLSXCellCoordinates
    {
        return $this->rightBottom;
    }

    public function rightTop(): XLSXCellCoordinates
    {
        return new XLSXCellCoordinates($this->rightBottom->columnId, $this->leftTop->rowId);
    }

    public function leftBottom(): XLSXCellCoordinates
    {
        return new XLSXCellCoordinates($this->leftTop->columnId, $this->rightBottom->rowId);
    }

    public function width(): int
    {
        return $this->rightBottom->columnId - $this->leftTop->columnId;
    }

    public function height(): int
    {
        return $this->rightBottom->rowId - $this->leftTop->rowId;
    }

    public function sheet(): ?XLSXSheet
    {
        return $this->sheet;
    }

    public function isRow(): bool
    {
        return $this->leftTop->rowId === $this->rightBottom->rowId;
    }

    public function isColumn(): bool
    {
        return $this->leftTop->columnId === $this->rightBottom->columnId;
    }

    public function containsCell(XLSXCellCoordinates $cell): bool
    {
        return ($cell->columnId - $this->leftTop->columnId >= 0) &&
            ($this->rightBottom->columnId - $cell->columnId >= 0) &&
            ($cell->rowId - $this->leftTop->rowId >= 0) &&
            ($this->rightBottom->rowId - $cell->rowId >= 0);
    }

    public function intersectRectangle(XLSXRectangle $rectangle): bool
    {
        return $this->containsCell($rectangle->leftTop()) ||
            $this->containsCell($rectangle->rightTop()) ||
            $this->containsCell($rectangle->rightBottom()) ||
            $this->containsCell($rectangle->leftBottom());
    }

    public function asExcelCellsRegion(bool $withSheetName = false): string
    {
        return $this->asExcelRegion(
            $this->leftTop()->asExcelCell(),
            $this->rightBottom()->asExcelCell(),
            $withSheetName
        );
    }

    public function asExcelCellsRegionFixed(bool $withSheetName = false): string
    {
        return $this->asExcelRegion(
            $this->leftTop()->asExcelCellFixed(),
            $this->rightBottom()->asExcelCellFixed(),
            $withSheetName
        );
    }

    private function asExcelRegion(string $leftTop, string $rightBottom, bool $withSheetName): string
    {
        $coords = "$leftTop:$rightBottom";
        $sheetName = $withSheetName && $this->sheet ? "'" . $this->sheet->sheetName() . "'!" : '';

        return $sheetName . $coords;
    }
}
