<?php
declare(strict_types=1);

namespace YAXLSX\Chart\LineChart;

use YAXLSX\Chart\XLSXBaseChart;

final class XLSXLineChart extends XLSXBaseChart
{
    private bool $smooth;

    private bool $showMarker;

    public function __construct()
    {
        parent::__construct();
        $this->smooth = false;
        $this->showMarker = false;
    }

    public function setShowMarker(bool $showMarker): self
    {
        $this->showMarker = $showMarker;

        return $this;
    }

    public function setSmooth(bool $smooth): self
    {
        $this->smooth = $smooth;

        return $this;
    }

    public function chartTag(): string
    {
        return 'c:lineChart';
    }

    public function specificParametersXml(): string
    {
        return /** @lang XML */
            '<c:marker val="' . ($this->showMarker ? '1' : '0') . '"/>' .
            '<c:smooth val="' . ($this->smooth ? '1' : '0') . '"/>';
    }
}
