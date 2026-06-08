<?php

use App\Models\BlogCategory;
use App\Models\Career;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Content;
use App\Models\CourseOrder;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Blog;
use App\Models\Management;
use App\Models\Course;
use App\Models\NewsCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

function newsCategory(){
    return NewsCategory::get();
}


function getTimeByFormat($time, $format = 'g:i:s A')
{
    if (!$time) {
        return '-';
    }
    return Carbon::parse($time)->format($format);
}

function bladeIcon($type)
{
    switch ($type) {
        case 'barcode':
            echo '<i class="fa fa-barcode" aria-hidden="true"></i>';
            break;
        case 'add':
            echo '<i class="fas fa-plus"></i>';
            break;
        case 'show':
            echo '<i class="fas fa-eye"></i>';
            break;
        case 'edit':
            echo '<i class="fas fa-edit"></i>';
            break;
        case 'delete':
            echo '<i class="fas fa-trash"></i>';
            break;
        case 'active':
            echo '<i class="fas fa-thumbs-up"></i>';
            break;
        case 'inactive':
            echo '<i class="fas fa-thumbs-down"></i>';
            break;
        case 'print':
            echo '<i class="icon fa fa-print"></i>';
            break;
        case 'approve':
        case 'accept':
            echo '<i class="fas fa-check-circle"></i>';
            break;
        case 'deliver':
            echo '<i class="fas fa-check-square"></i>';
            break;
        case 'hold':
            echo '<i class="fas fa-pause"></i>';
            break;
        case 'cancel':
            echo '<i class="far fa-window-close"></i>';
            break;
        case 'picked':
            echo '<i class="fas fa-people-carry"></i>';
            break;
    }
}


//
function showStatus($status)
{
    switch ($status) {
        case 'seen':
            return '<span class="badge badge-success">' . ucfirst($status) . '</span>';
        case 'unseen':
            return '<span class="badge badge-info">' . ucfirst($status) . '</span>';
        case 'picked':
            return '<span class="badge badge-primary">' . ucfirst($status) . '</span>';
        case 'accepted':
        case 'return':
            return '<span class="badge badge-light-danger">' . ucfirst($status) . '</span>';
        case 'inactive':
            return '<span class="badge badge-danger">' . ucfirst($status) . '</span>';
        case 'pending':
            return '<span class="badge badge-warning">Pending</span>';
        case 'hold':
            return '<span class="badge badge-light-warning">Hold</span>';
        case 'enable':
            return '<span class="badge badge-glow badge-success">Enable</span>';
        case 'active':
            return '<span class="badge badge-glow badge-success">Active</span>';
        case 'disable':
            return '<span class="badge badge-glow badge-warning">Disable</span>';

    }
}

//Api Function
function send_error($message, $errors = [], $code = 404)
{
    $response = [
        'status' => false,
        'message' => $message
    ];
    !empty($errors) ? $response['errors'] = $errors : null;
    return response()->json($response, $code);
}

// function send_response($message, $data = [], $code)
// {
//     $response = [
//         'status' => true,
//         'message' => $message,
//         'data' => $data
//     ];
//     return response()->json($response, $code);
// }

function authUserBusiness(bool $get_id = false)
{
    if (!$get_id) {
        return auth()->user()->business;
    }
    return auth()->user()->business_id;
}

function authUser(bool $get_id = false)
{
    if (!$get_id) {
        return auth()->user();
    }
    return auth()->user()->id;
}


function getDatesArrayFromDateRange(string $date_range)
{
    if (str_contains($date_range, ' to ')) {
        return explode(' to ', $date_range);
    }
    return [$date_range, $date_range];
}

