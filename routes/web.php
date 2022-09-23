<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('main.landingPage.index');
});

Auth::routes();

Route::get('/susisiekti', [App\Http\Controllers\ContactUsController::class, 'index'])->name('main.landingPage.contact');
Route::post('/susisiekti', [App\Http\Controllers\ContactUsController::class, 'store'])->name('main.landingPage.store');
Route::view('/apie-mus', 'main.landingPage.about')->name('main.landingPage.about');

Route::get('/svečias/skelbimai', [App\Http\Controllers\LandingPageController::class, 'index'])->name('main.guest.listings');
Route::get('/svečias/skelbimas/{id}', [App\Http\Controllers\LandingPageController::class, 'show'])->name('main.guest.listing');
Route::get('/svečias/paieška', [App\Http\Controllers\LandingPageController::class, 'search'])->name('main.guest.search');
Route::get('/svečias/filtras/pirkti', [App\Http\Controllers\LandingPageController::class, 'buyFilter'])->name('main.guest.filter.buy');
Route::get('/svečias/filtras/mainyti', [App\Http\Controllers\LandingPageController::class, 'tradeFilter'])->name('main.guest.filter.trade');
Route::get('/svečias/filtras/pirkti-arba-mainyti', [App\Http\Controllers\LandingPageController::class, 'buyOrTradeFilter'])->name('main.guest.filter.buyOrTrade');
Route::get('/svečias/skelbimai/kategorija{category}', [App\Http\Controllers\LandingPageController::class, 'searchCategory'])->name('main.guest.filter.category');

