<?php
declare(strict_types=1);

namespace YAXLSX\tests\Chart;

use PHPUnit\Framework\TestCase;
use YAXLSX\Chart\XLSXChartSpace;
use YAXLSX\Chart\XLSXChartSpaceManager;

class XLSXChartSpaceManagerTest extends TestCase
{
    /** @test */
    public function it_adds_chart_space_without_reference_doubling()
    {
        $chartSpace = new XLSXChartSpace();
        $manager = new XLSXChartSpaceManager();

        $chartSpace->attachToManager($manager);
        $manager->fromChartSpace($chartSpace);

        $this->assertEquals(1, $chartSpace->externalIndex());
        $this->assertEquals([ 1 => $chartSpace ], $manager->chartSpaces);
    }
}
