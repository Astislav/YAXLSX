<?php
declare(strict_types=1);

namespace YAXLSX\tests\Drawing;

use PHPUnit\Framework\TestCase;
use YAXLSX\Drawing\XLSXDrawing;
use YAXLSX\Drawing\XLSXDrawingManager;

class XLSXDrawingManagerTest extends TestCase
{
    /** @test */
    public function it_adds_drawing_without_reference_doubling()
    {
        $drawing = new XLSXDrawing();
        $manager = new XLSXDrawingManager();

        $drawing->attachToManager($manager);
        $manager->fromDrawing($drawing);

        $this->assertEquals(1, $drawing->index());
        $this->assertEquals([ 1 => $drawing ], $manager->drawings());
    }
}
