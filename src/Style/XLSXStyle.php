<?php
declare(strict_types=1);

namespace YAXLSX\Style;

use YAXLSX\Border\XLSXBorder;
use YAXLSX\Core\XLSXDefaults;
use YAXLSX\Fill\XLSXFill;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Format\XLSXFormat;

final class XLSXStyle
{
    public XLSXFont $font;

    public XLSXFormat $format;

    public bool $wordWrap;

    public bool $alignCenter;

    public XLSXBorder $border;

    public XLSXFill $fill;

    public function __construct(
        XLSXFont $font,
        XLSXFormat $format,
        ?XLSXBorder $border = null,
        bool $wordWrap = false,
        bool $alignCenter = false,
        ?XLSXFill $fill = null
    ) {
        $this->font = $font;
        $this->format = $format;
        $this->border = $border ?: XLSXDefaults::defaultBorder();
        $this->wordWrap = $wordWrap;
        $this->alignCenter = $alignCenter;
        $this->fill = $fill ?: XLSXDefaults::defaultFillNone();
    }
}
