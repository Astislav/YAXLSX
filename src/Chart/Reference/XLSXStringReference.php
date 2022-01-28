<?php
declare(strict_types=1);

namespace YAXLSX\Chart\Reference;

use YAXLSX\Chart\Reference\Cache\XLSXStringCache;

final class XLSXStringReference
{
    private ?XLSXFormula $formula;

    private ?XLSXStringCache $strCache;

    public function __construct(?XLSXFormula $formula, ?XLSXStringCache $strCache)
    {
        $this->formula = $formula;
        $this->strCache = $strCache;
    }

    public static function fromReferenceFormula(XLSXFormula $formula): self
    {
        return new self($formula, null);
    }

    public static function fromStringCache(XLSXStringCache $strCache): self
    {
        return new self(null, $strCache);
    }

    /** @param string[] $strings */
    public static function fromStringsArray(array $strings): self
    {
        return self::fromStringCache(new XLSXStringCache($strings));
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<c:strRef>' .
            $this->formulaXml() .
            $this->strCacheXml() .
            '</c:strRef>';
    }

    private function formulaXml(): string
    {
        return $this->formula ? $this->formula->asXml() : '';
    }

    private function strCacheXml(): string
    {
        return $this->strCache ? $this->strCache->asXml() : '';
    }
}
