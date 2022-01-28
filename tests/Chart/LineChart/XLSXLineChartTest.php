<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\LineChart;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\XLSXChartSpace;

class XLSXLineChartTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $chartSpace = new XLSXChartSpace();
        $actual = $chartSpace->newLineChart()
                             ->addCategories([ 'Июнь', 'Июль', 'Август' ])
                             ->addValues('Выпито пива Васей, л', [ 10, 20, 3.2 ])
                             ->addValues('Выпито пива Федей, л', [ 2, 3, 0.33 ])
                             ->addValues('Выпито пива Лешей, л', [ 4, 0.5, 2 ])
                             ->chartAsXml();

        $this->assertEquals($this->expected(), $actual);
    }

    private function expected(): string
    {
        return /** @lang XML */
            '<c:lineChart>' .
            '<c:marker val="0"/><c:smooth val="0"/>' .
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
            '</c:lineChart>';
    }
}
