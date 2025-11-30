<?php

// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\PerfumeVariantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DiscountController;
use App\Models\Category;
use App\Models\Role;
use App\Models\PerfumeSize;
use App\Models\PerfumeTier;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentMode;
use App\Models\DiscountType;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Users (Admin only - authorization in controller/request)
    Route::apiResource('users', UserController::class);
    
    // Perfumes (Admin CRUD, All view)
    Route::apiResource('perfumes', PerfumeController::class);
    
    // Perfume Variants (Admin CRUD, All view)
    Route::apiResource('variants', PerfumeVariantController::class);
    
    // Orders (Reseller own, Admin all)
    Route::apiResource('orders', OrderController::class);
    
    // Order Items (Read-only, managed through orders)
    Route::get('order-items', [OrderItemController::class, 'index']);
    Route::get('order-items/{orderItem}', [OrderItemController::class, 'show']);
    
    // Payments (Admin records, All view own)
    Route::apiResource('payments', PaymentController::class);
    
    // Discounts
    Route::apiResource('discounts', DiscountController::class);
    
    // Lookup Tables (Read-only, seeded data)
    Route::get('categories', fn() => response()->json(
        Category::where('is_active', true)->get()
    ));
    
    Route::get('roles', fn() => response()->json(
        Role::where('is_active', true)->get()
    ));
    
    Route::get('sizes', fn() => response()->json(
        PerfumeSize::where('is_active', true)->get()
    ));
    
    Route::get('tiers', fn() => response()->json(
        PerfumeTier::where('is_active', true)->get()
    ));
    
    Route::get('order-statuses', fn() => response()->json(
        OrderStatus::where('is_active', true)->get()
    ));
    
    Route::get('payment-statuses', fn() => response()->json(
        PaymentStatus::where('is_active', true)->get()
    ));
    
    Route::get('payment-modes', fn() => response()->json(
        PaymentMode::where('is_active', true)->get()
    ));
    
    Route::get('discount-types', fn() => response()->json(
        DiscountType::all()
    ));
});

/*
|--------------------------------------------------------------------------
| Route Explanations
|--------------------------------------------------------------------------
| 
| apiResource() generates these routes automatically:
| 
| GET    /api/users           -> index()   - List all
| POST   /api/users           -> store()   - Create new
| GET    /api/users/{id}      -> show()    - Get single
| PUT    /api/users/{id}      -> update()  - Update
| DELETE /api/users/{id}      -> destroy() - Delete
|
| All routes are prefixed with /api (configured in bootstrap/app.php)
| All routes require Sanctum authentication except /login
|
*/