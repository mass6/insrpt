<?php

use Illuminate\Support\Facades\View;

View::composer(['portal.orders.*', 'portal.report.products_custom'], 'Insight\Composers\PortalOrdersComposer');