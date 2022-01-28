<?php
declare(strict_types=1);

namespace YAXLSX\Chart\Reference;

use YAXLSX\Chart\Reference\Cache\XLSXNumberCache;

final class XLSXNumberReference
{
    private ?XLSXFormula $formula;

    private ?XLSXNumberCache $numCache;

    public function __construct(?XLSXFormula $formula, ?XLSXNumberCache $numCache)
    {
        $this->formula = $formula;
        $this->numCache = $numCache;
    }

    public static function fromReferenceFormula(XLSXFormula $formula): self
    {
        return new self($formula, null);
    }

    public static function fromNumberCache(XLSXNumberCache $numberCache): self
    {
        return new self(null, $numberCache);
    }

    /** @param float[] $floats */
    public static function fromNumberArray(array $floats): self
    {
        return self::fromNumberCache(new XLSXNumberCache($floats));
    }

    public function asXml(): string
    {
        return /** @lang XML */
            '<c:numRef>' .
            $this->formulaXml() .
            $this->numCacheXml() .
            '</c:numRef>';
    }

    private function formulaXml(): string
    {
        return $this->formula ? $this->formula->asXml() : '';
    }

    private function numCacheXml(): string
    {
        return $this->numCache ? $this->numCache->asXml() : '';
    }
}
