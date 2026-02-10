<?php

namespace App\Controllers;

use App\Models\ApplicationModel;

class AdminPortal extends BaseController
{
    protected $applicationModel;

    public function __construct()
    {
        $this->applicationModel = new ApplicationModel();
    }

    public function login()
    {
        return view('layout/header')
            . view('admin/login')
            . view('layout/footer');
    }

    public function dashboard()
    {
        $data['totalApplications'] = $this->applicationModel->countAllResults();
        $data['pendingApplications'] = $this->applicationModel->where('status', 'submitted')->countAllResults();
        $data['verifiedApplications'] = $this->applicationModel->where('status', 'verified')->countAllResults();
        
        return view('layout/header')
            . view('admin/dashboard', $data)
            . view('layout/footer');
    }

    public function applications()
    {
        $data['applications'] = $this->applicationModel->getApplicationsWithUsers();
        
        return view('layout/header')
            . view('admin/applications', $data)
            . view('layout/footer');
    }

    public function applicationDetail($id)
    {
        $data['application'] = $this->applicationModel->getApplicationWithUser($id);
        
        if (!$data['application']) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found');
        }
        
        return view('layout/header')
            . view('admin/application_detail', $data)
            . view('layout/footer');
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
        return view('layout/header')
            . view('admin/verification')
            . view('layout/footer');
    }

    public function lottery()
    {
        return view('layout/header')
            . view('admin/lottery')
            . view('layout/footer');
    }

    public function allotments()
    {
        return view('layout/header')
            . view('admin/allotments')
            . view('layout/footer');
    }

    public function payments()
    {
        return view('layout/header')
            . view('admin/payments')
            . view('layout/footer');
    }

    public function schemes()
    {
        return view('layout/header')
            . view('admin/schemes')
            . view('layout/footer');
    }

    public function reports()
    {
        return view('layout/header')
            . view('admin/reports')
            . view('layout/footer');
    }
}


