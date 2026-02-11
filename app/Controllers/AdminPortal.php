<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\PaymentModel;
use App\Models\PlotModel;

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
        
        // Count applications without payment records (pending payment)
        // Application fee is ₹1,000 per application
        $applicationFee = 1000;
        $allApplications = $this->applicationModel->findAll();
        $applicationsWithPayment = $paymentModel->select('application_id')->distinct()->findAll();
        $applicationIdsWithPayment = array_column($applicationsWithPayment, 'application_id');
        $pendingPaymentCount = 0;
        $pendingPaymentAmount = 0;
        foreach ($allApplications as $app) {
            if (!in_array($app['id'], $applicationIdsWithPayment)) {
                $pendingPaymentCount++;
                $pendingPaymentAmount += $applicationFee;
            }
        }
        $data['pendingPaymentApplications'] = $pendingPaymentCount;
        $data['pendingPaymentAmount'] = $pendingPaymentAmount;
        
        // Payment statistics
        $data['totalPayments'] = $paymentModel->countAllResults();
        $data['totalAmount'] = $paymentModel->selectSum('amount')->first()['amount'] ?? 0;
        $data['pendingPayments'] = $paymentModel->where('status', 'pending')->countAllResults();
        $pendingFromRecords = $paymentModel->selectSum('amount')->where('status', 'pending')->first()['amount'] ?? 0;
        
        // Total pending amount = pending from payment records + pending from applications without payment
        $data['pendingAmount'] = $pendingFromRecords + $pendingPaymentAmount;
        
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
        $applications = $this->applicationModel->getApplicationsWithUsers();
        
        // Get payment status for each application
        $paymentModel = new PaymentModel();
        foreach ($applications as &$app) {
            $payment = $paymentModel
                ->where('application_id', $app['id'])
                ->orderBy('created_at', 'DESC')
                ->first();
            
            if ($payment) {
                $app['payment_status'] = $payment['status'];
                $app['has_payment'] = true;
            } else {
                $app['payment_status'] = 'pending';
                $app['has_payment'] = false;
            }
        }
        
        $data['applications'] = $applications;
        
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

    public function editApplication($id)
    {
        $data['application'] = $this->applicationModel->getApplicationWithUser($id);
        
        if (!$data['application']) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found');
        }
        
        return view('layout/admin_header')
            . view('admin/application_edit', $data)
            . view('layout/admin_footer');
    }

    public function updateApplication($id)
    {
        $application = $this->applicationModel->find($id);
        
        if (!$application) {
            return redirect()->to('/admin/applications')->with('error', 'Application not found');
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'aadhaar' => $this->request->getPost('aadhaar'),
            'father_husband_name' => $this->request->getPost('father_husband_name'),
            'age' => $this->request->getPost('age'),
            'mobile' => $this->request->getPost('mobile'),
            'address' => $this->request->getPost('address'),
            'tehsil' => $this->request->getPost('tehsil'),
            'district' => $this->request->getPost('district'),
            'state' => $this->request->getPost('state'),
            'income' => $this->request->getPost('income'),
            'income_category' => $this->request->getPost('income_category'),
            'status' => $this->request->getPost('status'),
        ];

        if ($this->applicationModel->update($id, $data)) {
            return redirect()->to('/admin/applications/' . $id)->with('success', 'Application updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update application');
        }
    }

    public function rejectApplication($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        $reason = $this->request->getJSON(true)['reason'] ?? $this->request->getPost('reason');
        if (empty($reason)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Rejection reason is required']);
        }

        // Get admin ID from session (if available)
        $adminId = session()->get('admin_id') ?? null;

        // Save admin action
        $actionModel = new \App\Models\AdminActionModel();
        $actionData = [
            'application_id' => $id,
            'admin_id' => $adminId,
            'action_type' => 'rejected',
            'reason' => $reason,
            'confirmed' => 1,
        ];

        if (!$actionModel->insert($actionData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save admin action']);
        }

        // Update application status
        if ($this->applicationModel->update($id, ['status' => 'rejected'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Application rejected successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update application status'
            ]);
        }
    }

    public function verifyApplication($id)
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        $data = $this->request->getJSON(true) ?? [];
        $confirmed = $data['confirmed'] ?? $this->request->getPost('confirmed');

        if (!$confirmed) {
            return $this->response->setJSON(['success' => false, 'message' => 'Verification confirmation is required']);
        }

        // Get admin ID from session (if available)
        $adminId = session()->get('admin_id') ?? null;

        // Save admin action
        $actionModel = new \App\Models\AdminActionModel();
        $actionData = [
            'application_id' => $id,
            'admin_id' => $adminId,
            'action_type' => 'verified',
            'confirmed' => 1,
        ];

        if (!$actionModel->insert($actionData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to save admin action']);
        }

        // Update application status
        if ($this->applicationModel->update($id, ['status' => 'verified'])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Application verified successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update application status'
            ]);
        }
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
        $payments = $paymentModel->orderBy('created_at', 'DESC')->findAll();
        
        // Get all applications
        $allApplications = $this->applicationModel->findAll();
        
        // Get application IDs that have payment records
        $applicationsWithPayment = $paymentModel->select('application_id')->distinct()->findAll();
        $applicationIdsWithPayment = array_column($applicationsWithPayment, 'application_id');
        
        // Application fee is ₹1,000 per application
        $applicationFee = 1000;
        
        // Add pending payment records for applications without payment
        foreach ($allApplications as $app) {
            if (!in_array($app['id'], $applicationIdsWithPayment)) {
                // Create a virtual payment record for applications without payment
                $payments[] = [
                    'id' => null, // No payment ID yet
                    'application_id' => $app['id'],
                    'amount' => $applicationFee,
                    'status' => 'pending',
                    'transaction_ref' => null,
                    'created_at' => $app['created_at'] ?? date('Y-m-d H:i:s'),
                    'is_virtual' => true, // Flag to indicate this is a virtual record
                ];
            }
        }
        
        // Sort by created_at descending
        usort($payments, function($a, $b) {
            $dateA = strtotime($a['created_at'] ?? '1970-01-01');
            $dateB = strtotime($b['created_at'] ?? '1970-01-01');
            return $dateB - $dateA;
        });
        
        $data['payments'] = $payments;
        
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

    public function plots()
    {
        $plotModel = new PlotModel();
        $data['plots'] = $plotModel->orderBy('created_at', 'DESC')->findAll();
        
        // Get category-wise counts
        $categories = $plotModel->select('category, SUM(quantity) as total_quantity, SUM(available_quantity) as total_available')
                                ->groupBy('category')
                                ->findAll();
        $data['categoryStats'] = $categories;
        
        return view('layout/admin_header')
            . view('admin/plots', $data)
            . view('layout/admin_footer');
    }

    public function addPlot()
    {
        $plotModel = new PlotModel();
      
        // Handle form submission (POST)
        if (strtolower($this->request->getMethod()) === 'post') {
            
            // Common data for all category/quantity rows
            $baseData = [
                'plot_name'   => $this->request->getPost('plot_name'),
                'plot_number' => $this->request->getPost('plot_number'),
                'dimensions'  => $this->request->getPost('dimensions'),
                'area'        => $this->request->getPost('area'),
                'location'    => $this->request->getPost('location'),
                'price'       => $this->request->getPost('price'),
                'status'      => $this->request->getPost('status') ?? 'available',
                'description' => $this->request->getPost('description'),
            ];

            // Handle image upload once and reuse path for all rows
            $imagePath = null;
            $imageFile = $this->request->getFile('plot_image');
            if ($imageFile && $imageFile->isValid() && ! $imageFile->hasMoved()) {
                $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'plots' . DIRECTORY_SEPARATOR;
                if (! is_dir($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                $newName = $imageFile->getRandomName();
                $imageFile->move($uploadPath, $newName);
                $imagePath = 'uploads/plots/' . $newName;
            }

            $categories = $this->request->getPost('categories') ?? [];
            $totalQty   = (int) ($this->request->getPost('total_quantity') ?? 0);

            $pairs  = []; // for string like "EWS:2,LIG:3"
            $sumQty = 0;

            foreach ($categories as $row) {
                $category = trim($row['category'] ?? '');
                $qty      = (int) ($row['quantity'] ?? 0);

                if ($category === '' || $qty <= 0) {
                    continue;
                }

                $pairs[] = $category . ':' . $qty;
                $sumQty += $qty;
            }

            if (empty($pairs)) {
                return redirect()->back()->withInput()->with('error', lang('App.adminPlotAddFailed'));
            }

            // If total quantity provided, validate against sum of category quantities
            if ($totalQty > 0 && $sumQty !== $totalQty) {
                return redirect()->back()->withInput()->with('error', lang('App.adminPlotTotalQuantityMismatch'));
            }

            // Build single row: store category-wise quantity as string in category column
            $data = $baseData;
            $data['category']           = implode(',', $pairs);   // e.g. "EWS:2,LIG:3,SC:5"
            $data['quantity']           = $sumQty;
            $data['available_quantity'] = $sumQty;
            if ($imagePath) {
                $data['plot_image'] = $imagePath;
            }

            if ($plotModel->insert($data)) {
                return redirect()->to('/admin/plots')->with('success', lang('App.adminPlotAddedSuccess'));
            }

            // Collect validation/db errors for debugging
            $errors  = $plotModel->errors();
            $dbError = $plotModel->db->error();
            $message = lang('App.adminPlotAddFailed');
            if (! empty($errors)) {
                $message .= ' (' . implode(' ', $errors) . ')';
            } elseif (! empty($dbError['message'])) {
                $message .= ' (' . $dbError['message'] . ')';
            }

            return redirect()->back()->withInput()->with('error', $message);
        }

        return view('layout/admin_header')
            . view('admin/plot_add', [])
            . view('layout/admin_footer');
    }

    public function editPlot($id)
    {
        $plotModel = new PlotModel();
        $plot = $plotModel->find($id);
        
        if (!$plot) {
            return redirect()->to('/admin/plots')->with('error', lang('App.adminPlotNotFound'));
        }

        if ($this->request->getMethod() === 'post') {
            return $this->updatePlot($id);
        }

        $data['plot'] = $plot;
        return view('layout/admin_header')
            . view('admin/plot_edit', $data)
            . view('layout/admin_footer');
    }

    public function updatePlot($id)
    {
        $plotModel = new PlotModel();
        $plot = $plotModel->find($id);
        
        if (!$plot) {
            return redirect()->to('/admin/plots')->with('error', lang('App.adminPlotNotFound'));
        }

        // Base fields (common for all categories)
        $baseData = [
            'plot_name'   => $this->request->getPost('plot_name'),
            'plot_number' => $this->request->getPost('plot_number'),
            'dimensions'  => $this->request->getPost('dimensions'),
            'area'        => $this->request->getPost('area'),
            'location'    => $this->request->getPost('location'),
            'price'       => $this->request->getPost('price'),
            'status'      => $this->request->getPost('status') ?? 'available',
            'description' => $this->request->getPost('description'),
        ];

        // Decode category-wise quantities from form
        $categories = $this->request->getPost('categories') ?? [];
        $totalQty   = (int) ($this->request->getPost('total_quantity') ?? 0);

        $pairs  = [];
        $sumQty = 0;

        foreach ($categories as $row) {
            $category = trim($row['category'] ?? '');
            $qty      = (int) ($row['quantity'] ?? 0);

            if ($category === '' || $qty <= 0) {
                continue;
            }

            $pairs[] = $category . ':' . $qty;
            $sumQty += $qty;
        }

        if (empty($pairs)) {
            return redirect()->back()->withInput()->with('error', lang('App.adminPlotUpdateFailed'));
        }

        if ($totalQty > 0 && $sumQty !== $totalQty) {
            return redirect()->back()->withInput()->with('error', lang('App.adminPlotTotalQuantityMismatch'));
        }

        $data = $baseData;
        $data['category']           = implode(',', $pairs);
        $data['quantity']           = $sumQty;
        $data['available_quantity'] = $sumQty;

        // Handle image upload
        $imageFile = $this->request->getFile('plot_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete old image if exists
            if (!empty($plot['plot_image']) && file_exists(FCPATH . $plot['plot_image'])) {
                unlink(FCPATH . $plot['plot_image']);
            }
            
            $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'plots' . DIRECTORY_SEPARATOR;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            $newName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $newName);
            $data['plot_image'] = 'uploads/plots/' . $newName;
        }

        if ($plotModel->update($id, $data)) {
            return redirect()->to('/admin/plots')->with('success', lang('App.adminPlotUpdatedSuccess'));
        }

        $errors  = $plotModel->errors();
        $dbError = $plotModel->db->error();
        $message = lang('App.adminPlotUpdateFailed');
        if (! empty($errors)) {
            $message .= ' (' . implode(' ', $errors) . ')';
        } elseif (! empty($dbError['message'])) {
            $message .= ' (' . $dbError['message'] . ')';
        }

        return redirect()->back()->withInput()->with('error', $message);
    }

    public function deletePlot($id)
    {
        $plotModel = new PlotModel();
        $plot = $plotModel->find($id);
        
        if (!$plot) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => lang('App.adminPlotNotFound')]);
            }
            return redirect()->to('/admin/plots')->with('error', lang('App.adminPlotNotFound'));
        }

        // Delete image if exists
        if (!empty($plot['plot_image']) && file_exists(FCPATH . $plot['plot_image'])) {
            unlink(FCPATH . $plot['plot_image']);
        }

        if ($plotModel->delete($id)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => lang('App.adminPlotDeletedSuccess')]);
            }
            return redirect()->to('/admin/plots')->with('success', lang('App.adminPlotDeletedSuccess'));
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => lang('App.adminPlotDeleteFailed')]);
            }
            return redirect()->to('/admin/plots')->with('error', lang('App.adminPlotDeleteFailed'));
        }
    }
}


