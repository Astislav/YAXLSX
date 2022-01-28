<?php
declare(strict_types=1);

namespace YAXLSX\Chart\Reference\Cache;

use YAXLSX\Core\XLSXTools;
use function count;

final class XLSXStringCache
{
    /** @var string[] */
    private $strings;

    /** @param array<string> $strings */
    public function __construct(array $strings)
    {
        $this->strings = $strings;
    }

    public function asXml(): string
    {
        if (!$this->strings) {
            return '';
        }

        $count = count($this->strings);
        $data = '<c:ptCount val="' . $count . '"/>';

        foreach ($this->strings as $idx => $value) {
            $data .= /** @lang XML */
                '<c:pt idx="' . $idx . '">' .
                '<c:v>' . XLSXTools::filterChars($value) . '</c:v>' .
                '</c:pt>';
        }

        return "<c:strCache>$data</c:strCache>";
    }
}
