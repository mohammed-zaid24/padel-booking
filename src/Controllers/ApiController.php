<?php

namespace App\Controllers;

use App\Services\TimeslotService;
use App\Services\BookingService;

class ApiController
{
    private TimeslotService $timeslotService;
    private BookingService $bookingService;

    public function __construct()
    {
        $this->timeslotService = new TimeslotService();
        $this->bookingService = new BookingService();
    }

    public function availability()
    {
        // 1) Read query params from URL: ?court_id=1&date=2026-02-25
        $courtId = (int)($_GET['court_id'] ?? 0);
        $date = $_GET['date'] ?? '';

        // 2) Basic validation
        if ($courtId <= 0 || $date === '') {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Missing court_id or date']);
            return;
        }

        // 3) Load timeslots that exist for this court and selected date
        $timeslots = $this->timeslotService->getByCourtIdAndDate($courtId, $date);

        // 4) Load booked timeslot ids for this court+date
        $bookedIds = $this->bookingService->getBookedTimeslotIds($courtId, $date);

        // 5) Build JSON response
        $result = [];
        foreach ($timeslots as $t) {
            $result[] = [
                'id' => $t->id,
                'start_time' => $t->startTime,
                'end_time' => $t->endTime,
                'is_booked' => in_array($t->id, $bookedIds, true),
            ];
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
}