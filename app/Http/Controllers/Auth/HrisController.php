<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\Roles;
use App\Models\User;
use App\Models\UserHasCustomRole;
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
            'email.required' => 'Masukan Email Onekalbe',
            'email.email' => 'Email Tidak Valid',
            'password.required' => 'Masukan Kata Sandi',
        ]);

        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();
        if (!empty($user) && $user->jobLvl == 'Administrator') {
            if (\Auth::attempt($credentials)) {
                $user = \Auth::user();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil masuk',
                    'redirect' => route('v1.dashboard'),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau kata sandi salah, silahkan ulangi!',
                    'redirect' => route('login'),
                ]);
            }
        } else if(!empty($user) && $user->is_local == true) {
            if (\Auth::attempt($credentials)) {
                $user = \Auth::user();

                (new LogActivityService())->handle([
                    'perusahaan' => strtoupper($user['CompName']),
                    'user' => strtoupper($credentials['email']),
                    'tindakan' => 'Login',
                    'catatan' => 'Berhasil Login Account',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'success login',
                    'redirect' => route('v1.dashboard'),
                ]);
            } else {
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($credentials['email']),
                    'tindakan' => 'Login',
                    'catatan' => 'Salah Password atau Username',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Email atau kata sandi salah, silahkan ulangi!',
                    'redirect' => route('login'),
                ]);
            }
        } else {
            $data = $this->hris($request);

            if (empty($data['accessToken']) || $data['accessToken'] == null) {
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($credentials['email']),
                    'tindakan' => 'Login',
                    'catatan' => 'Salah Password atau Username',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $data ?? 'Response Not Found',
                    'redirect' => route('login'),
                ]);
            } else {
                $accountResult = $this->getAccount($data, $request);

                if (!$accountResult) {
                    (new LogActivityService())->handle([
                        'perusahaan' => '-',
                        'user' => strtoupper($credentials['email']),
                        'tindakan' => 'Login',
                        'catatan' => 'Gagal sync data dari HRIS ke sistem lokal',
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memproses data dari HRIS. Hubungi IT Support.',
                        'redirect' => route('login'),
                    ]);
                }

                if (\Auth::attempt($credentials)) {
                    $user = \Auth::user();

                    (new LogActivityService())->handle([
                        'perusahaan' => strtoupper($user['compCode'] ?? '-'),
                        'user' => strtoupper($credentials['email']),
                        'tindakan' => 'Login',
                        'catatan' => 'Berhasil Login Account via HRIS',
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Selamat datang',
                        'redirect' => route('v1.dashboard'),
                    ]);
                } else {
                    $userCheck = User::where('email', $credentials['email'])->first();
                    
                    \Log::error('Auth attempt failed after getAccount', [
                        'email' => $credentials['email'],
                        'user_exists' => $userCheck ? true : false,
                        'password_hashed' => $userCheck && !empty($userCheck->password),
                        'is_deleted' => $userCheck?->deleted_at,
                    ]);

                    (new LogActivityService())->handle([
                        'perusahaan' => '-',
                        'user' => strtoupper($credentials['email']),
                        'tindakan' => 'Login',
                        'catatan' => 'Autentikasi HRIS berhasil tetapi gagal login lokal',
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Email atau password salah, silakan coba lagi!',
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

            $parts = explode('.', $data['accessToken']);

            if (count($parts) < 2) {
                throw new \RuntimeException('Invalid accessToken format');
            }

            $payloadJson = $this->base64UrlDecode($parts[1]);
            $payload = json_decode($payloadJson, true);

            if (!is_array($payload)) {
                throw new \RuntimeException('Invalid JWT payload JSON');
            }

            $hrisProfile = $this->pickProfile($payload, 'PROINT') ?? $payload;
            $wdProfile = $this->pickProfile($payload, 'WORKDAY') ?? $payload;

            $employeeId = $hrisProfile['WorkdayJobs'][0]['EmpId'] ?? $hrisProfile['NIK'] ?? null;
            if (!$employeeId) {
                throw new \RuntimeException('NIK/EmpId not found in token');
            }

            $resultJson = json_encode($payload, JSON_UNESCAPED_UNICODE);

            $email = strtolower(trim($request->email));
            $fullname = trim($hrisProfile['Name'] ?? '');

            $user = User::whereRaw('LOWER(email) = ?', [$email])
                        ->withTrashed()
                        ->first();

            if (!$user && !empty($employeeId)) {
                $user = User::where('employeId', $employeeId)
                            ->withTrashed()
                            ->first();
            }

            if (!$user && !empty($fullname)) {
                $user = User::where('fullname', $fullname)
                            ->withTrashed()
                            ->first();
            }

            $rolesCollection = Roles::select('name')->get();

            $jobLvl = trim($hrisProfile['JobLvlName'] ?? '');

            $matchedRole = $rolesCollection->first(function ($r) use ($jobLvl) {
                return strcasecmp($r->name, $jobLvl) === 0;
            });

            if ($matchedRole) {
                $finalJobLvl = $matchedRole->name;
            } else {
                $priority = ['STAFF', 'PELAKSANA 3', 'PELAKSANA 2', 'PELAKSANA 1'];
                $finalJobLvl = null;

                foreach ($priority as $p) {
                    $found = $rolesCollection->first(function ($r) use ($p) {
                        return strcasecmp($r->name, $p) === 0;
                    });

                    if ($found) {
                        $finalJobLvl = $found->name;
                        break;
                    }
                }
            }

            $dataUser = [
                'compCode'     => $hrisProfile['CompCode'] ?? '01',
                'employeId'    => $employeeId,
                'empTypeGroup' => $hrisProfile['EmpTypeGroup'] ?? null,
                'fullname'     => $hrisProfile['Name'] ?? null,
                'email'        => $email,
                'email_backup' => $hrisProfile['Email'] ?? null,
                'phone'        => $hrisProfile['EmpHandPhone'] ?? null,
                'jobLvl'       => $finalJobLvl ?? 'STAFF',
                'jobTitle'     => $hrisProfile['JobTtlName'] ?? null,
                'deptKode'     => $hrisProfile['EmpOrg'] ?? null,
                'groupKode'    => $hrisProfile['DivCode'] ?? null,
                'groupName'    => $hrisProfile['DivName'] ?? null,
                'password'     => \Hash::make($request->password),
                'result'       => $resultJson,
                'is_local'     => false,
            ];

            if (!$user) {
                User::create($dataUser);
            } else {
                if ($user->trashed()) {
                    $user->restore();
                }
                $user->update($dataUser);
            }

            $userHasCustomRole = UserHasCustomRole::where('nik', $hrisProfile['NIK'] ?? $employeeId)->first();
            if ($userHasCustomRole) {
                $userHasCustomRole->update([
                    'nik' => $employeeId,
                ]);
            }

            \DB::commit();

            return [
                'compCode'     => $dataUser['compCode'],
                'employeId'    => $dataUser['employeId'],
                'fullname'     => $dataUser['fullname'],
                'empTypeGroup' => $dataUser['empTypeGroup'],
                'email'        => $dataUser['email'],
                'email_backup' => $dataUser['email_backup'],
                'phone'        => $dataUser['phone'],
                'jobLvl'       => $dataUser['jobLvl'],
                'jobTitle'     => $dataUser['jobTitle'],
                'groupName'    => $dataUser['groupName'],
                'groupKode'    => $dataUser['groupKode'],
                'result'       => $dataUser['result'],
            ];

        } catch (\Throwable $th) {
            \Log::error('getAccount error: ' . $th->getMessage(), [
                'email' => $request->email,
                'trace' => $th->getTraceAsString(),
            ]);
            \DB::rollBack();

            dd([
                'Pesan Error' => $th->getMessage(),
                'Di File'     => $th->getFile(),
                'Baris Ke'    => $th->getLine(),
                'Trace'       => $th->getTrace()
            ]);
            return null;
        }
    }

    private function base64UrlDecode(string $data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        $data = strtr($data, '-_', '+/');
        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            throw new \RuntimeException('Failed to base64url decode JWT payload');
        }
        return $decoded;
    }

    private function pickProfile(array $payload, string $dataSource)
    {
        $profilesRaw = $payload['Profiles'] ?? null;
        if (!$profilesRaw) return null;

        $profiles = is_string($profilesRaw) ? json_decode($profilesRaw, true) : $profilesRaw;
        if (!is_array($profiles)) return null;

        foreach ($profiles as $p) {
            if (is_array($p) && (($p['DataSource'] ?? null) === $dataSource)) {
                return $p;
            }
        }

        return null;
    }

    private function hris($request)
    {
        $credentials = [
            'username' => $request->email,
            'password' => $request->password,
            'getprofile' => true,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api-global-bundle-3scale-production.kalbe.co.id/api/v1/authentication/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => [
                'app_id: 8a33cfd2',
                'app_key: f7cce2000d6a3bbdb10e43871a19fadf',
                'X-API-VERSION: 2',
                'Content-Type: application/json'
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
                $data = json_decode(auth()->user()->result ?? '{}', true);
                (new LogActivityService())->handle([
                    'perusahaan' => strtoupper($data['CompName'] ?? '-'),
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
