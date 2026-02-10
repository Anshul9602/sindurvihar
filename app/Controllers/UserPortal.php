<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\UserModel;

class UserPortal extends BaseController
{
    protected $userModel;
    protected $applicationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->applicationModel = new ApplicationModel();
    }

    public function dashboard()
    {
        return view('layout/header')
            . view('user/dashboard')
            . view('layout/footer');
    }

    public function eligibility()
    {
        return view('layout/header')
            . view('user/eligibility')
            . view('layout/footer');
    }

    public function application()
    {
        return view('layout/header')
            . view('user/application')
            . view('layout/footer');
    }

    public function submitApplication()
    {
        if ($this->request->getMethod() !== 'post') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
            }
            return redirect()->to('/user/application');
        }

        $data = [
            'user_id' => $this->request->getPost('user_id') ?: 1, // TODO: Get from session
            'full_name' => $this->request->getPost('full_name'),
            'aadhaar' => $this->request->getPost('aadhaar'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city') ?: '',
            'state' => $this->request->getPost('state') ?: '',
            'income' => $this->request->getPost('income'),
            'income_category' => $this->request->getPost('income_category'),
            'status' => 'submitted'
        ];

        if ($this->applicationModel->insert($data)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Application submitted successfully',
                    'application_id' => $this->applicationModel->getInsertID()
                ]);
            }
            return redirect()->to('/user/documents')->with('success', 'Application submitted successfully');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to submit application'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Failed to submit application');
        }
    }

    public function applicationStatus()
    {
        return view('layout/header')
            . view('user/application_status')
            . view('layout/footer');
    }

    public function documents()
    {
        return view('layout/header')
            . view('user/documents')
            . view('layout/footer');
    }

    public function payment()
    {
        return view('layout/header')
            . view('user/payment')
            . view('layout/footer');
    }

    public function profile()
    {
        return view('layout/header')
            . view('user/profile')
            . view('layout/footer');
    }

    public function lotteryResults()
    {
        return view('layout/header')
            . view('user/lottery_results')
            . view('layout/footer');
    }

    public function allotment()
    {
        return view('layout/header')
            . view('user/allotment')
            . view('layout/footer');
    }

    public function refundStatus()
    {
        return view('layout/header')
            . view('user/refund_status')
            . view('layout/footer');
    }
}


