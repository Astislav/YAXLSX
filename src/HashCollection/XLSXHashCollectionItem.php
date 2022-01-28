<?php
declare(strict_types=1);

namespace YAXLSX\HashCollection;

use YAXLSX\Core\XLSXSerializableAsXml;

class XLSXHashCollectionItem implements XLSXSerializableAsXml
{
    private int $index;

    private XLSXHashable $hashable;

    private function __construct(XLSXHashable $hashable, int $index)
    {
        $this->index = $index;
        $this->hashable = $hashable;
    }

    public static function fromCollection(XLSXHashCollection $collection, XLSXHashable $hashable): self
    {
        $hash = $hashable->asHash();

        if ($collection->isReservedAsNull($hash)) {
            return new self($hashable, $collection->nextIndex());
        }

        return $collection->append($hashable);
    }

    public function hashable(): XLSXHashable
    {
        return $this->hashable;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function asXml(): string
    {
        return $this->hashable->asXml($this->index);
    }
}
