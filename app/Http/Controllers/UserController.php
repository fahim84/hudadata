<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public $diseases = [
        'heart disease',
        'stroke',
        'lung cancer',
        'colorectal cancer',
        'depression',
        'type 2 diabetes',
        'arthritis',
        'osteoporosis'
    ];
    public $allergies = [
        'Anaphylaxis',
        'Sinusitis',
        'Urticaria & Angioedema',
        'Rhinitis',
        'Asthma',
        'Autoimmunity-Immune Deficiency'
    ];

    public $identity_number = 4220102653665;
    public $firstname = 'FirstName';
    public $lastname = 'LastName';

    public function index()
    {
        my_var_dump('testing laravel');
        $directory_path = "C:\wamp64\www\hudadata\storage\app\public\Fingerprint";

        $results = $this->getDirContents($directory_path);

        $maxIdentity = DB::table('patients')->max('identity');
        if(!$maxIdentity)
        {
            $maxIdentity = $this->identity_number;
        }

        foreach ($results as $key => $file_path)
        {
            // check if the file is an image
            if(exif_imagetype($file_path))
            {
                DB::enableQueryLog();
                $filename = basename($file_path);

                // insert patient into database
                $patient = [
                    'identity' => ++$maxIdentity,
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'image' => $filename
                ];

                $new_id = DB::table('patients')->insertGetId(
                    $patient
                );

                // insert disease
                $random_key = array_rand($this->diseases);
                $disease = [
                    'user_id' => $new_id,
                    'name' => $this->diseases[$random_key]
                ];
                DB::table('diseases')->insertGetId(
                    $disease
                );

                // insert allergy
                $random_key = array_rand($this->allergies);
                $allergy = [
                    'user_id' => $new_id,
                    'name' => $this->allergies[$random_key]
                ];
                DB::table('allergies')->insertGetId(
                    $allergy
                );

                $query = DB::getQueryLog();

                my_var_dump($query);
            }
        }
    }

    private function getDirContents($dir, &$results = array()) {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                self::getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }

    public function empty_database()
    {
        DB::enableQueryLog();

        $table = 'patients';
        DB::table($table)->delete();
        DB::update("ALTER TABLE $table AUTO_INCREMENT = 1;");

        $table = 'diseases';
        DB::table($table)->delete();
        DB::update("ALTER TABLE $table AUTO_INCREMENT = 1;");

        $table = 'allergies';
        DB::table($table)->delete();
        DB::update("ALTER TABLE $table AUTO_INCREMENT = 1;");

        $query = DB::getQueryLog();

        my_var_dump($query);
    }
}
