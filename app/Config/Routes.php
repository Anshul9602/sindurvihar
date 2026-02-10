<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// New Housing Lottery Portal routes (replacing old site)

// Landing page (equivalent to Next.js src/app/page.tsx)
$routes->get('/', 'Portal::index');

// Authentication routes (equivalent to /auth/login, /auth/register)
$routes->group('auth', static function ($routes) {
    $routes->match(['get', 'post'], 'login', 'AuthPortal::login');
    $routes->match(['get', 'post'], 'register', 'AuthPortal::register');
    $routes->get('logout', 'AuthPortal::logout');
});

// Language switch (for Hindi/English toggle)
$routes->post('lang/switch', 'Lang::switch');

// User portal routes (equivalent to /user/* pages)
$routes->group('user', static function ($routes) {
    $routes->get('dashboard', 'UserPortal::dashboard');
    // Eligibility: allow GET (form) + POST (submit)
    $routes->match(['get', 'post'], 'eligibility', 'UserPortal::eligibility');
    $routes->get('application', 'UserPortal::application');
    $routes->post('application/submit', 'UserPortal::submitApplication');
    $routes->get('application/status', 'UserPortal::applicationStatus');
    $routes->match(['get', 'post'], 'documents', 'UserPortal::documents');
    // Payment: allow GET (summary) + POST (record payment)
    $routes->match(['get', 'post'], 'payment', 'UserPortal::payment');
    $routes->match(['get','post'], 'profile', 'UserPortal::profile');
    $routes->get('lottery-results', 'UserPortal::lotteryResults');
    $routes->get('allotment', 'UserPortal::allotment');
    $routes->get('refund-status', 'UserPortal::refundStatus');
});

// Admin portal routes (equivalent to /admin/* pages)
$routes->group('admin', static function ($routes) {
    $routes->get('login', 'AdminPortal::login');
    $routes->get('dashboard', 'AdminPortal::dashboard');
    $routes->get('applications', 'AdminPortal::applications');
    $routes->get('applications/(:segment)', 'AdminPortal::applicationDetail/$1');
    $routes->post('applications/update-status', 'AdminPortal::updateApplicationStatus');
    $routes->get('verification', 'AdminPortal::verification');
    $routes->get('lottery', 'AdminPortal::lottery');
    $routes->get('allotments', 'AdminPortal::allotments');
    $routes->get('payments', 'AdminPortal::payments');
    $routes->get('schemes', 'AdminPortal::schemes');
    $routes->get('reports', 'AdminPortal::reports');
});
