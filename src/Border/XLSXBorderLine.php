<?php
declare(strict_types=1);

namespace YAXLSX\Border;


use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXSerializableAsXml;

final class XLSXBorderLine implements XLSXSerializableAsXml
{
    private string $xmlTag;

    public XLSXBorderLineStyle $lineStyle;

    public ?XLSXColor $color;

    private function __construct(string $xmlTag)
    {
        $this->xmlTag = $xmlTag;
        $this->lineStyle = XLSXBorderLineStyle::none();
        $this->color = null;
    }

    public static function asLeft(): self
    {
        return new self('left');
    }

    public static function asRight(): self
    {
        return new self('right');
    }

    public static function asTop(): self
    {
        return new self('top');
    }

    public static function asBottom(): self
    {
        return new self('bottom');
    }

    public static function asDiagonal(): self
    {
        return new self('diagonal');
    }

    public function setLineStyle(XLSXBorderLineStyle $borderLineStyle): self
    {
        $this->lineStyle = $borderLineStyle;

        return $this;
    }

    public function setColor(XLSXColor $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function asXml(): string
    {
        if (!$this->lineStyle->value) {
            return "<$this->xmlTag/>";
        }

        $style = $this->lineStyle->value ? ' style="' . $this->lineStyle->value . '"' : '';
        $color = $this->color ? $this->color->asXml() : '';

        return "<$this->xmlTag$style>$color</$this->xmlTag>";
    }
}
