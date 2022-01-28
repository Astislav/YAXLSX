<?php
declare(strict_types=1);

namespace YAXLSX\Core;

use Assert\Assert;
use function dechex;
use function sprintf;
use function str_pad;
use function strtoupper;
use const STR_PAD_LEFT;

final class XLSXColor implements XLSXSerializableAsXml
{
    public int $red;

    public int $green;

    public int $blue;

    public function __construct(int $red, int $green, int $blue)
    {
        Assert::that($red)->between(0, 255, 'red value should be in [0, 255]');
        Assert::that($green)->between(0, 255, 'green value should be in [0, 255]');
        Assert::that($blue)->between(0, 255, 'blue value should be in [0, 255]');

        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public static function fromColorInt(int $color): self
    {
        $blue = $color & 0xFF;
        $green = ($color & 0xFF00) >> 8;
        $red = ($color & 0xFF0000) >> 16;

        return new self($red, $green, $blue);
    }

    public static function newBlack(): self
    {
        return new self(0, 0, 0);
    }

    public static function newWhite(): self
    {
        return new self(255, 255, 255);
    }

    public static function newRed(): self
    {
        return new self(255, 0, 0);
    }

    public static function newGreen(): self
    {
        return new self(0, 255, 0);
    }

    public static function newBlue(): self
    {
        return new self(0, 0, 255);
    }

    public static function newYellow(): self
    {
        return new self(255, 255, 0);
    }

    public static function newAqua(): self
    {
        return new self(0, 255, 255);
    }

    public static function newPurple(): self
    {
        return new self(255, 0, 255);
    }

    public static function newRtkOrange(): self
    {
        return new self(237, 125, 49);
    }

    public static function newRtkGray(): self
    {
        return new self(127, 127, 127);
    }

    public static function newRtkLightGray(): self
    {
        return new self(191, 191, 191);
    }

    public function asXml(): string
    {
        return sprintf('<color rgb="FF%s"/>', $this->asHexString());
    }

    public function asHexString(): string
    {
        $color = $this->colorInt();

        return strtoupper(str_pad(dechex($color), 6, '0', STR_PAD_LEFT));
    }

    public function colorInt(): int
    {
        return ($this->red << 16) + ($this->green << 8) + $this->blue;
    }
}
