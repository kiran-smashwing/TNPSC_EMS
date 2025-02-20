<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Granting Global Sequence Permissions in PostgreSQL

This guide helps you resolve `permission denied for sequence` errors in PostgreSQL, especially when inserting records that require using a sequence (e.g., auto-incrementing primary keys).

## Problem Example

If you're encountering the following error:

```text
SQLSTATE[42501]: Insufficient privilege: 7 ERROR: permission denied for sequence
```

This error occurs because the current user does not have sufficient privileges to access or use the sequence (e.g., `district_district_id_seq`). The user needs specific permissions to perform operations like `SELECT`, `USAGE`, or `UPDATE` on the sequence.

## Granting Permissions to All Sequences in a Schema

To grant `USAGE`, `SELECT`, and `UPDATE` permissions on **all sequences** in a specific schema (e.g., `public`) to a user, you can use the following command:

```sql
GRANT USAGE, SELECT, UPDATE ON ALL SEQUENCES IN SCHEMA public TO smasgkcg_tnpsc_ems_admin;
```

### Explanation of Permissions

1. **`USAGE`**: Allows the user to use the sequence, such as calling `nextval()` or `currval()`.
2. **`SELECT`**: Enables the user to view the sequence's current value (e.g., using `currval()`).
3. **`UPDATE`**: Allows the user to increment the sequence (e.g., using `nextval()`).

### When to Use

- Use this command when the user needs permissions for all sequences in the schema. This is useful for applications that dynamically interact with multiple sequences.

### Example

If your sequence is in the `public` schema and the user needing access is `smasgkcg_tnpsc_ems_admin`, run the following:

```sql
GRANT USAGE, SELECT, UPDATE ON ALL SEQUENCES IN SCHEMA public TO smasgkcg_tnpsc_ems_admin;
```

### Notes

- Replace `public` with the appropriate schema name if your sequences are not in the default `public` schema.
- Replace `smasgkcg_tnpsc_ems_admin` with the database username that needs these permissions.
- For better security, grant permissions only to the required sequences or roles if you do not need global permissions.


# PostgreSQL DateStyle Configuration

To ensure consistent date formatting (DD-MM-YYYY) in PostgreSQL, update the DateStyle setting.

## Temporary Change (Session Only)
```sql
SET DateStyle = 'ISO, DMY';
```

## Permanent Change (Database Level)
```sql
ALTER DATABASE your_database_name SET DateStyle TO 'ISO, DMY';
```


## System-Wide Change (postgresql.conf)
Edit the PostgreSQL configuration file and update:
```conf
datestyle = 'ISO, DMY'
```
Restart the PostgreSQL server after making this change.

## Verify the Setting
```sql
SHOW DateStyle;
```
# Editing the Default Apache SSL Configuration

This guide outlines the steps to modify the default Apache SSL configuration for the EMS project hosted on `ems.smashsoft.site`.

## Prerequisites
- Apache2 installed and running
- Proper SSL certificates available
- Sufficient permissions to modify configuration files

## Steps to Edit the Default Configuration

### 1. Backup the Default Configuration
Before making any changes, create a backup of the default SSL configuration:

```bash
sudo cp /etc/apache2/sites-available/000-default-le-ssl.conf /etc/apache2/sites-available/000-default-le-ssl.conf.backup
```

### 2. Edit the Default Configuration
Open the default SSL configuration file:

```bash
sudo nano /etc/apache2/sites-available/000-default-le-ssl.conf
```

Replace the existing content with the following configuration:

```apache
<VirtualHost *:443>
    ServerName ems.smashsoft.site
    DocumentRoot /var/www/html/tnpsc/public

    SSLEngine on
    SSLCertificateFile /path/to/ssl/cert.pem
    SSLCertificateKeyFile /path/to/ssl/key.pem

    # Enable proxy modules
    ProxyPreserveHost On
    ProxyRequests Off

    # Proxy WebSocket requests to Reverb
    ProxyPass /app ws://127.0.0.1:8080/app
    ProxyPassReverse /app ws://127.0.0.1:8080/app

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/ems_error.log
    CustomLog ${APACHE_LOG_DIR}/ems_access.log combined

    <Directory /var/www/html/tnpsc>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 3. Reload Apache
After saving the configuration file, reload Apache to apply the changes:

```bash
sudo systemctl reload apache2
```

## Verification

### 1. Check Apache Syntax
Run the following command to verify the configuration syntax:

```bash
sudo apachectl configtest
```
If the syntax is correct, you should see:

```
Syntax OK
```

### 2. Test the WebSocket Connection
Use `wscat` to test the WebSocket proxy:

```bash
wscat -c wss://ems.smashsoft.site/app/akekrpcgnfvkap1zusuk
```

If successful, you should see a connection message like:

```
Connected (press CTRL+C to quit)
< {"event":"pusher:connection_established","data":"{\"socket_id\":\"827750617.916928949\",\"activity_timeout\":30}"}
```

## Notes
- Ensure that `/path/to/ssl/cert.pem` and `/path/to/ssl/key.pem` are replaced with the actual SSL certificate and key paths.
- If any errors occur, check Apache logs using:
  ```bash
  sudo journalctl -xe
  ```
- If the WebSocket connection fails, verify the proxy configuration and network connectivity.

## Conclusion
Following these steps will properly configure the Apache SSL setup for `ems.smashsoft.site`, ensuring secure and efficient communication with WebSocket support.

# Configuring Systemd for Laravel Queue and Reverb

This guide provides steps to create and manage systemd service units for Laravel queue workers and WebSocket servers.

## Prerequisites
- A running Laravel application
- Systemd available on your server
- Proper permissions to create service files

## Creating Systemd Services

### 1. Create a Systemd Unit for the Laravel Queue Worker
Create a new service file for the Laravel queue worker:

```bash
sudo nano /etc/systemd/system/laravel-queue-worker.service
```

Add the following content:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=www-data
WorkingDirectory=/var/www/html/tnpsc
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3
Restart=always
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=laravel-queue-worker

[Install]
WantedBy=multi-user.target
```

Save and exit the file.

### 2. Create a Systemd Unit for the Laravel Reverb Server
Create another service file for the Reverb server:

```bash
sudo nano /etc/systemd/system/laravel-reverb.service
```

Add the following content:

```ini
[Unit]
Description=Laravel Reverb Server
After=network.target

[Service]
User=www-data
WorkingDirectory=/var/www/html/tnpsc
ExecStart=/usr/bin/php artisan reverb:start
Restart=always
StandardOutput=syslog
StandardError=syslog
SyslogIdentifier=laravel-reverb

[Install]
WantedBy=multi-user.target
```

Save and exit the file.

### 3. Enable and Start the Services
Reload systemd to recognize the new services:

```bash
sudo systemctl daemon-reload
```

Enable the services to start on boot:

```bash
sudo systemctl enable laravel-queue-worker
sudo systemctl enable laravel-reverb
```

Start the services:

```bash
sudo systemctl start laravel-queue-worker
sudo systemctl start laravel-reverb
```

### 4. Verify Service Status
To check if the services are running properly, use:

```bash
sudo systemctl status laravel-queue-worker
sudo systemctl status laravel-reverb
```

## Notes
- Ensure that `www-data` is the correct user for running Laravel. Adjust if necessary.
- If any issues occur, check logs using:
  ```bash
  journalctl -u laravel-queue-worker --no-pager
  journalctl -u laravel-reverb --no-pager
  ```
- Modify `ExecStart` commands based on Laravel's queue driver and environment requirements.

## Conclusion
Following these steps ensures that Laravel queue workers and Reverb run efficiently under systemd, providing stability and automatic restarts.
