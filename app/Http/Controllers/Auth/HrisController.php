<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Line;
use App\Services\System\LogActivityService;
use Illuminate\Http\Request;
use Log;

class HrisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Masukan EMail Onekalbe',
            'email.email' => 'EMail Tidak Valid',
            'password.required' => 'Masukan Password',
        ]);

        // Membuat array $kredensil langsung
        $kredensil = $request->only('email', 'password');

        // check User
        $user = User::where('email', $request->email)->first();
        if (!empty($user) && $user->jobLvl == 'Administrator') {
            // code...
            if (\Auth::attempt($kredensil)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil, Selamat Datang di '.env('APP_NAME'),
                    'redirect' => route('v1.dashboard'),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Gagal Silahkan Ulangi',
                    'redirect' => route('login'),
                ]);
            }
        } 
        
        else if (!empty($user) && ($user->jobLvl == 'SUPERVISOR' || $user->jobLvl == 'SENIOR SUPERVISOR')) {
            $data = $this->hris($request);

            if (empty($data['accessToken'])) {
                // ... (handle error login HRIS) ...
                return response()->json([
                    'success' => false,
                    'message' => $data ?? 'Response Not Found',
                    'redirect' => route('login'),
                ]);
            }
            
            // Sinkronkan data dari HRIS ke tabel 'users' (membuat atau update)
            $this->getAccount($data, $request);

            if (\Auth::attempt($kredensil)) {
                $loggedInUser = auth()->user();
        
                $data = json_decode($loggedInUser->result, true);
                
                $lineMapping = Line::pluck('id', 'empOrg')->all();

                $empOrgCode = $data['EmpOrg'] ?? null;
                $lineId = null;

                if ($empOrgCode && array_key_exists($empOrgCode, $lineMapping)) {
                    $lineId = $lineMapping[$empOrgCode];
                }

                // Gunakan $loggedInUser untuk update profile, bukan $user dari luar scope
                $loggedInUser->profile()->updateOrCreate(
                    ['user_id' => $loggedInUser->id],
                    ['line_id' => $lineId]
                );

                (new LogActivityService())->handle([
                    'perusahaan' => strtoupper($data['CompName']),
                    'user' => strtoupper($request->email),
                    'tindakan' => 'Login',
                    'catatan' => 'Berhasil Login Account',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil, Selamat Datang di '.env('APP_NAME'),
                    'redirect' => route('v1.dashboard'),
                ]);
            }
        } 
        
        else {
            $data = $this->hris($request);
            if (empty($data['accessToken']) || $data['accessToken'] == null) {
                // code...
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($request->email),
                    'tindakan' => 'Login',
                    'catatan' => 'Salah Password atau Username',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $data ?? 'Response Not Found',
                    'redirect' => route('login'),
                ]);
            } else {
                // code...
                $this->getAccount($data, $request);

                if (\Auth::attempt($kredensil)) {
                    $data = json_decode(auth()->user()->result, true);
                    (new LogActivityService())->handle([
                        'perusahaan' => strtoupper($data['CompName']),
                        'user' => strtoupper($request->email),
                        'tindakan' => 'Login',
                        'catatan' => 'Berhasil Login Account',
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Login Berhasil, Selamat Datang di '.env('APP_NAME'),
                        'redirect' => route('v1.dashboard'),
                    ]);
                } else {
                    (new LogActivityService())->handle([
                        'perusahaan' => '-',
                        'user' => strtoupper($request->email),
                        'tindakan' => 'Login',
                        'catatan' => 'Salah Password atau Username',
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Login Gagal Silahkan Ulangi',
                        'redirect' => route('login'),
                    ]);
                }
            }
        }
    }

    private function getAccount($data, $request)
    {
        try {
            \DB::beginTransaction();

            $response = explode('.', $data['accessToken']);
            $result = base64_decode($response[1]);
            $response = json_decode($result, true);

            $resultJson = json_encode($response);
            // Cek PLT
            $jobTitle = $response['JobTtlName'];
            $words = explode(' ', $jobTitle);

            // Ambil kata pertama dan terakhir
            $firstWord = $words[0];
            $lastWord = strtoupper($words[count($words) - 1]);

            if (in_array($firstWord, ['PLT', 'PIC', 'ASSOCIATE'])) {
                // code...
                $role = $lastWord;
            } else {
                // code...
                $role = $response['JobLvlName'];
            }

            $check = User::where('email', $request->email)->first();
            // dd($response);

            if (empty($check)) {
                // code...
                // dd($check);
                User::create([
                    'employeId' => $response['NIK'],
                    'compCode' => $response['CompCode'],
                    'fullname' => $response['Name'],
                    'email' => $request->email,
                    'phone' => $response['EmpHandPhone'] ?? 'NA',
                    'empTypeGroup' => $response['EmpTypeGroup'],
                    'email_backup' => $request['Email'],
                    'jobLvl' => $role,
                    'jobTitle' => $response['JobTtlName'],
                    'deptKode' => $response['EmpOrg'],
                    'groupName' => $response['DivName'],
                    'groupKode' => $response['DivCode'],
                    'password' => \Hash::make($request->password),
                    'result' => $resultJson,
                ]);
            } else {
                $check->update([
                    'employeId' => $response['NIK'],
                    'compCode' => $response['CompCode'],
                    'fullname' => $response['Name'],
                    'email' => $request->email,
                    'phone' => $response['EmpHandPhone'] ?? 'NA',
                    'empTypeGroup' => $response['EmpTypeGroup'],
                    'email_backup' => $request['Email'],
                    'jobLvl' => $role,
                    'jobTitle' => $response['JobTtlName'],
                    'deptKode' => $response['EmpOrg'],
                    'groupName' => $response['DivName'],
                    'groupKode' => $response['DivCode'],
                    'password' => \Hash::make($request->password),
                    'result' => $resultJson,
                ]);
            }

            \DB::commit();

            return [
                'employeId' => $response['NIK'],
                'fullname' => $response['Name'],
                'email' => $request->email,
                'phone' => $response['EmpHandPhone'] ?? 'NA',
                'empTypeGroup' => $response['EmpTypeGroup'],
                'email_backup' => $request['Email'],
                'jobLvl' => $role,
                'jobTitle' => $response['JobTtlName'],
                'groupName' => $response['DivName'],
                'groupKode' => $response['DivCode'],
            ];
        } catch (\Throwable $th) {
            // throw $th;
            \Log::error($th);
            \DB::rollBack();

            return null;
        }
    }

    private function hris($request)
    {
        $credentials = [
            'username' => $request->email,
            'password' => $request->password,
            'getprofile' => true,
        ];

        // Kirim permintaan ke endpoint API login

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-global-itpharma-production-3scale.kalbe.co.id/api/v1/GlobalLogin/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => [
                'app_id: a8ecd84f',
                'app_key: 1cb9d7c6d267a904ab461ad65d49458e',
                'Content-Type: application/json',
                'X-API-Key: SQA45CsPgqRCeyoO0ZzeKK6BFG1vpR1vy7r-gvPiEw4',
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $token = json_decode($response, true);

        return $token;
    }

    public function logout(Request $request)
    {
        try {
            \DB::beginTransaction();
            if (auth()->check() && auth()->user()->jobLvl != 'Administrator') {
                $data = json_decode(auth()->user()->result, true);
                (new LogActivityService())->handle([
                    'perusahaan' => strtoupper($data['CompName']),
                    'user' => strtoupper(auth()->user()->email),
                    'tindakan' => 'LogOut',
                    'catatan' => 'User Berhasil Logout System',
                ]);
            }

            \Auth::logout(); // Log out the user

            $request->session()->invalidate(); // Invalidate the session
            $request->session()->regenerateToken(); // Regenerate the session token

            \DB::commit();

            return redirect(route('login'))->with('success', 'Berhasil Logout, Terima Kasih');
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
