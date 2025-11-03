<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\allDataController;

class DashboardController extends Controller
{
    private $allDataController; // Declare AllGetDataController property

    // Constructor to initialize AllGetDataController
    public function __construct(allDataController $allDataController) {
        $this->allDataController = $allDataController;
    }

    function index(){
        if(session('userLogin') != true){
            return redirect('/admin');
        }
        $title = "Dashboard Admin / Udin Gallery";
        $users = $this->allDataController->userSession(session('userId'));

        // Get dashboard stats from API
        $statsResponse = $this->allDataController->dashboardStats();
        $statsData = json_decode($statsResponse->getContent(), true);

        $data = [
            'title' => $title,
            'users' => $users,
            'totalRevenue' => $statsData['data']['totalRevenue'] ?? 0,
            'productsSold' => $statsData['data']['productsSold'] ?? 0,
            'newCustomers' => $statsData['data']['newCustomers'] ?? 0,
            'pendingTransactions' => $statsData['data']['pendingTransactions'] ?? 0,
            'revenueChangePercent' => $statsData['data']['revenueChangePercent'] ?? 0,
            'productsSoldChangePercent' => $statsData['data']['productsSoldChangePercent'] ?? 0,
            'newCustomersChangePercent' => $statsData['data']['newCustomersChangePercent'] ?? 0,
            'pendingTransactionsChange' => $statsData['data']['pendingTransactionsChange'] ?? 0,
            'recentTransactions' => $statsData['data']['recentTransactions'] ?? [],
            'salesData' => $statsData['data']['salesData'] ?? [],
            'salesLabels' => $statsData['data']['salesLabels'] ?? []
        ];

        return view('admin.dashboard', $data);
    }
}
