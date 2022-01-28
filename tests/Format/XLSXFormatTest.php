<?php
declare(strict_types=1);

namespace YAXLSX\tests\Format;

use PHPUnit\Framework\TestCase;
use YAXLSX\Format\XLSXFormat;

class XLSXFormatTest extends TestCase
{
    /** @test */
    public function it_fails_for_empty_format_string(): void
    {
        $this->expectExceptionMessage('Excel Format should not be empty string');
        new XLSXFormat('');
    }

    /** @test */
    public function it_returns_correct_xml(): void
    {
        $format = new XLSXFormat('An Valid Format String');
        $expectedXml = '<numFmt numFmtId="239" formatCode="An Valid Format String"/>';

        $this->assertEquals($expectedXml, $format->asXml(239));
    }
}
