<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ChatAdminController extends BaseController
{
    public function index()
    {
        return view('chat_admin/index');
    }
}