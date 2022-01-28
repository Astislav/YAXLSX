<?php
declare(strict_types=1);

namespace YAXLSX\tests\Drawing;

use PHPUnit\Framework\TestCase;
use YAXLSX\Drawing\Anchor\Content\XLSXChartAnchorContent;
use YAXLSX\Drawing\Anchor\Content\XLSXImageAnchorContent;
use YAXLSX\Drawing\XLSXDrawing;
use YAXLSX\Sheet\XLSXRectangle;

class XLSXDrawingTest extends TestCase
{
    /** @test */
    public function it_generates_correct_xmls(): void
    {
        $chartRect = XLSXRectangle::fromRowAndColIds(5, 1, 12, 15);
        $chartContent = new XLSXChartAnchorContent(1, 1, 'График 1');

        $imageRect = XLSXRectangle::fromRowAndColIds(1, 16, 24, 47);
        $imageContent = new XLSXImageAnchorContent(2, 'Рисунок 2', 'image1.jpeg');

        $drawing = new XLSXDrawing();
        $drawing->addTwoCellAnchor($chartRect, $chartContent);
        $drawing->addTwoCellAnchor($imageRect, $imageContent);

        $actualXml = $drawing->asXml();
        $actualRelsXml = $drawing->asRelsXml();

        $this->assertEquals($this->expectedXml(), $actualXml);
        $this->assertEquals($this->expectedRelsXml(), $actualRelsXml);
    }

    private function expectedXml(): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<xdr:wsDr ' .
            'xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" ' .
            'xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">' .
            '<xdr:twoCellAnchor>' .
            '<xdr:from>' .
            '<xdr:col>5</xdr:col>' .
            '<xdr:colOff>0</xdr:colOff>' .
            '<xdr:row>1</xdr:row>' .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:from>' .
            '<xdr:to>' .
            '<xdr:col>12</xdr:col>' .
            '<xdr:colOff>0</xdr:colOff>' .
            '<xdr:row>15</xdr:row>' .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:to>' .
            '<xdr:graphicFrame macro="">' .
            '<xdr:nvGraphicFramePr>' .
            '<xdr:cNvPr id="1" name="График 1"/>' .
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
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" r:id="rId1"/>' .
            '</a:graphicData>' .
            '</a:graphic>' .
            '</xdr:graphicFrame>' .
            '<xdr:clientData/>' .
            '</xdr:twoCellAnchor>' .
            '<xdr:twoCellAnchor>' .
            '<xdr:from>' .
            '<xdr:col>1</xdr:col>' .
            '<xdr:colOff>0</xdr:colOff>' .
            '<xdr:row>16</xdr:row>' .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:from>' .
            '<xdr:to>' .
            '<xdr:col>24</xdr:col>' .
            '<xdr:colOff>0</xdr:colOff>' .
            '<xdr:row>47</xdr:row>' .
            '<xdr:rowOff>0</xdr:rowOff>' .
            '</xdr:to>' .
            '<xdr:pic>' .
            '<xdr:nvPicPr>' .
            '<xdr:cNvPr id="2" name="Рисунок 2"/>' .
            '<xdr:cNvPicPr>' .
            '<a:picLocks noChangeAspect="1"/>' .
            '</xdr:cNvPicPr>' .
            '</xdr:nvPicPr>' .
            '<xdr:blipFill>' .
            '<a:blip xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" r:embed="rId2"/>' .
            '<a:stretch>' .
            '<a:fillRect/>' .
            '</a:stretch>' .
            '</xdr:blipFill>' .
            '<xdr:spPr>' .
            '<a:xfrm>' .
            '<a:off x="0" y="0"/>' .
            '<a:ext cx="0" cy="0"/>' .
            '</a:xfrm>' .
            '<a:prstGeom prst="rect">' .
            '<a:avLst/>' .
            '</a:prstGeom>' .
            '</xdr:spPr>' .
            '</xdr:pic>' .
            '<xdr:clientData/>' .
            '</xdr:twoCellAnchor>' .
            '</xdr:wsDr>';
    }

    private function expectedRelsXml(): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship ' .
            'Id="rId1" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/chart" ' .
            'Target="../charts/chart1.xml"/>' .
            '<Relationship ' .
            'Id="rId2" ' .
            'Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" ' .
            'Target="../media/image1.jpeg"/>' .
            '</Relationships>';
    }
}
