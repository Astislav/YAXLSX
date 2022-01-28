<?php
declare(strict_types=1);

namespace YAXLSX\HashCollection;

use LogicException;
use YAXLSX\Core\XLSXSerializableAsXml;
use function count;

final class XLSXHashCollection implements XLSXSerializableAsXml
{
    private int $initialIndex;

    /** @var array<string, XLSXHashCollectionItem|null> */
    private array $items;

    private string $xmlTag;

    private int $index;

    public function __construct(string $xmlTag, int $initialIndex = 0)
    {
        $this->items = [];
        $this->initialIndex = $initialIndex;
        $this->index = 0;
        $this->xmlTag = $xmlTag;
    }

    public function shiftInitialIndexTo(int $newInitialIndex): void
    {
        if ($newInitialIndex < $this->initialIndex) {
            throw new LogicException(
                'new index value should be greater or equal than current'
            );
        }

        $this->initialIndex = $newInitialIndex;
        $this->index = 0;
    }

    public function nextIndex(): int
    {
        return $this->initialIndex + $this->index;
    }

    public function reserveAsNull(string $hash): void
    {
        $this->items[ $hash ] = null;
    }

    private function item(string $hash): ?XLSXHashCollectionItem
    {
        if (!isset($this->items[ $hash ])) {
            return null;
        }

        return $this->items[ $hash ];
    }

    public function isReservedAsNull(string $hash): bool
    {
        return $this->items[ $hash ] === null;
    }

    public function append(XLSXHashable $hashable): XLSXHashCollectionItem
    {
        $hash = $hashable->asHash();
        $item = $this->item($hash);

        if ($item) {
            return $item;
        }

        $this->reserveAsNull($hash);
        $item = XLSXHashCollectionItem::fromCollection($this, $hashable);
        $this->items[ $hash ] = $item;
        $this->index++;

        return $item;
    }

    public function asXml(): string
    {
        $xml = '';
        foreach ($this->items as $item) {
            $xml .= $item ? $item->asXml() : '';
        }

        $count = count($this->items);

        $openTag = $this->xmlTag ? "<$this->xmlTag " . 'count="' . $count . '">' : '';
        $closeTag = $this->xmlTag ? "</$this->xmlTag>" : '';

        return $xml ? $openTag . $xml . $closeTag : '';
    }
}
