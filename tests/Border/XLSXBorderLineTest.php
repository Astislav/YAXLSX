<?php
declare(strict_types=1);


namespace YAXLSX\tests\Border;

use PHPUnit\Framework\TestCase;
use YAXLSX\Border\XLSXBorderLine;
use YAXLSX\Border\XLSXBorderLineStyle;
use YAXLSX\Core\XLSXColor;

class XLSXBorderLineTest extends TestCase
{
    /** @test */
    public function it_returns_valid_xml(): void
    {
        $leftBorder = XLSXBorderLine::asLeft();
        $this->assertEquals('<left/>', $leftBorder->asXml());

        $leftBorder->setColor(XLSXColor::newRed())
                   ->setLineStyle(XLSXBorderLineStyle::medium());

        $expectedXml = /** @lang XML */
            '<left style="medium">' .
            '<color rgb="FFFF0000"/>' .
            '</left>';

        $this->assertEquals($expectedXml, $leftBorder->asXml());
    }
}
