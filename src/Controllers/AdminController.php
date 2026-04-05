<?php

namespace App\Controllers;

use App\Services\CourtService;
use App\Services\TimeslotService;
use App\Services\BookingService;

class AdminController
{
    private function requireAdmin(): void
    
    {
        // must be logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // must have admin role
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo "403 - Forbidden (Admins only)";
            exit;
        }
    }

    private CourtService $courtService;
    private TimeslotService $timeslotService;
    private BookingService $bookingService;

    public function __construct()
    {
        $this->courtService = new CourtService();
        $this->timeslotService = new TimeslotService();
        $this->bookingService = new BookingService();
    }

    public function index()
    {
        $this->requireAdmin();

        try {
            require __DIR__ . '/../Views/admin/index.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading the admin page.';
            header('Location: /');
            exit;
        }
    }

    public function courts()
    {
        $this->requireAdmin();

        try {
            $courts = $this->courtService->getAll();
            require __DIR__ . '/../Views/admin/courts.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading courts: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function createCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/courts');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $location = $_POST['location'] ?? '';

        if (trim($name) === '' || trim($location) === '') {
            $_SESSION['error_message'] = 'Please fill in both name and location.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $newCourtId = $this->courtService->create($name, $location);
            $_SESSION['flash_success'] = 'Court added.';
            $_SESSION['show_court_added_success'] = true;
            $_SESSION['new_court_id'] = $newCourtId;
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to add court: ' . $e->getMessage();
        }

        header('Location: /admin/courts');
        exit;
    }

    public function editCourt()
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error_message'] = 'Invalid court.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $court = $this->courtService->getById($id);
            if ($court === null) {
                $_SESSION['error_message'] = 'Court not found.';
                header('Location: /admin/courts');
                exit;
            }
            require __DIR__ . '/../Views/admin/courts_edit.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /admin/courts');
            exit;
        }
    }

    public function updateCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/courts');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $location = trim($_POST['location'] ?? '');

        if ($id <= 0 || $name === '' || $location === '') {
            $_SESSION['error_message'] = 'Invalid data.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $court = $this->courtService->getById($id);
            if ($court === null) {
                $_SESSION['error_message'] = 'Court not found.';
                header('Location: /admin/courts');
                exit;
            }

            $this->courtService->update($id, $name, $location);
            $_SESSION['flash_success'] = 'Court updated.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to update court: ' . $e->getMessage();
        }

        header('Location: /admin/courts');
        exit;
    }

    public function deleteCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/courts');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        try {
            if ($id > 0) {
                $this->courtService->delete($id);
            }
            $_SESSION['flash_success'] = 'Court deleted.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to delete court: ' . $e->getMessage();
        }

        header('Location: /admin/courts');
        exit;
    }

    public function timeslots()
    {
        $this->requireAdmin();

        try {
            $courts = $this->courtService->getAll();

            // Admin must explicitly choose a court.
            $selectedCourtId = (int)($_GET['court_id'] ?? 0);

            $selectedDate = trim($_GET['date'] ?? '');

            $timeslots = [];
            if ($selectedCourtId > 0 && $selectedDate !== '') {
                $timeslots = $this->timeslotService->getByCourtIdAndDate($selectedCourtId, $selectedDate);
            }

            $allTimeslots = [];
            if ($selectedCourtId > 0) {
                $allTimeslots = $this->timeslotService->getByCourtId($selectedCourtId);
            }

            require __DIR__ . '/../Views/admin/timeslots.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading timeslots: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function createTimeslot()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/timeslots');
            exit;
        }

        $courtId = (int)($_POST['court_id'] ?? 0);
        $slotDate = trim($_POST['slot_date'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');

        if ($courtId <= 0 || $slotDate === '' || $startTime === '' || $endTime === '') {
            $_SESSION['error_message'] = 'Please choose a court, date, and start/end time.';
            header('Location: /admin/timeslots');
            exit;
        }

        if (strtotime($endTime) <= strtotime($startTime)) {
            $_SESSION['error_message'] = 'End time must be later than start time.';
            header('Location: /admin/timeslots?court_id=' . $courtId);
            exit;
        }

        try {
            $this->timeslotService->create($courtId, $slotDate, $startTime, $endTime);
            $_SESSION['flash_success'] = 'Timeslot added.';
            $_SESSION['show_timeslot_added_success'] = true;
            $_SESSION['last_court_id'] = $courtId;
            $_SESSION['last_slot_date'] = $slotDate;
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to add timeslot: ' . $e->getMessage();
        }

        header('Location: /admin/timeslots?court_id=' . $courtId . '&date=' . urlencode($slotDate));
        exit;
    }

    public function editTimeslot()
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['error_message'] = 'Invalid timeslot.';
            header('Location: /admin/timeslots');
            exit;
        }

        try {
            $timeslot = $this->timeslotService->getById($id);
            if ($timeslot === null) {
                $_SESSION['error_message'] = 'Timeslot not found.';
                header('Location: /admin/timeslots');
                exit;
            }

            require __DIR__ . '/../Views/admin/timeslots_edit.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to load timeslot: ' . $e->getMessage();
            header('Location: /admin/timeslots');
            exit;
        }
    }

    public function updateTimeslot()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/timeslots');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $courtId = (int)($_POST['court_id'] ?? 0);
        $slotDate = trim($_POST['slot_date'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');

        if ($id <= 0 || $courtId <= 0 || $slotDate === '' || $startTime === '' || $endTime === '') {
            $_SESSION['error_message'] = 'Invalid timeslot data.';
            header('Location: /admin/timeslots?court_id=' . $courtId . '&date=' . urlencode($slotDate));
            exit;
        }

        if (strtotime($endTime) <= strtotime($startTime)) {
            $_SESSION['error_message'] = 'End time must be later than start time.';
            header('Location: /admin/timeslots/edit?id=' . $id);
            exit;
        }

        try {
            $this->timeslotService->update($id, $slotDate, $startTime, $endTime);
            $_SESSION['flash_success'] = 'Timeslot updated.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to update timeslot: ' . $e->getMessage();
        }

        header('Location: /admin/timeslots?court_id=' . $courtId . '&date=' . urlencode($slotDate));
        exit;
    }

    public function deleteTimeslot()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/timeslots');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $courtId = (int)($_POST['court_id'] ?? 0);
        $selectedDate = trim($_POST['date'] ?? '');

        try {
            if ($id > 0) {
                $this->timeslotService->delete($id);
            }
            $_SESSION['flash_success'] = 'Timeslot deleted.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to delete timeslot: ' . $e->getMessage();
        }

        $redirect = '/admin/timeslots?court_id=' . $courtId;
        if ($selectedDate !== '') {
            $redirect .= '&date=' . urlencode($selectedDate);
        }

        header('Location: ' . $redirect);
        exit;
    }

    public function bookings()
    {
        $this->requireAdmin();

        try {
            $bookings = $this->bookingService->getAll();
            require __DIR__ . '/../Views/admin/bookings.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading bookings: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function editBooking()
    {
        $this->requireAdmin();

        $bookingId = (int)($_GET['id'] ?? 0);
        if ($bookingId <= 0) {
            $_SESSION['error_message'] = 'Invalid booking.';
            header('Location: /admin/bookings');
            exit;
        }

        try {
            $booking = $this->bookingService->getBookingByIdForAdmin($bookingId);
            if ($booking === null) {
                $_SESSION['error_message'] = 'Booking not found.';
                header('Location: /admin/bookings');
                exit;
            }

            $timeslots = $this->timeslotService->getByCourtIdAndDate((int)$booking['court_id'], $booking['date']);
            require __DIR__ . '/../Views/admin/bookings_edit.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading the booking: ' . $e->getMessage();
            header('Location: /admin/bookings');
            exit;
        }
    }

    public function updateBooking()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/bookings');
            exit;
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $date = trim($_POST['date'] ?? '');
        $timeslotId = (int)($_POST['timeslot_id'] ?? 0);

        if ($bookingId <= 0 || $date === '' || $timeslotId <= 0) {
            $_SESSION['error_message'] = 'Invalid data.';
            header('Location: /admin/bookings');
            exit;
        }

        try {
            $updated = $this->bookingService->updateBookingForAdmin($bookingId, $date, $timeslotId);

            if ($updated) {
                $_SESSION['flash_success'] = 'Booking updated.';
            } else {
                $booking = $this->bookingService->getBookingByIdForAdmin($bookingId);
                if ($booking === null) {
                    $_SESSION['error_message'] = 'Booking not found.';
                } else {
                    $_SESSION['error_message'] = 'Could not update: that date and time are already booked.';
                }
            }
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to update booking: ' . $e->getMessage();
        }

        header('Location: /admin/bookings');
        exit;
    }

    public function deleteBooking()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['error_message'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/bookings');
            exit;
        }

        $bookingId = (int)($_POST['booking_id'] ?? 0);

        try {
            if ($bookingId > 0) {
                $this->bookingService->deleteById($bookingId);
            }
            $_SESSION['flash_success'] = 'Booking deleted.';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'Failed to delete booking: ' . $e->getMessage();
        }

        header('Location: /admin/bookings');
        exit;
    }
}