<?php
declare(strict_types=1);

namespace YAXLSX\Drawing\Anchor;

use YAXLSX\Drawing\Anchor\Content\XLSXAnchorContent;
use YAXLSX\Sheet\XLSXRectangle;

final class XLSXTwoCellAnchor
{
    public XLSXRectangle $rectangle;

    public XLSXAnchorContent $content;

    public function __construct(XLSXRectangle $rectangle, XLSXAnchorContent $content)
    {
        $this->rectangle = $rectangle;
        $this->content = $content;
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<xdr:twoCellAnchor>' .
            $this->rectangleAsXml($this->rectangle) .
            $this->content->asXml() .
            '<xdr:clientData/>' .
            '</xdr:twoCellAnchor>';
    }

    private function rectangleAsXml(XLSXRectangle $rectangle): string
    {
        $leftId = $rectangle->leftTop()->columnId;
        $topId = $rectangle->leftTop()->rowId;
        $rightId = $rectangle->rightBottom()->columnId;
        $bottomId = $rectangle->rightBottom()->rowId;

        return /** @lang XML */
            '<xdr:from>' .
            "<xdr:col>$leftId</xdr:col>" .
            '<xdr:colOff>0</xdr:colOff>' .
            "<xdr:row>$topId</xdr:row>" .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:from>' .

            '<xdr:to>' .
            "<xdr:col>$rightId</xdr:col>" .
            '<xdr:colOff>0</xdr:colOff>' .
            "<xdr:row>$bottomId</xdr:row>" .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:to>';
    }
}
