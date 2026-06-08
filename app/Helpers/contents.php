<?php

use App\Models\Upload;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\ServiceRequest;



function settings($name = null, $key = null, $default = null)
{
    if ($name == null || $key == null) {
        return '';
    }
    $data = $setting = Setting::firstOrCreate(
        ['name' => "$name", 'key' => "$key"],
        ['creator_id' => 0, 'value' => Str::title(str_replace("_", ' ', $name))],
    );

    if ($key == 159) {
        // dd($data);
    }


    if ($data) {
        $keywords = ['image', 'file', 'logo'];

        foreach ($keywords as $keyword) {
            if (str_contains($name, $keyword)) {
                if ($default) {
                    return [
                        dynamic_asset($data->value),
                        $data->value
                    ];
                }
                return dynamic_asset($data->value);
            }
        }

        return $data->value;
    }

    return '';
}







function uploads($file, $id = null)
{
    $file_extension = $file->getClientOriginalExtension();
    $file_name = (rand(1000, 100000) . time() * 40202) . '.' . $file_extension;

    $file_temp_name = $file->getRealPath();
    $file_size = $file->getSize();

    $file_mime_type = $file->getMimeType();
    $file_mime_type = explode('/', $file_mime_type)[0];


    $user_id = auth()->user()->id ?? 0;
    $destinationPath = public_path() . '/uploads/';
    $file->move($destinationPath, $file_name);
    if ($id != null) {
        $file_find = Upload::find($id);
        if ($file_find) {
            $oldFilePath = $destinationPath . '/' . $file_find->name;

            // Check if the old file exists and delete it
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                unlink($oldFilePath);
            }
            $file_find->name = $file_name;
            $file_find->extension = $file_extension;
            $file_find->size = $file_size;
            $file_find->save();
        } else {

            Upload::create([
                'name' => $file_name,
                'extension' => $file_extension,
                'size' => $file_size,
                'source' => 'Local',
                'user_id' => $user_id,
                // 'extension'=>$external_link,
            ]);
        }
    } else {
        Upload::create([
            'name' => $file_name,
            'extension' => $file_extension,
            'size' => $file_size,
            'source' => 'Local',
            'user_id' => $user_id,
            // 'extension'=>$external_link,
        ]);
    }
    //Move Uploaded File
    return Upload::where('name', $file_name)->get()->first()->id;

}





function dynamic_asset($id, $default = null, $type = 'asset')
{
    $destinationPath = 'uploads/';
    if ($id != null || $id != '') {
        if ($file1 = Upload::find($id)) {
            if (filter_var($file1->name, FILTER_VALIDATE_URL)) {
                return $file1->name;
            }

            $file1 = $destinationPath . $file1->name;
            if (File::exists(public_path($file1)) || is_dir(public_path($file1))) {
                $file1 = ($type == 'public') ? public_path($file1) : asset($file1);
                $file1 = preg_replace('/([^:])\/{2,}/', '$1/', $file1);
                return $file1;
            } else {
                $file = 'preset/' . ($default ?? 'fixing.png');
                $file = ($type == 'public') ? public_path($file) : asset($file);
                $file = preg_replace('/([^:])\/{2,}/', '$1/', $file);
                return $file;

            }


        } else {
            $file = 'preset/' . ($default ?? 'fixing.png');
            $file = ($type == 'public') ? public_path($file) : asset($file);
            $file = preg_replace('/([^:])\/{2,}/', '$1/', $file);
            return $file;
        }
    } else {
        $file = 'preset/' . ($default ?? 'fixing.png');
        $file = ($type == 'public') ? public_path($file) : asset($file);
        $file = preg_replace('/([^:])\/{2,}/', '$1/', $file);
        return $file;
    }
}

function dynamic_assets($ids)
{
    $data = [];
    foreach (explode(',', $ids) as $id) {
        $data[$id] = dynamic_asset($id);
    }
    return $data;
}


