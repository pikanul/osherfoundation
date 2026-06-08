<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;


class TraningCertificateController extends Controller
{
    public function index(Request $request){

        if( $request->phone != '' && $request->has('phone')   && $request->has('course_id') && $request->course_id != '' ){
            
            $student_id = DB::table('traning_apply_lists')->where('phone',$request->phone)->where('course_id',$request->course_id)->first('id');

            if(!$student_id){
                return <<<TEXT
                    <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Check your Phone number and Course </div>
                TEXT;
            }
          return $this->show($student_id->id);
        }else{
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Check your Phone number and Course </div>
                
            TEXT;
        }


    }




    public function show($id){

        $application = DB::table('traning_apply_lists')->where('id',$id)->first();
        if(!$application){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> No data found </div>
            TEXT;
        }

        if($application->application_status != 'approved'){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Your application is not approved yet. </div>
            TEXT;
        }



        
        $course = DB::table('courses')->where('id',$application->course_id)->first();
        if(!$course){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> No Course found </div>
            TEXT;
        }

       if(Carbon::now()->between(($course->start_enroll), ($course->end_enroll)) ){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Certificate is not available during enrollment period. </div>
            TEXT;
        }elseif(Carbon::now()->between(($course->course_start), ($course->course_end)) ){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Certificate is not available during course period. </div>
            TEXT;

        }elseif($course->certificate_publish >= Carbon::now()){
            return <<<TEXT
                <div class="text-white bg-danger p-3 margin-auto" style="max-width:400px; margin:0 auto; border-radius:7px; margin-bottom:30px;"> Certificate will be published on {$course->certificate_publish} </div>
            TEXT;
        }




        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );



        $writer = new Writer($renderer);
        $qr =  $writer->writeString(route('certificate-show', $id));
        
        return view('certificate', compact('qr','id', 'application', 'course'));
    }




    public function certificate_filter(Request $request,){
        return view('certificate_filter');

    }
}
