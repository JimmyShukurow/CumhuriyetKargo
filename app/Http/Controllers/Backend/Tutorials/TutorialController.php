<?php

namespace App\Http\Controllers\Backend\Tutorials;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    public function index()
    {
      

        $tutorials = Tutorial::all();

        GeneralLog('Eğitim sayfası görüntülendi');
        return view('backend.tutorials.user_all_tutorials', compact(['tutorials']));
    }
}
