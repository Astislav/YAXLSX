<?php
declare(strict_types=1);

namespace YAXLSX\Style;

use YAXLSX\Border\XLSXManagedBorder;
use YAXLSX\Fill\XLSXManagedFill;
use YAXLSX\Font\XLSXManagedFont;
use YAXLSX\Format\XLSXManagedFormat;
use YAXLSX\HashCollection\XLSXHashable;
use function sprintf;

final class XLSXSemiManagedStyle implements XLSXHashable
{
    private XLSXManagedFont $managedFont;

    private XLSXManagedFormat $managedFormat;

    private XLSXManagedBorder $managedBorder;

    private XLSXManagedFill $managedFill;

    private XLSXStyle $sourceStyle;

    public function __construct(
        XLSXManagedFont   $managedFont,
        XLSXManagedFormat $managedFormat,
        XLSXManagedBorder $managedBorder,
        XLSXManagedFill   $managedFill,
        XLSXStyle         $sourceStyle
    ) {
        $this->managedFont = $managedFont;
        $this->managedFormat = $managedFormat;
        $this->managedBorder = $managedBorder;
        $this->managedFill = $managedFill;
        $this->sourceStyle = $sourceStyle;
    }

    public function asHash(): string
    {
        return $this->managedFont->index() .
            $this->managedFormat->index() .
            $this->managedBorder->index() .
            $this->managedFill->index() .
            $this->sourceStyle->wordWrap .
            $this->sourceStyle->alignCenter;
    }

    public function asXml(int $index): string
    {
        $styleXml = /** @lang XML */
            '<xf xfId="%s" fontId="%s" numFmtId="%s" fillId="%s" borderId="%s"%s>%s</xf>';

        $applyAlignment = $this->sourceStyle->wordWrap || $this->sourceStyle->alignCenter;

        $alignmentXml = $this->sourceStyle->wordWrap ? 'wrapText="1"' : '';
        $alignmentXml .= $this->sourceStyle->alignCenter ? ' horizontal="center" vertical="center"' : '';

        $applyModifications = '';
        $applyModifications .= $this->managedFormat->index() ? ' applyNumberFormat="1"' : '';
        $applyModifications .= $this->managedFont->index() ? ' applyFont="1"' : '';
        $applyModifications .= $this->managedBorder->index() ? ' applyBorder="1"' : '';
        $applyModifications .= $this->managedFill->index() ? ' applyFill="1"' : '';
        $applyModifications .= $applyAlignment ? ' applyAlignment="1"' : '';

        return sprintf(
            $styleXml,
            $index,
            $this->managedFont->index(),
            $this->managedFormat->index(),
            $this->managedFill->index(),
            $this->managedBorder->index(),
            $applyModifications,
            $applyAlignment ? "<alignment $alignmentXml/>" : ''
        );
    }
}
