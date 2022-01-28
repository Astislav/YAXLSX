<?php
declare(strict_types=1);

namespace YAXLSX\tests\Border;

use PHPUnit\Framework\TestCase;
use YAXLSX\Border\XLSXBorder;

class XLSXBorderTest extends TestCase
{
    /** @test */
    public function its_valid_by_default()
    {
        $border = new XLSXBorder();
        $actualXml = $border->asXml(0);
        $expectedXml = /** @lang XML */
            '<border>' .
            '<left/>' .
            '<right/>' .
            '<top/>' .
            '<bottom/>' .
            '<diagonal/>' .
            '</border>';

        $this->assertEquals($expectedXml, $actualXml);
    }
}
