<?php

namespace Insight\ProductDefinitions;

class ProductRequestStatus {
    const __default = null;
    const Draft = 1;
    const Review = 2;
    const Pending = 3;
    const Approved = 4;
    const Upload = 5;
    const Closed = 6;
}