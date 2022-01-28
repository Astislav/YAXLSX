<?php
declare(strict_types=1);

namespace YAXLSX\Border;

use YAXLSX\HashCollection\XLSXHashable;

final class XLSXBorder implements XLSXHashable
{
    public XLSXBorderLine $left;

    public XLSXBorderLine $right;

    public XLSXBorderLine $top;

    public XLSXBorderLine $bottom;

    public XLSXBorderLine $diagonal;

    public function __construct()
    {
        $this->left = XLSXBorderLine::asLeft();
        $this->right = XLSXBorderLine::asRight();
        $this->top = XLSXBorderLine::asTop();
        $this->bottom = XLSXBorderLine::asBottom();
        $this->diagonal = XLSXBorderLine::asDiagonal();
    }

    public function asHash(): string
    {
        return ($this->left->lineStyle->value ?: '-') .
            ($this->right->lineStyle->value ?: '-') .
            ($this->top->lineStyle->value ?: '-') .
            ($this->bottom->lineStyle->value ?: '-') .
            ($this->diagonal->lineStyle->value ?: '-');
    }

    public function asXml(int $index): string
    {
        return /** @lang XML */
            '<border>' .
            $this->left->asXml() .
            $this->right->asXml() .
            $this->top->asXml() .
            $this->bottom->asXml() .
            $this->diagonal->asXml() .
            '</border>';
    }
}
