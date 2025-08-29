<?php

namespace App\Http\Controllers;

use App\Services\MainService;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $mainService;
    public $notificationService;
    public function __construct()
    {
        $this->mainService = new MainService();
        $this->notificationService = new NotificationService();

    }

    protected function responseMessage($status, $message, $data = null, $statusCode = 200, $route = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'route' => $route
        ], $statusCode);
    }
}