Route::group(['middleware' => ['auth', 'active_user', 'role:user|administrator']], function() {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::get('/user/filtras/pirkti', [App\Http\Controllers\UserController::class, 'buyFilter'])->name('user.filter.buy');
    Route::get('/user/filtras/mainyti', [App\Http\Controllers\UserController::class, 'tradeFilter'])->name('user.filter.trade');
    Route::get('/user/filtras/pirkti-arba-mainyti', [App\Http\Controllers\UserController::class, 'buyOrTradeFilter'])->name('user.filter.buyOrTrade');
    Route::get('/user/paieška', [App\Http\Controllers\SearchController::class, 'search'])->name('user.search');
    Route::get('/user/kategorija/{category}', [App\Http\Controllers\SearchController::class, 'searchCategory'])->name('user.category.index');
    
    Route::get('/asmeniniai_skelbimai', [App\Http\Controllers\UserListingController::class, 'index'])->name('user.userlistings.show');
    Route::post('/asmeniniai_skelbimai', [App\Http\Controllers\UserListingController::class, 'store'])->name('user.userlistings.store');
    Route::get('/asmeniniai_skelbimai/prideti', [App\Http\Controllers\UserListingController::class, 'create'])->name('user.userlistings.create');
    Route::delete('/asmeniniai_skelbimai/istrinti/{listing}', [App\Http\Controllers\UserListingController::class, 'destroy'])->name('user.userlistings.destroy');
    Route::get('/asmeniniai_skelbimai/{listing}/redaguoti', [App\Http\Controllers\UserListingController::class, 'edit'])->name('user.userlistings.edit');
    Route::patch('/asmeniniai_skelbimai/{listing}', [App\Http\Controllers\UserListingController::class, 'update'])->name('user.userlistings.update');
       
    Route::get('/skelbimai/{listing}', [App\Http\Controllers\ListingController::class, 'index'])->name('user.listings.show');
    Route::post('/skelbimas/pirkti/{product}', [App\Http\Controllers\ListingController::class, 'checkout'])->name('user.listings.checkout');
    Route::post('/skelbimas/pirkti', [App\Http\Controllers\ListingController::class, 'afterPayment'])->name('user.checkout.credit-card');
    Route::post('/skelbimas/{listing}/komentuoti', [App\Http\Controllers\ListingController::class, 'comment'])->name('user.listings.comment');
    Route::delete('/skelbimas/{listing}/komentaras/{comment}', [App\Http\Controllers\ListingController::class, 'destroyComment'])->name('user.listings.destroyComment');
    
    Route::get('/skelbimas/mainyti/{listing}', [App\Http\Controllers\TradeController::class, 'index'])->name('user.trade.index');
    Route::post('/skelbimas/mainyti/{listing}', [App\Http\Controllers\TradeController::class, 'offer'])->name('user.trade.offer');
    
    Route::get('/aktyvūs-mainai', [App\Http\Controllers\TradeController::class, 'show'])->name('user.activeTrade.index');
    Route::get('/aktyvūs-mainai/išsiūsti-pasiūlymai', [App\Http\Controllers\TradeController::class, 'sentTrades'])->name('user.activeTrade.sentTrades');
    Route::put('/aktyvūs-mainai/priimti/{trade}', [App\Http\Controllers\TradeController::class, 'accept'])->name('user.trade.accept');
    Route::put('/aktyvūs-mainai/atmesti/{trade}', [App\Http\Controllers\TradeController::class, 'cancel'])->name('user.trade.cancel');
    Route::put('/aktyvūs-mainai/baigti/{trade}', [App\Http\Controllers\TradeController::class, 'complete'])->name('user.trade.complete');
    Route::put('/aktyvūs-mainai/pašalinti/{trade}',[App\Http\Controllers\TradeController::class, 'delete'])->name('user.trade.delete');

    Route::get('/atsiliepimai', [App\Http\Controllers\CommentsController::class, 'index'])->name('user.comments.index');
    Route::get('/atsiliepimai/išsiųsti', [App\Http\Controllers\CommentsController::class, 'sentComments'])->name('user.comments.sentComments');
    Route::delete('/atsiliepimai/istrinti/{comment}', [App\Http\Controllers\CommentsController::class, 'destroy'])->name('user.comments.destroy');
    Route::post('/atsakas/skelbimas/{listing}', [App\Http\Controllers\CommentsController::class, 'reply'])->name('user.comments.reply');

    Route::get('user/aktyvūs-užsakymai', [App\Http\Controllers\ActiveOrdersController::class, 'index'])->name('user.activeOrders.index');
    Route::get('user/aktyvūs-gauti-užsakymai', [App\Http\Controllers\ActiveOrdersController::class, 'orders'])->name('user.activeOrders.orders');
    Route::put('user/aktyvūs-užsakymai/{order}/vykdyti', [App\Http\Controllers\ActiveOrdersController::class, 'start'])->name('user.activeOrders.start');
    Route::put('user/aktyvūs-užsakymai/{order}/siųsti', [App\Http\Controllers\ActiveOrdersController::class, 'sent'])->name('user.activeOrders.sent');
    Route::put('user/aktyvūs-užsakymai/{order}/užbaigti', [App\Http\Controllers\ActiveOrdersController::class, 'complete'])->name('user.activeOrders.complete'); 
    Route::put('user/aktyvūs-užsakymai/{order}/atmesti', [App\Http\Controllers\ActiveOrdersController::class, 'cancel'])->name('user.activeOrders.cancel');
    Route::put('user/aktyvūs-užsakymai/{order}/pašalinti', [App\Http\Controllers\ActiveOrdersController::class, 'delete'])->name('user.activeOrders.delete');

    Route::put('user/aktyvūs-gauti-užsakymai/{order}/patvirtinti', [App\Http\Controllers\ActiveOrdersController::class, 'confirm'])->name('user.activeOrders.confirm');

    Route::get('user/aktyvūs-užsakymai/paslaugos-užsakymas/{order}', [App\Http\Controllers\TransportCheckoutController::class, 'index'])->name('user.activeOrders.transportationOrder.index');
    Route::post('user/aktyvūs-užsakymai/paslaugos-užsakymas/apmokėti', [App\Http\Controllers\TransportCheckoutController::class, 'payment'])->name('user.activeOrders.transportationOrder.payment');
    Route::post('user/aktyvūs-užsakymai/paslauga/apmokėta', [App\Http\Controllers\TransportCheckoutController::class, 'afterPayment'])->name('user.activeOrders.transportationOrder.afterpayment');

    Route::get('user/aktyvūs-mainai/paslaugos-užsakymas/{trade}', [App\Http\Controllers\TransportTradeCheckoutController::class, 'index'])->name('user.activeTrade.transportationOrder.index');
    Route::post('user/aktyvūs-mainai/paslaugos-užsakymas/apmokėti', [App\Http\Controllers\TransportTradeCheckoutController::class, 'payment'])->name('user.activeTrade.transportationOrder.payment');
    Route::post('user/aktyvūs-mainai/paslauga/apmokėta', [App\Http\Controllers\TransportTradeCheckoutController::class, 'afterPayment'])->name('user.activeTrade.transportationOrder.afterpayment');

    Route::get('/profilis/{user}', [App\Http\Controllers\ProfilesController::class, 'index'])->name('user.profile.index');
    Route::get('/profilis/{user}/redaguoti', [App\Http\Controllers\ProfilesController::class, 'edit'])->name('user.profile.edit');
    Route::patch('/profilis/{user}', [App\Http\Controllers\ProfilesController::class, 'update'])->name('user.profile.update');

    Route::get('/archyvai', [App\Http\Controllers\ArchivesController::class, 'index'])->name('user.archives.index');
    Route::get('/archyvai/sandoriai', [App\Http\Controllers\ArchivesController::class, 'indexOrders'])->name('user.archives.orders');
    Route::delete('/archyvai/{archive}', [App\Http\Controllers\ArchivesController::class, 'delete'])->name('user.listings.destroy');
   // Route::get('/profilis/{user}/detaliau', [App\Http\Controllers\ProfilesController::class, 'details'])->name('user.profile.details');
});


