<?php

namespace Swarovsky\Core\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FAQRCode\Google2FA;
use Swarovsky\Core\Helpers\SessionHelper;
use Swarovsky\Core\Models\PasswordSecurity;

class PasswordSecurityController extends Controller
{

    public function show2faForm(Request $request){
        $user = Auth::user();
        $google2fa = new Google2FA();
        $google2fa_url = "";
        if($user->passwordSecurity()->exists()){
            $google2fa_url = $google2fa->getQRCodeInline(
                env('APP_NAME'),
                $user->email,
                $user->passwordSecurity->google2fa_secret
            );
        }
        $data = array(
            'user' => $user,
            'google2fa_url' => $google2fa_url
        );
        return view('swarovsky-core::auth.2fa')->with('data', $data);
    }

    public function generate2faSecret(Request $request){
        $user = Auth::user();
        $google2fa = new Google2FA();

        PasswordSecurity::create([
            'user_id' => $user->id,
            'google2fa_enable' => 0,
            'google2fa_secret' => $google2fa->generateSecretKey(),
        ]);

        SessionHelper::add_message('Secret Key is generated, Please verify Code to Enable 2FA', 'success');
        return redirect()->route('user.security');
    }


    public function enable2fa(Request $request){
        $user = Auth::user();
        $google2fa = new Google2FA();
        $secret = $request->input('verify-code');
        $valid = $google2fa->verifyKey($user->passwordSecurity->google2fa_secret, $secret);

        if($valid){
            $user->passwordSecurity->google2fa_enable = 1;
            $user->passwordSecurity->save();
            SessionHelper::add_message('2FA is Enabled Successfully.', 'success');
            return redirect()->route('user.security');
        }

        SessionHelper::add_message('Invalid Verification Code, Please try again.', 'danger');
        return redirect()->route('user.security');
    }

    public function disable2fa(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            SessionHelper::add_message('Your password does not matches with your account password. Please try again.', 'danger');
            return redirect()->back();
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->passwordSecurity->google2fa_enable = 0;
        $user->passwordSecurity->save();

        SessionHelper::add_message('2FA is now Disabled.', 'success');
        return redirect()->route('user.security');

    }
}
