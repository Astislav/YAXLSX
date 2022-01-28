<?php
declare(strict_types=1);

namespace YAXLSX\Chart;

final class XLSXDataLabelPosition
{
    public string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function left(): self
    {
        return new self('l');
    }

    public static function right(): self
    {
        return new self('r');
    }

    public static function top(): self
    {
        return new self('t');
    }

    public static function bottom(): self
    {
        return new self('b');
    }

    public static function center(): self
    {
        return new self('ctr');
    }

    public static function bestFit(): self
    {
        return new self('bestFit');
    }

    public static function insideBase(): self
    {
        return new self('inBase');
    }

    public static function insideEnd(): self
    {
        return new self('inEnd');
    }

    public static function outsideEnd(): self
    {
        return new self('outEnd');
    }
}
