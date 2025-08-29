<?php

use App\Http\Controllers\Api\About\AboutController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\CacheComparisonController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\SessionComparisonController;
use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\Regulations\LanguageController;
use \App\Http\Controllers\Api\Regulations\TranslationController;
use \App\Http\Controllers\Api\Regulations\TeamController;
use \App\Http\Controllers\Api\Regulations\CertificateController;
use \App\Http\Controllers\Api\Products\ProductController;
use \App\Http\Controllers\Api\Products\CategoryController;
use App\Http\Controllers\Api\Products\ProductReviewController;
use App\Http\Controllers\Api\Subscribe\SubscriberController;
use App\Http\Controllers\Api\Users\EmailChangeController;
use App\Http\Controllers\Api\Users\UserFavoriteController;
use App\Http\Controllers\Api\Users\UserOrderController;
use App\Http\Controllers\Api\Users\UserCardController;
use App\Http\Controllers\Api\VacancyApiController;
use App\Http\Controllers\Api\OurOnMapController;
use App\Http\Controllers\Api\StaticPageController;
use App\Http\Controllers\Api\BlogAndNewsController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PriceQuoteController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\Orders\CancelOrderReasonController;
use App\Http\Controllers\Api\Orders\CityController;
use App\Http\Controllers\Api\Users\UserComparisonController;
use App\Http\Controllers\Api\SiteSettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/users',[UserController::class,'index']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/resend-otp',[AuthController::class,'resendOtp']);


Route::post('/email/verify', [AuthController::class,'verify']);

Route::post('/login',[AuthController::class,'login']);
Route::post('/refresh-token',[AuthController::class,'refreshToken']);


