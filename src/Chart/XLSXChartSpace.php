<?php
declare(strict_types=1);

namespace YAXLSX\Chart;

use LogicException;
use YAXLSX\Chart\BarChart\XLSXBarChart;
use YAXLSX\Chart\LineChart\XLSXLineChart;
use YAXLSX\Core\XLSXSerializableAsXml;
use YAXLSX\Core\XLSXTools;
use YAXLSX\Sheet\XLSXRectangle;
use function count;
use function in_array;

final class XLSXChartSpace implements XLSXSerializableAsXml
{
    /** @var XLSXBaseChart[] */
    private array $charts;

    private int $externalIndex;

    private int $chartSeriesCount;

    public string $title;

    public XLSXRectangle $bounds;

    private bool $majorGridlinesVisible;

    public function __construct()
    {
        $this->charts = [];
        $this->externalIndex = -1;
        $this->title = '';
        $this->chartSeriesCount = 0;
        $this->majorGridlinesVisible = true;
        $this->bounds = XLSXRectangle::fromTopLeftIdAndWidthHeight(0, 0, 5, 10);
    }

    public function addChart(XLSXBaseChart $chart): self
    {
        if ($chart->parent() !== $this) {
            $chart->attachToChartSpace($this);
        }

        if (in_array($chart, $this->charts, true)) {
            return $this;
        }

        $this->charts[] = $chart;

        return $this;
    }

    public function newBarChart(): XLSXBarChart
    {
        $chart = new XLSXBarChart();
        $this->addChart($chart);

        return $chart;
    }

    public function newLineChart(): XLSXLineChart
    {
        $chart = new XLSXLineChart();
        $this->addChart($chart);

        return $chart;
    }

    public function lastChartSeriesIndex(): int
    {
        return $this->chartSeriesCount++;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setMajorGridlinesVisibility(bool $visible): self
    {
        $this->majorGridlinesVisible = $visible;

        return $this;
    }

    public function externalIndex(): int
    {
        return $this->externalIndex;
    }

    private function titleXml(): string
    {
        return $this->title ?
            /** @lang XML */
            '<c:title>' .
            '<c:tx>' .
            '<c:rich>' .
            '<a:bodyPr/>' .
            '<a:lstStyle/>' .
            '<a:p>' .
            '<a:pPr>' .
            '<a:defRPr/>' .
            '</a:pPr>' .
            '<a:r>' .
            '<a:rPr lang="ru-RU"/>' .
            '<a:t>' . XLSXTools::filterChars($this->title) . '</a:t>' .
            '</a:r>' .
            '</a:p>' .
            '</c:rich>' .
            '</c:tx>' .
            '<c:layout/>' .
            '</c:title>' : '';
    }

    public function isAttached(): bool
    {
        return $this->externalIndex !== -1;
    }

    public function isEmpty(): bool
    {
        return count($this->charts) === 0;
    }

    public function attachToManager(XLSXChartSpaceManager $manager): XLSXChartSpace
    {
        if ($this->isAttached()) {
            return $this;
        }

        $this->externalIndex = $manager->newIndex();

        return $manager->fromChartSpace($this);
    }

    public function asXml(): string
    {
        if ($this->isEmpty()) {
            throw new LogicException('XLSXChartSpace should not be empty');
        }

        $chartsXml = '';

        foreach ($this->charts as $chart) {
            $chartsXml .= $chart->chartAsXml();
        }

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<c:chartSpace ' .
            'xmlns:c="http://schemas.openxmlformats.org/drawingml/2006/chart" ' .
            'xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" ' .
            'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<c:lang val="ru-RU"/>' .
            '<c:chart>' .
            $this->titleXml() .
            '<c:plotArea>' .
            '<c:layout/>' .
            $chartsXml .
            $this->categoryAxis() .
            $this->valuesAxis() .
            '</c:plotArea>' .
            '<c:legend>' .
            '<c:legendPos val="r"/>' .
            '<c:layout/>' .
            '</c:legend>' .
            '<c:plotVisOnly val="1"/>' .
            '<c:dispBlanksAs val="gap"/>' .
            '</c:chart>' .
            '</c:chartSpace>';
    }

    private function categoryAxis(): string
    {
        return /** @lang XML */
            '<c:catAx>' .
            '<c:axId val="0"/>' .
            '<c:scaling>' .
            '<c:orientation val="minMax"/>' .
            '</c:scaling>' .
            '<c:axPos val="b"/>' .
            '<c:tickLblPos val="nextTo"/>' .
            '<c:crossAx val="1"/>' .
            '<c:crosses val="autoZero"/>' .
            '<c:auto val="1"/>' .
            '<c:lblAlgn val="ctr"/>' .
            '<c:lblOffset val="100"/>' .
            '</c:catAx>';
    }

    private function valuesAxis(): string
    {
        return /** @lang XML */
            '<c:valAx>' .
            '<c:axId val="1"/>' .
            '<c:scaling>' .
            '<c:orientation val="minMax"/>' .
            '</c:scaling>' .
            '<c:axPos val="l"/>' .
            ($this->majorGridlinesVisible ? '<c:majorGridlines/>' : '') .
            '<c:numFmt formatCode="0.00" sourceLinked="1"/>' .
            '<c:tickLblPos val="nextTo"/>' .
            '<c:crossAx val="0"/>' .
            '<c:crosses val="autoZero"/>' .
            '<c:crossBetween val="between"/>' .
            '</c:valAx>';
    }

    public function setBounds(XLSXRectangle $rectangle): self
    {
        $this->bounds = $rectangle;

        return $this;
    }

    public function setLeftTopColRow(int $leftColId, int $topRowId): self
    {
        $this->bounds->setLeftTopColRow($leftColId, $topRowId);

        return $this;
    }

    public function setRightBottomColRow(int $rightColId, int $bottomRowId): self
    {
        $this->bounds->setRightBottomColRow($rightColId, $bottomRowId);

        return $this;
    }
}
