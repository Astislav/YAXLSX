<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\Reference;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\Reference\Cache\XLSXStringCache;
use YAXLSX\Chart\Reference\XLSXFormula;
use YAXLSX\Chart\Reference\XLSXStringReference;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Sheet\XLSXCellCoordinates;

class XLSXStringReferenceTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $expected = /** @lang XML */
            '<c:strRef>' .
            '<c:f>\'Лист 1\'!$B$1:$D$1</c:f>' .
            '<c:strCache>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>Март</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>Апрель</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>Май</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>';

        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Лист 1');
        $from = new XLSXCellCoordinates(1, 0);
        $to = new XLSXCellCoordinates(3, 0);

        $reference = new XLSXFormula($sheet, $from, $to);
        $strCache = new XLSXStringCache([ 'Март', 'Апрель', 'Май' ]);
        $stringReference = new XLSXStringReference($reference, $strCache);

        $actual = $stringReference->asXml();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_correct_xml_without_reference()
    {
        $expected = /** @lang XML */
            '<c:strRef>' .
            '<c:strCache>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>Март</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>Апрель</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>Май</c:v>' .
            '</c:pt>' .
            '</c:strCache>' .
            '</c:strRef>';

        $stringReference = XLSXStringReference::fromStringCache(new XLSXStringCache([ 'Март', 'Апрель', 'Май' ]));

        $actual = $stringReference->asXml();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_correct_xml_without_cache()
    {
        $expected = /** @lang XML */
            '<c:strRef>' .
            '<c:f>\'Лист 1\'!$B$1:$D$1</c:f>' .
            '</c:strRef>';

        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Лист 1');
        $from = new XLSXCellCoordinates(1, 0);
        $to = new XLSXCellCoordinates(3, 0);

        $stringReference = XLSXStringReference::fromReferenceFormula(new XLSXFormula($sheet, $from, $to));

        $actual = $stringReference->asXml();

        $this->assertEquals($expected, $actual);
    }
}
