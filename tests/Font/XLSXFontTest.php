<?php
declare(strict_types=1);

namespace YAXLSX\tests\Font;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Font\XLSXFont;

class XLSXFontTest extends TestCase
{
    /** @test */
    public function it_returns_correct_xml(): void
    {
        $font = new XLSXFont('Calibri', 11, XLSXColor::fromColorInt(0xFF0000), true);
        $expectedXml = /** @lang XML */
            '<font>' .
            '<b/>' .
            '<sz val="11"/>' .
            '<color rgb="FFFF0000"/>' .
            '<name val="Calibri"/>' .
            '</font>';
        $this->assertEquals($expectedXml, $font->asXml(0));
    }

    /** @test */
    public function it_returns_same_hashes_for_equal_fonts(): void
    {
        $fontA = new XLSXFont('Calibri', 11, XLSXColor::fromColorInt(0xFF0000), true);
        $fontB = new XLSXFont('Calibri', 11, XLSXColor::fromColorInt(0xFF0000), true);
        $this->assertEquals($fontA->asHash(), $fontB->asHash());
    }

    /** @test */
    public function it_returns_different_hashes_for_unequal_fonts(): void
    {
        $fontA = new XLSXFont('Calibri', 11, XLSXColor::fromColorInt(0xFF0000), true);
        $fontB = new XLSXFont('Arial', 14, XLSXColor::fromColorInt(0xFFFF00), false);
        $this->assertNotEquals($fontA->asHash(), $fontB->asHash());
    }
}