function asset_unlink($id)
{

    // $destinationPath = public_path() . '/uploads/';
    $destinationPath = 'uploads/';
    if ($id != null && $id != '' && $id != 0) {
        $find_file = Upload::find($id);
        $file1 = '';
        if ($find_file) {
            $find_file->delete();
            $file1 = $destinationPath . $find_file->name;
        }

        // return static_asset($file1);
        if (File::exists(public_path($file1)) || is_dir(public_path($file1))) {
            if (unlink(public_path($file1))) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    return false;
}




/**
 * Summary of category
 * @return category[]|Illuminate\Database\Eloquent\Collection
 */
function category()
{
    return category::where('status', 1)->orderBy('name', 'asc')->get();
}


/**
 * Summary of numToWordsRec
 * @param mixed $number
 * @return string
 */
function numToWordsRec($number)
{
    $words = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety'
    );




    if ($number < 20) {
        return $words[$number];
    }

    if ($number < 100 && $number % 10 == 0) {
        return $words[10 * floor($number / 10)];
    } elseif ($number < 100) {
        return $words[10 * floor($number / 10)] .
            ' ' . $words[$number % 10];
    }

    if ($number < 1000 && $number % 100 == 0) {
        return $words[floor($number / 100)] . ' hundred ';
    } elseif ($number < 1000) {
        return $words[floor($number / 100)] . ' hundred '
            . numToWordsRec($number % 100);
    }

    if ($number < 1000000 && $number % 1000 == 0) {
        return numToWordsRec(floor($number / 1000)) .
            ' thousand ';
    } elseif ($number < 1000000) {
        return numToWordsRec(floor($number / 1000)) .
            ' thousand ' . numToWordsRec($number % 1000);
    }

    return numToWordsRec(floor($number / 1000000)) .
        ' million ' . numToWordsRec($number % 1000000);
}





/**
 * Update the value of a key in the .env file.
 *
 * @param string $key The key to be updated.
 * @param string $value The new value for the key.
 * @return void
 */
function updateEnvFile($key, $value)
{
    $path = base_path('.env'); // Path to the .env file

    if (file_exists($path)) {
        // Read the .env file into an array
        $envFile = file_get_contents($path);

        // Search for the key and update it
        $envFile = preg_replace("/^{$key}=[^\n]*$/m", "{$key}={$value}", $envFile);

        // If the key wasn't found, add it to the end of the file
        if (strpos($envFile, "{$key}=") === false) {
            $envFile .= "\n{$key}={$value}";
        }

        // Write the changes back to the .env file
        file_put_contents($path, $envFile);
    }
}



function unchecked_order($type = null)
{
    $order = Order::where('status', 0);
    if ($type)
        $order = $order->where('type', $type);
    $order = $order->get();
    return $order->count();
}



function settings_data(array $data)
{
    return setting::where(function ($query) use ($data) {
        foreach ($data as $condition) {
            $query->orWhere(function ($q) use ($condition) {
                $q->where('name', $condition['name'])
                    ->where('key', $condition['key']);
            });
        }
    })->get();
}




function getLocation($ip)
{
    try {
        $response = file_get_contents("http://ip-api.com/json/{$ip}");

        if ($response === false) {
            return (object) [
                "status" => "fail",
                "message" => "Failed to get location"
            ];
        }

        return json_decode($response);
    } catch (\Exception $e) {
        return (object) [
            "status" => "fail",
            "message" => "Invalid IP Address"
        ];
    }

    // dd($data);

}


function getUnreadServiceRequest()
{
    return ServiceRequest::where('status', 0)->count();
}



function isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function offerbanner_type()
{
    return [
        1 => 'Feature Card',
        2 => 'Full Banner',
        3 => 'Countdown',
        4 => 'Frontend Popup',
    ];
}


function get_branch()
{
    // if(auth()->check()){
    //     if (session()->get('branch_id') != null) {
    //        return session()->get('branch_id');
    //     }elseif (auth()->user()->branch_id != null) {
    //         session()->put('branch_id', auth()->user()->branch_id);
    //         return auth()->user()->branch_id;
    //     }
    // }
    return 1;

}







function button_g($urls, $name = '', $ajax = true, $permission = null)
{
    $return = '';
    $pre = '';
    $end = '';
    if (count($urls) > 1) {
        $pre = '<div class="dt-buttons btn-group ">';
        $end = '</div>';
    }

    $return .= $pre;


    foreach ($urls as $key => $url) {
        if ($permission) {
            if (!Auth::hasP($permission . ' ' . $key)) {
                continue;
            }
        }
        if ($ajax) {
            $tag = 'button';
            $content_inner_tag = 'data-href="' . $url . '"  onclick="button_ajax(this)"   data-dialog=" modal-dialog-scrollable modal-lg"';
        } else {
            $tag = 'a';
            $content_inner_tag = 'href="' . $url . '"   ';
        }

        if ($key == 'delete') {
            $csrf_php = csrf_token();
            $return .= <<<HTML
                           <button data-href="$url"    onclick="return confirmAlert(this)"  data-csrf="$csrf_php" class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        HTML;
        } elseif ($key == 'create') {
            $return .= <<<HTML
                           <$tag class="btn btn-sm btn-primary" $content_inner_tag
                                data-title="Add New  $name" data-href="$url"  href="$url" >+ Add New
                                $name</$tag>
                        HTML;

        } elseif ($key == 'edit') {
            $return .= <<<HTML
                           <$tag class="btn btn-sm btn-primary" data-title="Edit  $name" $content_inner_tag >   <i class="fas fa-edit"></i></$tag>
                        HTML;
        }elseif ($key == 'return') {
            $return .= <<<HTML
                           <$tag class="btn btn-sm btn-info" data-title="Return  $name" $content_inner_tag >  <i class="fas fa-undo"></i></$tag>
                        HTML;
        } elseif ($key == 'view') {
            $return .= <<<HTML
                           <$tag class="btn btn-sm btn-primary" $content_inner_tag  data-title="View  $name"  >   <i class="fas fa-eye"></i></$tag>
                        HTML;
        } elseif ($key == 'preview') {
            $return .= <<<HTML
                           <a class="btn btn-sm btn-info" href="$url" target="_blank" title="Preview"><i class="fas fa-eye"></i></a>
                        HTML;
        } elseif ($key == 'permission') {
            $return .= <<<HTML
                           <$tag class="btn btn-sm btn-warning"   data-title="View  $name" $content_inner_tag  >   <i class="fas fa-key"></i> $key </$tag>
                        HTML;

        } elseif ($key == 'created_at') {
            $date = $url->created_at ? $url->created_at->format('d M Y, h:i:s a') : '';
            $return .= <<<HTML
                            <div class="created_at_format">
                                  $date
                            </div>
                        HTML;
        } elseif ($key == 'image') {
            $image = dynamic_asset($url);
            // check image or video

            $image = dynamic_asset($url);
            // check image or video
            if (Str::endsWith($image, ['.mp4', '.webm', '.ogg'])) {
                $return .= <<<HTML
                            <video style="max-width: 150px; max-height: 80px;" controls class="img-fluid" preload="none">
                                <source src="$image" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        HTML;
                continue;
            }
            $return .= <<<HTML
                            <img style="max-width: 150px; max-height: 60px;" src="$image" alt="" class="img-fluid">
                        HTML;
        } elseif ($key == 'manage') {
            $url_data = route('admin.setting.index', ['page' => 'main']) . $url;
            $return .= <<<HTML
                            <a class="btn btn-sm btn-warning" target="_blank"  href="$url_data" >   <i class="fas fa-cogs"></i> Manage </a>
                        HTML;
        } else {

            $return .= <<<HTML
                            <a class="btn btn-sm btn-primary" href="$url" >   $key</a>
                        HTML;
        }
    }

    $return .= $end;

    return $return;
}







use App\Models\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

function setMailConfig($setting_id = null){
      if(Schema::hasTable('mail_settings') ){
            $mail_information = MailSetting::query();
            if($setting_id){
                $mail_information = $mail_information->where('id', $setting_id);
            }

            $mail_information = $mail_information->first();
            if($mail_information){
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $mail_information->smtp_host,
                    'port' => $mail_information->smtp_port,
                    'encryption' => $mail_information->smtp_encryption,
                    'username' => $mail_information->smtp_username,
                    'password' => $mail_information->smtp_password,
                ];
                Config::set('mail.mailers.dynamic',  $smtp);


                $from = [
                    'address' => $mail_information->from_address,
                    'name' => $mail_information->from_name,
                ];
                Config::set('mail.from',  $from);


                $mailtrap = [ // account identifier
                    'protocol' => 'imap',
                    'host' => $mail_information->smtp_host,
                    'port' => $mail_information->imap_port,
                    'encryption' => $mail_information->smtp_encryption,
                    'validate_cert' => true,
                    'username' => $mail_information->smtp_username,
                    'password' => $mail_information->smtp_password,
                    'authentication' => true,
                ];
                Config::set('imap.accounts.default',   $mailtrap);


                // dd(Config::get('mail.mailers.smtp'));

            }
        }
}



function formatMinutes($minutes)
{
    if ($minutes <= 0) {
        return '0 minutes';
    }

    $hours = intdiv($minutes, 60);
    $mins  = $minutes % 60;

    if ($hours > 0 && $mins > 0) {
        return "{$hours} hour" . ($hours > 1 ? 's' : '') . " {$mins} minutes";
    }

    if ($hours > 0) {
        return "{$hours} hour" . ($hours > 1 ? 's' : '');
    }

    return "{$mins} minutes";
}



function date_d_format($value, $format = 'd M Y') {
    return \Carbon\Carbon::parse($value)->format($format);
}
