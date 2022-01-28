<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\XLSXChartSpace;

class XLSXChartSpaceTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $chartSpace = new XLSXChartSpace();
        $chartSpace->setTitle('Название')
                   ->newBarChart()
                   ->addCategories([ 'Июнь', 'Июль', 'Август' ])
                   ->addValues('Выпито пива Васей, л', [ 10, 20, 3.2 ])
                   ->addValues('Выпито пива Федей, л', [ 2, 3, 0.33 ])
                   ->addValues('Выпито пива Лешей, л', [ 4, 0.5, 2 ])
                   ->setGroupingPercentStacked()
                   ->setOrientationHorizontal();

        $actual = $chartSpace->asXml();

        $this->assertEquals($this->expected(), $actual);
    }

    private function expected(): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<c:chartSpace xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" ' .
            'xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" ' .
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<c:lang val="ru-RU"/>' .
            '<c:chart>' .
            '<c:title>' .
            '<c:tx>' .
            '<c:rich>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr>' .
            '<a:defRPr/>' .
            '</a:pPr>' .
            '<a:r>' .
            '<a:rPr lang="ru-RU"/>' .
            '<a:t>Название</a:t>' .
            '</a:r>' .
            '</a:p>' .
            '</c:rich>' .
            '</c:tx>' .
            '<c:layout/>' .
            '</c:title>' .
            '<c:plotArea>' .
            '<c:layout/>' .
            '<c:barChart>' .
            '<c:barDir val="bar"/>' .
            '<c:grouping val="percentStacked"/>' .
            '<c:ser>' .
            '<c:idx val="0"/>' .
            '<c:order val="0"/>' .
            '<c:dLbls>' .
            '<c:txPr>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr><a:defRPr ></a:defRPr></a:pPr>' .
            '<a:endParaRPr lang="ru-RU"/></a:p></c:txPr>' .
            '<c:showVal val="1"/>' .
            '</c:dLbls>' .
            '<c:tx>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="1"/>' .
            '<c:pt idx="0">' .
            '<c:v>Выпито пива Васей, л</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:tx>' .
            '<c:cat>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>Июнь</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>Июль</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>Август</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:cat>' .
            '<c:val>' .
            '<c:numRef>' .
            '<c:numCache>' .
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>10</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>20</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>3.2</c:v>' .
            '</c:pt>' .
            '</c:numCache>' .
            '</c:numRef>' .
            '</c:val>' .
            '</c:ser>' .
            '<c:ser>' .
            '<c:idx val="1"/>' .
            '<c:order val="1"/>' .
            '<c:dLbls>' .
            '<c:txPr>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr><a:defRPr ></a:defRPr></a:pPr>' .
            '<a:endParaRPr lang="ru-RU"/></a:p></c:txPr>' .
            '<c:showVal val="1"/>' .
            '</c:dLbls>' .
            '<c:tx>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="1"/>' .
            '<c:pt idx="0">' .
            '<c:v>Выпито пива Федей, л</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:tx>' .
            '<c:cat>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>Июнь</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>Июль</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>Август</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:cat>' .
            '<c:val>' .
            '<c:numRef>' .
            '<c:numCache>' .
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>2</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>3</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>0.33</c:v>' .
            '</c:pt>' .
            '</c:numCache>' .
            '</c:numRef>' .
            '</c:val>' .
            '</c:ser>' .
            '<c:ser>' .
            '<c:idx val="2"/>' .
            '<c:order val="2"/>' .
            '<c:dLbls>' .
            '<c:txPr>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr><a:defRPr ></a:defRPr></a:pPr>' .
            '<a:endParaRPr lang="ru-RU"/></a:p></c:txPr>' .
            '<c:showVal val="1"/>' .
            '</c:dLbls>' .
            '<c:tx>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="1"/>' .
            '<c:pt idx="0">' .
            '<c:v>Выпито пива Лешей, л</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:tx>' .
            '<c:cat>' .
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>Июнь</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>Июль</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>Август</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>' .
            '</c:cat>' .
            '<c:val>' .
            '<c:numRef>' .
            '<c:numCache>' .
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>4</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>0.5</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>2</c:v>' .
            '</c:pt>' .
            '</c:numCache>' .
            '</c:numRef>' .
            '</c:val>' .
            '</c:ser>' .
            '<c:axId val="0"/>' .
            '<c:axId val="1"/>' .
            '</c:barChart>' .
            '<c:catAx>' .
            '<c:axId val="0"/>' .
            '<c:scaling>' .
            '<c:orientation val="minMax"/>' .
            '</c:scaling>' .
            '<c:axPos val="b"/>' .
            '<c:tickLblPos val="nextTo"/>' .
            '<c:crossAx val="1"/>' .
            '<c:crosses val="autoZero"/>' .
            '<c:auto val="1"/>' .
            '<c:lblAlgn val="ctr"/>' .
            '<c:lblOffset val="100"/>' .
            '</c:catAx>' .
            '<c:valAx>' .
            '<c:axId val="1"/>' .
            '<c:scaling>' .
            '<c:orientation val="minMax"/>' .
            '</c:scaling>' .
            '<c:axPos val="l"/>' .
            '<c:majorGridlines/>' .
            '<c:numFmt formatCode="0.00" sourceLinked="1"/>' .
            '<c:tickLblPos val="nextTo"/>' .
            '<c:crossAx val="0"/>' .
            '<c:crosses val="autoZero"/>' .
            '<c:crossBetween val="between"/>' .
            '</c:valAx>' .
            '</c:plotArea>' .
            '<c:legend>' .
            '<c:legendPos val="r"/>' .
            '<c:layout/>' .
            '</c:legend>' .
            '<c:plotVisOnly val="1"/>' .
            '<c:dispBlanksAs val="gap"/>' .
            '</c:chart>' .
            '</c:chartSpace>';
    }
}
