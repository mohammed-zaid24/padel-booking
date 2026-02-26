<?php

namespace App\Controllers;

use App\Services\BookingService;

class BookingController
{
    private BookingService $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    public function create()
    {
        // 1) Validate CSRF
        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /courts');
            exit;
        }

        // 2) Must be logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $courtId = (int)($_POST['court_id'] ?? 0);
        $timeslotId = (int)($_POST['timeslot_id'] ?? 0);
        $date = $_POST['date'] ?? '';

        // basic validation
        if ($courtId <= 0 || $timeslotId <= 0 || $date === '') {
            echo "Invalid booking data";
            return;
        }

        // 2) Insert booking
        $this->bookingService->createBooking($userId, $courtId, $date, $timeslotId);

        // flash success message
        $_SESSION['flash_success'] = 'Booking created successfully.';

        // 3) Redirect back to the court page (same date)
        header("Location: /courts/$courtId?date=" . urlencode($date));
        exit;
    }

    public function myBookings()
  {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $userId = (int)$_SESSION['user_id'];

    $bookings = $this->bookingService->getByUserId($userId);

    require __DIR__ . '/../Views/bookings/my.php';
  }

  public function cancel()
 {
    if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
        $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
        header('Location: /my-bookings');
        exit;
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $userId = (int)$_SESSION['user_id'];
    $bookingId = (int)($_POST['booking_id'] ?? 0);

    if ($bookingId <= 0) {
        echo "Invalid booking";
        return;
    }

    $deleted = $this->bookingService->cancelBooking($bookingId, $userId);

    // flash success
    $_SESSION['flash_success'] = 'Booking canceled.';

    header('Location: /my-bookings');
    exit;
  }


}