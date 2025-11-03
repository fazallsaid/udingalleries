<?php

namespace App\Http\Controllers;

use App\Mail\ResetNotif;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    function index(Request $request){
        $urlCust = $request->is('login');
        $urlAdmin = $request->is('admin');

        if($request->has('redirect')){
            session()->put('redirect_after_login', $request->get('redirect'));
        }

        if($urlAdmin){
            $title = "Login Admin / Udin Gallery";
            $data = [
                'title' => $title
            ];
            return view('admin.login', $data);
        }elseif($urlCust){
            $title = "Login / Udin Gallery";
            $data = [
                'title' => $title
            ];
            return view('auth', $data);
        }
    }

    function login(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $userCheck = Users::where('email', $validatedData['email'])->first();

        if($userCheck && Hash::check($validatedData['password'], $userCheck->password)){
            session()->put('userLogin', true);
            session()->put('userId', $userCheck->id_user);
            session()->put('userRole', $userCheck->role);
            toastr()->success('Anda berhasil masuk!');
            if($userCheck->role == "admin"){
                return redirect('admin/dashboard');
            }else{
                if(session('redirect_after_login')){
                    $redirect = session('redirect_after_login');
                    session()->forget('redirect_after_login');
                    return redirect($redirect);
                }else{
                    return redirect('/');
                }
            }
        }else{
            toastr()->error('Cek email dan password Anda.');
            if($request->is('admin')){
                return redirect('admin');
            }else{
                return redirect('login');
            }
        }
    }

    function register(Request $request){
        $validatedData = $request->validate([
            'nama_tampilan' => 'required',
            'email' => 'required|email',
            'nomor_whatsapp' => 'required',
            'password' => 'required',
            'more_password' => 'required'
        ]);

        $users = Users::where('email', $validatedData['email'])->first();

        if($users){
            toastr()->error('Anda sudah punya akun dengan email ini. Silakan login.');
            return redirect('login');
        }

        $passOne = $validatedData['password'];
        $passTwo = $validatedData['more_password'];

        if($passOne != $passTwo){
            toastr()->error('Password Anda tidak sama. Ulangi lagi.');
            return redirect('login');
        }

        $users = new Users;
        $users->nama_tampilan = $validatedData['nama_tampilan'];
        $users->email = $validatedData['email'];
        $users->nomor_whatsapp = $validatedData['nomor_whatsapp'];
        $users->password = Hash::make($validatedData['password']);
        $users->role = "customer";
        $users->save();

        if($users){
            toastr()->success('Akun Anda telah berhasil dibuat. Silakan login kembali dengan kredensial Anda.');
            return redirect('login');
        }
    }

    function logout(Request $request){
        $userCheck = Users::where('id_user', session('userId'))->first();

        if($userCheck){
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            toastr()->success('Anda berhasil keluar dari sistem. Terima kasih!');
            if($userCheck->role == "admin"){
                return redirect('/admin');
            }else{
                return redirect('/');
            }
        }
    }

    function forgotPassword(Request $request){
        $title = "Lupa Password / Udin Gallery";
        $data = [
            'title' => $title
        ];
        return view('forgot-password', $data);
    }

    function sendResetLink(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email'
        ]);

        $user = Users::where('email', $validatedData['email'])->first();

        if (!$user) {
            toastr()->error('Email tidak ditemukan dalam sistem kami.');
            return redirect('forgot-password');
        }

        // Generate reset token
        $token = Str::random(64);
        $user->reset_token = $token;
        $user->reset_token_expires = Carbon::now()->addMinutes(30);
        $user->save();

        // Buat URL reset
        $resetUrl = url('/reset-password/' . $token);

        // --- INI CARA KIRIM EMAIL YANG BENAR ---
        try {

            // 1. Buat instance Mailable dengan data
            $emailMessage = new ResetNotif($user, $token, $resetUrl);

            // 2. Kirim email menggunakan Mail facade
            Mail::to($user->email, $user->nama_tampilan)
                ->send($emailMessage);

            // ----------------------------------------

            toastr()->success('Link reset password telah dikirim ke email Anda.');

        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            toastr()->error('Gagal mengirim email. Silakan coba lagi.');
        }

        return redirect('forgot-password');
    }

    function resetPassword(Request $request, $token){
        $user = Users::where('reset_token', $token)
                    ->where('reset_token_expires', '>', Carbon::now())
                    ->first();

        if(!$user){
            toastr()->error('Token reset password tidak valid atau telah kedaluwarsa.');
            return redirect('login');
        }

        $title = "Reset Password / Udin Gallery";
        $data = [
            'title' => $title,
            'token' => $token
        ];
        return view('reset-password', $data);
    }

    function updatePassword(Request $request){
        $validatedData = $request->validate([
            'token' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = Users::where('reset_token', $validatedData['token'])
                    ->where('reset_token_expires', '>', Carbon::now())
                    ->first();

        if(!$user){
            toastr()->error('Token reset password tidak valid atau telah kedaluwarsa.');
            return redirect('login');
        }

        $user->password = Hash::make($validatedData['password']);
        $user->reset_token = null;
        $user->reset_token_expires = null;
        $user->save();

        toastr()->success('Password Anda telah berhasil direset. Silakan login dengan password baru.');
        return redirect('login');
    }
}
