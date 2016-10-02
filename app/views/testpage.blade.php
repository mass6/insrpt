@extends('layouts.horizontal.layout')

@section('page-head')
    @include('layouts.horizontal.partials._page-head', ['heading' => 'Test Page'])
    <style>
        input {width:800px;height:30px;padding:5px;border: 1px solid #e3e3e3;}
        em {background-color: #ffff00;font-weight: bold;font-style: normal;}
        .product-name h4 {font-size:18px;font-weight: bold;}
        .tt-menu {
            width:100%;
            border: 1px solid #e3e3e3;
            text-align:left;
            position: relative;
            z-index: 2;
        }
        .products-header, .product-requests-header {
            font-size:12px;
            padding: 6px;
            font-weight:bold;
        }
        .product-requests-header {
            background-color: #D9FFD9;
        }
        .products-header {
            background-color: #D6EDFB;
        }

        .tt-suggestion {
            margin:0;
            padding: 20px 10px;
            background-color: #ffffff;
            border-bottom: 1px solid #e3e3e3;
        }
        .tt-suggestion.tt-selectable.tt-cursor {
            background-color: #dddddd;
        }
        .product-hit {background-color: lightskyblue;}
        .product-request-hit {background-color: lightgreen;}
    </style>
@stop

@section('content')

    <input id="typeahead"
           type="text"
           v-model="query"
           v-on:keyup.enter="search | debounce 500">

    <div class="results">
        <article v-for="product in products">
            <h4><span v-html="product._highlightResult.product_description.value"></span></h4>
        </article>
    </div>
@stop

@section('bottomlinks')
    @parent
    <script src="https://cdn.jsdelivr.net/typeahead.js/0.11.1/typeahead.jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/hogan.js/3.0/hogan.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>

    <script>
        new Vue({
            el: 'body',
            data: {
                products: [],
                query: ''
            },
            ready: function() {
                this.client = $.algolia.Client('U6I6QXR743', '8657e009230438ea047b6c970875fc47');
                this.index = this.client.initIndex('product_requests_test');
                this.index2 = this.client.initIndex('products_test');

                $('#typeahead').typeahead({
                    hint:true,
                    limit: 10
                    },  [
                    {
                        source: this.index.ttAdapter({
                            hitsPerPage: 3,
                            queryType: 'prefixAll',
                            removeWordsIfNoResults: 'lastWords'
                        }),
                        displayKey: 'product_description',
                        templates: {
                            header: '<div class="product-requests-header">Product Requests</div>',
                            suggestion: function(hit) {
                                return '<div><small><strong>Request ID: ' + hit.request_id  + '</strong></small>' +
                                        '<div class="product-name"><h4>' + hit._highlightResult.product_description.value + '</h4>' +
                                        '</div></div>';
                            }
                        }
                    },
                    {
                        source: this.index2.ttAdapter({
                            hitsPerPage: 3,
                            queryType: 'prefixAll',
                            removeWordsIfNoResults: 'lastWords'
                        }),
                        displayKey: 'name',
                        templates: {
                            header: '<div class="products-header">Products</div>',
                            suggestion: function(hit) {
                                console.log(hit);
                                return '<div><small><strong>SKU: ' + hit.sku  + '</strong></small>' +
                                        '<div class="product-name"><h4>' + hit._highlightResult.name.value + '</h4>' +
                                        '</div></div>';
                            }
                        }
                    }
                ]).on('typeahead:select', function(e, suggestion) {
                    this.query = suggestion.product_description ? suggestion.product_description : suggestion.name;
                }.bind(this));
            },
            methods: {
                search: function() {
                    if (this.query.length < 3 ) {
                        this.products = [];
                        return;
                    }
                    this.index.search(this.query,
                            {
                                hitsPerPage: 30,
                                queryType: 'prefixAll',
                                removeWordsIfNoResults: 'lastWords'
                            },
                            function searchDone(err, results) {
//                                console.log(results);
                                this.products = results.hits;
                            }.bind(this));
                }
            }
        })

    </script>
@stop