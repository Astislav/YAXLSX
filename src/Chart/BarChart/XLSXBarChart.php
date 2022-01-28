<?php
declare(strict_types=1);

namespace YAXLSX\Chart\BarChart;

use YAXLSX\Chart\XLSXBaseChart;
use function max;
use function min;

final class XLSXBarChart extends XLSXBaseChart
{
    private const ORIENTATION_HORIZONTAL = 'bar';
    private const ORIENTATION_VERTICAL = 'col';

    private const GROUPING_CLUSTERED = 'clustered';
    private const GROUPING_PERCENT_STACKED = 'percentStacked';
    private const GROUPING_STACKED = 'stacked';
    private const GROUPING_STANDARD = 'standard';

    private string $orientation;

    private string $grouping;

    private int $overlapPercent;

    public function __construct()
    {
        parent::__construct();
        $this->overlapPercent = 0;
        $this->grouping = self::GROUPING_STANDARD;
        $this->orientation = self::ORIENTATION_VERTICAL;
    }

    public function setOrientationVertical(): self
    {
        $this->orientation = self::ORIENTATION_VERTICAL;

        return $this;
    }

    public function setOrientationHorizontal(): self
    {
        $this->orientation = self::ORIENTATION_HORIZONTAL;

        return $this;
    }

    public function setGroupingClustered(): self
    {
        $this->grouping = self::GROUPING_CLUSTERED;

        return $this;
    }

    public function setGroupingPercentStacked(): self
    {
        $this->grouping = self::GROUPING_PERCENT_STACKED;

        return $this;
    }

    public function setGroupingStacked(): self
    {
        $this->grouping = self::GROUPING_STACKED;

        return $this;
    }

    public function setGroupingStandard(): self
    {
        $this->grouping = self::GROUPING_STANDARD;

        return $this;
    }

    public function setOverlapPercent(int $percent): self
    {
        $this->overlapPercent = max(0, min($percent, 100));

        return $this;
    }

    public function chartTag(): string
    {
        return 'c:barChart';
    }

    public function specificParametersXml(): string
    {
        return /** @lang XML */
            '<c:barDir val="' . ($this->orientation ?: self::ORIENTATION_VERTICAL) . '"/>' .
            '<c:grouping val="' . ($this->grouping ?: self::GROUPING_STANDARD) . '"/>' .
            ($this->overlapPercent ? '<c:overlap val="' . $this->overlapPercent . '"/>' : '');
    }
}
