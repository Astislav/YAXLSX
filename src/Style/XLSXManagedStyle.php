<?php
declare(strict_types=1);

namespace YAXLSX\Style;

use YAXLSX\HashCollection\XLSXHashCollectionItem;
use YAXLSX\HashCollection\XLSXManagedHashable;

final class XLSXManagedStyle implements XLSXManagedHashable
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
}