function numberFormat($number, $decimals = 0)
{
    if (strpos($number, '.') != null) {
        $decimalNumbers = substr($number, strpos($number, '.'));
        $decimalNumbers = substr($decimalNumbers, 1, $decimals);
    } else {
        $decimalNumbers = 0;
        for ($i = 2; $i <= $decimals; $i++) {
            $decimalNumbers = $decimalNumbers . '0';
        }
    }


    $number = (int)$number;
    $number = strrev($number);  // reverse

    $n = '';
    $stringlength = strlen($number);

    for ($i = 0; $i < $stringlength; $i++) {
        // from digit 3, every 2 digit () add comma
        if ($i == 2 || ($i > 2 && $i % 2 == 0)) $n = $n . $number[$i] . ',';
        else $n = $n . $number[$i];
    }

    $number = $n;
    $number = strrev($number); // reverse

    ($decimals != 0) ? $number = $number . '.' . $decimalNumbers : $number;
    if ($number[0] == ',') $number = substr_replace($number, '', 0, 1);
    if ($number[1] == ',' && $number[0] == '-') $number = substr_replace($number, '', 1, 1);

    return $number;
}

function addImage($data,UploadedFile $image)
{
    \App\Models\Image::create([
        'image' => getFileNameAfterImageUpload($image),
        'model_type' => get_class($data),
        'model_id' => $data->id,
    ]);
}

function getClient(){
    return \App\Models\Client::where('status',1)->get();
}

function getFileNameAfterImageUpload(UploadedFile $image){
    $filename = null;
    $filename = date('Ymdmhs').uniqid() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('/upload'), $filename);
    return $filename;
}

function managing_team()
{
    $messages = Management::pluck('designation','slug')->toArray();
    return $messages;
}



function project($limit = null){
    $project = \App\Models\Project::where('status',1);
    if($limit){
        $project = $project->take($limit);
    }
    return $project->latest()->get();
}

function pages(){
    return \App\Models\Page::where('status',1)->get();
}


function blog($type = null, $limit = 6, $category_id = null)
{
    $blogs =  Blog::query();
    if($type = 'recent'){
        $blogs = $blogs->latest();
    }
    if($category_id){
        $blogs = $blogs->where('category_id', $category_id);
    }
    $blogs = $blogs->take($limit)->get();
    return $blogs;
}

function product($type = null, $limit = 6, $cat_id = null)
{
    $products =  Product::query();
    if($type = 'recent'){
        $products = $products->latest();
    }
    if($cat_id){
        $products = $products->where('category_id', $cat_id);
    }
    $products = $products->take($limit)->get();
    return $products;
}




function encryptEmailForCloudflare($email)
{
    $key = random_int(0, 255); // 1-byte key
    $output = chr($key); // Start output with the key

    for ($i = 0; $i < strlen($email); $i++) {
        $output .= chr(ord($email[$i]) ^ $key);
    }

    // Convert to hexadecimal
    return bin2hex($output);
}






function storeValue($key, $value)
{
    $exists = Setting::where('key', $key)->first();
    if ($exists) {
        $exists->update([
            'value' => $value
        ]);
    } else {
        Setting::create([
            'key' => $key,
            'value' => $value ?? '',
        ]);
    }
}


function duplicateFoundByColumn($model, $column_name, $value)
{
    return $model::where($column_name, $value)->exists();
}
function generateRandomCode($prefix,$length){
    if ($length > 0){
        return $prefix.'-'.Str::random($length);
    }else{
        return $prefix;
    }
}

function generateRandomCodeWithDuplicateCheck($prefix,$length, $model, $column_name) :string
{
    $code = generateRandomCode($prefix,$length);
    if (duplicateFoundByColumn($model, $column_name, $code)){
        generateRandomCodeWithDuplicateCheck($prefix,$length,  $model, $column_name);
    }
    return strtoupper($code);
}




function getBlogCategory(){
    return BlogCategory::where('status',1)->get();
}




function unread_message(){
    return Contact::where('read_status',0)->count();
}
function unread_careear(){
    return Career::where('read_status',0)->count();
}



function ajaxAbort($code, $message = '') {
    return response()->json([
        'type' => false,
        'title' => $message != '' ? $message : 'Permission Denied. Contact Administrator. Error Code: ' . $code,
    ]);
}



function daysToYMD($days)
{
    $years = floor($days / 365);
    $days %= 365;

    $months = floor($days / 30);
    $days %= 30;

    if($years == 0 && $months == 0 && $days == 0){
        return '0 Days';
    }elseif($years == 0 && $months == 0){
        return $days . ' Days';
    }elseif($years == 0){
        return $months . ' Months ' . $days . ' Days';
    }else{
        return $years . ' Years ' . $months . ' Months ' . $days . ' Days';
    }
}
