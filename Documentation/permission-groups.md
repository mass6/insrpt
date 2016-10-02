[Back to: Documentation - Table of Contents](contents.md)

# Permission Groups #
Below is the list of configured permission groups and details.

* System Administrators
* Portal: Order Reports
* Portal: Order Reports - Third Party
* Portal: Product Reports
* Portal: Product Reports - Allow Export
* Portal: Deliveries
* Portal: Deliveries - Materials Received Note
* Portal: Management Reports
* Product Requests: Requester
* Product Requests: Approver
* Product Requests: Assign Contracts
* Product Requests: Procurement Team
* Cataloguing: Requester
* Cataloguing: Supplier
* Cataloguing: Admin
* Company Settings
* Customer Data

## Group Assignment Combinations for Various User Types ##

* Portal Customers
  * Portal: Order Reports
  * Portal: Product Reports
  * Portal: Management Reports
  
* Product Requests - Requesters
  * Product Requests: Requester
  
* Product Requests - Management/Approvers
  * Product Requests: Approver
  
* Product Requests - Procurement Team
  * Product Requests: Procurement Team
  
* Cataloguing: Requesters
  * Cataloguing: Requester
  
* Cataloguing: Suppliers
  * Cataloguing: Supplier
  
* Cataloguing: Requesters
  * Cataloguing: Admin
  
* Company Administrator
  * Company Settings
  

## Group Details ##

### System Administrators ###
Provides overall system administration access to create companies, users, permissions, groups, and global system settings 
#### Permissions Granted ####
* admin.companies.create
* admin.companies.delete
* admin.groups.create
* admin.logs
* admin.permissions.create
* admin.settings
* admin.users.create

### Portal: Order Reports ###
Provides access to the Dashboard and Portal Order Reports section (not including 3rd party orders)
#### Permissions Granted ####
* dashboards
* portal.orders

### Portal: Order Reports - Third Party ###
Provides access to 3rd Party Order Reports (Dashboard widget and Reports)
#### Permissions Granted ####
* portal.orders.thirdparty

### Portal: Deliveries ###
Provides access to the view the Deliveries overview page, and delivery information within the Portal Orders pages
#### Permissions Granted ####
* portal.deliveries

### Portal: Deliveries - Materials Received Note ###
Provides access to the view Materials Received Note associated with Portal Deliveries
#### Permissions Granted ####
* portal.deliveries.materials-received-note

### Portal: Product Reports ###
Provides access to Portal Product Reports (Products, Product Ordered, Custom Product Report)
#### Permissions Granted ####
* portal.products

### Portal: Product Reports - Allow Export ###
Provides access to Portal Product Reports (Products, Product Ordered, Custom Product Report)
#### Permissions Granted ####
* portal.products.export

### Portal: Management Reports ###
Provides access to portal meta data, typically for management use (users, contracts, doa)
#### Permissions Granted ####
* portal.users
* portal.contracts
* portal.doa

### Product Requests: Requesters ###
Provides access to the Product Requests module for the role of a requester.
#### Permissions Granted ####
* product-requests.create
* product-requests.delete_draft
* product-requests.edit
* product-requests.save_draft
* product-requests.submit_request
* product-request-lists.create
* product-proposals.approve

### Product Requests: Approvers ###
Provides access to approve proposals for user's created by anyone within the user's same company in response to product requests (generally assigned to a management function). Functionality 
is limited to approving/rejecting proposals, and creating comments.
#### Permissions Granted ####
* customers.data
* product-proposals.approve
* product-requests.edit
* product-requests.save_pending_approval

### Product Requests: Assign Contracts ###
Provides access to the assign contracts functionality, allowing user to associate a product request to one or more portal contracts.
#### Permissions Granted ####
* product-requests.contracts

### Product Requests: Procurement Team ###
Provides access to functionality needed by users fulfilling the procurement function for product requests. Allows user to edit, comment on, 
close, and route product requests to other status queues within the workflow. Users can also create quotations, quotation requests, as well as 
create and submit proposals to the requester for approval.
#### Permissions Granted ####
* customers.data
* product-proposals.create
* product-proposals.edit
* product-proposals.recall_proposal
* product-proposals.save_draft
* product-proposals.submit_proposal
* product-proposals.update
* product-proposals.workbench
* product-request-lists.create
* product-requests.close
* product-requests.complete
* product-requests.contracts
* product-requests.create
* product-requests.create-on-behalf
* product-requests.delete_draft
* product-requests.edit
* product-requests.reassign_to_requester
* product-requests.reopen
* product-requests.revert_for_review
* product-requests.revert_to_cataloguing
* product-requests.save_cataloguing
* product-requests.save_deployment
* product-requests.save_draft
* product-requests.save_pending_approval
* product-requests.save_pending_proposal
* product-requests.save_reviewing
* product-requests.save_sourcing
* product-requests.submit_for_cataloguing
* product-requests.submit_for_deployment
* product-requests.submit_for_pricing
* product-requests.submit_for_sourcing
* product-requests.submit_request
* quotations.create

### Cataloguing: Requester ###
Provides access to the Product Cataloguing module for the role of a requester.
#### Permissions Granted ####
* cataloguing.products.add
* cataloguing.products.assign-supplier
* cataloguing.products.customer
* cataloguing.products.edit
* cataloguing.products.edit.full

### Cataloguing: Supplier ###
Provides access to the Product Cataloguing module for the role of a supplier (Financial data is hidden)
#### Permissions Granted ####
* cataloguing.products.edit

### Cataloguing: Admin ###
Provides full access to the Product Cataloguing module for the role of admin. Allows full control of workflow.
#### Permissions Granted ####
* cataloguing.products.add
* cataloguing.products.admin
* cataloguing.products.assign-supplier
* cataloguing.products.catalogue
* cataloguing.products.customer
* cataloguing.products.edit
* cataloguing.products.edit.full
