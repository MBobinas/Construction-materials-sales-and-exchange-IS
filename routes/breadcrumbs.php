<?php
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
Use App\Models\Listing;
use App\Models\MaterialCategory;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Pradžia', route('user.index'));
});

Breadcrumbs::for('admin.index', function (BreadcrumbTrail $trail): void {
    $trail->push('Pradžia', route('admin.index'));
});

Breadcrumbs::for('user.profile.index', function (BreadcrumbTrail $trail,User $user): void {
    $trail->parent('user.index');
    $trail->push($user->name, route('user.profile.index', $user));
});

Breadcrumbs::for('user.profile.edit', function (BreadcrumbTrail $trail,User $user): void {
    $trail->parent('user.profile.index', $user);
    $trail->push('Redagavimas', route('user.profile.edit', $user));
});

Breadcrumbs::for('admin.profile.index', function (BreadcrumbTrail $trail,User $user): void {
    $trail->parent('admin.index');
    $trail->push($user->name, route('admin.profile.index', $user));
});

Breadcrumbs::for('admin.profile.edit', function (BreadcrumbTrail $trail,User $user): void {
    $trail->parent('admin.profile.index', $user);
    $trail->push('Redagavimas', route('admin.profile.edit', $user));
});

Breadcrumbs::for('user.profile.details', function (BreadcrumbTrail $trail,User $user): void {
    $trail->parent('user.index');
    $trail->push($user->name, route('user.profile.details', $user));
});

Breadcrumbs::for('user.userlistings.show', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Skelbimų valdymas', route('user.userlistings.show'));
});

Breadcrumbs::for('user.userlistings.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.userlistings.show');
    $trail->push('Naujas skelbimas', route('user.userlistings.create'));
});

Breadcrumbs::for('user.userlistings.edit', function (BreadcrumbTrail $trail, Listing $listing): void {
    $trail->parent('user.userlistings.show');
    $trail->push($listing->title, route('user.userlistings.edit', $listing));
});

Breadcrumbs::for('user.listings.show', function (BreadcrumbTrail $trail, Listing $listing): void {
    $trail->parent('user.index');
    $trail->push($listing->title, route('user.listings.show', $listing));
});

Breadcrumbs::for('admin.categories.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Kategorijos', route('admin.categories.index'));
});

Breadcrumbs::for('user.checkout.credit-card', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Mokėjimas', route('user.checkout.credit-card'));
});

Breadcrumbs::for('user.trade.index', function (BreadcrumbTrail $trail, Listing $listing): void {
    $trail->parent('user.listings.show', $listing);
    $trail->push('Mainyti', route('user.trade.index', $listing));
});

Breadcrumbs::for('user.activeTrade.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Aktyvūs mainų pasiūlymai', route('user.activeTrade.index'));
});

Breadcrumbs::for('user.activeTrade.sentTrades', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.activeTrade.index');
    $trail->push('Išsiūsti mainai', route('user.activeTrade.sentTrades'));
});

Breadcrumbs::for('admin.accountControl.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Paskyrų valdymas', route('admin.accountControl.index'));
});

Breadcrumbs::for('user.search', function ($trail) {
    $trail->parent('user.index');
    $trail->push('Paieškos rezultatai', route('user.search'));
});

Breadcrumbs::for('user.comments.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Atsiliepimai', route('user.comments.index'));
});

Breadcrumbs::for('user.comments.sentComments', function (BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Išsiųsti atsiliepimai', route('user.comments.sentComments'));
});

Breadcrumbs::for('admin.listings', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Skelbimai', route('admin.listings'));
});

Breadcrumbs::for('admin.temporaryListings.index', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Skelbimų patvirtinimas', route('admin.temporaryListings.index'));
});

Breadcrumbs::for('home', function(BreadcrumbTrail $trail): void {
    $trail->push('Pradžia', route('home'));
});

