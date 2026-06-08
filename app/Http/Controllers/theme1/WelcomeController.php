<?php

namespace App\Http\Controllers\theme1;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Process;
use App\Models\Reward;
use App\Models\Service;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\Team;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->theme = config('database.connections.mysql.theme');
    }
    public function index()
    {
      
        return view($this->theme.'.page.home');
    }
    public function detail($slug){
        $services = Service::all();
        $detail = Service::with('images')->where('slug',$slug)->first();
        return view('fontend.serveice-show',compact('detail','services'));
    }
    public function roleRegulation(){
        return view('fontend.roles-regulation');
    }

}
