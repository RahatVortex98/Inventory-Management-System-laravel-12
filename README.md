<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Before starting:

```
composer global require laravel/installer
```
then,
```
composer global config bin-dir --absolute
```
For using Laravel 12,

```
composer create-project laravel/laravel ims "^12.0"
```
### Add Login and registration system:

Starter kit(breeze):

```
composer require laravel/breeze --dev

php artisan breeze:install
```
Role adding existing User table:

Step 1: Make a command for that existing table
```
php artisan make:migration add_role_to_users_table --table=users  
```
Step 2: Add the column name to that new migration file

```
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('email')->default('user');
        });
    }

    
```
Step 3: Just migrate them :
```
php artisan migrate
```

Separate Dashboard for Admin & User:

Step 1: make view for admin

```
php artisan make:view admin.admin-dashboard
```
