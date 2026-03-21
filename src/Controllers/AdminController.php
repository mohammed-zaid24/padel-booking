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
            $_SESSION['flash_error'] = 'An error occurred loading the admin page.';
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
            $_SESSION['flash_error'] = 'An error occurred loading courts: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function createCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/courts');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $location = $_POST['location'] ?? '';

        if (trim($name) === '' || trim($location) === '') {
            $_SESSION['flash_error'] = 'Please fill in both name and location.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $this->courtService->create($name, $location);
            $_SESSION['flash_success'] = 'Court added.';
            $_SESSION['show_court_added_success'] = true;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to add court: ' . $e->getMessage();
        }

        header('Location: /admin/courts');
        exit;
    }

    public function editCourt()
    {
        $this->requireAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'Invalid court.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $court = $this->courtService->getById($id);
            if ($court === null) {
                $_SESSION['flash_error'] = 'Court not found.';
                header('Location: /admin/courts');
                exit;
            }
            require __DIR__ . '/../Views/admin/courts_edit.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /admin/courts');
            exit;
        }
    }

    public function updateCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/courts');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $location = trim($_POST['location'] ?? '');

        if ($id <= 0 || $name === '' || $location === '') {
            $_SESSION['flash_error'] = 'Invalid data.';
            header('Location: /admin/courts');
            exit;
        }

        try {
            $court = $this->courtService->getById($id);
            if ($court === null) {
                $_SESSION['flash_error'] = 'Court not found.';
                header('Location: /admin/courts');
                exit;
            }

            $this->courtService->update($id, $name, $location);
            $_SESSION['flash_success'] = 'Court updated.';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to update court: ' . $e->getMessage();
        }

        header('Location: /admin/courts');
        exit;
    }

    public function deleteCourt()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
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
            $_SESSION['flash_error'] = 'Failed to delete court: ' . $e->getMessage();
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

            $timeslots = [];
            if ($selectedCourtId > 0) {
                $timeslots = $this->timeslotService->getByCourtId($selectedCourtId);
            }

            require __DIR__ . '/../Views/admin/timeslots.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading timeslots: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function createTimeslot()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/timeslots');
            exit;
        }

        $courtId = (int)($_POST['court_id'] ?? 0);
        $startTime = trim($_POST['start_time'] ?? '');
        $endTime = trim($_POST['end_time'] ?? '');

        if ($courtId <= 0 || $startTime === '' || $endTime === '') {
            $_SESSION['flash_error'] = 'Please choose a court and start/end time.';
            header('Location: /admin/timeslots');
            exit;
        }

        if (strtotime($endTime) <= strtotime($startTime)) {
            $_SESSION['flash_error'] = 'End time must be later than start time.';
            header('Location: /admin/timeslots?court_id=' . $courtId);
            exit;
        }

        try {
            $this->timeslotService->create($courtId, $startTime, $endTime);
            $_SESSION['flash_success'] = 'Timeslot added.';
            $_SESSION['show_timeslot_added_success'] = true;
            $_SESSION['last_court_id'] = $courtId;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to add timeslot: ' . $e->getMessage();
        }

        header('Location: /admin/timeslots?court_id=' . $courtId);
        exit;
    }

    public function deleteTimeslot()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
            header('Location: /admin/timeslots');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $courtId = (int)($_POST['court_id'] ?? 0);

        try {
            if ($id > 0) {
                $this->timeslotService->delete($id);
            }
            $_SESSION['flash_success'] = 'Timeslot deleted.';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to delete timeslot: ' . $e->getMessage();
        }

        header('Location: /admin/timeslots?court_id=' . $courtId);
        exit;
    }

    public function bookings()
    {
        $this->requireAdmin();

        try {
            $bookings = $this->bookingService->getAll();
            require __DIR__ . '/../Views/admin/bookings.php';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'An error occurred loading bookings: ' . $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function deleteBooking()
    {
        $this->requireAdmin();

        if (!\App\Framework\Csrf::validate($_POST['_csrf'] ?? null)) {
            $_SESSION['flash_error'] = 'Invalid request (CSRF). Please try again.';
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
            $_SESSION['flash_error'] = 'Failed to delete booking: ' . $e->getMessage();
        }

        header('Location: /admin/bookings');
        exit;
    }
}