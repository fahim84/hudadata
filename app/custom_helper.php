<?php

use App\Models\User;

if (! function_exists('my_var_dump')) {
    function my_var_dump($string)
    {
        $http_host = isset($_SERVER['HTTP_HOST']) ? true : false;
        $line_break = isset($_SERVER['HTTP_HOST']) ? "<br>" : "\n";
        $pre_tag_open = isset($_SERVER['HTTP_HOST']) ? "<pre>" : "\n";
        $pre_tag_close = isset($_SERVER['HTTP_HOST']) ? "</pre>" : "\n";

        if(is_array($string) or is_object($string))
        {
            echo $pre_tag_open;
            print_r($string);
            echo $pre_tag_close;
        }
        elseif(is_string($string))
        {
            echo $string.$line_break;
        }
        else
        {
            echo $pre_tag_open;
            var_dump($string);
            echo $pre_tag_close;
        }
    }

    function call_api_using_curl($url = '',$method = 'GET',$fields_array = [],$headers = [])
    {
        $curl_handle = curl_init();
        //curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        //curl_setopt($curl_handle, CURLOPT_VERBOSE, 0);
        switch ( $method )
        {
            case 'HEAD':
                curl_setopt( $curl_handle, CURLOPT_NOBODY, true );
                break;
            case 'POST':
                curl_setopt( $curl_handle, CURLOPT_POST, true );
                curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $fields_array );
                break;
            case 'GET':
                curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, $method );

                if(is_array($fields_array) and count($fields_array))
                {
                    //curl_setopt( $curl_handle, CURLOPT_POST, true );
                    //curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fields_array);
                    $url.= '?'.http_build_query($fields_array);
                }
                break;
            case 'PUT':
                curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT' );
                curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $fields_array );
                break;
            default:
                curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, $method );
                if(is_array($fields_array) and count($fields_array))
                {
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fields_array);
                }
                break;
        }

        if(is_array($headers) and count($headers))
        {
            curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $buffer;
    }


}
if (! function_exists('userRole')) {
    function userRole($id=0){
        $rights = [];
        $user = User::firstWhere('id', $id);
        if($user && $user->roles()->get() !== null) {
            // dd($user->roles()->get());
            if(isset($user->roles()->get()[0])){

                $userRole = $user->roles()->get()[0];
                return strtolower($userRole->name);
            }
            return "";
        } else{
            return 'administrator';
        }
    }
}
