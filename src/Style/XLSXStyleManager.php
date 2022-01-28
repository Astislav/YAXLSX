<?php
declare(strict_types=1);

namespace YAXLSX\Style;

use YAXLSX\Border\XLSXManagedBorder;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXDefaults;
use YAXLSX\Core\XLSXSerializableAsXml;
use YAXLSX\Fill\XLSXFill;
use YAXLSX\Fill\XLSXManagedFill;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Font\XLSXManagedFont;
use YAXLSX\Format\XLSXFormat;
use YAXLSX\Format\XLSXManagedFormat;
use YAXLSX\HashCollection\XLSXHashCollection;

final class XLSXStyleManager implements XLSXSerializableAsXml
{
    public const USER_FORMATS_INITIAL_INDEX = 165;
    public const USER_FILLS_INITIAL_INDEX = 3;

    private XLSXHashCollection $styles;

    private XLSXHashCollection $formats;

    private XLSXHashCollection $fonts;

    private XLSXHashCollection $borders;

    private XLSXHashCollection $fills;

    private XLSXManagedStyle $defaultStringManagedStyle;

    private XLSXManagedStyle $defaultNumberManagedStyle;

    private XLSXManagedStyle $defaultGeneralManagedStyle;

    public function __construct()
    {
        $this->styles = new XLSXHashCollection('cellXfs');
        $this->formats = new XLSXHashCollection('numFmts');
        $this->fonts = new XLSXHashCollection('fonts');
        $this->borders = new XLSXHashCollection('borders');
        $this->fills = new XLSXHashCollection('fills');

        $this->fills->append(XLSXFill::none());
        $this->fills->append(XLSXFill::gray125());

        $this->defaultGeneralManagedStyle = $this->fromStyle(XLSXDefaults::generalStyle());
        $this->defaultStringManagedStyle = $this->fromStyle(XLSXDefaults::stringStyle());
        $this->defaultNumberManagedStyle = $this->fromStyle(XLSXDefaults::numberStyle());

        $this->formats->shiftInitialIndexTo(self::USER_FORMATS_INITIAL_INDEX);
    }

    public function fromStyle(XLSXStyle $style): XLSXManagedStyle
    {
        $managedFont = new XLSXManagedFont($this->fonts->append($style->font));
        $managedFormat = new XLSXManagedFormat($this->formats->append($style->format));
        $managedBorder = new XLSXManagedBorder($this->borders->append($style->border));
        $managedFill = new XLSXManagedFill($this->fills->append($style->fill));
        $semiManaged = new XLSXSemiManagedStyle($managedFont, $managedFormat, $managedBorder, $managedFill, $style);

        return new XLSXManagedStyle($this->styles->append($semiManaged));
    }

    public function fromFontAndFormat(XLSXFont $font, XLSXFormat $format): XLSXManagedStyle
    {
        return $this->fromStyle(new XLSXStyle($font, $format, XLSXDefaults::defaultBorder()));
    }

    public function fromFormatParams(string $excelFormatString): XLSXManagedStyle
    {
        return $this->fromFormat(new XLSXFormat($excelFormatString));
    }

    public function fromFormat(XLSXFormat $format): XLSXManagedStyle
    {
        return $this->fromStyle(new XLSXStyle(XLSXDefaults::defaultFont(), $format, XLSXDefaults::defaultBorder()));
    }

    public function fromFontParams(string $name, int $size, XLSXColor $color, bool $isBold): XLSXManagedStyle
    {
        return $this->fromFont(new XLSXFont($name, $size, $color, $isBold));
    }

    public function fromFont(XLSXFont $font): XLSXManagedStyle
    {
        return $this->fromStyle(new XLSXStyle($font, XLSXDefaults::generalFormat(), XLSXDefaults::defaultBorder()));
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
            $this->formats->asXml() .
            $this->fonts->asXml() .
            $this->fills->asXml() .
            $this->borders->asXml() .
            '<cellStyleXfs>' .
            '<xf fontId="0" numFmtId="0" fillId="0" borderId="0"/>' .
            '</cellStyleXfs>' .
            $this->styles->asXml() .
            '<cellStyles count="1">' .
            '<cellStyle name="Обычный" xfId="0" builtinId="0"/>' .
            '</cellStyles>' .
            '<dxfs count="0"/>' .
            '<tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleLight16"/>' .
            '</styleSheet>';
    }

    public function defaultNumberManagedStyle(): XLSXManagedStyle
    {
        return $this->defaultNumberManagedStyle;
    }

    public function defaultStringManagedStyle(): XLSXManagedStyle
    {
        return $this->defaultStringManagedStyle;
    }

    public function defaultGeneralManagedStyle(): XLSXManagedStyle
    {
        return $this->defaultGeneralManagedStyle;
    }
}
