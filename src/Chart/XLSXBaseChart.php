<?php
declare(strict_types=1);

namespace YAXLSX\Chart;

use LogicException;
use YAXLSX\Chart\Reference\Cache\XLSXStringCache;
use YAXLSX\Chart\Reference\XLSXFormula;
use YAXLSX\Chart\Reference\XLSXNumberReference;
use YAXLSX\Chart\Reference\XLSXStringReference;
use YAXLSX\Core\XLSXColor;

class XLSXBaseChart
{
    private ?XLSXStringReference $categories = null;

    /** @var XLSXStringReference[] */
    private array $texts;

    /** @var XLSXNumberReference[] */
    private array $values;

    /** @var array<XLSXColor|null> */
    private array $fillColors;

    /** @var array<XLSXColor|null> */
    private array $lineColors;

    /** @var array<XLSXColor|null> */
    private array $textColors;

    /** @var array<XLSXColor|null> */
    private array $textBgColors;

    /** @var array<XLSXDataLabelPosition|null> */
    private array $dataLabelPositions;

    private ?XLSXChartSpace $parent = null;

    public function __construct()
    {
        $this->categories = null;
        $this->values = [];

        $this->texts = [];
        $this->fillColors = [];
        $this->lineColors = [];
        $this->textColors = [];
        $this->textBgColors = [];
        $this->dataLabelPositions = [];

        $this->parent = null;
    }

    private function setCategories(XLSXStringReference $categories): void
    {
        $this->categories = $categories;
    }

    public function attachToChartSpace(XLSXChartSpace $parent): void
    {
        $this->parent = $parent;
        $parent->addChart($this);
    }

    public function parent(): ?XLSXChartSpace
    {
        return $this->parent;
    }

    /** @param float[] $values */
    public function addValues(
        string $text,
        array $values,
        ?XLSXColor $fillColor = null,
        ?XLSXColor $lineColor = null,
        ?XLSXColor $textColor = null,
        ?XLSXColor $textBgColor = null,
        ?XLSXDataLabelPosition $dataLabelPosition = null
    ): self {
        $this->values[] = XLSXNumberReference::fromNumberArray($values);

        $this->texts[] = XLSXStringReference::fromStringsArray([ $text ]);
        $this->fillColors[] = $fillColor;
        $this->lineColors[] = $lineColor ?: $fillColor;
        $this->textColors[] = $textColor;
        $this->textBgColors[] = $textBgColor;
        $this->dataLabelPositions[] = $dataLabelPosition;

        return $this;
    }

    public function addValuesAsFormula(
        string $text,
        XLSXFormula $formula,
        ?XLSXColor $fillColor = null,
        ?XLSXColor $lineColor = null,
        ?XLSXColor $textColor = null,
        ?XLSXColor $textBgColor = null,
        ?XLSXDataLabelPosition $dataLabelPosition = null
    ): self {
        $this->values[] = XLSXNumberReference::fromReferenceFormula($formula);

        $this->texts[] = XLSXStringReference::fromStringsArray([ $text ]);
        $this->fillColors[] = $fillColor;
        $this->lineColors[] = $lineColor ?: $fillColor;
        $this->textColors[] = $textColor;
        $this->textBgColors[] = $textBgColor;
        $this->dataLabelPositions[] = $dataLabelPosition;

        return $this;
    }

    /** @param string[] $categories */
    public function addCategories(array $categories): self
    {
        $cache = new XLSXStringCache($categories);
        $this->setCategories(XLSXStringReference::fromStringCache($cache));

        return $this;
    }

    public function addCategoriesAsFormula(XLSXFormula $referenceFormula): self
    {
        $this->setCategories(XLSXStringReference::fromReferenceFormula($referenceFormula));

        return $this;
    }

    private function seriesAsXml(
        ?XLSXStringReference $text = null,
        ?XLSXStringReference $categories = null,
        ?XLSXNumberReference $values = null,
        ?XLSXColor $fillColor = null,
        ?XLSXColor $lineColor = null,
        ?XLSXColor $textColor = null,
        ?XLSXColor $textBgColor = null,
        ?XLSXDataLabelPosition $dataLabelPosition = null
    ): string {
        if (!$this->parent) {
            throw new LogicException('Chart should be attached to ChartSpace');
        }

        $id = $this->parent->lastChartSeriesIndex();
        $fillXml = $fillColor ? $this->asSolidFillTag($fillColor) : '';
        $lineXml = $lineColor ? ('<a:ln>' . $this->asSolidFillTag($lineColor) . '</a:ln>') : '';
        $textBgColorXml = $textBgColor ? $this->asSolidFillTag($textBgColor) : '';

        return /** @lang XML */
            '<c:ser>' .
            '<c:idx val="' . $id . '"/>' .
            '<c:order val="' . $id . '"/>' .
            '<c:dLbls>' .
            ($textBgColorXml ? "<c:spPr>$textBgColorXml</c:spPr>" : '') .
            $this->asTextParametersTag($textColor, false) .
            ($dataLabelPosition ? '<c:dLblPos val="' . $dataLabelPosition->value . '"/>' : '') .
            '<c:showVal val="1"/>' .
            '</c:dLbls>' .
            '<c:tx>' . ($text ? $text->asXml() : '') . '</c:tx>' .
            ($fillXml || $lineXml ? "<c:spPr>$fillXml$lineXml</c:spPr>" : '') .
            '<c:cat>' . ($categories ? $categories->asXml() : '') . '</c:cat>' .
            '<c:val>' . ($values ? $values->asXml() : '') . '</c:val>' .
            '</c:ser>';
    }

    private function asSolidFillTag(XLSXColor $color): string
    {
        return /** @lang XML */
            '<a:solidFill>' .
            '<a:srgbClr val="' . $color->asHexString() . '"/>' .
            '</a:solidFill>';
    }

    private function asTextParametersTag(?XLSXColor $textColor, bool $isBold): string
    {
        return /** @lang XML */
            '<c:txPr>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr>' .
            '<a:defRPr ' . ($isBold ? 'b="1"' : '') . '>' .
            ($textColor ? $this->asSolidFillTag($textColor) : '') .
            '</a:defRPr>' .
            '</a:pPr>' .
            '<a:endParaRPr lang="ru-RU"/>' .
            '</a:p>' .
            '</c:txPr>';
    }

    public function specificParametersXml(): string
    {
        return '';
    }

    public function chartTag(): string
    {
        return 'c:UNDEFINED_CHART_TAG';
    }

    public function chartAsXml(): string
    {
        if (!$this->categories) {
            throw new LogicException('No categories was defined for chart');
        }

        if (!$this->texts) {
            throw new LogicException('No values was defined for chart');
        }

        $seriesXml = '';
        foreach ($this->texts as $index => $text) {
            $seriesXml .= $this->seriesAsXml(
                $text,
                $this->categories,
                $this->values[ $index ],
                $this->fillColors[ $index ],
                $this->lineColors[ $index ],
                $this->textColors[ $index ],
                $this->textBgColors[ $index ],
                $this->dataLabelPositions[ $index ]
            );
        }

        return /** @lang XML */
            '<' . $this->chartTag() . '>' .
            $this->specificParametersXml() .
            $seriesXml .
            '<c:axId val="0"/>' .
            '<c:axId val="1"/>' .
            '</' . $this->chartTag() . '>';
    }
}
