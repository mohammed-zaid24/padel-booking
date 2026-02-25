<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load autoloader
require_once __DIR__ . '/../src/autoload.php';

// 2. Use the controller
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CourtController;


// 1) Get the URL path the user requested (example: "/" or "/login")
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/' || $path === '') {
    $controller = new HomeController();
    $controller->index();
    } elseif ($path === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new AuthController();
    $controller->registerForm();
    } elseif ($path === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $controller->register();

    } elseif ($path === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new AuthController();
    $controller->loginForm();

    } elseif ($path === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $controller->login();
    
   } elseif ($path === '/logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
      $controller = new AuthController();
      $controller->logout();

   } elseif ($path === '/courts' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new CourtController();
    $controller->index();

    } elseif (preg_match('#^/courts/(\d+)$#', $path, $matches)) {
    $id = (int)$matches[1];

    $controller = new CourtController();
    $controller->get($id);

    } elseif ($path === '/book' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\BookingController();
    $controller->create();

    } elseif ($path === '/my-bookings' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\BookingController();
    $controller->myBookings();

    } elseif ($path === '/cancel-booking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\BookingController();
    $controller->cancel();

    } elseif ($path === '/api/availability' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\ApiController();
    $controller->availability();

    } elseif ($path === '/admin' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\AdminController();
    $controller->index();

      } elseif ($path === '/admin/courts' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\AdminController();
    $controller->courts();

    } elseif ($path === '/admin/courts/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\AdminController();
    $controller->createCourt();

    } elseif ($path === '/admin/courts/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\AdminController();
    $controller->deleteCourt();

      } elseif ($path === '/admin/timeslots' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\AdminController();
    $controller->timeslots();

   } elseif ($path === '/admin/timeslots/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\AdminController();
    $controller->createTimeslot();

} elseif ($path === '/admin/timeslots/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\AdminController();
    $controller->deleteTimeslot(); 

      } elseif ($path === '/admin/bookings' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new \App\Controllers\AdminController();
    $controller->bookings();

} elseif ($path === '/admin/bookings/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new \App\Controllers\AdminController();
    $controller->deleteBooking();
    
   } else {
    http_response_code(404);
    echo "404 - Page not found";
 }