<?php

namespace App\Http\Controllers;

use App\Traits\Filepond;
use App\Traits\UploadTheme;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SCORMController extends Controller
{
    use UploadTheme, Filepond;

    public $url = '';
    public $path = '';
    public $host = '';

    public function getScormUrl($path, $host = 'local')
    {
        $uniqId = uniqid();
        $contents = '';
        try {
            $zip = new ZipArchive;
            $res = $zip->open($path);


            if ($res === true) {
                $stream = $zip->getStream('imsmanifest.xml');

                if (!$stream) {
                    abort(500, 'Invalid SCORM file');
                }

                while (!feof($stream)) {
                    $contents .= fread($stream, 2);
                }

                $zip->extractTo(storage_path('app/tempScorm/' . $uniqId));
                $zip->close();

            } else {
                abort(500, 'Error! Could not open File');
            }


            $src = storage_path('app/tempScorm/' . $uniqId);
            $folder = '/public/uploads/scorm/' . $uniqId . '/';
            $temp_folder = 'storage/app/tempScorm/' . $uniqId . '/';
            $dst = base_path($folder);

            $imsmanifest = base_path($temp_folder . 'imsmanifest.xml');
            $index = 'index.html';
            if (file_exists($imsmanifest)) {
                $result = simplexml_load_file($imsmanifest)[0];
                $index = $result->resources->resource['href'][0];
            }

            if ($host == "SCORM-AwsS3") {
                $files = Storage::allFiles('tempScorm/' . $uniqId . '/');
                foreach ($files as $object) {
                    $path_info = pathinfo($object);
                    $file_name = $path_info['filename'] . '.' . $path_info['extension'];
                    $dirname = str_replace('tempScorm/', '', $path_info['dirname']);
                    $fullname = $dirname . '/' . $file_name;
                    Storage::disk('s3')->put($fullname, file_get_contents(storage_path('app/' . $object), 'public'));
                }
                $link = Storage::disk('s3')->url($uniqId);
                $link .= '/' . $index;
                $return = $link;
            } else {
                $this->recurse_copy($src, $dst);
                $return = $folder . $index;
            }
            $manifestData = $this->parseLMSManifest($contents);
            $manifestData['url'] = $return;
            $this->deleteFile($host, $path);
            return $manifestData;
        } catch (\Exception $e) {
            $this->deleteFile($host, $path);
        }
        return false;
    }


    public function deleteFile($host, $path = null)
    {
        if (storage_path('app/tempScorm')) {
            $this->delete_directory(storage_path('app/tempScorm'));
        }
        if ($path) {
            File::delete($path);
        }
    }


    private function parseLMSManifest($contents)
    {

        $data = [];
        $dom = new DOMDocument();
        $dom->loadXML($contents);
        $manifest = $dom->getElementsByTagName('manifest')->item(0);
        if (!is_null($manifest->attributes->getNamedItem('identifier'))) {
            $data['identifier'] = $manifest->attributes->getNamedItem('identifier')->nodeValue;
        } else {
            $data['identifier'] = '';
        }

        $titles = $dom->getElementsByTagName('title');
        if ($titles->length > 0) {
            $data['title'] = trim($titles->item(0)->textContent);
        }

        $scormVersionElements = $dom->getElementsByTagName('schemaversion');


        $SCORM_12 = 'scorm_12';
        $SCORM_2004 = 'scorm_2004';
        if ($scormVersionElements->length > 0) {
            switch ($scormVersionElements->item(0)->textContent) {
                case '1.2':
                    $data['version'] = $SCORM_12;
                    break;
                case 'CAM 1.3':
                case '2004 3rd Edition':
                case '2004 4th Edition':
                    $data['version'] = $SCORM_2004;
                    break;
                default:
                    $data['version'] = $SCORM_12;
            }
        } else {
            $data['version'] = $SCORM_12;
        }

        return $data;
    }


    public function viewer($version, $link)
    {
        $link = base64_decode($link);
        return view('viewer', compact('version', 'link'));
    }

    public function action(Request $request)
    {
        return view('frontend-view');
    }

    public function getProgress(Request $request, $lesson_id)
    {
        // Implementation for getting SCORM progress for the given lesson_id
        // This is a placeholder implementation and should be replaced with actual logic to retrieve progress data from
        // the database or SCORM tracking system.
        // For example, you might query the database for the user's progress on the specified lesson_id and return it as JSON.
       
        $progressData = [
            'lesson_id' => $lesson_id,
            'progress' => 75, // Example progress percentage
            'status' => 'incomplete', // Example status
        ];
        return response()->json($progressData);
    }
}
