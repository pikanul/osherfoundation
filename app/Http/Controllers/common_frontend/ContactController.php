<?php

namespace App\Http\Controllers\common_frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Contact;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function __construct(){
        $this->theme = config('database.connections.mysql.theme');
    }
    public function store(Request $request){

        // dd($request->all());
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'subject' => 'nullable',
        'phone' => ['nullable'],
        'message' => 'required|min:10',

    ], [
        'name.required' => 'Name is required',
        'email.required' => 'Email is required',

        'message.required' => 'Message is required',
        'message.min' => 'Your message is too short. Please enter a longer message.',
    ]);

    // 🚫 Bad word / sexual content filter
    $badWords = ['sex', 'porn', 'xxx', 'nude', 'hot girl', 'escort', 'djfun2desi', 'desi.', 'xvideos', 'mailinator'];

    foreach ($badWords as $word) {
        if (
            Str::contains(Str::lower($request->message), $word) ||
            Str::contains(Str::lower($request->subject), $word) ||
            Str::contains(Str::lower($request->name), $word) ||
            Str::contains(Str::lower($request->email), $word)
        ) {
            return response()->json([
                'title' => 'You are trying suspicious or inappropriate content or Dummy Content',
                'type' => 'error',
            ], 422);
        }
    }


        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'description' => $request->message,
        ]);




        // if($request->phone){
        //     $smsService = new SmsService();
        //     // $smsService->sendSMS($request->phone, 'Dear Sir, We received your query about : `'. ($request->subject ?? $request->description).'` As soon as possible we will back. Stay Connected with '. url('/'));

        //     // $smsService->sendSMS(
        //     //     settings('app_tel', 9),
        //     //     "Name: {$request->name}\nPhone: {$request->phone}\nEmail: {$request->email}\nAbout: {$request->subject} {$request->description}"
        //     // );

        // }




        return json_encode([
            'title'=>'Successfully  Received Query',
            'type'=>'success',


        ]);
    }




    public function carrearStore(Request $request){

        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'nullable',
            'phone' => 'nullable',
            'description' => 'required',
            'file_name' => 'required|mimes:pdf',
        ],[
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'subject.required' => 'Subject is required',
            'description.required' => 'Description is required',
            'file_name.required' => 'File is required',
        ]);

        $validated['file_name'] = uploads($request->file('file_name'));

        Career::create($validated);

        return json_encode([
            'title'=>'Successfully  Received CV',
            'type'=>'success',
            'url'=>route('contact.thankyou'),

        ]);
    }



    // , unsubscribe, verifysubscribe received md5 email`
    public function subscribe(Request $request){
        $request->validate([
            'email' => 'required|email',

        ]);

        $email = strtolower(trim($request->email));
        $subscriber = Subscriber::where('email', $email)->first();

        if($subscriber){
            if ((int) $subscriber->status === 1 && !is_null($subscriber->subscribed_at) && is_null($subscriber->unsubscribed_at)) {
                return json_encode([
                    'title' => 'This email is already subscribed and verified.',
                    'type' => 'success',
                ]);
            }

            $subscriber->status = 0;
            $subscriber->subscribed_at = null;
            $subscriber->unsubscribed_at = null;
            $subscriber->save();
        }else{
            Subscriber::create([
                'email' => $email,
                'status' => 0,
                'subscribed_at' => null,
                'unsubscribed_at' => null,
            ]);
        }

        // Send mail
         setMailConfig();
        Mail::mailer('dynamic')->to($email)->send(new \App\Mail\SubscribeMail($email));



        return json_encode([
            'title'=>'Successfully Subscribed . You need to verify your email',
            'type'=>'success',

        ]);
    }

    public function unsubscribe($md5email =null){

        $email = base64_decode($md5email);
        $subscriber = Subscriber::where('email', $email)->first();

        if($subscriber){
            $subscriber->status = 2;
            $subscriber->unsubscribed_at = now();
            $subscriber->save();
            $data =[
                'title'=>'Successfully Unsubscribed',
                'type'=>'success',
            ];
        }else{
            $data =[
                'title'=>'Invalid Link',
                'type'=>'error',
            ];
        }

        return redirect()->route('contact.thankyou')->with($data);
    }


    public function verifysubscribe($md5email =null){
        $email = base64_decode($md5email);
        $subscriber = Subscriber::where('email', $email)->first();
        if($subscriber){
            $subscriber->status = 1;
            $subscriber->subscribed_at = now();
            $subscriber->unsubscribed_at = null;
            $subscriber->save();
            $data = [
                'title' => 'Successfully Subscribed . You are verified now',
                'type' => 'success',
            ];
        }else{
            $data = [
                'title' => 'Invalid Link',
                'type' => 'error',
            ];
        }

        return redirect()->route('contact.thankyou')->with($data);
    }



    public function thankyou(){
        return Inertia::render('thankyou');
    }
}
