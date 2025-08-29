<?php

use App\Http\Controllers\Backend\Admins\AdminController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Regulations\BrendController;
use App\Http\Controllers\Backend\Regulations\CityController;
use App\Http\Controllers\Backend\Regulations\LanguageController;
use App\Http\Controllers\Backend\Regulations\TranslationController;
use App\Http\Controllers\Backend\Regulations\TeamController;
use App\Http\Controllers\Backend\Regulations\CertificateController;
use App\Http\Controllers\Backend\Regulations\CategoryController;
use App\Http\Controllers\Backend\Regulations\SubCategoryController;
use App\Http\Controllers\Backend\Regulations\BrandController;
use App\Http\Controllers\Backend\Regulations\VacancyReceipentController;
use App\Http\Controllers\Backend\Regulations\VacancyShareSocialController;
use App\Http\Controllers\Backend\Settings\SiteSettingController;
use App\Http\Controllers\Backend\Users\UserController;
use App\Http\Controllers\Backend\WeeklySelectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\About\AboutController;
use App\Http\Controllers\Backend\OurOnMap\OurOnMapController;
use App\Http\Controllers\Backend\Products\ProductController;
use App\Http\Controllers\Backend\BlogNew\BlogNewController;
use App\Http\Controllers\Backend\Rating\RatingController;
use App\Http\Controllers\Backend\ReturnPolicy\ReturnPolicyController;
use App\Http\Controllers\Backend\ComplainManagement\ComplainManagementController;
use App\Http\Controllers\Backend\DeliveryPayment\DeliveryPaymentController;
use App\Http\Controllers\Backend\Catalog\CatalogController;
use App\Http\Controllers\Backend\Regulations\BannerController;
use App\Http\Controllers\Backend\Regulations\BannerDetailController;
use App\Http\Controllers\Backend\SocialLinkController;
use App\Http\Controllers\Backend\VacancyController;
use App\Http\Controllers\Backend\PriceQuoteController;
use App\Http\Controllers\Backend\VacancyApplicationController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OrderCancellationReasonController;
use App\Http\Controllers\Backend\Vacancy\VacancyBannerController;
use App\Http\Controllers\Backend\GeneralController;
use App\Http\Controllers\Backend\SubScriberController;


Route::get("/login", [LoginController::class, 'login'])->name('.login');
Route::post("/login", [LoginController::class, 'loginPost'])->name('.login.post');
Route::post("/logout", [LoginController::class, 'logout'])->name('.logout');

