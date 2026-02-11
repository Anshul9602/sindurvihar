<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\PaymentModel;

class AdminPortal extends BaseController
{
    protected $applicationModel;
    protected $adminModel;

    public function __construct()
    {
        $this->applicationModel = new ApplicationModel();
        $this->adminModel       = new AdminModel();
    }

    public function login()
    {
        return view('layout/header')
            . view('admin/login')
            . view('layout/footer');
    }

    public function register()
    {
        // Admin registration (for creating admin accounts)
        if ($this->request->getPost('mobile') !== null) {
            $name     = (string) $this->request->getPost('name');
            $mobile   = (string) $this->request->getPost('mobile');
            $email    = (string) $this->request->getPost('email');
            $password = (string) $this->request->getPost('password');

            if ($mobile === '' || $password === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Mobile and password are required.');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $data = [
                'name'          => $name,
                'mobile'        => $mobile,
                'email'         => $email,
                'password_hash' => $hash,
                'role'          => 'admin',
            ];

            $ok = $this->adminModel->insert($data);
            if (! $ok) {
                $dbError  = $this->adminModel->db->error();
                $errorMsg = $dbError['message'] ?? 'Failed to register admin.';

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }

            return redirect()->to('/admin/login')
                ->with('success', 'Admin registered successfully. You can now login.');
        }

        return view('layout/header')
            . view('admin/register')
            . view('layout/footer');
    }

    public function dashboard()
    {
        $userModel = new UserModel();
        $paymentModel = new PaymentModel();
        
        // User statistics
        $data['totalUsers'] = $userModel->countAllResults();
        $data['activeUsers'] = $userModel->countAllResults(); // Assuming all are active
        
        // Application statistics
        $data['totalApplications'] = $this->applicationModel->countAllResults();
        $data['pendingApplications'] = $this->applicationModel->where('status', 'submitted')->countAllResults();
        $data['verifiedApplications'] = $this->applicationModel->where('status', 'verified')->countAllResults();
        
        // Payment statistics
        $data['totalPayments'] = $paymentModel->countAllResults();
        $data['totalAmount'] = $paymentModel->selectSum('amount')->first()['amount'] ?? 0;
        $data['pendingPayments'] = $paymentModel->where('status', 'pending')->countAllResults();
        $data['pendingAmount'] = $paymentModel->selectSum('amount')->where('status', 'pending')->first()['amount'] ?? 0;
        
        // Today's statistics
        $today = date('Y-m-d');
        $data['todayPayments'] = $paymentModel->like('created_at', $today)->countAllResults();
        $data['todayAmount'] = $paymentModel->selectSum('amount')->like('created_at', $today)->first()['amount'] ?? 0;
        
        return view('layout/admin_header')
            . view('admin/dashboard', $data)
            . view('layout/admin_footer');
    }

    public function applications()
    {
        $data['applications'] = $this->applicationModel->getApplicationsWithUsers();
        
        return view('layout/admin_header')
            . view('admin/applications', $data)
            . view('layout/admin_footer');
    }

    public function applicationDetail($id)
    {
        $data['application'] = $this->applicationModel->getApplicationWithUser($id);
        
        if (!$data['application']) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found');
        }
        
        // Fetch documents for this application
        $documentModel = new \App\Models\ApplicationDocumentModel();
        $data['documents'] = $documentModel
            ->where('application_id', $id)
            ->first();
        
        // Fetch payment details for this application
        $paymentModel = new PaymentModel();
        $data['payment'] = $paymentModel
            ->where('application_id', $id)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        return view('layout/admin_header')
            . view('admin/application_detail', $data)
            . view('layout/admin_footer');
    }

    public function updateApplicationStatus()
    {
        if ($this->request->getMethod() !== 'post') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
            }
            return redirect()->to('/admin/applications');
        }

        $id = $this->request->getPost('application_id');
        $status = $this->request->getPost('status');

        if ($this->applicationModel->update($id, ['status' => $status])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Application status updated successfully'
                ]);
            }
            return redirect()->back()->with('success', 'Application status updated successfully');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update application status'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to update application status');
        }
    }

    public function verification()
    {
        $data['applications'] = $this->applicationModel
            ->whereIn('status', ['submitted', 'under_verification'])
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        return view('layout/admin_header')
            . view('admin/verification', $data)
            . view('layout/admin_footer');
    }

    public function lottery()
    {
        return view('layout/admin_header')
            . view('admin/lottery')
            . view('layout/admin_footer');
    }

    public function allotments()
    {
        return view('layout/admin_header')
            . view('admin/allotments')
            . view('layout/admin_footer');
    }

    public function payments()
    {
        $paymentModel = new PaymentModel();
        $data['payments'] = $paymentModel->orderBy('created_at', 'DESC')->findAll();
        
        return view('layout/admin_header')
            . view('admin/payments', $data)
            . view('layout/admin_footer');
    }

    public function schemes()
    {
        return view('layout/admin_header')
            . view('admin/schemes')
            . view('layout/admin_footer');
    }

    public function reports()
    {
        return view('layout/admin_header')
            . view('admin/reports')
            . view('layout/admin_footer');
    }
}


