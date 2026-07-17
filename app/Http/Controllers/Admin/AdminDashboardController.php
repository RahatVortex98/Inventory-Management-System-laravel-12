<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class AdminDashboardController extends Controller
{
   public function index(){
    $todaySales = \App\Models\Sale::whereDate('created_at', today())->count();
    $todayRevenue = \App\Models\Sale::whereDate('created_at', today())->sum('total_amount');
    $lowStockProducts = Product::whereColumn('current_stock', '<=', 'reorder')->where('status', 1)->get();
    $recentSales = \App\Models\Sale::with('user')->latest()->take(5)->get();

    return view('admin.admin-dashboard', compact('todaySales', 'todayRevenue', 'lowStockProducts', 'recentSales'));
}

    public function addCategory(){
        return view('admin.category.addCategory');
    }

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
        return redirect()->route('admin.categoryList')->with('message','New category added');



    }
    public function listOfCategory(){
        $categories= Category::all();
        return view('admin.category.category-list',compact('categories'));
    }

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

    public function destroyCategory(Category $category){
        $category->delete();
        return redirect()->route('admin.categoryList')->with('message','Deleted');
    }

    public function suppliersList(){
        $suppliers = Suppliers::all();
        return view('admin.suppliers.suppliers-list',compact('suppliers'));
    }
    public function supplierCreate(){
        return view('admin.suppliers.suppliers-create');
    }
    public function supplierStore(Request $request){
    $attr=$request->validate([
            'supplier_name'=>'string|max:255|nullable',
            'contact_person'=>'string|max:255|required',              
            'email' => 'nullable|email|max:255',
            'phone'=>'string|max:255|required',
            'address'=>'string|max:255|required',
            'city'=>'string|max:255|required',
            'postal_code'=>'string|max:255|required',
            'country'=>'string|max:255|required',
            'website' => 'nullable|url|max:255',
            'tax_number'=>'string|max:255|nullable',
            'status' => 'required|boolean',
            'notes'=>'string|max:255|nullable',
        ]);
        Suppliers::create($attr);

        return redirect()
               ->route('admin.supplierList')
               ->with('Success','New supplier listed');
    }
    public function supplierEdit(Suppliers $supplier){
        return view('admin.suppliers.supplier-edit',compact('supplier'));
    }
    public function supplierUpdate(Request $request,Suppliers $supplier){

        $attr=$request->validate([
             'supplier_name'=>'string|max:255|nullable',
            'contact_person'=>'string|max:255|required',              
            'email' => 'nullable|email|max:255',
            'phone'=>'string|max:255|required',
            'address'=>'string|max:255|required',
            'city'=>'string|max:255|required',
            'postal_code'=>'string|max:255|required',
            'country'=>'string|max:255|required',
            'website' => 'nullable|url|max:255',
            'tax_number'=>'string|max:255|nullable',
            'status' => 'required|boolean',
            'notes'=>'string|max:255|nullable',
        ]);

        $supplier->update($attr);
        return redirect()
               ->route('admin.supplierList');

    }
    public function supplierDelete(Suppliers $supplier){
        $supplier->delete();
        return redirect()->route('admin.supplierList');
    }


    public function brandList(){
        $brands = Brand::with('category')->paginate(10);
        return view('admin.brand.brand-list',compact('brands'));
    }
    public function brandCreate(){
        $categories=Category::where('status',1)->get();
        return view('admin.brand.brand-create',compact('categories'));
    }

    public function brandStore(Request $request){
        $attr=$request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'=>'string|required|max:255',
            'description'=>'string|nullable',
            'status'=>'boolean|required'
        ]);
        Brand::create($attr);
        return redirect()->route('admin.brandList')->with('Success','New brand created');
    }

    public function brandEdit(Brand $brand){
        $categories = Category::all();
        return view('admin.brand.brand-edit',compact('brand', 'categories'));
    }
    public function brandUpdate(Request $request,Brand $brand){
        $attr=$request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'=>'string|required|max:255',
            'description'=>'string|nullable',
            'status'=>'boolean|required'
        ]);
        $brand->update($attr);
        return redirect()
               ->route('admin.brandList');
        
        }   





       public function productList(){
        $products=Product::with(['category','supplier','brand'])->latest()->paginate(10);
        return view('admin.product.product-list',compact('products'));
       } 
       

       public function productCreate(){
        $categories = Category::where('status',1)->get();
        $suppliers = Suppliers::where('status',1)->get();
        $brands = Brand::where('status',1)->get();
        return view('admin.product.product-create',compact('categories','suppliers','brands'));
       }

        public function productStore(Request $request,Product $product){
            $attr=$request->validate([

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'brand_id'    => 'required|exists:brands,id',
            'name'=>'required|string|max:255',
             'sku' => [
                'required',
                'string',
                Rule::unique('products')->ignore($product->id),
            ],
            'unit'=>'required|string|max:20',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock'=>'nullable|integer|min:0',
            'reorder' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',


            ]);
             $attr['status'] = $request->boolean('status');
             Product::create($attr);
             return redirect()->route('admin.productList');
        }

        public function productEdit(Product $product){
            $categories=Category::where('status',1)->get();
            $brands=Brand::where('status',1)->get();
            $suppliers=Suppliers::where('status',1)->get();

            return view('admin.product.product-edit',compact('product','categories','brands','suppliers'));
        }

        public function productUpdate(Request $request,Product $product){
            $attr=$request->validate([

            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'brand_id'    => 'required|exists:brands,id',
            'name'=>'required|string|max:255',
            'sku' => [
                'required',
                'string',
                Rule::unique('products')->ignore($product->id),
            ],
            'unit'=>'required|string|max:20',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock'=>'nullable|integer|min:0',
            'reorder' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',


        ]);
        $attr['status']=$request->boolean('status');
        $product->update($attr);
         return redirect()->route('admin.productList');

    }
}
