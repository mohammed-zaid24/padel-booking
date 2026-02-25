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

        require __DIR__ . '/../Views/admin/index.php';
    }

    public function courts()
    {
        $this->requireAdmin();

        $courts = $this->courtService->getAll();

        require __DIR__ . '/../Views/admin/courts.php';
    }

    public function createCourt()
    {
        $this->requireAdmin();

        $name = $_POST['name'] ?? '';
        $location = $_POST['location'] ?? '';

        if (trim($name) === '' || trim($location) === '') {
            header('Location: /admin/courts');
            exit;
        }

        $this->courtService->create($name, $location);

        $_SESSION['flash_success'] = 'Court added.';

        header('Location: /admin/courts');
        exit;
    }

    public function deleteCourt()
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->courtService->delete($id);
        }

        $_SESSION['flash_success'] = 'Court deleted.';
        header('Location: /admin/courts');
        exit;
    }

    public function timeslots()
    {
        $this->requireAdmin();

        $courts = $this->courtService->getAll();

        // For simplicity: show timeslots for selected court (or first court)
        $selectedCourtId = (int)($_GET['court_id'] ?? ($courts[0]->id ?? 0));

        $timeslots = [];
        if ($selectedCourtId > 0) {
            $timeslots = $this->timeslotService->getByCourtId($selectedCourtId);
        }

        require __DIR__ . '/../Views/admin/timeslots.php';
    }

    public function createTimeslot()
    {
        $this->requireAdmin();

        $courtId = (int)($_POST['court_id'] ?? 0);
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? '';

        if ($courtId <= 0 || $startTime === '' || $endTime === '') {
            header('Location: /admin/timeslots');
            exit;
        }

        $this->timeslotService->create($courtId, $startTime, $endTime);

        $_SESSION['flash_success'] = 'Timeslot added.';
        header('Location: /admin/timeslots?court_id=' . $courtId);
        exit;
    }

    public function deleteTimeslot()
    {
        $this->requireAdmin();

        $id = (int)($_POST['id'] ?? 0);
        $courtId = (int)($_POST['court_id'] ?? 0);

        if ($id > 0) {
            $this->timeslotService->delete($id);
        }

        $_SESSION['flash_success'] = 'Timeslot deleted.';
        header('Location: /admin/timeslots?court_id=' . $courtId);
        exit;
    }

    public function bookings()
    {
        $this->requireAdmin();

        $bookings = $this->bookingService->getAll();

        require __DIR__ . '/../Views/admin/bookings.php';
    }

    public function deleteBooking()
    {
        $this->requireAdmin();

        $bookingId = (int)($_POST['booking_id'] ?? 0);

        if ($bookingId > 0) {
            $this->bookingService->deleteById($bookingId);
        }

        $_SESSION['flash_success'] = 'Booking deleted.';
        header('Location: /admin/bookings');
        exit;
    }
}