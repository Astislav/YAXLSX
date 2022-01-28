<?php
declare(strict_types=1);

namespace YAXLSX\tests\Core;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;

class XLSXColorTest extends TestCase
{
    /** @test */
    public function it_correctly_creates_from_integer(): void
    {
        $anOrangeColor = XLSXColor::fromColorInt(0xFF944D);
        $this->assertEquals(0xFF, $anOrangeColor->red);
        $this->assertEquals(0x94, $anOrangeColor->green);
        $this->assertEquals(0x4D, $anOrangeColor->blue);
    }

    /** @test */
    public function it_returns_correct_xmls(): void
    {
        $red = XLSXColor::newRed();
        $green = XLSXColor::newGreen();
        $blue = XLSXColor::newBlue();

        $yellow = XLSXColor::newYellow();
        $aqua = XLSXColor::newAqua();
        $purple = XLSXColor::newPurple();

        $anPlasticOrange = new XLSXColor(255, 148, 77);

        $this->assertEquals('<color rgb="FFFF0000"/>', $red->asXml());
        $this->assertEquals('<color rgb="FF00FF00"/>', $green->asXml());
        $this->assertEquals('<color rgb="FF0000FF"/>', $blue->asXml());
        $this->assertEquals('<color rgb="FFFFFF00"/>', $yellow->asXml());
        $this->assertEquals('<color rgb="FF00FFFF"/>', $aqua->asXml());
        $this->assertEquals('<color rgb="FFFF00FF"/>', $purple->asXml());
        $this->assertEquals('<color rgb="FFFF944D"/>', $anPlasticOrange->asXml());
    }
}
