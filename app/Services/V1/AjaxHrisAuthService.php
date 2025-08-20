<?php

namespace App\Services\V1;

/**
 * Class AjaxHrisAuthService.
 */
class AjaxHrisAuthService
{
    public function handle($data)
    {
        $credentials = [
            'username' => $data['email'],
            'password' => $data['password'],
            'getprofile' => true
        ];

        // Kirim permintaan ke endpoint API login

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://api-global-it-pharma-production-3scale-apicast-staging.apps.alpha.kalbe.co.id/api/v1/GlobalLogin/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => array(
                'app_id: a8ecd84f',
                'app_key: 1cb9d7c6d267a904ab461ad65d49458e',
                'Content-Type: application/json',
                'X-API-Key: SQA45CsPgqRCeyoO0ZzeKK6BFG1vpR1vy7r-gvPiEw4'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $token = json_decode($response, true);
        $hasil = explode('.', $token['accessToken']);
        $result = base64_decode($hasil[1]);
        $result = json_decode($result, true);

        if ($result['NIK'] == auth()->user()->employeId) {
            # code...
            return true;
        } else {
            # code...
            return false;
        }
    }
}
