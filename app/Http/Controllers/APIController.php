<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{

    public function getTasks()
    {
        $query = Task::select('id', 'description');
        return datatables($query)->make(true);
    }

}