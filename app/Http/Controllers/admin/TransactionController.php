<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\allDataController;

class TransactionController extends Controller
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
        $title = "Transaksi / Udin Gallery";
        $users = $this->allDataController->userSession(session('userId'));
        $data = [
            'title' => $title,
            'users' => $users
        ];

        return view('admin.pages.transactions.index', $data);
    }
}
