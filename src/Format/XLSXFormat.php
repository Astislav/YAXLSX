<?php
declare(strict_types=1);

namespace YAXLSX\Format;

use Assert\Assert;
use YAXLSX\HashCollection\XLSXHashable;
use function sprintf;

final class XLSXFormat implements XLSXHashable
{
    public string $excelFormat;

    public function __construct(string $excelFormat)
    {
        Assert::that($excelFormat)->notEq('', 'Excel Format should not be empty string');
        $this->excelFormat = $excelFormat;
    }

    public function asHash(): string
    {
        return $this->excelFormat;
    }

    public function asXml(int $index): string
    {
        $formatXml = '<numFmt numFmtId="%s" formatCode="%s"/>';

        return sprintf($formatXml, $index, $this->excelFormat);
    }
}
