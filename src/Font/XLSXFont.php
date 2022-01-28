<?php
declare(strict_types=1);

namespace YAXLSX\Font;

use YAXLSX\Core\XLSXColor;
use YAXLSX\Core\XLSXDefaults;
use YAXLSX\HashCollection\XLSXHashable;
use function sprintf;

final class XLSXFont implements XLSXHashable
{
    public string $name;

    public int $size;

    public bool $isBold;

    public XLSXColor $color;

    public function __construct(
        string $name,
        int $size,
        XLSXColor $color,
        bool $isBold
    ) {
        $this->name = $name;
        $this->size = $size;
        $this->color = $color;
        $this->isBold = $isBold;
    }

    public static function fromColor(XLSXColor $color): self
    {
        $default = XLSXDefaults::defaultFont();

        return new self($default->name, $default->size, $color, $default->isBold);
    }

    public static function fromColorInt(int $color): self
    {
        return self::fromColor(XLSXColor::fromColorInt($color));
    }

    public function asHash(): string
    {
        return $this->name . $this->size . $this->color->asXml() . $this->isBold;
    }

    public function asXml(int $index): string
    {
        $fontXml = '<font>%s<sz val="%s"/>%s<name val="%s"/></font>';

        return sprintf(
            $fontXml,
            $this->isBold ? '<b/>' : '',
            $this->size,
            $this->color->asXml(),
            $this->name
        );
    }
}