Route::middleware("auth:admin")->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('.dashboard');

    Route::group(['prefix' => 'languages', 'as' => '.languages'], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('.index');
        Route::get('/create', [LanguageController::class, 'create'])->name('.create');
        Route::post('/store', [LanguageController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [LanguageController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [LanguageController::class, 'update'])->name('.update');
        Route::delete('/{item}', [LanguageController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'users', 'as' => '.users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('.index');
        Route::get('/create', [UserController::class, 'create'])->name('.create');
        Route::post('/store', [UserController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [UserController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [UserController::class, 'update'])->name('.update');
        Route::delete('/{item}', [UserController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'translations', 'as' => '.translations'], function () {
        Route::get('/', [TranslationController::class, 'index'])->name('.index');
        Route::get('/create', [TranslationController::class, 'create'])->name('.create');
        Route::post('/store', [TranslationController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [TranslationController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [TranslationController::class, 'update'])->name('.update');
        Route::delete('/{item}', [TranslationController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'about', 'as' => '.about'], function () {
        Route::get('/', [AboutController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [AboutController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'teams', 'as' => '.teams'], function () {
        Route::get('/', [TeamController::class, 'index'])->name('.index');
        Route::get('/create', [TeamController::class, 'create'])->name('.create');
        Route::post('/store', [TeamController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [TeamController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [TeamController::class, 'update'])->name('.update');
        Route::delete('/{item}', [TeamController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'certificates', 'as' => '.certificates'], function () {
        Route::get('/', [CertificateController::class, 'index'])->name('.index');
        Route::get('/create', [CertificateController::class, 'create'])->name('.create');
        Route::post('/store', [CertificateController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CertificateController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CertificateController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CertificateController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'categories', 'as' => '.categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CategoryController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CategoryController::class, 'update'])->name('.update');
        Route::post('/subcategories', [CategoryController::class, 'getSubCategories'])->name('.getSubCategories');
        Route::post('/brends', [CategoryController::class, 'getBrends'])->name('.getBrends');
        Route::delete('/{item}', [CategoryController::class, 'delete'])->name('.destroy');
        
        Route::group(['prefix' => '{category}/subcategories', 'as' => '.subcategories'], function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('.store');
            Route::get('/{item}/edit', [SubCategoryController::class, 'edit'])->name('.edit');
            Route::put('/{item}/update', [SubCategoryController::class, 'update'])->name('.update');
            Route::delete('/{item}', [SubCategoryController::class, 'delete'])->name('.destroy');
        });
    });

    Route::group(['prefix' => 'brands', 'as' => '.brands'], function () {
        Route::get('/', [BrandController::class, 'index'])->name('.index');
        Route::get('/create', [BrandController::class, 'create'])->name('.create');
        Route::post('/store', [BrandController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BrandController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BrandController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BrandController::class, 'delete'])->name('.destroy');
    });


    

    Route::group(['prefix' => 'products', 'as' => '.products'], function () {
        Route::get('/', [ProductController::class, 'index'])->name('.index');
        Route::get('/create', [ProductController::class, 'create'])->name('.create');
        Route::post('/store', [ProductController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [ProductController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [ProductController::class, 'update'])->name('.update');
        Route::delete('/{item}', [ProductController::class, 'delete'])->name('.destroy');
        Route::delete('/delete/image/{image}', [ProductController::class, 'deleteImage'])->name('.deleteImage');

       


        Route::get('/properties_old', [ProductController::class, 'properties'])->name('.properties_old.index');
        Route::get('/properties_old/create', [ProductController::class, 'propertiesCreate'])->name('.properties_old.create');
        Route::post('/properties_old/store', [ProductController::class, 'propertiesStore'])->name('.properties_old.store');
        Route::get('/properties_old/{id}/edit/{item_id}', [ProductController::class, 'propertiesEdit'])->name('.properties_old.edit');
        Route::put('/properties_old/{id}/update/{item_id}', [ProductController::class, 'propertiesUpdate'])->name('.properties_old.update');
        Route::delete('/properties_old/{id}/{item_id}', [ProductController::class, 'propertiesDelete'])->name('.properties_old.destroy');


        Route::post('/toggle-seasonal', [ProductController::class, 'toggleSeasonal'])->name('products.toggle-seasonal');
        Route::post('/toggle-special-offer', [ProductController::class, 'toggleSpecialOffer'])->name('products.toggle-special-offer');
        Route::post('/toggle-bundle', [ProductController::class, 'toggleBundle'])->name('products.toggle-bundle');
        Route::post('/toggle-weekly-offer', [ProductController::class, 'toggleWeeklyOffer'])->name('products.toggle-weekly-offer');

        Route::get('/get-sub-properties', [ProductController::class, 'getSubProperties'])->name('get-sub-properties');

    });


    Route::group(['prefix' => 'blognews', 'as' => '.blognews'], function () {
        Route::get('/', [BlogNewController::class, 'index'])->name('.index');
        Route::get('/create', [BlogNewController::class, 'create'])->name('.create');
        Route::post('/store', [BlogNewController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BlogNewController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BlogNewController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BlogNewController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'oru-on-map', 'as' => '.ouronmap'], function () {
        Route::get('/', [OurOnMapController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [OurOnMapController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'rating', 'as' => '.rating'], function () {
        Route::get('/', [RatingController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [RatingController::class, 'update'])->name('.update');
    });
    Route::group(['prefix' => 'return-policy', 'as' => '.returnpolicy'], function () {
        Route::get('/', [ReturnPolicyController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [ReturnPolicyController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'complain-management', 'as' => '.complainmanagement'], function () {
        Route::get('/', [ComplainManagementController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [ComplainManagementController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'delivery-payment', 'as' => '.deliverypayment'], function () {
        Route::get('/', [DeliveryPaymentController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [DeliveryPaymentController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'catalogs', 'as' => '.catalogs'], function () {
        Route::get('/', [CatalogController::class, 'index'])->name('.index');
        Route::get('/create', [CatalogController::class, 'create'])->name('.create');
        Route::post('/store', [CatalogController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CatalogController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CatalogController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CatalogController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'vacancies', 'as' => '.vacancies'], function () {
        Route::get('/', [VacancyController::class, 'index'])->name('.index');
        Route::get('/create', [VacancyController::class, 'create'])->name('.create');
        Route::post('/store', [VacancyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [VacancyController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'brends', 'as' => '.brends'], function () {
        Route::get('/', [BrendController::class, 'index'])->name('.index');
        Route::get('/create', [BrendController::class, 'create'])->name('.create');
        Route::post('/store', [BrendController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BrendController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BrendController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BrendController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'banners', 'as' => '.banners'], function () {
        Route::get('/', [BannerController::class, 'index'])->name('.index');
        Route::get('/create', [BannerController::class, 'create'])->name('.create');
        Route::post('/store', [BannerController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BannerController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BannerController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BannerController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'banner-details', 'as' => '.banner_details'], function () {
        Route::get('/', [BannerDetailController::class, 'index'])->name('.index');
        Route::get('/create', [BannerDetailController::class, 'create'])->name('.create');
        Route::post('/store', [BannerDetailController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [BannerDetailController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [BannerDetailController::class, 'update'])->name('.update');
        Route::delete('/{item}', [BannerDetailController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'social-links', 'as' => '.social_links'], function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('.index');
        Route::get('/create', [SocialLinkController::class, 'create'])->name('.create');
        Route::post('/store', [SocialLinkController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [SocialLinkController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [SocialLinkController::class, 'update'])->name('.update');
        Route::delete('/{item}', [SocialLinkController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'price-quote', 'as' => '.price-quotes'], function () {
        Route::get('/', [PriceQuoteController::class, 'index'])->name('.index');
        Route::get('/delete-price-quote/{price}', [PriceQuoteController::class, 'deletePriceQuote'])->name('.delete');
    });

    Route::group(['prefix' => 'properties', 'as' => '.properties'], function () {
        Route::get('/', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'index'])->name('.index');
        Route::get('/create', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'create'])->name('.create');
        Route::post('/store', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [\App\Http\Controllers\Backend\Regulations\PropertyController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'sub-properties', 'as' => '.sub-properties'], function () {
        Route::get('/', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'index'])->name('.index');
        Route::get('/create', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'create'])->name('.create');
        Route::post('/store', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'update'])->name('.update');
        Route::delete('/{item}', [\App\Http\Controllers\Backend\Regulations\SubPropertyController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'vacancy_applications', 'as' => '.vacancy_applications'], function () {
        Route::get('/', [VacancyApplicationController::class, 'index'])->name('.index');
        Route::delete('/{id}', [VacancyApplicationController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'subscribers', 'as' => '.subscribers'], function () {
        Route::get('/', [SubScriberController::class, 'index'])->name('.index');
        Route::delete('{id}', [SubScriberController::class, 'delete'])->name('.destroy');
    });

    Route::group(['prefix' => 'orders', 'as' => '.orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('.show');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('.destroy');
        Route::post('/status/{order}', [OrderController::class, 'updateStatus'])->name('.update-status');
    });

    Route::group(['prefix' => 'order-cancellation-reasons', 'as' => '.order_cancellation_reasons'], function () {
        Route::get('/', [OrderCancellationReasonController::class, 'index'])->name('.index');
        Route::get('/create', [OrderCancellationReasonController::class, 'create'])->name('.create');
        Route::post('/store', [OrderCancellationReasonController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [OrderCancellationReasonController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [OrderCancellationReasonController::class, 'update'])->name('.update');
        Route::delete('/{item}', [OrderCancellationReasonController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'cities', 'as' => '.cities'], function () {
        Route::get('/', [CityController::class, 'index'])->name('.index');
        Route::get('/create', [CityController::class, 'create'])->name('.create');
        Route::post('/store', [CityController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [CityController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [CityController::class, 'update'])->name('.update');
        Route::delete('/{item}', [CityController::class, 'delete'])->name('.destroy');
    });


    Route::group(['prefix'=>'vacancy-receipents', 'as'=>'.vacancy-receipents'],function(){
        Route::get('/', [VacancyReceipentController::class,'index'])->name('.index');
        Route::get('/create', [VacancyReceipentController::class,'create'])->name('.create');
        Route::post('/store', [VacancyReceipentController::class,'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyReceipentController::class,'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyReceipentController::class,'update'])->name('.update');
        Route::delete('/{item}', [VacancyReceipentController::class,'delete'])->name('.destroy');
    });

    Route::group(['prefix'=>'vacancy-share-socials', 'as'=>'.vacancy-share-socials'], function(){
        Route::get('/', [VacancyShareSocialController::class,'index'])->name('.index');
        Route::get('/create', [VacancyShareSocialController::class,'create'])->name('.create');
        Route::post('/store', [VacancyShareSocialController::class,'store'])->name('.store');
        Route::get('/{item}/edit', [VacancyShareSocialController::class,'edit'])->name('.edit');
        Route::put('/{item}/update', [VacancyShareSocialController::class,'update'])->name('.update');
        Route::delete('/{item}', [VacancyShareSocialController::class,'delete'])->name('.destroy');
    });


    Route::group(['prefix' => 'vacancy-banner', 'as' => '.vacancy-banner'], function () {
        Route::get('/', [VacancyBannerController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [VacancyBannerController::class, 'update'])->name('.update');
    });


    Route::group(['prefix' => 'weekly-selections', 'as' => '.weekly_selections'], function () {
        Route::get('/', [WeeklySelectionController::class, 'index'])->name('.index');
        Route::get('/create', [WeeklySelectionController::class, 'create'])->name('.create');
        Route::post('/store', [WeeklySelectionController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [WeeklySelectionController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [WeeklySelectionController::class, 'update'])->name('.update');
        Route::delete('/{item}', [WeeklySelectionController::class, 'delete'])->name('.destroy');
    });
    

    // web.php
    Route::post('/all/update-order', [GeneralController::class, 'updateOrder'])->name('.all.update-order');
    Route::post('/update-status', [GeneralController::class, 'updateStatus']) ->name('.update-status');


    Route::group(['prefix' => 'settings', 'as' => '.settings'], function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('.index');
        Route::put('/{item}/update', [SiteSettingController::class, 'update'])->name('.update');
    });

    Route::group(['prefix' => 'admins', 'as' => '.admins'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('.index');
        Route::get('/create', [AdminController::class, 'create'])->name('.create');
        Route::post('/store', [AdminController::class, 'store'])->name('.store');
        Route::get('/{item}/edit', [AdminController::class, 'edit'])->name('.edit');
        Route::put('/{item}/update', [AdminController::class, 'update'])->name('.update');
        Route::delete('/{item}', [AdminController::class, 'delete'])->name('.destroy');
    });
});
