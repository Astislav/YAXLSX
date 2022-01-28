<?php
declare(strict_types=1);

namespace YAXLSX\Chart\Reference;

use YAXLSX\Sheet\XLSXCellCoordinates;
use YAXLSX\Sheet\XLSXSheet;

final class XLSXFormula
{
    /** @var XLSXSheet */
    private $sheet;

    /** @var XLSXCellCoordinates */
    private $from;

    /** @var XLSXCellCoordinates|null */
    private $to;

    public function __construct(XLSXSheet $sheet, XLSXCellCoordinates $from, ?XLSXCellCoordinates $to = null)
    {
        $this->sheet = $sheet;
        $this->from = $from;
        $this->to = $to;
    }

    public function asXml(): string
    {
        $ref = "'" . $this->sheet->sheetName() . "'!" . $this->from->asExcelCellFixed();
        $ref .= $this->to ? ':' . $this->to->asExcelCellFixed() : '';

        return "<c:f>$ref</c:f>";
    }

    public function sheet(): XLSXSheet
    {
        return $this->sheet;
    }

    public function from(): XLSXCellCoordinates
    {
        return $this->from;
    }

    public function to(): ?XLSXCellCoordinates
    {
        return $this->to;
    }
}
