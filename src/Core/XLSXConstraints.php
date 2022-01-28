<?php
declare(strict_types=1);

namespace YAXLSX\Core;


final class XLSXConstraints
{
    public const MAX_ROWS_IN_SHEET_COUNT = 1048576;
    public const MAX_COLUMNS_IN_SHEET_COUNT = 16384;
    public const MAX_CHARS_IN_CELL = 32766;
}
