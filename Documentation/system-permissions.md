[Back to: Documentation - Table of Contents](contents.md)

# System Permissions #
Below is the list of system permissions along with their respective purposes

## Portal Reports ##
* **dashboards** - access to view dashboards
* **portal.orders** - access to view the portal orders reports (Pending Approval, Today, Yesterday, This Month, Last Month, Year to Date)
* **portal.orders.products-ordered** - access to the products ordered report (Dashboard widget and Product Orders report)
* **portal.orders.products-ordered.filter.suppliers** - permission to select/filter the supplier in the Products Ordered report
* **portal.orders.thirdparty** - access to the view third-party report and third party dashboard widget
* **portal.deliveries** - access to view portal order delivery information
* **portal.deliveries.material-received-note** - access to the view the materials received note attached to portal deliveries
* **portal.contracts** - access to view the portal contracts report
* **portal.users** - access to view the portal users report
* **portal.products** - access to view the portal products report
* **portal.products.export** - access to the export buttons on the Portal Products report
* **portal.approvals** - access to view the portal approval statistics report
* **portal.doa** - access to view the portal DOA report

## Product Requests ##
* **product-requests.create** - permission to create product requests
* **product-requests.create-on-behalf** - permission to create a product request on behalf of another requester/company
* **product-requests.edit** - permission to edit product requests
* **product-requests.contracts** - permission to assign/associate portal contracts with a product request
* **product-request-lists.create** - permission to create product request list (i.e. bulk upload)

#### Product Request State Transition Permissions ####

* **product-requests.save_draft** - save draft transition (Draft)
* **product-requests.save_reviewing** - save in review transition (In Review)
* **product-requests.save_sourcing** - save in sourcing transition (Sourcing)
* **product-requests.save_pending_proposal** - save pending proposal transition (Pending Proposal)
* **product-requests.save_pending_approval** - save pending approval transition (Pending Approval)
* **product-requests.save_cataloguing** - save ready for cataloguing transition (Ready to Catalogue)
* **product-requests.save_deployment** - save ready for deployment transition (Ready to Deploy)
* **product-requests.submit_request** - submit product requests for review (In Review)
* **product-requests.submit_for_sourcing** - submit for sourcing (Sourcing)
* **product-requests.submit_for_pricing** - submit for pricing (Pending Proposal)
* **product-requests.submit_for_cataloguing** - submit for cataloguing (Ready to Catalogue)
* **product-requests.submit_for_deployment** - submit for deployment (i.e. activate) the product to the required/relevant systems (Ready to Deploy)
* **product-requests.reassign_to_requester** - reassign request to requestor (Input Pending)
* **product-requests.revert_for_review** - revert request for review (In Review)
* **product-requests.revert_to_cataloguing** - revert request back to cataloguing (Ready to Catalogue)
* **product-requests.reopen** - reopen request and put in review (In Review)
* **product-requests.complete** - mark the request complete (Complete)
* **product-requests.close** - mark the request closed (Closed)
* **product-requests.delete_draft** - close a request currently in draft status (Closed)


## Product Proposals ##
* **product-proposals.create** - permission to create product proposals
* **product-proposals.edit** - permission to edit and recall product proposals
* **product-proposals.workbench** - permission to access the product proposal workbench

#### Product Proposal State Transition Permissions ####

* **product-proposals.save_draft** - save a proposal in draft state (Draft)
* **product-proposals.update** - save/update a proposal in its current state
* **product-proposals.submit_proposal** - submit a proposal for approval (Pending Approval)
* **product-proposals.recall_proposal** - recall a proposal and put it back in draft status (Draft)
* **product-proposals.approve** - approve or reject a proposal


## Quotations/Quotation Requests ##
* **quotations.create** - permission to create and edit quotations and quotation-requests


## Catalog Requests ##
* **cataloguing.products.customer** - access to the view list of completed catalog requests
* **cataloguing.products.add** - permission to the create a new catalog request
* **cataloguing.products.edit** - permission to edit a new catalog request
* **cataloguing.products.edit.full** - permission to view complete request details (including sensitive customer data, such as price)
* **cataloguing.products.catalogue** - permission to assign catalog request to the customer
* **cataloguing.products.assign-supplier** - permission to assign catalog request to the associated supplier
* **cataloguing.products.admin** - full view, assignment, and admin permissions of catalog requests (import, export)

## Data Access & Settings ##
* **customers.data** - permission to view all data for user's associated company (currently only applies to Product Request)
* **customers.company-settings** - permission to administer the user's company settings

## Administrative Permissions ##

* **admin** - access to the admin section
* **admin.companies.create** - permission to create new companies
* **admin.companies.delete** - permission to delete existing companies
* **admin.users.create** - permission to administer users
* **admin.permissions.create** - permission to create system permissions
* **admin.groups.create** - permission to administer system permission groups
* **admin.settings** - permission to administer the global system settings
* **admin.logs** - permission to view the system log files


