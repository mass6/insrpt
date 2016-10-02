[Back to: Documentation - Table of Contents](contents.md)

# Testing Email using Mailtrap.io #
Mailtrap is a fake SMTP server for development teams to test, view and share emails sent from the development 
and staging environments without spamming real customers.

## Configuration ##
To set the system up to use Mailtrap, you need to have the following environment variables defined in the **.env.local.php** file.

```
'MAIL_DRIVER'          => 'smtp',
'SMTP_HOST'            => 'mailtrap.io',
'SMTP_HOST_PORT'       => '2525',
'MAILTRAP_USERNAME'    => {Mailtrap username credentials},
'MAILTRAP_PASSWORD'    => {Mailtrap password credentials},
```

Once these values are set, outgoing emails will automatically be intercepted and sent to Mailtrap. 
The best and *safest* way to test and ensure that Mailtrap is functioning properly is to attempt 
to reset a user's password from the "Forgot Password" link on the login screen. Then go to Mailtrap.io
and verify that the reset password mail was received.

To deactivate Mailtrap and resume sending emails as normal, you only need to change the mail driver variable in the **.env.local.php** file as follows.

```
'MAIL_DRIVER'          => 'mandrill',
```

Mail delivery will then continue as normal.

