<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



User Roles
Admin: Full access to all modules.
Inventory Manager: Manage products, purchases, stock, and suppliers.
Sales Staff: Create sales, manage customers, view available stock.
Warehouse Staff: Update stock, receive goods, perform stock adjustments.
Viewer/Accountant (optional): View reports and financial summaries.
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

Step 2: make controller for user and admin

```
php artisan make:controller UserDashboardController
php artisan make:controller Admin/AdminDashboardController
```
step 3: Create Middleware

```
php artisan make:middleware IsAdmin
```
step 4: Middleware -> Isadmin.php:

```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (Auth::check() && Auth::user()->role === 'admin') {
        return $next($request);
    }

       abort(403);

    
    }
}

```
step 5: bootstrap/app.php:
```
  ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['admin' => \App\Http\Middleware\IsAdmin::class]);
    })
```
step 6: Web.php

```
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // other admin routes...
});
```
step 7:
app/Http/Controllers/Auth/AuthenticatedSessionController.php
```
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = $request->user();

    return $user->role === 'admin'
        ? redirect()->intended(route('admin.dashboard', absolute: false))
        : redirect()->intended(route('user.dashboard', absolute: false));
}
```
### Category model and data table:
step 1: making model and data table
```
php artisan make:model Category -m
```
after making table and model :
```
php artisan migrate
```
step 2: making controller for category creation :

- 1. create() (or your current addCategory()) :
```
public function addCategory()
{
    return view('admin.category.addCategory');
}

```
web.php:
```
Route::get('/add-category',[AdminDashboardController::class,'addCategory'])->name('addCategory');
```
- 2. store() (or your current storeCategory()):
rules:
 - There is no 'text' validation rule in Laravel.
 - call it like this when a situation like this
```
'status' => $request->boolean('status'),
```
```
    public function storeCategory(Request $request){

        $request->validate([
            'name'=> 'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'nullable|boolean',

        ]);
        Category::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'status'=>$request->boolean('status')
        ]);
        return redirect()->back()->with('message','New category added');
}

```
- 3.fetch data(or show all data listOfCategory()):
```
public function listOfCategory(){
        $categories= Category::all();
        return view('admin.category.category-list',compact('categories'));
    }

```
- 4. edit Category(you just call the form):
  rules:
    - in form must mention must mention this for old value =>> ```   value="{{ old('name', $category->name) }} ```
