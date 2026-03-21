<?php

namespace App\Controllers;

use App\Services\BookingService;
use App\Services\TimeslotService;

class BookingController
{
    private BookingService $bookingService;
    private TimeslotService $timeslotService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
        $this->timeslotService = new TimeslotService();
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

        try {
            $this->bookingService->createBooking($userId, $courtId, $date, $timeslotId);
            $_SESSION['flash_success'] = 'Booking created successfully.';
            $_SESSION['show_my_bookings_button'] = true;
            header("Location: /courts/$courtId?date=" . urlencode($date));
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create booking: ' . $e->getMessage();
            header("Location: /courts/$courtId?date=" . urlencode($date));
            exit;
        }
    }

    public function myBookings()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user_id'];

        try {
            $bookings = $this->bookingService->getByUserId($userId);
            require __DIR__ . '/../Views/bookings/my.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading your bookings: ' . $e->getMessage();
            header('Location: /');
            exit;
        }
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

        try {
            $this->bookingService->cancelBooking($bookingId, $userId);
            $_SESSION['flash_success'] = 'Booking canceled.';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to cancel booking: ' . $e->getMessage();
        }

        header('Location: /my-bookings');
        exit;
    }

    public function editForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];
        $bookingId = (int) ($_GET['id'] ?? 0);

        if ($bookingId <= 0) {
            $_SESSION['flash_error'] = 'Invalid booking.';
            header('Location: /my-bookings');
            exit;
        }

        try {
            $booking = $this->bookingService->getBookingById($bookingId, $userId);
            if ($booking === null) {
                $_SESSION['flash_error'] = 'Booking not found or you do not have permission to edit it.';
                header('Location: /my-bookings');
                exit;
            }

            $timeslots = $this->timeslotService->getByCourtId((int) $booking['court_id']);
            require __DIR__ . '/../Views/bookings/edit.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading the booking: ' . $e->getMessage();
            header('Location: /my-bookings');
            exit;
        }
    }

    public function update()
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

        $userId = (int) $_SESSION['user_id'];
        $bookingId = (int) ($_POST['booking_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $timeslotId = (int) ($_POST['timeslot_id'] ?? 0);

        if ($bookingId <= 0 || $date === '' || $timeslotId <= 0) {
            $_SESSION['flash_error'] = 'Invalid data.';
            header('Location: /my-bookings');
            exit;
        }

        try {
            $updated = $this->bookingService->updateBooking($bookingId, $userId, $date, $timeslotId);

            if ($updated) {
                $_SESSION['flash_success'] = 'Booking updated successfully.';
            } else {
                $booking = $this->bookingService->getBookingById($bookingId, $userId);
                if ($booking === null) {
                    $_SESSION['flash_error'] = 'Booking not found or you do not have permission to edit it.';
                } else {
                    $_SESSION['flash_error'] = 'Could not update: that date and time are already booked.';
                }
            }
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred updating the booking: ' . $e->getMessage();
        }

        header('Location: /my-bookings');
        exit;
    }
}