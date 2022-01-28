<?php
declare(strict_types=1);

namespace YAXLSX\tests\Style;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXDefaults;
use YAXLSX\Font\XLSXFont;
use YAXLSX\Format\XLSXFormat;
use YAXLSX\Style\XLSXStyle;
use YAXLSX\Style\XLSXStyleManager;

class XLSXStyleManagerTest extends TestCase
{
    private const DEFAULT_GENERAL_XF = /** @lang XML */
        '<xf xfId="0" fontId="0" numFmtId="0" fillId="0" borderId="0"></xf>';

    private const DEFAULT_STRING_XF = /** @lang XML */
        '<xf xfId="1" fontId="0" numFmtId="1" fillId="0" borderId="0" applyNumberFormat="1" applyAlignment="1">' .
        '<alignment wrapText="1"/>' .
        '</xf>';

    private const DEFAULT_NUMBER_XF = /** @lang XML */
        '<xf xfId="2" fontId="0" numFmtId="2" fillId="0" borderId="0" applyNumberFormat="1"></xf>';

    /** @test */
    public function its_valid_by_default(): void
    {
        $manager = new XLSXStyleManager();

        $expectedFormatsTag = '<numFmts count="3">' .
            XLSXDefaults::generalFormat()->asXml(0) .
            XLSXDefaults::stringFormat()->asXml(1) .
            XLSXDefaults::numberFormat()->asXml(2) .
            '</numFmts>';

        $expectedFontsTag = '<fonts count="1">' . XLSXDefaults::defaultFont()->asXml(0) . '</fonts>';
        $expectedStylesTag = '<cellXfs count="3">' .
            self::DEFAULT_GENERAL_XF .
            self::DEFAULT_STRING_XF .
            self::DEFAULT_NUMBER_XF .
            '</cellXfs>';

        $expectedXml = $this->expectedXml($expectedFormatsTag, $expectedFontsTag, $expectedStylesTag);
        $actualXml = $manager->asXml();

        $this->assertEquals($expectedXml, $actualXml);
    }

    private function expectedXml(string $formatsTag, string $fontsTag, string $stylesTag): string
    {
        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
            $formatsTag .
            $fontsTag .
            '<fills count="2">' .
            '<fill><patternFill patternType="none"/></fill>' .
            '<fill><patternFill patternType="gray125"/></fill>' .
            '</fills>' .
            '<borders count="1">' .
            '<border><left/><right/><top/><bottom/><diagonal/></border></borders>' .
            '<cellStyleXfs><xf fontId="0" numFmtId="0" fillId="0" borderId="0"/></cellStyleXfs>' .
            $stylesTag .
            '<cellStyles count="1"><cellStyle name="Обычный" xfId="0" builtinId="0"/></cellStyles>' .
            '<dxfs count="0"/>' .
            '<tableStyles count="0" defaultTableStyle="TableStyleMedium9" defaultPivotStyle="PivotStyleLight16"/>' .
            '</styleSheet>';
    }

    /** @test */
    public function it_generates_valid_xml(): void
    {
        $manager = new XLSXStyleManager();

        $font = new XLSXFont('Arial', 11, XLSXColor::newBlack(), false);
        $format = new XLSXFormat('# ##0\ _$₽;-# ##0\ _$₽');
        $style = new XLSXStyle($font, $format, XLSXDefaults::defaultBorder());
        $manager->fromStyle($style);

        $expectedFormatsTag = /** @lang XML */
            '<numFmts count="4">' .
            XLSXDefaults::generalFormat()->asXml(0) .
            XLSXDefaults::stringFormat()->asXml(1) .
            XLSXDefaults::numberFormat()->asXml(2) .
            $format->asXml($manager::USER_FORMATS_INITIAL_INDEX) .
            '</numFmts>';

        $expectedFontsTag = /** @lang XML */
            '<fonts count="2">' .
            XLSXDefaults::defaultFont()->asXml(0) .
            $font->asXml(1) .
            '</fonts>';

        $expectedStylesTag = /** @lang XML */
            '<cellXfs count="4">' .
            self::DEFAULT_GENERAL_XF .
            self::DEFAULT_STRING_XF .
            self::DEFAULT_NUMBER_XF .
            '<xf xfId="3" fontId="1" numFmtId="165" fillId="0" borderId="0" applyNumberFormat="1" applyFont="1"></xf>' .
            '</cellXfs>';

        $expectedXml = $this->expectedXml($expectedFormatsTag, $expectedFontsTag, $expectedStylesTag);
        $actualXml = $manager->asXml();

        $this->assertEquals($expectedXml, $actualXml);
    }
}