Breadcrumbs::for('main.guest.listings', function(BreadcrumbTrail $trail): void {
    $trail->push('Visi Skelbimai', route('main.guest.listings'));
});

Breadcrumbs::for('main.guest.listing', function(BreadcrumbTrail $trail, Listing $listing): void {
    $trail->parent('main.guest.listings', $listing);
    $trail->push('Skelbimai', route('main.guest.listing', $listing));
});

Breadcrumbs::for('main.guest.search', function(BreadcrumbTrail $trail): void {
    $trail->parent('main.guest.listings');
    $trail->push('Paieškos rezultatai', route('main.guest.search'));
});

Breadcrumbs::for('user.activeOrders.index', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Aktyvūs užsakymai', route('user.activeOrders.index'));
});

Breadcrumbs::for('user.activeOrders.orders', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.activeOrders.index');
    $trail->push('Užsakymai jums', route('user.activeOrders.orders'));
});

Breadcrumbs::for('user.filter.buy', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Medžiagų skelbimai pardavimui', route('user.filter.buy'));
});

Breadcrumbs::for('user.filter.trade', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Medžiagų skelbimai mainyti', route('user.filter.trade'));
});

Breadcrumbs::for('user.filter.buyOrTrade', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Medžiagų skelbimai pirkti arba mainyti', route('user.filter.buyOrTrade'));
});

Breadcrumbs::for('user.transportationCompany.index', function(BreadcrumbTrail $trail): void {
    $trail->push('Pradžia', route('user.transportationCompany.index'));
});

Breadcrumbs::for('user.transportationCompany.active', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.transportationCompany.index');
    $trail->push('Aktyvūs užsakymai', route('user.transportationCompany.active'));
});

Breadcrumbs::for('admin.filter.buy', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.listings');
    $trail->push('Medžiagų skelbimai pardavimui', route('admin.filter.buy'));
});

Breadcrumbs::for('admin.filter.trade', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.listings');
    $trail->push('Medžiagų skelbimai mainyti', route('admin.filter.trade'));
});

Breadcrumbs::for('admin.filter.buyOrTrade', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.listings');
    $trail->push('Medžiagų skelbimai pirkti arba mainyti', route('admin.filter.buyOrTrade'));
});

Breadcrumbs::for('user.archives.index', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.index');
    $trail->push('Archyvas', route('user.archives.index'));
});

Breadcrumbs::for('user.archives.orders', function(BreadcrumbTrail $trail): void {
    $trail->parent('user.archives.index');
    $trail->push('Sandorių archyvas', route('user.archives.orders'));
});

Breadcrumbs::for('admin.archives.index', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.index');
    $trail->push('Archyvas', route('admin.archives.index'));
});

Breadcrumbs::for('admin.archives.orders', function(BreadcrumbTrail $trail): void {
    $trail->parent('admin.archives.index');
    $trail->push('Sandorių archyvas', route('admin.archives.orders'));
});

Breadcrumbs::for('main.guest.filter.buy', function(BreadcrumbTrail $trail): void {
    $trail->parent('main.guest.listings');
    $trail->push('Pirkimo skelbimai', route('main.guest.filter.buy'));
});

Breadcrumbs::for('main.guest.filter.trade', function(BreadcrumbTrail $trail): void {
    $trail->parent('main.guest.listings');
    $trail->push('Mainymo skelbimai', route('main.guest.filter.trade'));
});

Breadcrumbs::for('main.guest.filter.buyOrTrade', function(BreadcrumbTrail $trail): void {
    $trail->parent('main.guest.listings');
    $trail->push('Mainymo arba pirkimo skelbimai', route('main.guest.filter.buyOrTrade'));
});

Breadcrumbs::for('admin.specificListing.index', function (BreadcrumbTrail $trail, Listing $listing): void {
    $trail->parent('admin.index');
    $trail->push($listing->title, route('admin.specificListing.index', $listing));
});

