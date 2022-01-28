<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\Reference;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\Reference\Cache\XLSXNumberCache;
use YAXLSX\Chart\Reference\XLSXFormula;
use YAXLSX\Chart\Reference\XLSXNumberReference;
use YAXLSX\Core\XLSXWriter;
use YAXLSX\Sheet\XLSXCellCoordinates;

class XLSXNumberReferenceTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $expected = /** @lang XML */
            '<c:numRef>' .
            '<c:f>\'Лист 1\'!$B$2:$D$2</c:f>' .
            '<c:numCache>' .
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>1.2</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>2.5</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>3.28</c:v>' .
            '</c:pt>' .
            '</c:numCache>' .
            '</c:numRef>';

        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Лист 1');
        $from = new XLSXCellCoordinates(1, 1);
        $to = new XLSXCellCoordinates(3, 1);

        $reference = new XLSXFormula($sheet, $from, $to);
        $numCache = new XLSXNumberCache([ 1.2, 2.5, 3.28 ]);
        $numReference = new XLSXNumberReference($reference, $numCache);

        $actual = $numReference->asXml();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_correct_xml_without_reference()
    {
        $expected = /** @lang XML */
            '<c:numRef>' .
            '<c:numCache>' .
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="3"/>' .
            '<c:pt idx="0">' .
            '<c:v>1.2</c:v>' .
            '</c:pt>' .
            '<c:pt idx="1">' .
            '<c:v>2.5</c:v>' .
            '</c:pt>' .
            '<c:pt idx="2">' .
            '<c:v>3.28</c:v>' .
            '</c:pt>' .
            '</c:numCache>' .
            '</c:numRef>';

        $numReference = XLSXNumberReference::fromNumberCache(new XLSXNumberCache([ 1.2, 2.5, 3.28 ]));

        $actual = $numReference->asXml();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_returns_correct_xml_without_cache()
    {
        $expected = /** @lang XML */
            '<c:numRef>' .
            '<c:f>\'Лист 1\'!$B$2:$D$2</c:f>' .
            '</c:numRef>';

        $writer = new XLSXWriter('');
        $sheet = $writer->newSheet('Лист 1');

        $from = new XLSXCellCoordinates(1, 1);
        $to = new XLSXCellCoordinates(3, 1);

        $numReference = XLSXNumberReference::fromReferenceFormula(new XLSXFormula($sheet, $from, $to));

        $actual = $numReference->asXml();

        $this->assertEquals($expected, $actual);
    }
}
