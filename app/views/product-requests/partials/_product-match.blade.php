<div id="product-matches" class="row">

    <div class="col-md-12">
        <!-- BEGIN PRODUCT MATCHES ACCORDION -->
        <div class="panel-group accordion" id="accordion3">
            <div class="panel panel-defaul">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle accordion-toggle-styled {{ $open ? '' : 'collapsed' }}" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_1">
                            View Product Matches <span class="title-tip pull-right">click to expand</span></a>
                    </h4>
                </div>
                <div id="collapse_3_1" class="panel-collapse collapse {{ $open ? 'in' : '' }}">
                    <div class="panel-body">

                        <div class="form-group">
                            <input type="text" id="product-requests-search" placeholder="Search more products matches..." class="form-control" v-model="query" @keyup="findMatches | debounce 500">
                        </div>

                        <div class="col-md-12">

                            <div class="panel-group accordion" id="accordion2">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion2" href="#collapse_2">
                                                Customer Specific Matches </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_2" class="panel-collapse in">
                                        <div class="panel-body">

                                            <div id="product-request-hit-results" class="col-md-6">
                                                <h4 class="hit-results">Product Requests</h4>
                                                <div class="list-group"  v-show="productRequestHits.length">
                                                    <template v-for="hit in productRequestHits">
                                                        <a href="/product-requests/@{{ hit.requestId }}" target="_blank" class="list-group-item list-group-item"
                                                           v-html="'<span class=\'badge badge-primary\'> ' + hit.requestId + ' </span><span class=\'badge badge-info\'>' + hit.status + '</span><div>' +
                                                                           '<h5 class=\'hit-result\'>' + hit._highlightResult.productDescription.value + '</h5>' +
                                                                           '<span class=\'customer\'>' + hit.company + '</span>' +
                                                                           '<br/><span class=\'meta\'>' + hit.createdBy + 'on ' + hit.createdAt + '</span></div>'">
                                                        </a>
                                                    </template>
                                                </div>
                                                <h5 class="no-match" v-else>No matches found.</h5>
                                            </div>

                                            <div id="product-hit-results" class="col-md-6">
                                                <h4 class="hit-results">Customer Products</h4>
                                                <ul class="list-group"  v-show="customerProductHits.length">
                                                    <template v-for="hit in customerProductHits">
                                                        <li class="list-group-item"
                                                            v-html="'<span class=\'badge badge-primary\'> ' + hit.sku + ' </span><span class=\'badge badge-success\'>' + hit.price + '</span><div>' +
                                                                    '<h5 class=\'hit-result\'>' + hit._highlightResult.name.value + '</h5>' +
                                                                     {{--'<span class=\'meta\'><strong>' + hit.supplier + '</strong></span>' +--}}
                                                                    '<span class=\'uom\'>' + hit.uom + '</span>' +
                                                                   '</div>'">
                                                        </li>
                                                    </template>
                                                </ul>
                                                <h5 class="no-match" v-else>No matches found.</h5>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-group accordion" id="accordion4">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion4" href="#collapse_4">
                                                Global Product Matches</a>
                                        </h4>
                                    </div>
                                    <div id="collapse_4" class="panel-collapse in">
                                        <div class="panel-body">

                                            <div id="all-product-requests-hit-results" class="col-md-6">
                                                <h4 class="hit-results">All Product Requests</h4>
                                                <div class="list-group"  v-show="globalProductRequestHits.length">
                                                    <template v-for="hit in globalProductRequestHits">
                                                        <a href="/product-requests/@{{ hit.requestId }}" target="_blank" class="list-group-item list-group-item"
                                                           v-html="'<span class=\'badge badge-primary\'> ' + hit.requestId + ' </span><span class=\'badge badge-info\'>' + hit.status + '</span><div>' +
                                                                           '<h5 class=\'hit-result\'>' + hit._highlightResult.productDescription.value + '</h5>' +
                                                                           '<span class=\'customer\'>' + hit.company + '</span>' +
                                                                           '<br/><span class=\'meta\'>' + hit.createdBy + ' on ' + hit.createdAt + '</span></div>'">
                                                        </a>
                                                    </template>
                                                </div>
                                                <h5 class="no-match" v-else>No matches found.</h5>
                                            </div>

                                            <div id="all-products-hit-results" class="col-md-6">
                                                <h4 class="hit-results">All Products</h4>
                                                <ul class="list-group"  v-show="globalProductHits.length">
                                                    <template v-for="hit in globalProductHits">
                                                        <li class="list-group-item"
                                                            v-html="'<span class=\'badge badge-primary\'> ' + hit.sku + ' </span><span class=\'badge badge-success\'>' + hit.price + '</span><div>' +
                                                                    '<h5 class=\'hit-result\'>' + hit._highlightResult.name.value + '</h5>' +
                                                                     {{--'<span class=\'meta\'><strong>' + hit.supplier + '</strong></span>' +--}}
                                                                    '<span class=\'uom\'>' + hit.uom + '</span>' +
                                                                   '</div>'">
                                                        </li>
                                                    </template>
                                                </ul>
                                                <h5 class="no-match" v-else>No matches found.</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- END PRODUCT MATCHES ACCORDION -->
    </div>
</div>