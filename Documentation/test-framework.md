[Back to: Documentation - Table of Contents](contents.md)

# Test Framwork #
Insight Reporting utilises the **Codeception** test framework, which provides modules for Unit, Functional,
and Acceptance testing suites. You can learn more about Codeception at http://codeception.com. The test suites 
can be found under the **"tests"** directory within in the project root.

## Configuration for local testing ##
The Codeception framework is already configured for the Insight Reporting, so no installation is needed
other than just cloning the repository and running a composer install (or update if necessary). 
The only thing that needs to be setup is the local test environment and test database.

**NOTE:** The following instruction will assume the tests will be conducted in an environment named **"test"**, 
and that it will uses a database named **"insight-reporting-test"**. If you wish to use another convention, make
sure the make the necessary configurations as specified below.

### Database Setup ###
Create a new local database for testing (separate from your local development database). By default, the 
configuration is set to use a database name **insight-reporting-test**, however it can be named something
else, so long as the name chosen is reflected in the required testing configurations (see Configuration Files).

### Test Environment ###
To keep tests separate from the local development environment, the test suite is setup to use the **"test"** 
environment. This will require an addition environment file name *.env.test.php*. Again, if you wish to use another convention, make
sure the make the necessary configurations as specified below

```
// .env.test.php 

<?php

return [
    "APP_URL"                  => 'http://insight-reporting.app', // the local development url
    'DB_TYPE'                  => 'mysql',
    'DB_HOST'                  => 'localhost',
    'DB_NAME'                  => 'insight-reporting-test', // change if needed/required
    'DB_USERNAME'              => 'homestead', // change if needed/required
    'DB_PASSWORD'              => 'secret', // change if needed/required
    "TEST_USER_EMAIL"          => 'johndoe@test.com',
    "TEST_USER_PASSWORD"       => 'testing'
    
    ...
    // and the rest of the standard .env variables from .env.local.php
    
];
```

### Configuration Files ###
The below configuration files will altering if a different naming convention was used
for the environment name and/or the test database name.

* codeception.yml
* tests/functional.suite.yml
* tests/acceptance.suite.yml


#### codeception.yml file ####
```
// codeception.yml

/**
 * The dsn, user, and password properties will need to be altered to match
 * your local test environment
*/
modules:
    config:
        Db:
            dsn: 'mysql:host=localhost;dbname=insight-reporting-test'
            user: 'homestead'
            password: 'secret'
            dump: app/database/dump.sql
            cleanup: true
```

#### functional.suite.yml file ####
```
// tests/functional.suite.yml

// change the environment name if needed

class_name: FunctionalTester
modules:
    enabled: [Filesystem, FunctionalHelper, Laravel4, Db, Asserts]
    config:
      Laravel4:
        cleanup: true
        environment: test // change if needed
```

#### acceptance.suite.yml file ####
```
// tests/acceptance.suite.yml

// change the url if needed to match the local development url

class_name: AcceptanceTester
modules:
    enabled: [WebDriver]
    config:
        WebDriver:
            url: 'http://insight-reporting.app/' // change if needed
            browser: firefox
            capabilities:
                unexpectedAlertBehaviour: 'accept'
```


### Migrate and Seed the Test Database ###
Once the test database is created and any configurations changes have been made,
the it will now need to be seeded.
```
// from the terminal, within the project root directory

> php artisan migrate --env="test"
> php artisan db:seed --env="test"
```

### Create a DB Dump File ###
The test framework will restore the test database from the dump file before and after 
each test suite is run. So to start, you will need to make a dump file of the newly seeded database.
```
// from the terminal, within the project root directory

> mysqldump {insight-reporting-test} > app/database/dump.sql -u{homestead} -p
password: {yourDatabasePassword}

// where: {insight-reporting-test} = your test db name
// where: {homestead} = DB user name
// where: {yourDatabasePassword} = DB password
// NOTE: do not include braces "{}"
```

## Running the Tests ##

### Functional Tests ###
To run all test within the functional test suite:
```
// from the terminal, within the project root directory

> vendor/bin/codecept run functional
```

To run a single test:
```
// from the terminal, within the project root directory

> vendor/bin/codecept run functional {filename, e.g. Authentication/LoginCept.php}
```

### Acceptance Tests ###
Acceptance tests simulate real browser interaction. Therefore, acceptance tests require a running 
webserver before tests can be run. The test suite is configured to use the PhantomJS webserver, 
which simulates a real browser without having to actually launch a visible browser.

To launch the webserver:
```
// from the terminal, within the project root directory

> vendor/bin/phantomjs --webdriver=4444

// you will need to keep this terminal window open while the webserver is running
// To stop the webserver, press Ctl+C, or Command+C (Windows or Mac, respectively)
```

Once the webserver is running, you can run the acceptance tests. However, you will need
to open an additional terminal window to do so.
 ```
 // in a second terminal window, within the project root directory
 
 > vendor/bin/codecept run acceptance
 
 ```
 
### Run All Test Suites at Once ###
 
 To run the entire package of test suites (Acceptance, Functional, Unit):
 ```
  // in a second terminal window, within the project root directory
 
 > vendor/bin/codecept run
 ```
 
 