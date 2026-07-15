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
- 4. edit & update Category(you just call the form):
  rules:
    - in form must mention must mention this for old value =>> ```   value="{{ old('name', $category->name) }} ```
    - Edit and update => edit takes u to the form and update helps u to update your previous data.
    - Both function has model binding!
  Controller:
```
public function editCategory(Category $category){

      return view('admin.category.edit-category', compact('category'));
    }

    public function updateCategory(Request $request,Category $category){

        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'string|nullable',
            'status'=>'boolean|nullable'
        ]);
        $category->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'status'=>$request->boolean('status')

        ]);
        return redirect()->route('admin.categoryList')->with('message','Category Updated');

    }


```
Blade.php:

```
Edit function:
 <a href="{{ route('admin.editCategory',$category->id) }}" class="text-blue-600 hover:underline">
                                Edit
                            </a>
Update function:
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

            <button
                type="submit"
                class="bg-green-950 text-black px-5 py-2 rounded">
                Update Category
            </button>
        </form>
```
web.php:

```
Route::get('/edit-category/{category}',[AdminDashboardController::class,'editCategory'])->name('editCategory');
Route::put('/update-category/{category}',[AdminDashboardController::class,'updateCategory'])->name('updateCategory');

```

5. Destroy category:
Controller :
```
 public function destroyCategory(Category $category){
        $category->delete();
        return redirect()->route('admin.categoryList')->with('message','Deleted');
    }
```
Form / blade:
```
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

web.php:
```
Route::delete('/delete-category/{category}',[AdminDashboardController::class,'destroyCategory'])->name('deleteCategory');
```
