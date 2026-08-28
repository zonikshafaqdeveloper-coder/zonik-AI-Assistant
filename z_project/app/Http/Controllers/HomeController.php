<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\BrandImage;
use App\Models\DeliveryManagemnet;
use App\Models\Banner;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Cart;
use App\Models\FestivalandOffers;
use App\Models\Product_subcat_brand;
use App\Models\Quote;
use App\Models\Tag;
use App\Models\Type;
use App\Models\User;
use App\Models\Brandsassoc;
use App\Models\Clientserve;
use Illuminate\Pagination\Paginator;
use App\Models\Notification; // Assuming your notification model is Notification
use App\Notifications\NewEnqueryRequestCustomerNotification;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{

    public function index(Category $category)
    {
        $categories = Category::all();
        $brandsimage = BrandImage::where('status', 'Active')->get();
        $festivalandoffers = FestivalandOffers::where('status', 'Active')->get();
        $subcategories = Category::with('subcategories')->get();
        return view('web.front.before-login', compact('categories', 'subcategories', 'BrandImage', 'festivalandoffers'));
    }



    // public function home(Request $request)
    // {
    //     // Fetch necessary data
    //     // $categories = Category::with(['products' => function ($query) {
    //     //     $query->where('status', 'active');
    //     // }])->get();
        
    //     $categories = Cache::remember('categories_with_products', 60, function () {
    //         return Category::with(['products' => function ($query) {
    //             $query->where('status', 'active');
    //         }])->get();
    //     });
        
    
    //     // dd($categories);
    //     $products = Product::with('subcategory')->get();
    //     $brandsimage = BrandImage::where('status', 'Active')->get();
    //     $bannersImage = Banner::where('status', 'Active')->get();
    //     $festivalandoffers = FestivalandOffers::where('status', 'Active')->get();
    
    //     $subcategories = Subcategory::with(['products' => function ($query) {
    //         $query->where('status', 'active');
    //     }])->get();
    
    //     // Check if user is authenticated
    //     if (auth()->check()) {
    //         $priorityUsers = User::where('priority', auth()->user()->id)->get();
    //         // Log::info('Priority Users:', $priorityUsers->toArray());
    
    //         foreach ($priorityUsers as $priorityUser) {
    //             // Log::info('Checking user:', ['id' => $priorityUser->id]);
    
    //             if ($priorityUser->verified_status === 'verified' && $priorityUser->user_verified === 'false') {
    //                 // Log::info('Condition passed for user:', ['id' => $priorityUser->id]);
    
    //                 // Send notification
    //                 // $customMessage = "Your outlet '{$priorityUser->outlet_name}' has been Verified you can now Place orders at Zonik!";
    //                 // auth()->user()->notify(new NewEnqueryRequestCustomerNotification($priorityUser->id, $customMessage));
    //                 // Log::info('Notification sent to:', ['auth_user_id' => auth()->user()->id, 'priority_user_id' => $priorityUser->id]);
    
    //                 // Update user_verified
    //                 $updated = $priorityUser->update(['user_verified' => true]);
    //                 // Log::info('Update result for user_verified:', ['id' => $priorityUser->id, 'result' => $updated]);
    //             } else {
    //                 // Log::info('Condition did not pass for user:', ['id' => $priorityUser->id]);
    //             }
    //         }
    //     } else {
    //         // Log::info('No authenticated user. Skipping notifications.');
    //     }
    



    //     // Return the view regardless of authentication
    //     return view('web.front.after-login', compact('categories', 'bannersImage', 'products', 'subcategories', 'brandsimage', 'festivalandoffers'));
    // }
    
    
    

public function home(Request $request)
{
    // Cache categories and products data
$categories = Cache::remember('categories_with_products_v2', 300, function () {
    return Category::whereHas('products', function ($q) {
            $q->where('status', 'active');
        })
        ->with(['products' => function ($q) {
            $q->where('status', 'active');
        }])
        ->get();
});
    // Cache products data
$products = Cache::remember('all_products', 300, function () {
    return Product::with('subcategory')
        ->where('status', 'active')
        ->get();
});

    // Cache brand images
    $brandsimage1 = Cache::remember('active_brands', 300, function () {
        return BrandImage::where('status', 'Active')->get();
    });

    // Cache banners images
    $bannersImage = Cache::remember('active_banners', 300, function () {
        return Banner::where('status', 'Active')->get();
    });

    // Cache festival and offers data
$festivalandoffers = Cache::remember('active_festival_and_offers', 300, function () {
    return FestivalandOffers::with(['brandImages' => function ($query) {
        $query->where('status', 'Active');
    }])->where('status', 'Active')->get();
});


    // dd($festivalandoffers);

    // Cache subcategories with active products
    $subcategories = Cache::remember('subcategories_with_active_products', 300, function () {
        return Subcategory::with(['products' => function ($query) {
            $query->where('status', 'active');
        }])->get();
    });

    // Check if user is authenticated
    if (auth()->check()) {
        $priorityUsers = User::where('priority', auth()->user()->id)->get();

        foreach ($priorityUsers as $priorityUser) {
            if ($priorityUser->verified_status === 'verified' && $priorityUser->user_verified === 'false') {
                // Update user_verified
                $updated = $priorityUser->update(['user_verified' => true]);
            }
        }
    }

    // Return the view
    return view('web.front.after-login', compact('categories', 'bannersImage', 'products', 'subcategories', 'brandsimage1', 'festivalandoffers'));
}

    
    

    //   public function updateNotification(Request $request) {
    //     // Update notification table
    //     Notification::where('notifiable_id', auth()->user()->id)->update(['read' => true]);

    //     // Get updated notification count
    //     $notificationCount = Notification::where('notifiable_id', auth()->user()->id)->where('read', false)->count();

    //     // Return updated notification count
    //     return response()->json(['notificationCount' => $notificationCount]);
    // }
    
    
    
    public function updateNotification(Request $request)
{
    $user = $request->user();

    \DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->where('notifiable_type', get_class($user))
        ->whereNull('read_at')
         ->update([
            'read_at' => now(),
            'read'    => true,
        ]);

    $notificationCount = Notification::where('notifiable_id', auth()->user()->id)->where('read', false)->count();

    return response()->json(['notificationCount' => $notificationCount]);
}


    public function search(Request $request)
    {
        if ($request->ajax()) {
            $searchTerm = $request->search;
            $terms = explode(' ', $searchTerm);
    
            // Fetch products based on the brand or product name
            $data = Product::with('subcategory')
                ->where('status', 'active')
                ->where(function ($query) use ($terms, $searchTerm) {
                    $query->where(function ($innerQuery) use ($terms) {
                        foreach ($terms as $term) {
                            $innerQuery->where('product_name', 'like', "%$term%");
                        }
                    })
                    ->orWhere('brands', 'like', "%$searchTerm%") // Match entire search term in brands
                    ->orWhere('product_name', 'like', "%$searchTerm%"); // Match entire search term in product name
                })
                ->orderBy('brands') // Optional: Order results by brands
                ->get();
    
            $output = '';
    
            if (count($data) > 0) {
                $output = '<li><b>Products</b></li>';
                foreach ($data as $row) {
                    $comma_pos = strpos($row->product_name, ','); // Find the position of the first comma
    
                    if ($comma_pos !== false) {
                        $before_comma = substr($row->product_name, 0, $comma_pos);
                        $after_comma = substr($row->product_name, $comma_pos + 1);
    
                        // Concatenate the parts with a line break
                        $output .= '<li>
                                        <img src="/uploads/' . $row->image . '" alt="' . $row->product_name . '" height="48" width="48" >
                                        <a href="' . route('product-details', $row->id) . '">
                                            <h6>' . $before_comma . '</h6>
                                            <span>' . $after_comma . '</span>
                                        </a>
                                    </li>';
                    } else { // If no comma exists, just output the product name
                        $output .= '<li>
                                        <img src="/uploads/' . $row->image . '" alt="' . $row->product_name . '" height="48" width="48" >
                                        <a href="' . route('product-details', $row->id) . '">
                                            <h6>' . $row->product_name . '</h6>
                                        </a>
                                    </li>';
                    }
                }
            } else {
                $output .= '<li>No results found</li>';
            }
    
            return $output;
        }
    }
    
    public function searchdata(Request $request)
        {


            // dd($request->all());
                $searchTerm = $request->search;
                $terms = explode(' ', $searchTerm);
                $data = Product::with('subcategory')
                    ->where('status', 'active')
                    ->where(function ($query) use ($terms, $searchTerm) {
                        $query->where(function ($innerQuery) use ($terms) {
                            foreach ($terms as $term) {
                                $innerQuery->where('product_name', 'like', "%$term%");
                            }
                        })
                            ->orWhere('brands', 'like', "%$searchTerm%")
                            ->orWhere('types', 'like', "%$searchTerm%")
                            ->orWhere('brands', 'like', substr($searchTerm, 0, 3) . '%')
                            ->orWhere('brands', 'like', substr($searchTerm, 0, 2) . '%')
                            ->orWhere('types', 'like', substr($searchTerm, 0, 3) . '%')
                            ->orWhere('product_name', 'like', substr($searchTerm, 0, 3) . '%')
                            ->orWhere('product_name', 'like', substr($searchTerm, 0, 2) . '%')
                            ->orWhere('brands', 'like', '%' . substr($searchTerm, -3))
                            ->orWhere('brands', 'like', '%' . substr($searchTerm, -2))
                            ->orWhere('types', 'like', '%' . substr($searchTerm, -3))
                            ->orWhere('product_name', 'like', '%' . substr($searchTerm, -3))
                            ->orWhere('product_name', 'like', '%' . substr($searchTerm, -2))
                            ->orWhere('types', 'like', "%$searchTerm%")
                            ->orWhere(function ($innerQuery) use ($searchTerm) {
                                $innerQuery->whereRaw('LEFT(product_name, 3) = ?', [substr($searchTerm, 0, 3)])
                                    ->orWhereRaw('RIGHT(product_name, 3) = ?', [substr($searchTerm, -3)])
                                    ->orWhereRaw('SOUNDEX(product_name) = SOUNDEX(?)', [$searchTerm]);
                            })
                            ->orWhereRaw('LEFT(brands, 3) = ?', [substr($searchTerm, 0, 3)])
                            ->orWhereRaw('RIGHT(brands, 3) = ?', [substr($searchTerm, -3)])
                            ->orWhereRaw('LEFT(types, 3) = ?', [substr($searchTerm, 0, 3)])
                            ->orWhereRaw('RIGHT(types, 3) = ?', [substr($searchTerm, -3)])
                            ->orWhere(function ($innerQuery) use ($searchTerm) {
                                $innerQuery->whereRaw('LEFT(product_name, 2) = ?', [substr($searchTerm, 0, 2)])
                                    ->orWhereRaw('RIGHT(product_name, 2) = ?', [substr($searchTerm, -2)])
                                    ->orWhereRaw('SOUNDEX(product_name) = SOUNDEX(?)', [$searchTerm]);
                            })
                            ->orWhereRaw('LEFT(brands, 2) = ?', [substr($searchTerm, 0, 2)])
                            ->orWhereRaw('RIGHT(brands, 2) = ?', [substr($searchTerm, -2)])
                            ->orWhereRaw('LEFT(types, 2) = ?', [substr($searchTerm, 0, 2)])
                            ->orWhereRaw('RIGHT(types, 2) = ?', [substr($searchTerm, -2)])
                            ->orWhereRaw('SUBSTRING(product_name, 1, 3) = ?', [substr($searchTerm, 0, 3)])
                            ->orWhereRaw('SUBSTRING(product_name, -3) = ?', [substr($searchTerm, -3)])
                            ->orWhereRaw('SUBSTRING(brands, 1, 3) = ?', [substr($searchTerm, 0, 3)])
                            ->orWhereRaw('SUBSTRING(brands, -3) = ?', [substr($searchTerm, -3)])
                            ->orWhereRaw('SUBSTRING(types, 1, 3) = ?', [substr($searchTerm, 0, 3)])
                            ->orWhereRaw('SUBSTRING(types, -3) = ?', [substr($searchTerm, -3)])
                            ->orWhereRaw('SUBSTRING(product_name, 1, 2) = ?', [substr($searchTerm, 0, 2)])
                            ->orWhereRaw('SUBSTRING(product_name, -2) = ?', [substr($searchTerm, -2)])
                            ->orWhereRaw('SUBSTRING(brands, 1, 2) = ?', [substr($searchTerm, 0, 2)])
                            ->orWhereRaw('SUBSTRING(brands, -2) = ?', [substr($searchTerm, -2)])
                            ->orWhereRaw('SUBSTRING(types, 1, 2) = ?', [substr($searchTerm, 0, 2)])
                            ->orWhereRaw('SUBSTRING(types, -2) = ?', [substr($searchTerm, -2)]);
                    })
                    ->orderBy('product_name', 'asc') // Sort by product name in ascending order
                    ->get();


                return view('web.front.search', compact('data', 'searchTerm'));

        }





    public function cat()
    {
        return view('web.front.catalogue1');
    }






//cmt on 13-12-24

public function productlist(Request $request)
{
    $searchTerm = $request->search;
    $offset = $request->offset ?? 0;
    $limit = 20;
    
     $brands = $request->brands ? explode(',', $request->brands) : [];
    $categories = $request->categories ? explode(',', $request->categories) : [];

    $query = Product::with('subcategory')
        ->where('status', 'active')
        ->orderBy('product_name', 'asc');

    if ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('product_name', 'like', "%{$searchTerm}%")
              ->orWhere('brands', 'like', "%{$searchTerm}%")
              ->orWhere('types', 'like', "%{$searchTerm}%");
        });
    }
    
      if (!empty($brands)) {
        $query->whereIn('brands', $brands);
    }
     
    if (!empty($categories)) {
        $query->whereIn('category_id', $categories);
    }

    $products = $query->skip($offset)->take($limit)->get();
    
        $allBrands = Product::whereNotNull('brands')
                        ->pluck('brands')
                        ->unique()
                        ->values();

    $allCategories = Category::orderBy('category_name', 'asc')->pluck('category_name', 'id');

