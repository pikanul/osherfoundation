<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\PngEncoder;
use Illuminate\Support\Facades\File;


class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $upload_data = Upload::where(function($query) use ($request){
            if($request->has('id')){
                $query->where('id', '<', "$request->id");
            }
        })->orderBy('id','desc')->limit(30)->get();
        //  $upload_data = array_reverse($upload_data);
        return response()->json([
        'success' => true,
        'data' =>   $upload_data
        ]);
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
        //return 4545;



          // Temporary directory for chunks
          $tempPath = public_path('uploads/temp');
          $finalPath = public_path('uploads');

          // Create directories if not exist
          File::ensureDirectoryExists($tempPath);
          File::ensureDirectoryExists($finalPath);

         $orgfname =$request->input('originalName');
         $fileName = $request->input('fileName');
          $chunkIndex = $request->input('chunkIndex');
          $totalChunks = $request->input('totalChunks');

          // Save the chunk to a temporary file
          $chunkFile = "{$tempPath}/{$fileName}.part{$chunkIndex}";
          $request->file('file')->move($tempPath, $chunkFile);

          // Check if all chunks are uploaded
          if ($this->allChunksUploaded($tempPath, $fileName, $totalChunks)) {
              // Combine chunks into the final file
              $finalFilePath = "{$finalPath}/{$fileName}";
              $finalFile = fopen($finalFilePath, 'wb');

              for ($i = 0; $i < $totalChunks; $i++) {
                  $chunkFile = "{$tempPath}/{$fileName}.part{$i}";
                  $chunk = fopen($chunkFile, 'rb');
                  stream_copy_to_stream($chunk, $finalFile);
                  fclose($chunk);
                  unlink($chunkFile); // Remove chunk file
              }
              fclose($finalFile);

             // check image unsupport format by img tag
              $unsupport_format = ['avif',  'webp', 'BMP', 'bmp',  'jfif', 'pjpeg', 'tif', 'tiff', 'pjp'];
               $file_explode = explode('.', $fileName);

                if(in_array(strtolower($file_explode[count($file_explode) - 1]), $unsupport_format) || $request->converter_enabled == 'convert_eanabled'){

                    $imageManager = new ImageManager(new Driver());

                    // Read the image
                    $image = $imageManager->read($finalFilePath);

                    // Resize the image if enabled
                    if ($request->converter_enabled == 'convert_eanabled') {
                        $width = $request->input('resize_width');
                        $height = $request->input('resize_height');


                        if ($width && $height) {
                            $image->resize($width, $height);
                        } elseif ($width) {
                            $image->resize($width, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } elseif ($height) {
                            $image->resize(null, $height, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                    }
                    // end resize enabled



                // File if unsupported convert in png if quality  change quality
                    // Get the file extension
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $quality = $request->quality; // Quality for JPEG, WEBP, etc. (0-100)

                    // Check if format is supported
                    if (!in_array($fileExtension, $unsupport_format) && $request->converter_enabled == 'convert_eanabled') {
                        // Save the resized image in its original format with quality control
                        $outputPath = public_path('uploads/' . $fileName);

                        $image->save($outputPath, $quality);

                    } else {

                        $new_file = pathinfo($fileName, PATHINFO_FILENAME) . '.png';
                        $outputPath = public_path('uploads/' . $new_file);

                        $encoder = new PngEncoder();

                        if( $request->converter_enabled == 'convert_eanabled'){
                            $image = $image->encode($encoder, $quality);
                        }else{
                            $image = $image->encode($encoder);
                        }
                        // $image->interlace();
                        file_put_contents($outputPath, (string) $image);

                        unlink($finalFilePath);
                        $fileName = $new_file;
                    }

                // End of convert and save


                }
                // Rename file name
                // rename($finalFilePath, public_path('uploads/' . $fileName));

                $user_id = auth()->user()->id ?? 0;
                $upload = Upload::create([
                    'name' => $fileName,
                    'extension' => explode('.',$fileName)[1],
                    'size' => $request->size,
                    'source' => 'Local',
                    'user_id' => $user_id,
                    'orginal_name' => $orgfname
                    // 'extension'=>$external_link,
                ]);



            return response()->json([
                'complete' => true,
                'message' => 'Chunk uploaded successfully!',
                'name' => $fileName,
                'file_path' => public_path('uploads/'.$fileName),
                'orginal_name' => $upload->orginal_name,
                'id' => $upload->id
            ]);
          }


         return  response()->json([
            'complete' => false,
            'message' => 'Chunk uploaded successfully!',
          ]);
    }

    private function allChunksUploaded($tempPath, $fileName, $totalChunks)
    {
        for ($i = 0; $i < $totalChunks; $i++) {
            if (!file_exists("{$tempPath}/{$fileName}.part{$i}")) {
                return false;
            }
        }
        return true;
    }

    /**
     * Display the specified resource.
     */
    public function show(upload $upload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $upload = Upload::find($request->id);
        if($upload){
            if(asset_unlink($upload->id)){
                if($upload->delete()){
                    return response()->json(['success'=> true]);
                }else{
                    return response()->json(['success'=> false,'message'=> 'Someting went wrong']);
                }
            }else{
                return response()->json(['success'=> false,'message'=> 'Unable to delete file']);
            }
        }else{
            return response()->json(['success'=> false,'message'=> 'Data not found']);
        }
    }
}
