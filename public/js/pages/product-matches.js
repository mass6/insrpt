var algolia = function() {
    return {
        id:  Insight.algolia.id,
        key:  Insight.algolia.key,
        productRequestsIndex: Insight.algolia.productRequestsIndex,
        productsIndex: Insight.algolia.productsIndex,
        productRequestId:  Insight.algolia.product_request_id,
        customer:  Insight.algolia.customer,
        customerName:  Insight.algolia.customerName,
        siteOwner:  Insight.algolia.siteOwner
    };
}();

new Vue({
    el: '#general-information',
    data: {
        product_description: '',
        productRequestHits: [],
        customerProductHits: [],
        globalProductRequestHits: [],
        globalProductHits: [],
        query: ''
    },
    ready: function() {
        this.client = algoliasearch(algolia.id, algolia.key);
        this.findMatches();
    },
    methods: {
        findMatches: function() {
            var queries = [{
                indexName: algolia.productRequestsIndex,
                query: this.query.length ? this.query : this.product_description,
                params: {
                    facets: 'company_code, id',
                    facetFilters: [
                        'company_code:' + algolia.customer,
                        'id:-' + algolia.productRequestId
                    ],
                    hitsPerPage: 5,
                    queryType: 'prefixAll',
                    removeWordsIfNoResults: 'lastWords'
                }
            }, {
                indexName: algolia.productsIndex,
                query: this.query.length ? this.query : this.product_description,
                params: {
                    facets: 'customer',
                    facetFilters: [
                        'customer:' + algolia.customerName
                    ],
                    hitsPerPage: 5,
                    queryType: 'prefixAll',
                    removeWordsIfNoResults: 'lastWords'
                }
            }, {
                indexName: algolia.productRequestsIndex,
                query: this.query.length ? this.query : this.product_description,
                params: {
                    facets: 'company_code',
                    facetFilters: [
                        'company_code:-' + algolia.customer
                    ],
                    hitsPerPage: 5,
                    queryType: 'prefixAll',
                    removeWordsIfNoResults: 'lastWords'
                }
            }, {
                indexName: algolia.productsIndex,
                query: this.query.length ? this.query : this.product_description,
                params: {
                    facets: 'customer',
                    facetFilters: [
                        'customer:' + algolia.siteOwner
                    ],
                    hitsPerPage: 5,
                    queryType: 'prefixAll',
                    removeWordsIfNoResults: 'lastWords'
                }
            }];
            this.client.search(queries, function(err, response){
                console.log(response);
                this.productRequestHits = response.results[0].hits;
                this.customerProductHits = response.results[1].hits;
                this.globalProductRequestHits = response.results[2].hits;
                this.globalProductHits = response.results[3].hits;
            }.bind(this));
        }
    }
})