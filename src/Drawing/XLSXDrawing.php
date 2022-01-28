<?php
declare(strict_types=1);

namespace YAXLSX\Drawing;

use LogicException;
use YAXLSX\Chart\XLSXChartSpace;
use YAXLSX\Core\XLSXSerializableAsXml;
use YAXLSX\Drawing\Anchor\Content\XLSXAnchorContent;
use YAXLSX\Drawing\Anchor\Content\XLSXChartAnchorContent;
use YAXLSX\Drawing\Anchor\XLSXTwoCellAnchor;
use YAXLSX\Sheet\XLSXRectangle;
use function count;

final class XLSXDrawing implements XLSXSerializableAsXml
{
    /** @var XLSXTwoCellAnchor[] */
    private array $anchors;

    private int $index;

    public function __construct()
    {
        $this->anchors = [];
        $this->index = -1;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function isAttached(): bool
    {
        return $this->index !== -1;
    }

    public function attachToManager(XLSXDrawingManager $manager): XLSXDrawing
    {
        if ($this->isAttached()) {
            return $this;
        }

        $this->index = $manager->newIndex();

        return $manager->fromDrawing($this);
    }

    public function asXml(): string
    {
        $anchorsXml = '';
        foreach ($this->anchors as $anchor) {
            $anchorsXml .= $anchor->asXml();
        }

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<xdr:wsDr ' .
            'xmlns:xdr="http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing" ' .
            'xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">' .
            $anchorsXml .
            '</xdr:wsDr>';
    }

    public function asRelsXml(): string
    {
        $rels = '';
        foreach ($this->anchors as $anchor) {
            $rels .= $anchor->content->relXml();
        }

        return /** @lang XML */
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            $rels .
            '</Relationships>';
    }

    /** * @internal Используется для тестов и внутри класса */
    public function addTwoCellAnchor(XLSXRectangle $rectangle, XLSXAnchorContent $content): void
    {
        $this->anchors[] = new XLSXTwoCellAnchor($rectangle, $content);
    }

    public function addChartSpace(XLSXChartSpace $chartSpace): self
    {
        if (!$chartSpace->isAttached()) {
            $message = 'XLSXChartSpace was not attached to XLSXChartSpaceManager. Use XLSXWriter->newChartSpace()';

            throw new LogicException($message);
        }

        $chartContent = new XLSXChartAnchorContent(
            count($this->anchors),
            $chartSpace->externalIndex(),
            $chartSpace->title
        );
        $this->addTwoCellAnchor($chartSpace->bounds, $chartContent);

        return $this;
    }
}
