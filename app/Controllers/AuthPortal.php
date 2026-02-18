<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ForgotOtpModel;
use App\Models\AadhaarOtpModel;

class AuthPortal extends BaseController
{
    protected $userModel;
    protected $forgotOtpModel;
    protected $aadhaarOtpModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->forgotOtpModel = new ForgotOtpModel();
        $this->aadhaarOtpModel = new AadhaarOtpModel();
    }

    /**
     * Login page (GET) and login submit (POST)
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('user_id')) {
            return redirect()->to('/user/dashboard');
        }

        // Handle POST: process login (detect by presence of mobile field)
        if ($this->request->getPost('mobile') !== null) {
            $mobile   = (string) $this->request->getPost('mobile');
            $password = (string) $this->request->getPost('password');

            if (empty($mobile) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Mobile and password are required');
            }

            $user = $this->userModel->where('mobile', $mobile)->first();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return redirect()->back()->withInput()->with('error', 'Invalid mobile number or password');
            }

            session()->set([
                'user_id'     => $user['id'],
                'user_name'   => $user['name'],
                'user_mobile' => $user['mobile'],
                'user_email'  => $user['email'] ?? '',
                'logged_in'   => true,
            ]);

            return redirect()->to('/user/dashboard')->with('success', 'Login successful');
        }

        // GET: show form
        return view('layout/header')
            . view('auth/login')
            . view('layout/footer');
    }

    /**
     * Registration page (GET) and submit (POST)
     */
    public function register()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('user_id')) {
            return redirect()->to('/user/dashboard');
        }

        // Handle POST: process registration (detect by presence of mobile field)
        if ($this->request->getPost('mobile') !== null) {
            $mobile   = (string) $this->request->getPost('mobile');
            $password = (string) $this->request->getPost('password');
            $name     = trim((string) $this->request->getPost('name'));
            $email    = trim((string) $this->request->getPost('email'));
            $language = trim((string) $this->request->getPost('language'));
            $category = trim((string) $this->request->getPost('category'));

            if (empty($mobile) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Mobile and password are required');
            }

            if (empty($name)) {
                return redirect()->back()->withInput()->with('error', 'Name is required');
            }

            if (empty($email)) {
                return redirect()->back()->withInput()->with('error', 'Email is required');
            }

            if (empty($language)) {
                return redirect()->back()->withInput()->with('error', 'Language is required');
            }

            if (empty($category)) {
                return redirect()->back()->withInput()->with('error', 'Category is required');
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return redirect()->back()->withInput()->with('error', 'Please enter a valid email address');
            }

            // Check if mobile already exists
            $existingUser = $this->userModel->where('mobile', $mobile)->first();
            if ($existingUser) {
                return redirect()->back()->withInput()->with('error', 'Mobile number already registered. Please login.');
            }

            // Check if email already exists
            $existingEmail = $this->userModel->where('email', $email)->first();
            if ($existingEmail) {
                return redirect()->back()->withInput()->with('error', 'Email already registered. Please login.');
            }

            // Validate mobile number (10 digits)
            if (!preg_match('/^[0-9]{10}$/', $mobile)) {
                return redirect()->back()->withInput()->with('error', 'Please enter a valid 10-digit mobile number');
            }

            // Validate password (minimum 6 characters)
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password must be at least 6 characters long');
            }

            // Check Aadhaar verification (required for registration)
            $aadhaar = trim((string) $this->request->getPost('aadhaar'));
            $aadhaar = preg_replace('/[\s\-]/', '', $aadhaar);

            if (empty($aadhaar)) {
                return redirect()->back()->withInput()->with('error', 'Aadhaar number is required for registration');
            }

            if (!preg_match('/^[0-9]{12}$/', $aadhaar)) {
                return redirect()->back()->withInput()->with('error', 'Please enter a valid 12-digit Aadhaar number');
            }

            // Check if Aadhaar is verified (check by aadhaar number, user_id will be 0 or session-based)
            // For registration, we check if Aadhaar is verified (user_id can be 0 or from session if user verified while logged in)
            $verifiedAadhaar = $this->aadhaarOtpModel
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 1)
                ->orderBy('updated_at', 'DESC')
                ->first();

            if (!$verifiedAadhaar) {
                return redirect()->back()->withInput()->with('error', 'Please verify your Aadhaar number before completing registration.');
            }

            // Check if Aadhaar is already linked to a different registered user
            if ($verifiedAadhaar['user_id'] > 0) {
                $existingUser = $this->userModel->find($verifiedAadhaar['user_id']);
                if ($existingUser) {
                    return redirect()->back()->withInput()->with('error', 'This Aadhaar number is already registered by another user. Please use a different Aadhaar number or login with your existing account.');
                }
            }

            // Create user
            $data = [
                'mobile'        => $mobile,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'name'          => $name,
                'email'         => $email,
                'language'      => $language,
                'category'      => $category,
            ];

            if ($this->userModel->insert($data)) {
                $userId = $this->userModel->getInsertID();

                // Link verified Aadhaar to this new user
                $this->aadhaarOtpModel->update($verifiedAadhaar['id'], [
                    'user_id' => $userId
                ]);

                // Auto-fill user name from KYC if available and name is empty or different
                if (!empty($verifiedAadhaar['kyc_name'])) {
                    // Use KYC name if it's more complete or if user name is empty
                    if (empty($name) || strlen($verifiedAadhaar['kyc_name']) > strlen($name)) {
                        $this->userModel->update($userId, [
                            'name' => $verifiedAadhaar['kyc_name']
                        ]);
                        $data['name'] = $verifiedAadhaar['kyc_name'];
                    }
                }

                // Auto-login after registration
                session()->set([
                    'user_id'     => $userId,
                    'user_name'   => $data['name'],
                    'user_mobile' => $mobile,
                    'user_email'  => $email,
                    'logged_in'   => true,
                ]);

                return redirect()->to('/user/dashboard')->with('success', 'Registration successful! Welcome to the portal.');
            }

            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        // GET: show form
        return view('layout/header')
            . view('auth/register')
            . view('layout/footer');
    }

    public function forgotPassword()
    {
        $session = session();
        $stage   = $session->get('forgot_stage') ?? 'mobile';

        if (strtolower($this->request->getMethod()) === 'post') {
            $step = (string) $this->request->getPost('step');

            // STEP 1: Enter mobile, generate & save OTP (always 123456)
            if ($step === 'mobile') {
                $mobile = (string) $this->request->getPost('mobile');

                if ($mobile === '') {
                    return redirect()->back()->withInput()->with('error', 'Please enter your registered mobile number');
                }

                $user = $this->userModel->where('mobile', $mobile)->first();

                if (! $user) {
                    return redirect()->back()->withInput()->with('error', 'No account found with this mobile number');
                }

                $session->set('forgot_user_id', $user['id']);

                // Fixed OTP 123456 (as requested)
                $otpValue = '123456';

                // Upsert into forgot_otps table (one row per user)
                $existing = $this->forgotOtpModel->where('user_id', $user['id'])->first();
                if ($existing) {
                    $this->forgotOtpModel->update($existing['id'], ['otp' => $otpValue]);
                } else {
                    $this->forgotOtpModel->insert([
                        'user_id' => $user['id'],
                        'otp'     => $otpValue,
                    ]);
                }

                $session->set('forgot_stage', 'otp');

                return redirect()->to('/auth/forgot-password')
                    ->with('success', 'OTP has been generated. For demo, your OTP is 123456.');
            }

            // STEP 2: Verify OTP
            if ($step === 'otp') {
                $userId = (int) $session->get('forgot_user_id');
                $inputOtp = (string) $this->request->getPost('otp');

                if (! $userId) {
                    return redirect()->to('/auth/forgot-password')->with('error', 'Session expired. Please try again.');
                }

                $record = $this->forgotOtpModel->where('user_id', $userId)->first();

                if (! $record) {
                    return redirect()->back()->with('error', 'OTP expired. Please request again.');
                }

                if ($inputOtp !== $record['otp']) {
                    return redirect()->back()->with('error', 'Invalid OTP entered.');
                }

                // OTP verified – delete row as per requirement
                $this->forgotOtpModel->delete($record['id']);

                $session->set('forgot_stage', 'reset');

                return redirect()->to('/auth/forgot-password')
                    ->with('success', 'OTP verified. Please set your new password.');
            }

            // Optional: Resend OTP (update same row)
            if ($step === 'resend') {
                $userId = (int) $session->get('forgot_user_id');

                if (! $userId) {
                    return redirect()->to('/auth/forgot-password')->with('error', 'Session expired. Please start again.');
                }

                $otpValue = '123456';
                $existing = $this->forgotOtpModel->where('user_id', $userId)->first();

                if ($existing) {
                    $this->forgotOtpModel->update($existing['id'], ['otp' => $otpValue]);
                } else {
                    $this->forgotOtpModel->insert([
                        'user_id' => $userId,
                        'otp'     => $otpValue,
                    ]);
                }

                $session->set('forgot_stage', 'otp');

                return redirect()->to('/auth/forgot-password')
                    ->with('success', 'OTP resent. For demo, your OTP is 123456.');
            }

            // STEP 3: Reset password
            if ($step === 'reset') {
                $userId = (int) $session->get('forgot_user_id');

                if (! $userId) {
                    return redirect()->to('/auth/forgot-password')->with('error', 'Session expired. Please try again.');
                }

                $password     = (string) $this->request->getPost('password');
                $passwordConf = (string) $this->request->getPost('password_confirm');

                if ($password === '' || $passwordConf === '') {
                    return redirect()->back()->with('error', 'Please enter and confirm your new password.');
                }

                if ($password !== $passwordConf) {
                    return redirect()->back()->with('error', 'Passwords do not match.');
                }

                if (strlen($password) < 6) {
                    return redirect()->back()->with('error', 'Password must be at least 6 characters long.');
                }

                $this->userModel->update($userId, [
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);

                // Clear session data for flow
                $session->remove(['forgot_user_id', 'forgot_stage']);

                return redirect()->to('/auth/login')->with('success', 'Password has been reset successfully. Please login with your new password.');
            }
        }

        $data['stage'] = $stage;

        return view('layout/header')
            . view('auth/forgot_password', $data)
            . view('layout/footer');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out successfully');
    }
}