Route::middleware('auth:api')->post('/user',[AuthController::class,'user']);


Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::get('/site-languages', [LanguageController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/translations', [TranslationController::class, 'index']);
Route::get('/teams', [TeamController::class, 'index']);
Route::get('/certificates', [CertificateController::class, 'index']);



Route::get('/products', [ProductController::class, 'index']);
Route::post('/search-products', [ProductController::class, 'search']);
Route::get('/products/{slug}', [ProductController::class, 'single']);
Route::get('/all-products', [ProductController::class, 'allProducts']);
Route::get('/product/{slug}', [ProductController::class, 'product']);
Route::get('/products-similarly/{product_id}', [ProductController::class, 'productSimilary']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/mobile-categories', [CategoryController::class, 'mobileCategories']);
Route::get('/category/{slug}', [CategoryController::class, 'category']);
Route::get('/category/{slug}/products', [CategoryController::class, 'products']);
Route::get('/catalogs', [CatalogApiController::class,'index']);
Route::get('/vacancies', [VacancyApiController::class,'index']);
Route::get('/vacancy-receipents', [VacancyApiController::class,'vacancyReceipents']);
Route::get('/vacany-share-links', [VacancyApiController::class,'vacancyShareLinks']);
Route::get('/vacancies/{slug}', [VacancyApiController::class,'single']);
Route::get('/our-on-map', [OurOnMapController::class,'index']);
Route::get('/pages/{slug}', [StaticPageController::class,'index']);
Route::get('/blog-and-news', [BlogAndNewsController::class,'index']);
Route::get('/blog-and-news-other', [BlogAndNewsController::class,'others']);
Route::get('/blog-and-news/{slug}', [BlogAndNewsController::class,'single']);
Route::get('/all-blog-and-news', [BlogAndNewsController::class,'getAllBlogs']);
Route::get('/home-products', [HomeController::class,'index']);
Route::get('/brends', [HomeController::class,'getBrends']);
Route::get('/brends/{id}', [HomeController::class,'getBrend']);
Route::get('/properties', [PropertyController::class,'index']);
Route::get('/banners', [HomeController::class,'getBanners']);
Route::get('/weekly-offers', [HomeController::class,'getWeeklyOffers']);
Route::get('/banner-details', [HomeController::class,'getBannerDetails']);
Route::get('/discounted-products', [HomeController::class,'getDiscountedProducts']);
Route::get('/social-links', [HomeController::class,'getSocialLinks']);
Route::post('/vacancy/apply', [VacancyApiController::class,'apply']);
Route::get('/vacancy/{id}', [VacancyApiController::class, 'show']);
Route::get('/cancel-order-reasons', [CancelOrderReasonController::class, 'index']);
Route::get('/cities', [CityController::class, 'index']);
Route::get('vacancy-banner', [VacancyApiController::class, 'banner']);
Route::get('menu/categories', [CategoryController::class, 'menuCategories']);
Route::get('menu/category/{item}', [CategoryController::class, 'getMenu']);



Route::post('/order', [OrderController::class, 'placeOrder']);


Route::post('/calculate-price', [UserCardController::class, 'calculatePrice']);


Route::middleware('auth:api')->group(function () {
    Route::post('/products/reviews', [ProductReviewController::class, 'store']);
    Route::post('/favorites', [UserFavoriteController::class, 'addToFavorites']);
    Route::get('/user/favorites', [UserFavoriteController::class, 'getFavorites']);

    Route::get('/user/orders', [UserOrderController::class, 'getOrders']);
    Route::get('/user/orders/change-address', [UserOrderController::class, 'changeAddress']);

    Route::post('/user/profile', [UserController::class, 'update']);
    Route::post('/change-email/send-otp', [EmailChangeController::class, 'sendOtp']);
    Route::post('/change-email', [EmailChangeController::class, 'changeEmail']);
    //add card item
    Route::post('/cards', [UserCardController::class, 'addToCards']);
    //get user cards
    Route::get('/user/cards', [UserCardController::class, 'getCards']);



    Route::post('/cards/remove-product', [UserCardController::class, 'removeCardProduct']);
    Route::post('/cards/update-quantity-increase', [UserCardController::class, 'updateCardQuantityIncrease']);
    Route::post('/cards/update-quantity-decrease', [UserCardController::class, 'updateCardQuantityDecrease']);

    //orders
    Route::post('/cancel-order', [OrderController::class, 'cancelOrder']);

    //comparisons
    Route::post('/comparisons', [UserComparisonController::class, 'addToComparisons']);
    Route::post('/comparison', [UserComparisonController::class, 'addSingleToComparison']);
    Route::delete('/comparison/{productId}', [UserComparisonController::class, 'removeFromComparison']);
    Route::get('/user/comparisons', [UserComparisonController::class, 'getComparisons']);
    Route::get('/user/comparisons/category', [UserComparisonController::class, 'getComparisonsByCategory']);
    Route::get('/user/comparisons/category/{id}', [UserComparisonController::class, 'getComparisonsByCategoryId']);
    Route::get('/user/comparisons/categories-list', [UserComparisonController::class, 'getComparisonCategories']);
    
    Route::prefix('user/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/delete-all', action: [NotificationController::class, 'destroryAll']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
    });
});


Route::prefix('/cache')->group(function () {
    Route::post('/comparisons', [CacheComparisonController::class, 'addToComparisons']);
    Route::post('/comparison', [CacheComparisonController::class, 'addSingleToComparison']);
    Route::delete('/comparison/{productId}', [CacheComparisonController::class, 'removeFromComparison']);
    Route::get('/comparisons', [CacheComparisonController::class, 'getComparisons']);
    Route::get('/comparisons/category', [CacheComparisonController::class, 'getComparisonsByCategory']);
    Route::post('/comparisons/category/{id}', [CacheComparisonController::class, 'getComparisonsByCategoryId']);
    Route::post('/comparisons/categories-list', [CacheComparisonController::class, 'getComparisonCategories']);
});


Route::post('/subscribe', [SubscriberController::class,'subscribe']);
Route::post('/price-quote', [PriceQuoteController::class, 'store']);
Route::get('/site-settings', [SiteSettingController::class, 'index']);




//favicon deyisdirilmelidi
//icon pakeiti
//qiymet teklifi al footer da link kamandamizda hemcinin
