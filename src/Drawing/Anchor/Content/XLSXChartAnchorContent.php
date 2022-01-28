<?php
declare(strict_types=1);

namespace YAXLSX\Drawing\Anchor\Content;

use YAXLSX\Core\XLSXTools;

final class XLSXChartAnchorContent implements XLSXAnchorContent
{
    private int $relationId;

    private string $name;

    private int $chartId;

    public function __construct(int $relationId, int $chartId, string $name)
    {
        $this->relationId = $relationId;
        $this->chartId = $chartId;
        $this->name = $name;
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<xdr:graphicFrame macro="">' .
            '<xdr:nvGraphicFramePr>' .
            '<xdr:cNvPr id="' . $this->relationId() . '" name="' . $this->nameXml() . '"/>' .
            '<xdr:cNvGraphicFramePr/>' .
            '</xdr:nvGraphicFramePr>' .
            '<xdr:xfrm>' .
            '<a:off x="0" y="0"/>' .
            '<a:ext cx="0" cy="0"/>' .
            '</xdr:xfrm>' .
            '<a:graphic>' .
            '<a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/chart">' .
            '<c:chart ' .
            'xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" ' .
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" ' .
            'r:id="rId' . $this->relationId() . '"/>' .
            '</a:graphicData>' .
            '</a:graphic>' .
            '</xdr:graphicFrame>';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function nameXml(): string
    {
        return XLSXTools::filterChars($this->name);
    }

    public function relationId(): int
    {
        return $this->relationId;
    }

    public function relXml(): string
    {
        return /** @lang XML */
            '<Relationship ' .
            'Id="rId' . $this->relationId . '" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" ' .
            'Target="../charts/chart' . $this->chartId . '.xml"/>';
    }
}
