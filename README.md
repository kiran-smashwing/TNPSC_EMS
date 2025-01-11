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
