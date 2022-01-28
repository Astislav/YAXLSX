<?php
declare(strict_types=1);

namespace YAXLSX\tests\Fill;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Fill\XLSXFill;

final class XLSXFillTest extends TestCase
{
    /** @test */
    public function its_valid_for_solid(): void
    {
        $sut = XLSXFill::none();
        $actualXml = $sut->asXml(0);
        $expectedXml = '<fill><patternFill patternType="none"/></fill>';

        $this->assertEquals($expectedXml, $actualXml);
    }

    /** @test */
    public function its_valid_for_with_color(): void
    {
        $sut = XLSXFill::solid()->withForegroundColor(XLSXColor::newRtkOrange());
        $actualXml = $sut->asXml(0);
        $expectedXml = '<fill><patternFill patternType="solid"><fgColor rgb="ED7D31"/></patternFill></fill>';

        $this->assertEquals($expectedXml, $actualXml);
    }
}
