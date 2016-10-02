# Insight Reporting

### Installation
**Step 1** - Clone repository to your local server. Ideally, you want to assign a local domain name to your local app (e.g. insight-reporting.dev). Easiest way of setting up a local environment is by using [Laravel Homestead](http://laravel.com/docs/4.2/homestead).

**Step 2**. After you have cloned the repository, from the terminal, cd into the root directory of the repo and run composer install
```
> cd insight-reporting
> composer install
```

**Step 3** - Setup/create a new database in your local environment

**Step 4** - Create a file in the root project folder named environment.php that sets the environment.
```
// insight-reporting/environment.php

<?php

return 'local';
```

**Step 5** - In the root directory, create an file named .env.local.php, and populate it with the appropriate config variables. Refer to .env-local-sample.php
```
// insight-reporting/.env-local-sample.php

<?php

return [
    "APP_URL" => '', // application base url "http://insight-reporting.dev"
    "ENCRYPTION_KEY" => '', // application encryption key
    "DEBUG" => '', // true/false
    "APP_TIMEZONE" => '', // 'Asia/Dubai', see http://php.net/manual/en/timezones.php for other options
    'DB_TYPE'   => '', // 'mysql'  other ('sqlite', 'sqlsrv', 'pgsql')
    "DB_HOST" => '', // localhost
    "DB_NAME" => '', // Database Name
    "DB_USERNAME" => '', // Database Username
    "DB_PASSWORD" => '', // Database Password
    "MANDRILL_KEY" => '', // Mandrill API key
    "AWS_KEY" => '', // Amazon WS API key
    "AWS_SECRET" => '', // Amazon WS API Secret Key
    "AWS_REGION" => '', // Amazon WS region  "ap-southeast-1"
    "AWS_BUCKET" => '', // Name of AWS bucket
    "WS_KEY" => '', // webservice key
    "WS_REPORT_URL" => '', // 36S Report Service API Endpoint
    "WS_QUERY_URL" => '', // 36S Query Service API Endpoint
    "WS_VALIDATION_REPORT_URL" => '', // 36S Metadata Validation API Endpoint
    "WEBSERVICES_USER" => '', // 36S Webservices User
    "WEBSERVICES_APIKEY" => '', // 36S Webservices API Key
    "WEBSERVICES_URL" => '', // 36S Webservices API Endpoint
];
```

**Step 6** - Migrate the database *(this now includes the Cartalyst/Sentry package migrations)*
```
php artisan migrate
```
**Step 7** - Seed the database *(only in local environment)* 
```
php artisan db:seed
```

This will seed the following database with the following:
* Permissions: add the various permissions used in application
* Groups: Administrator, Full Customer Permissions, Limited Customer Permissions
* Users: Two admin users, and 10 fake customer users randomly assigned to either
* Settings: the various system settings used by the application
* Notifications: create the default system notifications
* Product Definitions: 50 sample Product Definition requests with random data
* Product Definition Statuses: create the product definition statuses lookup table

**Step 8** - Log in to application. From your browser, go to you local app domain (e.g. http://insight-reporting.dev/), and login using one of the test user credentials.
  - admin@admin.com | admin
  - johndoe@test.com | testing

## Migrations ##
**Note:**
> If you will be deploying and running new migrations on a server whos migrations were based on a previous schema, then before you migrate, you will need to manually update the server's migration table with the following values. Otherwise you will encounter errors when trying to deploy new mirations or rollbacks.

#### DB Table: migrations ####

| name       |  batch  |
|:-------------|-----------------------|
| 2012_12_06_225921_migration_cartalyst_sentry_install_users | 1 |
| 2012_12_06_225929_migration_cartalyst_sentry_install_groups |	1 |
| 2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot |	1 |
| 2012_12_06_225988_migration_cartalyst_sentry_install_throttle |	1 |
| 2014_07_03_122455_create_companies_table | 1 |
| 2014_07_27_064914_add_company_to_users_table | 1 |
| 2014_07_28_091320_create_permissions_table | 1 |
| 2014_07_29_121740_create_profiles_table | 1 |
| 2014_07_29_124111_add_avatar_fields_to_profiles_table | 1 |
| 2014_08_10_105127_create_contracts_table | 1 |
| 2014_08_17_062933_create_products_table | 1 |
| 2014_08_18_045530_create_notifications_table | 1 |
| 2014_08_18_050933_create_notification_user_table | 1 |
| 2014_10_28_043925_create_system_settings_table | 1 |
| 2014_11_06_061847_create_product_definition_statuses_table | 1 |
| 2014_11_06_061901_create_product_definitions_table | 1 |
| 2014_11_06_105539_create_product_images_table | 1 |
| 2014_11_06_110207_create_product_attachments_table | 1 |
| 2014_11_07_205132_create_customer_supplier_table | 1 |
| 2014_11_16_111630_create_contacts_table | 1 |
| 2014_11_17_114445_create_attribute_sets_table | 1 |
| 2014_11_17_114833_create_attributes_table | 1 |
| 2014_11_17_115721_create_attribute_attribute_set_table | 1 |
| 2014_11_22_094837_create_comments_table | 1 |
| 2014_11_23_104024_add_primary_contact_field_to_companies_table | 1 |
| 2014_11_24_190527_add_settings_to_companies_table | 1 |
| 2014_12_03_175535_add_image_fields_to_product-definitions_table | 1 |
| 2014_12_03_183859_add_attachment_fields_to_product-definitions_table | 1 |
| 2015_03_25_074902_add_magento_customer_id_to_companies_table | 1 |
