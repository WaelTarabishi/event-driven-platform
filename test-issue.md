
1s
2s
Run ./vendor/bin/pest
  ./vendor/bin/pest
  shell: /usr/bin/bash -e {0}
  env:
    COMPOSER_PROCESS_TIMEOUT: 0
    COMPOSER_NO_INTERACTION: 1
    COMPOSER_NO_AUDIT: 1

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   FAIL  Tests\Feature\Admin\EventManagementTest
  ⨯ it blocks non admins from the admin events area
  ⨯ it allows admins to create events
  ⨯ it recalculates available seats when an admin updates event capacity
  ⨯ it prevents deleting events that already have confirmed bookings

   FAIL  Tests\Feature\Api\BookingApiTest
  ⨯ it creates a booking and payment for an authenticated user
  ⨯ it rejects duplicate bookings for the same user and event
  ⨯ it rejects bookings when an event is sold out
  ⨯ it returns validation failure when the fake payment is declined

   FAIL  Tests\Feature\Api\EventApiTest
  ⨯ it lists only published events from the public api

   FAIL  Tests\Feature\Auth\AuthenticationTest
  ⨯ login screen can be rendered
  ⨯ users can authenticate using the login screen
  ⨯ users can not authenticate with invalid password
  ⨯ users can logout

   FAIL  Tests\Feature\Auth\EmailVerificationTest
  ⨯ email verification screen can be rendered
  ⨯ email can be verified
  ⨯ email is not verified with invalid hash

   FAIL  Tests\Feature\Auth\PasswordConfirmationTest
  ⨯ confirm password screen can be rendered
  ⨯ password can be confirmed
  ⨯ password is not confirmed with invalid password

   FAIL  Tests\Feature\Auth\PasswordResetTest
  ⨯ reset password link screen can be rendered
  ⨯ reset password link can be requested
  ⨯ reset password screen can be rendered
  ⨯ password can be reset with valid token

   FAIL  Tests\Feature\Auth\RegistrationTest
  ⨯ registration screen can be rendered
  ⨯ new users can register

   FAIL  Tests\Feature\DashboardTest
  ⨯ guests are redirected to the login page
  ⨯ authenticated users can visit the dashboard

   FAIL  Tests\Feature\ExampleTest
  ⨯ it returns a successful response

   FAIL  Tests\Feature\Settings\PasswordUpdateTest
  ⨯ password can be updated
  ⨯ correct password must be provided to update password

   FAIL  Tests\Feature\Settings\ProfileUpdateTest
  ⨯ profile page is displayed
  ⨯ profile information can be updated
  ⨯ email verification status is unchanged when the email address is unchanged
  ⨯ user can delete their account
  ⨯ correct password must be provided to delete account
  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Admin\EventManagementTest > it bloc…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Admin\EventManagementTest > it allo…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Admin\EventManagementTest > it reca…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Admin\EventManagementTest > it prev…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Api\BookingApiTest > it creates a b…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Api\BookingApiTest > it rejects dup…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Api\BookingApiTest > it rejects boo…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Api\BookingApiTest > it returns val…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Api\EventApiTest > it lists only pu…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\AuthenticationTest > login scr…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\AuthenticationTest > users can…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\AuthenticationTest > users can…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\AuthenticationTest > users can…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\EmailVerificationTest > email…   QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\EmailVerificationTest > email…   QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\EmailVerificationTest > email…   QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordConfirmationTest > con…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordConfirmationTest > pas…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordConfirmationTest > pas…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordResetTest > reset pass…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordResetTest > reset pass…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordResetTest > reset pass…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\PasswordResetTest > password c…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\RegistrationTest > registratio…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Auth\RegistrationTest > new users c…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\DashboardTest > guests are redirect…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\DashboardTest > authenticated users…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\ExampleTest > it returns a successf…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\PasswordUpdateTest > passw…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\PasswordUpdateTest > corre…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\ProfileUpdateTest > profil…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\ProfileUpdateTest > profil…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\ProfileUpdateTest > email…   QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\ProfileUpdateTest > user c…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.


  ────────────────────────────────────────────────────────────────────────────  
   FAILED  Tests\Feature\Settings\ProfileUpdateTest > correc…  QueryException   
  SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: event_booking_testing, SQL: select exists (select 1 from information_schema.tables where table_schema = schema() and table_name = 'migrations' and table_type in ('BASE TABLE', 'SYSTEM VERSIONED')) as `exists`)

  at vendor/laravel/framework/src/Illuminate/Database/Connectors/Connector.php:67
     63▕     protected function createPdoConnection($dsn, $username, #[\SensitiveParameter] $password, $options)
     64▕     {
     65▕         return version_compare(PHP_VERSION, '8.4.0', '<')
     66▕             ? new PDO($dsn, $username, $password, $options)
  ➜  67▕             : PDO::connect($dsn, $username, $password, $options); /** @phpstan-ignore staticMethod.notFound (PHP 8.4) */
     68▕     }
     69▕ 
     70▕     /**
     71▕      * Handle an exception that occurred during connect execution.



  Tests:    35 failed, 1 passed (1 assertions)
  Duration: 1.11s

Error: Process completed with exit code 2.
