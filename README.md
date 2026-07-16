<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">📦 Laravel IMS — Personal Cheatsheet & Boilerplate Reference</h1>

<p align="center">A quick-start reference for spinning up an Inventory Management System (or any role-based CRUD app) in Laravel — so I never have to start from scratch again.</p>

---

## 📑 Table of Contents
- [User Roles](#-user-roles)
- [Project Setup](#-project-setup)
- [Auth System (Breeze)](#-add-login-and-registration-system)
- [Adding Roles to Existing Users Table](#-role-adding-existing-user-table)
- [Separate Dashboards for Admin & User](#-separate-dashboard-for-admin--user)
- [Category Module (Full CRUD)](#-category-model-and-data-table)
- [Suggestions & Notes](#-suggestions--notes)

---

## 👤 User Roles
| Role | Access |
|---|---|
| **Admin** | Full access to all modules |
| **Inventory Manager** | Manage products, purchases, stock, and suppliers |
| **Sales Staff** | Create sales, manage customers, view available stock |
| **Warehouse Staff** | Update stock, receive goods, perform stock adjustments |
| **Viewer / Accountant** *(optional)* | View reports and financial summaries |

---

## 🚀 Project Setup

Before starting:
```bash
composer global require laravel/installer
```
then,
```bash
composer global config bin-dir --absolute
```

For using Laravel 12:
```bash
composer create-project laravel/laravel ims "^12.0"
```

---

## 🔐 Add Login and registration system

Starter kit (Breeze):
```bash
composer require laravel/breeze --dev

php artisan breeze:install
```

---

## 🏷️ Role adding existing User table

**Step 1:** Make a migration for the existing table
```bash
php artisan make:migration add_role_to_users_table --table=users
```

**Step 2:** Add the column to the new migration file
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->after('email')->default('user');
    });
}
```

**Step 3:** Migrate
```bash
php artisan migrate
```

---

## 🧭 Separate Dashboard for Admin & User

**Step 1:** Make view for admin
```bash
php artisan make:view admin.admin-dashboard
```

**Step 2:** Make controllers for user and admin
```bash
php artisan make:controller UserDashboardController
php artisan make:controller Admin/AdminDashboardController
```

**Step 3:** Create Middleware
```bash
php artisan make:middleware IsAdmin
```

**Step 4:** `app/Http/Middleware/IsAdmin.php`
```php
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

**Step 5:** `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias(['admin' => \App\Http\Middleware\IsAdmin::class]);
})
```

**Step 6:** `routes/web.php`
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // other admin routes...
});
```

**Step 7:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
```php
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

---

## 🗂️ Category Model and Data Table

**Step 1:** Make model and migration
```bash
php artisan make:model Category -m
```
After building the table in the migration:
```bash
php artisan migrate
```

**Step 2:** Controller methods for Category creation

### 1. `create()` (a.k.a. `addCategory()`)
```php
public function addCategory()
{
    return view('admin.category.addCategory');
}
```
`web.php`:
```php
Route::get('/add-category', [AdminDashboardController::class, 'addCategory'])->name('addCategory');
```

> 💡 **Rules to remember:**
> - There is no `'text'` validation rule in Laravel — use `'string'`.
> - For checkbox/boolean fields, read them like this: `$request->boolean('status')`.

### 2. `store()` (a.k.a. `storeCategory()`)
```php
public function storeCategory(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|boolean',
    ]);

    Category::create([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->boolean('status'),
    ]);

    return redirect()->back()->with('message', 'New category added');
}
```

### 3. Fetch data / list all (`listOfCategory()`)
```php
public function listOfCategory()
{
    $categories = Category::all();
    return view('admin.category.category-list', compact('categories'));
}
```

### 4. Edit & Update Category
> 💡 **Rules to remember:**
> - In the form, always reference old values like: `value="{{ old('name', $category->name) }}"`.
> - `edit` takes you to the form; `update` persists the change.
> - Both routes use **Route Model Binding**.

Controller:
```php
public function editCategory(Category $category)
{
    return view('admin.category.edit-category', compact('category'));
}

public function updateCategory(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'string|nullable',
        'status' => 'boolean|nullable',
    ]);

    $category->update([
        'name' => $request->name,
        'description' => $request->description,
        'status' => $request->boolean('status'),
    ]);

    return redirect()->route('admin.categoryList')->with('message', 'Category Updated');
}
```

Blade — Edit link:
```blade
<a href="{{ route('admin.editCategory', $category->id) }}" class="text-blue-600 hover:underline">
    Edit
</a>
```

Blade — Update form:
```blade
<form method="POST" action="{{ route('admin.updateCategory', $category->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-2">Category Name</label>
        <input
            type="text"
            name="name"
            class="w-full border rounded p-2"
            value="{{ old('name', $category->name) }}">
    </div>

    <div class="mb-4">
        <label class="block mb-2">Category Description</label>
        <textarea
            name="description"
            class="w-full border rounded p-2"
            rows="4">{{ old('description', $category->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label>
            <input
                type="checkbox"
                name="status"
                value="1"
                {{ old('status', $category->status) ? 'checked' : '' }}>
            Active
        </label>
    </div>

    <button type="submit" class="bg-green-950 text-black px-5 py-2 rounded">
        Update Category
    </button>
</form>
```

`web.php`:
```php
Route::get('/edit-category/{category}', [AdminDashboardController::class, 'editCategory'])->name('editCategory');
Route::put('/update-category/{category}', [AdminDashboardController::class, 'updateCategory'])->name('updateCategory');
```

### 5. Destroy Category
Controller:
```php
public function destroyCategory(Category $category)
{
    $category->delete();
    return redirect()->route('admin.categoryList')->with('message', 'Deleted');
}
```

Blade / form:
```blade
<form method="POST" action="{{ route('admin.deleteCategory', $category->id) }}">
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="text-red-600 hover:underline"
        onclick="return confirm('Are you sure you want to delete this category?')">
        Delete
    </button>
</form>
```

`web.php`:
```php
Route::delete('/delete-category/{category}', [AdminDashboardController::class, 'destroyCategory'])->name('deleteCategory');
```


Add Suppliers(Admin Part):

Model and migration table:
```
php artisan make:model Suppliers -m
```
For the email field, use the email rule:
```
'email' => 'nullable|email|max:255',
'website' => 'nullable|url|max:255',
```
Migrate them:
```
php artisan migrate
```

> controller and blade same as before!

Brands(Admin part):

Making Model and table:
```
php artisan make:model Brand -m
```
we have relation here with category:

```
A category hasMany Brands => so this means in the category model u have to write about hasMany brands
A brand belongsTo category => so this means in the brand model u have to write about belongsTo category

Category.php:
public function brands(){
        return $this->hasMany(Brand::class);
    }
Brand.php:
 public function category(){
        return $this->belongsTo(Category::class);
    }
```

Then migrate them all:

```
php artisan migrate
```

so in store and update function:

```
'category_id' => 'required|exists:categories,id',
```

brandList   =>  * Display all brands.
brandCreate =>  * Show create form.
brandStore  =>  * Store a new brand.
brandEdit   =>  * Show edit form.
brandUpdate =>  * Update brand.

brandStore(form like this beacuse we have realtion between brand and category!):
to select category then make them to brand's we can write like this so they associated like this:
```
<select name="category_id" class="w-full border rounded p-2">
                <option value="">-- Select Category --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

```
message / success handling:
```
 @error('category_id')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
```
brandUpdate:
for showing old data:
option section:
```
<option
    value="{{ $category->id }}"
    {{ old('category_id', $brand->category_id) == $category->id ? 'selected' : '' }}>
    {{ $category->name }}
</option>

```

checkbox section:
```
<input
    type="checkbox"
    name="status"
    value="1"
    {{ old('status', $brand->status) ? 'checked' : '' }}>
```
USe this if u want to inactive any update:
```
<input type="hidden" name="status" value="0">

<label class="inline-flex items-center">
    <input
        type="checkbox"
        name="status"
        value="1"
        {{ old('status', $brand->status) ? 'checked' : '' }}>

    <span class="ml-2">Active</span>
</label>
```
> ⚠️ **Missing route spotted:** your `addCategory()` and `editCategory()` views submit to `storeCategory` and `listOfCategory`, but no routes for those two were defined anywhere in the original notes. Add these to `web.php` so the module actually works end-to-end:
> ```php
> Route::post('/store-category', [AdminDashboardController::class, 'storeCategory'])->name('storeCategory');
> Route::get('/category-list', [AdminDashboardController::class, 'listOfCategory'])->name('categoryList');
> ```

---

## 🛠️ Suggestions & Notes

Nothing here was removed from your original notes — these are just things worth keeping in mind next time you reuse this as a starting point:

1. **Route naming consistency** — you reference `admin.categoryList` in redirects, but the route itself wasn't in your notes (added above). Worth double-checking every controller method has a matching named route before you copy-paste this into a new project.
2. **Role checks will get messy at scale** — `Auth::user()->role === 'admin'` works fine for 2 roles, but with 5 roles (Admin, Inventory Manager, Sales Staff, Warehouse Staff, Viewer) you'll want **Laravel Gates/Policies** or the `spatie/laravel-permission` package instead of hardcoding string checks in every middleware/controller.
3. **Form Requests over inline validation** — once you have more than 2-3 fields, move `$request->validate([...])` into a dedicated `StoreCategoryRequest` / `UpdateCategoryRequest` class. Keeps controllers thin and validation reusable.
4. **`role` as an enum, not a plain string** — `$table->string('role')` allows any typo'd value to slip in. Consider `$table->enum('role', ['admin','inventory_manager','sales_staff','warehouse_staff','viewer'])` or a PHP backed enum cast on the model.
5. **`Route::resource()`** — for straightforward CRUD like Category, `Route::resource('categories', CategoryController::class)` replaces 5+ manual route lines and auto-generates RESTful names (`categories.index`, `categories.store`, etc.). Worth it once you're comfortable with the manual version (which is great for learning, which is clearly what this doc is for).
6. **Minor typo fixed**: `Isadmin.php` → `IsAdmin.php` (filename should match the class name exactly, case-sensitive on Linux servers).

---

*Personal reference doc — update as new modules (Products, Purchases, Stock, Suppliers) are built out.*
