<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart\Cache;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\Reference\Cache\XLSXNumberCache;

class XLSXNumberCacheTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml()
    {
        $expected = /** @lang XML */
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
            '</c:numCache>';

        $numCache = new XLSXNumberCache([ 1.2, 2.5, 3.28 ]);
        $actual = $numCache->asXml();

        $this->assertEquals($expected, $actual);
    }
}
