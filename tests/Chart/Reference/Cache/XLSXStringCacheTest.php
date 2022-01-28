<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\Cache;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\Reference\Cache\XLSXStringCache;

class XLSXStringCacheTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $expected = /** @lang XML */
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
            '</c:strCache>';

        $strCache = new XLSXStringCache([ 'Март', 'Апрель', 'Май' ]);
        $actual = $strCache->asXml();

        $this->assertEquals($expected, $actual);
    }
}
