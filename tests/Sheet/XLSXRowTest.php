<?php
declare(strict_types=1);

namespace YAXLSX\tests\Sheet;

use PHPUnit\Framework\TestCase;
use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXWriter;
use function sys_get_temp_dir;

class XLSXRowTest extends TestCase
{
    /** @test */
    public function it_returns_valid_xml(): void
    {
        $writer = new XLSXWriter(sys_get_temp_dir() . '/');
        $sheet = $writer->newSheet('Тест');

        $manager = $writer->styleManager();
        $floatStyle = $manager->fromFormatParams('#,##0.00');
        $stringStyle = $manager->fromFontParams('Arial', 10, XLSXColor::fromColorInt(0x9400D3), true);
        $manager->fromFormatParams('general');

        $row = $sheet->newRow();
        $a = $row->addNumber(239.239, $floatStyle)->asExcelCell();
        $b = $row->addNumber(239.239)->asExcelCell();
        $row->addInlineString('Какая-нибудь строка с кастомным стилем', $stringStyle);
        $row->addInlineString('Какая-нибудь строка текстовая по-умолчанию');
        $row->addFormula("$a+$b/2", $stringStyle);
        $row->addFormula("$a-$b/2");

        $expectedXml = /** @lang XML */
            '<row r="1">' .
            '<c r="A1" t="n" s="2"><v>239.239</v></c>' .
            '<c r="B1" t="n" s="2"><v>239.239</v></c>' .
            '<c r="C1" t="inlineStr" s="3"><is><t>Какая-нибудь строка с кастомным стилем</t></is></c>' .
            '<c r="D1" t="inlineStr" s="1"><is><t>Какая-нибудь строка текстовая по-умолчанию</t></is></c>' .
            '<c r="E1" s="3"><f>A1+B1/2</f></c>' .
            '<c r="F1" s="0"><f>A1-B1/2</f></c>' .
            '</row>';
        $this->assertEquals($expectedXml, $row->asXml());
    }
}