if ($request->ajax()) {
    return response()->json([
        'html' => view('web.front.products.product_grid', [
            'data' => $products,
            'offset' => $offset
        ])->render(),
        'count' => $products->count(),
    ]);
}

return view('web.front.products.productlist', [
    'data' => $products,
    'searchTerm' => $searchTerm,
    'offset' => $offset,
    'allBrands' => $allBrands,
        'allCategories' => $allCategories,
]);
}




//  public function productlist(Request $request)
// {
//     $searchTerm = $request->search;

//     $query = Product::with('subcategory')
//         ->where('status', 'active')
//         ->orderBy('product_name', 'asc');

  
//     if (!empty($searchTerm)) {
//         $query->where('product_name', 'like', "%{$searchTerm}%")
//               ->orWhere('brands', 'like', "%{$searchTerm}%")
//               ->orWhere('types', 'like', "%{$searchTerm}%");
//     }

//     $data = $query->paginate(10);

//     return view('web.front.products.productlist', compact('data', 'searchTerm'));
// }


    // public function subcateg(Request $request)
    // {

    //     $categories = Category::all();
    //     $selectedCategoryId = $request->query('category_id');
    //     $selectedSubCategoryId = $request->query('sub_id');


    //     $selectedBrandIds = $request->query('brand_ids', []);

    //     if ($selectedCategoryId) {
    //         $selectedCategory = Category::where('id',  $selectedCategoryId)->first();

    //         $subcategories = $selectedCategory->subcategories;
    //         $distinctSubcategoryIds = $subcategories->pluck('id')->unique();
    //         $brands = Brand::whereIn('subcategory_id', $distinctSubcategoryIds)->get();
    //         $tags = Tag::whereIn('subcategory_id', $distinctSubcategoryIds)->get();


    //         // if ($id) {
    //         //     $products = Subcategory::find($id)->products;
    //         // } else {

    //         $products = Product::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
    //         // }


    //         if (!empty($selectedBrandIds)) {
    //             $products->whereIn('brand_id', $selectedBrandIds);
    //         }

    //         // $productss = $products->get();
    //         // $products = collect();

    //     } else {
    //         $subcategories = collect();
    //         $products = collect();
    //         $selectedCategory = collect();
    //         $brands = collect();
    //     }
    //     // dd($categories);
    //     // $brands = Brand::whereIn('subcategory_id', $subcategories->pluck('id'));
    //     $brands =  Brand::all();
    //     $brands1 = Brand::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
    //     $types =   Type::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
    //     $quoteCounts =   Quote::where('user_id', auth()->user()?->id)->get();
    //     // $tags = Tag::whereIn('subcategory_id', $distinctSubcategoryIds)->get();
    //     // dd($tags);
    //     if ($selectedSubCategoryId == null) {
    //         $productFilters = Product::where('category_id', $selectedCategoryId)->get();
    //     } else {
    //         $productFilters = Product::where('category_id', $selectedCategoryId)->where('subcategory_id', $selectedSubCategoryId)->get();
    //     }


    //     // return view('web.front.product', compact('categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'selectedBrandIds', 'brands1', 'tags'));
    //     return view('web.front.catalogue1', compact('productFilters', 'quoteCounts', 'categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'types', 'selectedBrandIds', 'brands1', 'selectedSubCategoryId'));
    // }


    public function subcateg(Request $request)
    {
        // $productFilters = Product::query();
        
        $productFilters = Product::where('status', 'active');
        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id');
        $selectedBrandId = $request->query('brand_id');
        $selectedBrandName = $request->query('brand_name');

        $selectedSubCategoryId = $request->query('sub_id');
        $selectedCategory = null;
        $subcategories = collect();

        if ($selectedCategoryId) {
            $productFilters->where('category_id', $selectedCategoryId);
            $selectedCategory = Category::find($selectedCategoryId);
            $subcategories = $selectedCategory->subcategories;
        } elseif ($selectedBrandId) {
            $productFilters->where('brand_id', $selectedBrandId);
        } elseif ($selectedBrandName) {
            $productFilters->where(function($query) use ($selectedBrandName) {
                $query->where('brands', $selectedBrandName)
                      ->orWhere('product_name', 'like', '%' . $selectedBrandName . '%');
            });
        }

        if ($selectedSubCategoryId) {
            $productFilters->where('subcategory_id', $selectedSubCategoryId);
        }

        if (!$selectedCategoryId && $selectedBrandName) {
            $selectedCategoryId = 10;
            $selectedCategory = Category::find($selectedCategoryId);
            $subcategories = $selectedCategory->subcategories;
            $productFilters->where('brands', $selectedBrandName);
        }

        $products = $productFilters->get();
        $brands = Brand::all();

        return view('web.front.catalogue1', compact('productFilters',  'selectedBrandName', 'categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'selectedBrandId', 'selectedSubCategoryId'));
    }

    public function filterTypeProducts(Request $request)
    {      $categories = Category::all();
        $selectedTypes = $request->typeitems;
        $selectedCategoryId = $request->selectedCategoryId;
        $selectedTypeSubCategoryId = $request->type_sub_category_id;
        $selectedSubCategoryId = $request->query('sub_id');
        $query = Product::query();
        $selectedCategory = Category::where('id',  $selectedCategoryId)->first();
        if ($selectedTypeSubCategoryId == null) {
            $query->where('category_id', $selectedCategoryId);
        } else {
            $query->where('subcategory_id', $selectedTypeSubCategoryId);
        }

        if (is_array($selectedTypes)) {
            $query->where(function ($query) use ($selectedTypes) {
                foreach ($selectedTypes as $type) {
                    $query->orWhere('types', 'LIKE', "%$type%");
                }
            });
        } elseif (is_string($selectedTypes)) {
            $query->where('types', 'LIKE', "%$selectedTypes%");
        }
        if ($selectedSubCategoryId) {
            $productFilters->where('subcategory_id', $selectedSubCategoryId);
        }
        $selectedCategory = Category::find($selectedCategoryId);
            $subcategories = $selectedCategory->subcategories;

        $products = $query->get();
        return view('web.front.catalogue1', compact('products','selectedSubCategoryId','categories','selectedCategory','subcategories','selectedCategoryId'));
    }



    public function subcateg2(Request $request)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id');
        $selectedSubCategoryId = $request->query('sub_id');

        $selectedBrandIds = $request->query('brand_ids');

        if ($selectedCategoryId) {
            $selectedCategory = Category::where('id',  $selectedCategoryId)->first();

            $subcategories = $selectedCategory->subcategories;
            $distinctSubcategoryIds = $subcategories->pluck('id')->unique();
            $brands = Brand::whereIn('subcategory_id', $distinctSubcategoryIds)->get();
            $tags = Tag::whereIn('subcategory_id', $distinctSubcategoryIds)->get();


            // if ($id) {
            //     $products = Subcategory::find($id)->products;
            // } else {

            $products = Product::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
            // }


            if (!empty($selectedBrandIds)) {
                $products->whereIn('brand_id', $selectedBrandIds);
            }

            // $productss = $products->get();
            // $products = collect();

        } else if ($selectedBrandIds) {
        } else {
            $subcategories = collect();
            $products = collect();
            $selectedCategory = collect();
            $brands = collect();
        }

        // $brands = Brand::whereIn('subcategory_id', $subcategories->pluck('id'));
        $brands =  Brand::all();
        $brands1 = Brand::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
        $types =   Type::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
        $quoteCounts =   Quote::where('user_id', auth()->user()?->id)->get();
        $offerListCount = Enquiry::where('user_id', $user->id)->where('status', 'isadminApproved')->count();
        $cartsCount =   Cart::where('user_id', auth()->user()?->id)->get();
        $tags = Tag::whereIn('subcategory_id', $distinctSubcategoryIds)->get();



        // return view('web.front.product', compact('categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'selectedBrandIds', 'brands1', 'tags'));
        return view('web.front.catalogue1', compact('quoteCounts', 'categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'types', 'selectedBrandIds', 'brands1', 'tags', 'selectedSubCategoryId',));
    }


    public function singleproduct1()
    {
        return view('web.front.singleproduct');
    }

    public function singlepage()
    {
        return view('web.front.product-detail');
    }



    public function product(Request $request, Product $product)
    {
        // Get the brand of the current product
        $currentBrand = $product->brands;
// dd($currentBrand);
        // Find other products with the same brand, excluding the current product
         $relatedProducts = Product::where('brands', $currentBrand)
        ->where('status', 'active') // ✅ important
        ->where('id', '!=', $product->id)
        ->limit(5)
        ->get();

            // dd($relatedProducts);

        return view('web.front.products.product-details', compact('product', 'relatedProducts'));
    }


    public function cart(Request $request)
    {
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $uid = $request->session()->get('FRONT_USER_ID');
        }

        $result['list'] = DB::table('quote')
            ->leftJoin('products', 'products.id', '=', 'quote.product_id')
            ->where(['user_id' => $uid])
            ->select('cart.qty', 'products.name', 'products.product_quantity', 'products.slug', 'products.id as pid')
            ->get();
        return view('front.quote', $result);
    }


    public function getFilteredProducts($subcategoryId, $brandCategoryId)
    {
        $filteredProducts = Product::where('subcategory_id', $subcategoryId)
            ->where('brand_category_id', $brandCategoryId)
            ->get();

        return view('partials.products', ['products' => $filteredProducts]);
    }


    public function subcateg1(Request $request)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id');
        $selectedBrandIds = $request->query('brand_ids', []);

        if ($selectedCategoryId) {
            $selectedCategory = Category::where('id', $selectedCategoryId)->first();
            $subcategories = $selectedCategory->subcategories;

            // Fetch all brands associated with the selected subcategories
            $brands = Brand::whereIn('subcategory_id', $subcategories->pluck('id'))->get();

            // Filter products based on selected brand checkboxes
            $productsQuery = Product::whereIn('subcategory_id', $subcategories->pluck('id'));

            if (!empty($selectedBrandIds)) {
                $productsQuery->whereIn('brand_id', $selectedBrandIds);
            }

            $products = $productsQuery->get();
        } else {
            $subcategories = collect();
            $brands = collect();
            $products = collect();
            $selectedCategory = collect();
        }

        return view('web.front.product', compact('categories', 'subcategories', 'products', 'selectedCategoryId', 'selectedCategory', 'brands', 'selectedBrandIds'));
    }


    function landingPage(Request $request)
    {
        $categories = Category::all();
        $brandsassoc = Brandsassoc::all();
        $clientserve = Clientserve::all();
        $selectedCategory = $categories[0];
        
        $agent = new Agent();

        if ($agent->isMobile() && !$agent->isTablet()) {
           
            // dd('mobile view');
            return view('web/mobile-welcome');
        }
        
        return view('web/welcome', compact('categories', 'selectedCategory','brandsassoc','clientserve'));
    }

    function subcategoryPages(Request $request)
    {
        $Subcategories = Subcategory::where('category_id', $request->CategoryId)->get();
        return view('web.front.products.product_view', compact('Subcategories'));
    }

    function redirectAntherPage(Subcategory $subcategory)
    {
        dd($subcategory);
    }

    function orders()
    {
        $month = '';
        $orderId = '';
        $outlet = '';
        $userData = User::where('priority', auth()->id())->get();
// dd($userData)
        if ($userData->isEmpty()) {
            $userData = User::where('id', auth()->id())->get();
        }

        $OrderData = collect();

      if($userData){
        foreach ($userData as $user) {
            $orders = Order::with('deliveries', 'outstanding')
                        ->where('user_id', auth()->id())
                        ->orderby('created_at', 'desc')
                        ->where('outlet_id', $user->id)
                        ->get();

// dd($orders);
           if ($orders->isEmpty()) {
                $orders = Order::with('deliveries', 'outstanding')
                    ->where('outlet_id', auth()->id())
                    ->orderby('created_at', 'desc')
                    ->where('outlet_id', $user->id)
                    ->get();
            }
            
            // dd($orders);

            foreach ($orders as $order) {
                $userData = User::find($order->outlet_id);
                $orderItems = OrderItem::with('product')->where('order_id', $order->id)->get();
                $order->user_name = $userData->name;
                $order->user_mobile_number = $userData->mobile_number;
                $order->user_email = $userData->email;
                $order->order_items_count = $orderItems->count();
                $orderItemsArray = [];
                foreach ($orderItems as $item) {
                    $orderItemsArray[] = $item->toArray();
                }
                foreach ($order->deliveries as $delivery) {
                    switch ($delivery->delivery_status) {
                        case 'pending':
                            $text = 'In Review';
                            $backgroundColor = 'rgb(255, 153, 51)';
                            $textColor = '#ffffff';
                            $borderColor = 'rgb(255, 229, 204)';
                            $borderradis = '12px';
                            break;
                        case 'in_progress':
                            $text = 'In Progress';
                            $backgroundColor = 'rgb(207 131 18)';
                            $textColor = 'rgb(255, 255, 255)';
                            $borderColor = 'rgb(207 131 18)';
                            $borderradis = '12px';
                            break;
                        case 'ready_for_dispatch':
                            $text = 'Dispatched';
                            $backgroundColor = 'rgb(17, 145, 153)';
                            $textColor = '#ffffff';
                            $borderColor = 'rgb(182, 222, 224)';
                            $borderradis = '12px';
                         
                            break;
                            case 'delivered':
                                $text = 'Delivered';
                                $backgroundColor = 'rgb(58, 183, 87)';
                                $textColor = '#ffffff';
                                $borderColor = 'rgb(163, 240, 181)';
                                $borderradis = '12px';
                            $borderradis = '12px';
                               
                                break;
                        case 'cancelled':
                            $text = 'Cancelled';
                            $backgroundColor = 'rgb(153, 17, 17)';
                            $textColor = '#ffffff';
                            $borderColor = 'rgb(224, 182, 182)';
                            $borderradis = '12px';
                           
                            break;
                        default:
                            $text = 'Pending';
                            $backgroundColor = 'rgb(255, 255, 255)';
                            $textColor = 'rgb(0, 0, 0)';
                            $borderColor = 'rgb(0, 0, 0)';
                            $borderradis = '12px';
                    
                        }

                    // Add these values to the delivery object
                    $delivery->status_text = $text;
                    $delivery->background_color = $backgroundColor;
                    $delivery->text_color = $textColor;
                    $delivery->border_color = $borderColor;
                    $delivery->borderradis = $borderradis;
                }

                $order->order_items = $orderItemsArray;
                // dd()
                $OrderData->push($order);
            // dd($OrderData);
                }
                }
            }else{
            $orders = Order::with('outstanding')
                ->where('outlet_id', auth()->id())
                ->orderby('order_id', 'desc')
                ->get();

// dd($orders);
            foreach ($orders as $order) {
                $userData = User::find($order->outlet_id);
                $orderItems = OrderItem::with('product')->where('order_id', $order->id)->get();
                $order->user_name = $userData->name;
                $order->user_mobile_number = $userData->mobile_number;
                $order->user_email = $userData->email;
                $order->order_items_count = $orderItems->count();
                $orderItemsArray = [];
                foreach ($orderItems as $item) {
                    $orderItemsArray[] = $item->toArray();
                }
                foreach ($order->deliveries as $delivery) {
                    switch ($delivery->delivery_status) {
                        case 'pending':
                            $text = 'In Review';
                            $backgroundColor = 'rgb(255, 255, 204)';
                            $textColor = 'rgb(255, 153, 51)';
                            $borderColor = 'rgb(255, 229, 204)';
                            break;
                        case 'in_progress':
                            $text = 'In Progress';
                            $backgroundColor = 'rgb(255, 255, 112)';
                            $textColor = 'rgb(255, 225, 51)';
                            $borderColor = 'rgb(255, 229, 100)';
                            break;
                        case 'ready_for_dispatch':
                            $text = 'Dispatched';
                            $backgroundColor = 'rgb(235, 255, 239)';
                            $textColor = 'rgb(17, 145, 153)';
                            $borderColor = 'rgb(182, 222, 224)';
                            break;
                        case 'delivered':
                            $text = 'Delivered';
                            $backgroundColor = 'rgb(58, 183, 87)';
                            $textColor = '#ffffff';
                            $borderColor = 'rgb(163, 240, 181)';
                            break;
                        case 'cancelled':
                            $text = 'Cancelled';
                            $backgroundColor = 'rgb(255, 235, 235)';
                            $textColor = 'rgb(153, 17, 17)';
                            $borderColor = 'rgb(224, 182, 182)';
                            break;
                        default:
                            $text = 'Pending';
                            $backgroundColor = 'rgb(255, 255, 255)';
                            $textColor = 'rgb(0, 0, 0)';
                            $borderColor = 'rgb(0, 0, 0)';
                    }

                    // Add these values to the delivery object
                    $delivery->status_text = $text;
                    $delivery->background_color = $backgroundColor;
                    $delivery->text_color = $textColor;
                    $delivery->border_color = $borderColor;
                }
                $order->order_items = $orderItemsArray;

                $OrderData->push($order);

        }


      }
      $OrderData = $OrderData->sortByDesc('created_at');
    //   dd($OrderData);
      $userData = User::where('priority', auth()->id())->get();

      if ($userData->isEmpty()) {
          $userData = User::where('id', auth()->id())->get();
      }
// dd($userData);

        return view('web.front.orders', compact('OrderData', 'month','orderId','userData','outlet'));
    }




    function orders_filter(Request $request)
    {
        $orderId = $request->input('orderId');
        $month = $request->input('month');
        $outlet = $request->input('outlet_name');
       if(!$outlet){
        $userData = User::where('priority', auth()->id())->get();

        if ($userData->isEmpty()) {
            $userData = User::where('id', auth()->id())->get();
        }
       }else{
        $userData = User::where('id', $outlet)->get();
       }

        $OrderData = collect();

      if($userData){

        foreach ($userData as $user) {
            $orders = Order::with('deliveries', 'outstanding')
            ->where('user_id', auth()->id())
            ->where(function ($query) use ($orderId, $month) {
                if ($orderId) {
                    $query->where('order_id', $orderId);
                }
                if ($month) {
                    list($year, $month) = explode('-', $month);
                    $query->whereYear('created_at', $year)
                          ->whereMonth('created_at', $month);
                }
            })
            ->where('outlet_id', $user->id)
            ->orderBy('order_id', 'desc')
            ->get();

           if ($orders->isEmpty()) {
                $orders = Order::with('deliveries', 'outstanding')
                    ->where('outlet_id', auth()->id())
                    ->orderby('order_id', 'desc')
                    ->where('outlet_id', $user->id)
                    ->get();
            }

            foreach ($orders as $order) {
                $userData = User::find($order->outlet_id);
                $orderItems = OrderItem::with('product')->where('order_id', $order->id)->get();
                $order->user_name = $userData->name;
                $order->user_mobile_number = $userData->mobile_number;
                $order->user_email = $userData->email;
                $order->order_items_count = $orderItems->count();
                $orderItemsArray = [];
                foreach ($orderItems as $item) {
                    $orderItemsArray[] = $item->toArray();
                }
                foreach ($order->deliveries as $delivery) {
                    switch ($delivery->delivery_status) {
                        case 'pending':
                            $text = 'In Review';
                            $backgroundColor = 'rgb(255, 255, 204)';
                            $textColor = 'rgb(255, 153, 51)';
                            $borderColor = 'rgb(255, 229, 204)';
                            break;
                        case 'in_progress':
                            $text = 'In Progress';
                            $backgroundColor = 'rgb(207 131 18)';
                            $textColor = 'rgb(255, 255, 255)';
                            $borderColor = 'rgb(207 131 18)';
                            break;
                        case 'ready_for_dispatch':
                            $text = 'Dispatched';
                            $backgroundColor = 'rgb(235, 255, 239)';
                            $textColor = 'rgb(17, 145, 153)';
                            $borderColor = 'rgb(182, 222, 224)';
                            break;
                        case 'delivered':
                            $text = 'Delivered';
                            $backgroundColor = 'rgb(235, 255, 239)';
                            $textColor = 'rgb(58, 183, 87)';
                            $borderColor = 'rgb(163, 240, 181)';
                            break;
                        case 'cancelled':
                            $text = 'Cancelled';
                            $backgroundColor = 'rgb(255, 235, 235)';
                            $textColor = 'rgb(153, 17, 17)';
                            $borderColor = 'rgb(224, 182, 182)';
                            break;
                        default:
                            $text = 'Pending';
                            $backgroundColor = 'rgb(255, 255, 255)';
                            $textColor = 'rgb(0, 0, 0)';
                            $borderColor = 'rgb(0, 0, 0)';
                    }

                    // Add these values to the delivery object
                    $delivery->status_text = $text;
                    $delivery->background_color = $backgroundColor;
                    $delivery->text_color = $textColor;
                    $delivery->border_color = $borderColor;
                }

                $order->order_items = $orderItemsArray;

                $OrderData->push($order);
                }
                }
            }else{
            $orders = Order::with('outstanding')
                ->where('outlet_id', auth()->id())
                ->orderby('order_id', 'desc')
                ->get();


            foreach ($orders as $order) {
                $userData = User::find($order->outlet_id);
                $orderItems = OrderItem::with('product')->where('order_id', $order->id)->get();
                $order->user_name = $userData->name;
                $order->user_mobile_number = $userData->mobile_number;
                $order->user_email = $userData->email;
                $order->order_items_count = $orderItems->count();
                $orderItemsArray = [];
                foreach ($orderItems as $item) {
                    $orderItemsArray[] = $item->toArray();
                }
                foreach ($order->deliveries as $delivery) {
                    switch ($delivery->delivery_status) {
                        case 'pending':
                            $text = 'In Review';
                            $backgroundColor = 'rgb(255, 255, 204)';
                            $textColor = 'rgb(255, 153, 51)';
                            $borderColor = 'rgb(255, 229, 204)';
                            break;
                        case 'in_progress':
                            $text = 'In Progress';
                            $backgroundColor = 'rgb(255, 255, 112)';
                            $textColor = 'rgb(255, 225, 51)';
                            $borderColor = 'rgb(255, 229, 100)';
                            break;
                        case 'ready_for_dispatch':
                            $text = 'Dispatched';
                            $backgroundColor = 'rgb(235, 255, 239)';
                            $textColor = 'rgb(17, 145, 153)';
                            $borderColor = 'rgb(182, 222, 224)';
                            break;
                        case 'delivered':
                            $text = 'Delivered';
                            $backgroundColor = 'rgb(235, 255, 239)';
                            $textColor = 'rgb(58, 183, 87)';
                            $borderColor = 'rgb(163, 240, 181)';
                            break;
                        case 'cancelled':
                            $text = 'Cancelled';
                            $backgroundColor = 'rgb(255, 235, 235)';
                            $textColor = 'rgb(153, 17, 17)';
                            $borderColor = 'rgb(224, 182, 182)';
                            break;
                        default:
                            $text = 'Pending';
                            $backgroundColor = 'rgb(255, 255, 255)';
                            $textColor = 'rgb(0, 0, 0)';
                            $borderColor = 'rgb(0, 0, 0)';
                    }

                    // Add these values to the delivery object
                    $delivery->status_text = $text;
                    $delivery->background_color = $backgroundColor;
                    $delivery->text_color = $textColor;
                    $delivery->border_color = $borderColor;
                }
                $order->order_items = $orderItemsArray;

                $OrderData->push($order);

        }


      }
      $OrderData = $OrderData->sortByDesc('order_id');
      $userData = User::where('priority', auth()->id())->get();

      if ($userData->isEmpty()) {
          $userData = User::where('id', auth()->id())->get();
      }

        return view('web.front.orders', compact('OrderData','month','orderId','userData','outlet'));
    }





 
 function requestproduct()
{
    if (auth()->check()) {
        return view('web.front.requestproduct');
    } else {
        return redirect()->route('customer.logout')->with('error', 'You have not logged in yet.');
    }
}



    function profile()
    {

     if (!Auth::check()) {
            return redirect()->route('homepage')->with('error', 'You are not logged in. Please log in to continue.');
      }
        $userData = User::where('priority', auth()->id())->get();

        
        return view('web.front.profile', ['userData' => $userData]);
    }
    
    
    public function terms_condition()
    {
         $agent = new Agent();

        if ($agent->isMobile() && !$agent->isTablet()) {
           
            // dd('mobile view');
            return view('web/mobile_terms_condition');
        }
        
        return view('web.terms-condition'); 
    }

    public function privacy_policy()
    {
         $agent = new Agent();

        if ($agent->isMobile() && !$agent->isTablet()) {
           
            // dd('mobile view');
            return view('web/mobile_privacy_policy');
        }
        
        return view('web.privacy-policy'); 
    }
    
     public function shipping_policy()
    {
        return view('web.shipping_policy'); 
    }
    
    public function refund()
    {
        return view('web.retrurn-replacement'); 
    }
    public function payment()
    {
        return view('web.payments'); 
    }
    
    
    
    
    public function updateImage(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = auth()->user();

    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);

        // Remove old image if exists
        if ($user->profile_image && file_exists(public_path('uploads/profile/' . $user->profile_image))) {
            unlink(public_path('uploads/profile/' . $user->profile_image));
        }

        $user->profile_image = $filename;
        $user->save();
    }

    return back()->with('success', 'Profile image updated successfully!');
}
    
    
}