Route::group(['middleware' => ['role:administrator', 'auth', 'active_user']], function() {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/skelbimai', [App\Http\Controllers\AdminController::class, 'listings'])->name('admin.listings');
    Route::patch('/admin/pašalinti/{listing}', [App\Http\Controllers\AdminController::class, 'listingDestroy'])->name('admin.listingDestroy');

    Route::get('/medžiagos_kategorija', [App\Http\Controllers\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/medžiagos_kategorija', [App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::delete('/medžiagos_kategorija/istrinti/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/medžiagos_kategorija/{category}/redaguoti', [App\Http\Controllers\CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::patch('/medžiagos_kategorija/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('admin.categories.update');
  
    Route::get('/paskyrų-valdymas', [App\Http\Controllers\AdminUserController::class, 'index'])->name('admin.accountControl.index');
    Route::get('/paskyrų-valdymas/{user}/redaguoti', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('admin.accountControl.edit');
    Route::patch('/paskyrų-valdymas/{user}', [App\Http\Controllers\AdminUserController::class, 'update'])->name('admin.accountControl.update');
    Route::put('/paskyrų-valdymas/blokuoti/{user}', [App\Http\Controllers\AdminUserController::class, 'block'])->name('admin.accountControl.block');
    Route::put('/paskyrų-valdymas/atblokuoti/{user}', [App\Http\Controllers\AdminUserController::class, 'unblock'])->name('admin.accountControl.unblock');

    Route::get('/admin/skelbimų-patvirtinimas', [App\Http\Controllers\AdminListingController::class, 'index'])->name('admin.temporaryListings.index');
    Route::patch('/admin/skelbimų-patvirtinimas/{listing}', [App\Http\Controllers\AdminListingController::class, 'update'])->name('admin.temporaryListings.update');

    Route::get('/admin/skelbimas/{listing}', [App\Http\Controllers\ListingController::class, 'index'])->name('admin.specificListing.index');
    Route::post('/admin/skelbimas/{listing}/komentuoti', [App\Http\Controllers\ListingController::class, 'comment'])->name('listings.specificListing.comment');
    Route::get('/admin/skelbimas/{listing}/komentaras/{comment}', [App\Http\Controllers\ListingController::class, 'destroyComment'])->name('user.listings.specificListing.deleteComment');

    Route::get('/admin/profilis/{user}', [App\Http\Controllers\ProfilesController::class, 'index'])->name('admin.profile.index');
    Route::get('/admin/profilis/{user}/redaguoti', [App\Http\Controllers\ProfilesController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profilis/{user}', [App\Http\Controllers\ProfilesController::class, 'update'])->name('admin.profile.update');
   // Route::get('/admin/profilis/{user}/detaliau', [App\Http\Controllers\ProfilesController::class, 'details'])->name('user.profile.details');

   Route::get('/admin/filtras/pirkti', [App\Http\Controllers\AdminController::class, 'buyFilter'])->name('admin.filter.buy');
   Route::get('/admin/filtras/mainyti', [App\Http\Controllers\AdminController::class, 'tradeFilter'])->name('admin.filter.trade');
   Route::get('/admin/filtras/pirkti-arba-mainyti', [App\Http\Controllers\AdminController::class, 'buyOrTradeFilter'])->name('admin.filter.buyOrTrade');
   Route::get('/admin/kategorija/{category}', [App\Http\Controllers\SearchController::class, 'searchCategoryAdmin'])->name('admin.category.index');

   Route::get('/admin/archyvai', [App\Http\Controllers\ArchivesAdminController::class, 'index'])->name('admin.archives.index');
   Route::get('/admin/archyvai/sandoriai', [App\Http\Controllers\ArchivesAdminController::class, 'indexOrders'])->name('admin.archives.orders');
   Route::delete('/admin/archyvai/{archive}', [App\Http\Controllers\ArchivesAdminController::class, 'delete'])->name('admin.listings.destroy');
});

Route::group(['middleware' => ['role:administrator|user', 'auth', 'active_user']], function() {
    Route::get('/profilis/{user}/detaliau', [App\Http\Controllers\ProfilesController::class, 'details'])->name('user.profile.details');
});


Route::group(['middleware' => ['role:transportation_company', 'auth', 'active_user']], function () {
    Route::get('/pervežimo-įmonė', [App\Http\Controllers\TransportationCompanyController::class, 'index'])->name('user.transportationCompany.index');
    Route::get('/pervežimo-įmonė/aktyvūs-užsakymai', [App\Http\Controllers\TransportationCompanyController::class, 'active'])->name('user.transportationCompany.active');
    Route::put('/pervežimo-įmonė/aktyvūs-užsakymai/{order}/patvirtinti', [App\Http\Controllers\TransportationCompanyController::class, 'confirm'])->name('user.transportationCompany.confirm');
    Route::put('/pervežimo-įmonė/aktyvūs-užsakymai/{order}/atmesti', [App\Http\Controllers\TransportationCompanyController::class, 'cancel'])->name('user.transportationCompany.cancel');
    Route::put('/pervežimo-įmonė/aktyvūs-užsakymai/{order}/užbaigti', [App\Http\Controllers\TransportationCompanyController::class, 'complete'])->name('user.transportationCompany.complete');
    Route::put('/pervežimo-įmonė/aktyvūs-užsakymai/{order}/išrašas', [App\Http\Controllers\TransportationCompanyController::class, 'invoice'])->name('user.transportationCompany.invoice');

    Route::get('/pervežimo-įmonė/aktyvūs-mainų-užsakymai', [App\Http\Controllers\TransportationCompanyTradeController::class, 'index'])->name('user.transportationCompany.activeTradeOrders');
    Route::put('/pervežimo-įmonė/aktyvūs-mainų-užsakymai/{trade}/patvirtinti', [App\Http\Controllers\TransportationCompanyTradeController::class, 'confirm']);
    Route::put('/pervežimo-įmonė/aktyvūs-mainų-užsakymai/{trade}/atmesti', [App\Http\Controllers\TransportationCompanyTradeController::class, 'cancel']);
    Route::put('/pervežimo-įmonė/aktyvūs-mainų-užsakymai/{trade}/užbaigti', [App\Http\Controllers\TransportationCompanyTradeController::class, 'complete']);
    Route::put('/pervežimo-įmonė/aktyvūs-mainų-užsakymai/{trade}/išrašas', [App\Http\Controllers\TransportationCompanyTradeController::class, 'invoice']);
});


Route::stripeWebhooks('webhook');


Auth::routes();
