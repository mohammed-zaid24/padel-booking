<?php

namespace App\Controllers;

use App\Services\CourtService;
use App\Services\TimeslotService;
use App\Services\BookingService;

class CourtController
{
    private CourtService $courtService;
    private TimeslotService $timeslotService;
    private BookingService $bookingService;


    public function __construct()
    {
        $this->courtService = new CourtService();
        $this->bookingService = new BookingService();
        $this->timeslotService = new TimeslotService();

    }

    public function index()
    {
        try {
            $courts = $this->courtService->getAll();
            require __DIR__ . '/../Views/courts/index.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading courts: ' . $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function get(int $id)
    {
        try {
            $court = $this->courtService->getById($id);

            if ($court === null) {
                http_response_code(404);
                echo "Court not found";
                return;
            }

            $date = $_GET['date'] ?? null;
            $timeslots = $this->timeslotService->getByCourtId($id);
            $bookedTimeslotIds = [];

            if ($date) {
                $bookedTimeslotIds = $this->bookingService->getBookedTimeslotIds($id, $date);
            }

            require __DIR__ . '/../Views/courts/get.php';
        } catch (\Exception $e) {
            $_SESSION['error_message'] = 'An error occurred loading the court: ' . $e->getMessage();
            header('Location: /courts');
            exit;
        }
    }
}