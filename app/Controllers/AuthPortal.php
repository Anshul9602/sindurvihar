<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthPortal extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

            if (empty($mobile) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Mobile and password are required');
            }

            // Check if mobile already exists
            $existingUser = $this->userModel->where('mobile', $mobile)->first();
            if ($existingUser) {
                return redirect()->back()->withInput()->with('error', 'Mobile number already registered. Please login.');
            }

            // Validate mobile number (10 digits)
            if (!preg_match('/^[0-9]{10}$/', $mobile)) {
                return redirect()->back()->withInput()->with('error', 'Please enter a valid 10-digit mobile number');
            }

            // Validate password (minimum 6 characters)
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password must be at least 6 characters long');
            }

            // Create user
            $data = [
                'mobile'        => $mobile,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'name'          => 'User ' . substr($mobile, -4),
                'language'      => 'en',
            ];

            if ($this->userModel->insert($data)) {
                $userId = $this->userModel->getInsertID();

                // Auto-login after registration
                session()->set([
                    'user_id'     => $userId,
                    'user_name'   => $data['name'],
                    'user_mobile' => $mobile,
                    'user_email'  => '',
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'You have been logged out successfully');
    }
}


