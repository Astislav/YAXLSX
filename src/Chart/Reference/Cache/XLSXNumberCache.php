<?php
declare(strict_types=1);

namespace YAXLSX\Chart\Reference\Cache;

use function count;

final class XLSXNumberCache
{
    /** @var float[] */
    private $floats;

    /** @param array<float> $floats */
    public function __construct(array $floats)
    {
        $this->floats = $floats;
    }

    public function asXml(): string
    {
        if (!$this->floats) {
            return '';
        }

        $count = count($this->floats);
        $data = /** @lang XML */
            '<c:formatCode>General</c:formatCode>' .
            '<c:ptCount val="' . $count . '"/>';

        foreach ($this->floats as $idx => $value) {
            $data .= /** @lang XML */
                '<c:pt idx="' . $idx . '">' .
                "<c:v>$value</c:v>" .
                '</c:pt>';
        }

        return "<c:numCache>$data</c:numCache>";
    }
}
