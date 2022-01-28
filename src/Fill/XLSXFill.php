<?php
declare(strict_types=1);

namespace YAXLSX\Fill;

use YAXLSX\Core\XLSXColor;
use YAXLSX\HashCollection\XLSXHashable;

final class XLSXFill implements XLSXHashable
{
    public string $value;

    public ?XLSXColor $foregroundColor = null;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function none(): self
    {
        return new self('none');
    }

    public static function solid(): self
    {
        return new self('solid');
    }

    public static function gray125(): self
    {
        return new self('gray125');
    }

    public function withForegroundColor(XLSXColor $color): self
    {
        $clone = clone $this;
        $clone->foregroundColor = $color;

        return $clone;
    }

    public function asXml(int $index): string
    {
        $content = ($this->foregroundColor ? '<fgColor rgb="' . $this->foregroundColor->asHexString() . '"/>' : '');

        return /** @lang XML */
            "<fill><patternFill patternType=\"$this->value\"" .
            ($content ? ">$content</patternFill>" : '/>') .
            '</fill>';
    }

    public function asHash(): string
    {
        return $this->value . ($this->foregroundColor ? $this->foregroundColor->asHexString() : '-');
    }
}
