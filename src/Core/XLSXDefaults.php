<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use YAXLSX\Border\XLSXBorder;
use YAXLSX\Fill\XLSXFill;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Format\XLSXFormat;
use YAXLSX\Style\XLSXStyle;

final class XLSXDefaults
{
    public static function defaultFont(): XLSXFont
    {
        return new XLSXFont('Calibri', 11, XLSXColor::newBlack(), false);
    }

    public static function numberFormat(): XLSXFormat
    {
        return new XLSXFormat('#,##0.00');
    }

    public static function generalFormat(): XLSXFormat
    {
        return new XLSXFormat('GENERAL');
    }

    public static function stringFormat(): XLSXFormat
    {
        return new XLSXFormat('@');
    }

    public static function defaultBorder(): XLSXBorder
    {
        return new XLSXBorder();
    }

    public static function defaultFillNone(): XLSXFill
    {
        return XLSXFill::none();
    }

    public static function generalStyle(): XLSXStyle
    {
        return new XLSXStyle(self::defaultFont(), self::generalFormat(), self::defaultBorder(), false);
    }

    public static function stringStyle(): XLSXStyle
    {
        return new XLSXStyle(self::defaultFont(), self::stringFormat(), self::defaultBorder(), true);
    }

    public static function numberStyle(): XLSXStyle
    {
        return new XLSXStyle(self::defaultFont(), self::numberFormat(), self::defaultBorder(), false);
    }
}
