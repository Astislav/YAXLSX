<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use YAXLSX\Sheet\XLSXRectangle;

final class XLSXListDataValidation
{
    public static function asXml(
        XLSXRectangle $sourceRectangle,
        XLSXRectangle $destinationRectangle
    ): string {
        $srcRectangle = $sourceRectangle->asExcelCellsRegionFixed(true);
        $dstRectangle = $destinationRectangle->asExcelCellsRegion();

        return /** @lang XML */
            '<dataValidation type="list" allowBlank="1" showInputMessage="1" showErrorMessage="1" ' .
            "sqref=\"$dstRectangle\">" .
            "<formula1>$srcRectangle</formula1>" .
            '</dataValidation>';
    }
}
