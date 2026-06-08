<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Mail\TestMail;
use App\Mail\MailerDynamic;

use Illuminate\Http\Request;
use App\Models\MailSetting;
use App\Http\Controllers\Controller;
use App\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;


class MailSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mail_config = MailSetting::first();
        if(!$mail_config){
            $mail_config = new MailSetting();
            $mail_config->save();
        }
        $mail_template = MailTemplate::get();
        return view('admin.mailconfigration.index', compact('mail_config','mail_template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $mail_setting =  MailSetting::first();
        if(!$mail_setting){
            $mail_setting = new MailSetting();
        }
        $mail_setting->from_address = $request->from_ad;
        $mail_setting->from_name = $request->from_name;
        $mail_setting->smtp_encryption = $request->smtp_encryption;
        $mail_setting->smtp_host = $request->smtp_host;
        $mail_setting->smtp_password = $request->smtp_password;
        $mail_setting->smtp_port = $request->smtp_port;
        $mail_setting->imap_port = $request->imap_port;
        $mail_setting->smtp_username = $request->smtp_username;
        $mail_setting->status = $request->status;
        $mail_setting->from_address = $request->from_address;

        $mail_setting->save();

        return response()->json([
            'title'=>'Successfully  updated',
            'type'=>'success',
            'refresh'=>'true',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(MailSetting $mailSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailSetting $mailSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailSetting $mailSetting)
    {
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailSetting $mailSetting)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function testMail(Request $request)
    {
        setMailConfig();
        $request->validate(['mail' => 'required|email']);

        $mailInfo = [
            'title' => 'Test Mail',
            'subject' => 'Test Subject',
            'body' => 'Test Body',
            'user' => $request->mail,
            'name' => 'Test Name'
        ];


        Mail::mailer('dynamic')->to($mailInfo['user'], $mailInfo['name'])->send(new TestMail($mailInfo));

             return response()->json([
                'title' => 'Successfully sent mail',
                'type' => 'success',
                'refresh' => false,
            ]);



    }



    public function imap_index(){
        return view('admin.mailconfigration.imap');
    }


}
