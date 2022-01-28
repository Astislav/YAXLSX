<?php
declare(strict_types=1);

namespace YAXLSX\Font;

use YAXLSX\HashCollection\XLSXHashable;
use YAXLSX\HashCollection\XLSXHashCollectionItem;
use YAXLSX\HashCollection\XLSXManagedHashable;

final class XLSXManagedFont implements XLSXManagedHashable
{
    private XLSXHashCollectionItem $collectionItem;

    public function __construct(XLSXHashCollectionItem $collectionItem)
    {
        $this->collectionItem = $collectionItem;
    }

    public function index(): int
    {
        return $this->collectionItem->index();
    }

    public function hashable(): XLSXHashable
    {
        return $this->collectionItem->hashable();
    }
}
