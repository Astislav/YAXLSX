<?php
declare(strict_types=1);

namespace YAXLSX\Border;

final class XLSXBorderLineStyle
{
    public string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function none(): self
    {
        return new self('');
    }

    public static function dashDot(): self
    {
        return new self('dashDot');
    }

    public static function dashDotDot(): self
    {
        return new self('dashDotDot');
    }

    public static function dashed(): self
    {
        return new self('dashed');
    }

    public static function dotted(): self
    {
        return new self('dotted');
    }

    public static function double(): self
    {
        return new self('double');
    }

    public static function hair(): self
    {
        return new self('hair');
    }

    public static function medium(): self
    {
        return new self('medium');
    }

    public static function mediumDashDot(): self
    {
        return new self('mediumDashDot');
    }

    public static function mediumDashDotDot(): self
    {
        return new self('mediumDashDotDot');
    }

    public static function mediumDashed(): self
    {
        return new self('mediumDashed');
    }

    public static function slantDashDot(): self
    {
        return new self('slantDashDot');
    }

    public static function thick(): self
    {
        return new self('thick');
    }

    public static function thin(): self
    {
        return new self('thin');
    }
}
