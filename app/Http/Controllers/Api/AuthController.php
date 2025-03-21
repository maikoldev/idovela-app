<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Http\Services\SmsServices;
use Illuminate\Support\Facades\Hash;
use App\Models\Cart;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Str;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if (get_setting('customer_login_with') == 'email') {
            $user = User::where('email', $request->email)->first();
        } elseif (get_setting('customer_login_with') == 'phone') {
            $user = User::where('phone', $request->phone)->first();
        } else {
            $user = User::where('phone', $request->phone)->orWhere('email', $request->email)->first();
        }

        if ($user != null) {
            return response()->json([
                'success' => false,
                'message' => translate('User already exists.'),
                'data' => null
            ]);
        }

        if (!$request->has('phone') || !$request->has('email')) {
            return response()->json([
                'success' => false,
                'message' => translate('Email & phone is required.'),
                'data' => null
            ], 200);
        }

        $user = new User([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'person_type' => $request->personType,
            'first_name' => $request->firstName,
            'second_name' => $request->secondName,
            'first_lastname' => $request->firstLastname,
            'second_lastname' => $request->secondLastname,
            'document_type' => $request->documentType,
            'document_number' => $request->documentNumber,
            'company_name' => $request->companyName,
            'company_type' => $request->companyType,
            'company_document_type' => $request->companyDocumentType,
            'company_document_number' => $request->companyDocumentNumber,
            'phone' => $request->phone,
            'policies_and_cookies_consent' => $request->policiesAndCookiesConsent,
            'offers_consent' => $request->offersConsent,
            'verification_code' => rand(100000, 999999)
        ]);

        $user->save();

        if ($request->has('temp_user_id') && $request->temp_user_id != null) {
            Cart::where('temp_user_id', $request->temp_user_id)->update(
                [
                    'user_id' => $user->id,
                    'temp_user_id' => null
                ]
            );
        }

        if (get_setting('customer_otp_with') != 'disabled') {
            if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
                $user->notify(new EmailVerificationNotification());
                return response()->json([
                    'success' => true,
                    'verified' => false,
                    'message' => translate('A verification code has been sent to your email.')
                ], 200);
            } else {
                (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
                return response()->json([
                    'success' => true,
                    'verified' => false,
                    'message' => translate('A verification code has been sent to your phone.')
                ], 200);
            }
        }

        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
            'password' => 'required|string',
        ]);

        $phone = Str::replace(' ', '', $request->phone);
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->phone) {
            $user = User::where('phone', $phone)->first();
        } else {
            $user = null;
        }
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => translate('Invalid login information')
            ], 200);
        }

        // banned user
        if ($user->banned) {
            auth()->logout();
            return response()->json(
                [
                    'success' => false,
                    'message' => translate('You are banned!'),
                ],
                200,
            );
        }

        if ($user->user_type == 'customer') {
            if ($request->has('temp_user_id') && $request->temp_user_id != null) {
                Cart::where('temp_user_id', $request->temp_user_id)->update(
                    [
                        'user_id' => $user->id,
                        'temp_user_id' => null
                    ]
                );
            }

            if (get_setting('customer_otp_with') != 'disabled') {
                if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email') && $user->email_verified_at == null) {

                    $user->notify(new EmailVerificationNotification());
                    return response()->json([
                        'success' => true,
                        'verified' => false,
                        'email_verified' => false,
                        'message' => translate('Please verify your account')
                    ], 200);
                } elseif ((get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')) && $user->phone_verified_at == null) {

                    (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
                    return response()->json([
                        'success' => true,
                        'verified' => false,
                        'phone_verified' => false,
                        'message' => translate('Please verify your account')
                    ], 200);
                }
            }

            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        } else {
            return response()->json([
                'success' => false,
                'message' => translate('Only customers can login here')
            ], 200);
        }
    }

    public function verify(Request $request)
    {
        $phone = Str::replace(' ', '', $request->phone);
        if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
            $user = User::where('email', $request->email)->first();
        } elseif (get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')) {
            $user = User::where('phone', $phone)->first();
        } else {
            $user = null;
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }
        if ($user->verification_code != $request->code) {
            return response()->json([
                'success' => false,
                'message' => translate('Code does not match.')
            ], 200);
        } else {

            if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else {
                $user->phone_verified_at = date('Y-m-d H:m:s');
            }

            $user->save();
            $tokenResult = $user->createToken('Personal Access Token');
            return $this->loginSuccess($tokenResult, $user);
        }
    }

    public function resend_code(Request $request)
    {
        $phone = Str::replace(' ', '', $request->phone);
        if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
            $user = User::where('email', $request->email)->first();
        } elseif (get_setting('customer_login_with') == 'phone' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'phone')) {
            $user = User::where('phone', $phone)->first();
        } else {
            $user = null;
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => translate('No user found with this email address.')
            ], 200);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if (get_setting('customer_login_with') == 'email' || (get_setting('customer_login_with') == 'email_phone' && get_setting('customer_otp_with') == 'email')) {
            $user->notify(new EmailVerificationNotification());
            return response()->json([
                'success' => true,
                'verified' => false,
                'message' => translate('A verification code has been sent to your email.')
            ], 200);
        } else {
            (new SmsServices)->phoneVerificationSms($user->phone, $user->verification_code);
            return response()->json([
                'success' => true,
                'verified' => false,
                'message' => translate('A verification code has been sent to your phone.')
            ], 200);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();

        return response()->json([
            'message' => translate('Successfully logged out')
        ]);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();

        return response()->json([
            'success' => true,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'verified' => true,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'balance' => $user->balance,
                'name' => $user->first_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => api_asset($user->avatar),
            ],
            'message' => translate('Successfully logged in'),
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }

    public function tempIdCartUpdate(Request $request)
    {
        if ($request->temp_user_id != null) {
            Cart::where('temp_user_id', $request->temp_user_id)->update([
                'user_id' => auth()->guard('api')->user()->id,
                'temp_user_id' => null,
            ]);
        }

        return response()->json([
            'result' => true,
            'message' => translate('Cart updated'),
        ]);
    }
}
