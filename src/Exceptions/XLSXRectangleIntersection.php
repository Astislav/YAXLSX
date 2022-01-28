<?php
declare(strict_types=1);

namespace YAXLSX\Exceptions;

use Exception;
use Sheet\XLSXRectangle;

final class XLSXRectangleIntersection extends Exception
{
    public static function fromRects(XLSXRectangle $new, XLSXRectangle $existing): self
    {
        $newRect = $new->asExcelCellsRegion();
        $existingRect = $existing->asExcelCellsRegion();

        return new self("New rectangle $newRect is intersects with existing rectangle $existingRect");
    }
}
