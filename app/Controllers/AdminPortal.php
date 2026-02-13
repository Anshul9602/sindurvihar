<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\PaymentModel;
use App\Models\PlotModel;
use App\Models\AllotmentModel;

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
            $role     = (string) ($this->request->getPost('role') ?? 'admin');

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
                'role'          => $role !== '' ? $role : 'admin',
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

    /**
     * Admin settings page - add new admin users from inside the admin panel
     */
    public function settings()
    {
        // Handle add-admin form submit
        if (strtolower($this->request->getMethod()) === 'post') {
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
                $errorMsg = $dbError['message'] ?? 'Failed to create admin user.';

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }

            return redirect()->to('/admin/settings')
                ->with('success', 'Admin user added successfully.');
        }

        // List existing admins (simple overview)
        $data['admins'] = $this->adminModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('layout/admin_header')
            . view('admin/settings', $data)
            . view('layout/admin_footer');
    }

    /**
     * Update an admin's password from settings page
     */
    public function updateAdminPassword($id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/admin/settings');
        }

        $admin = $this->adminModel->find($id);
        if (! $admin) {
            return redirect()->to('/admin/settings')->with('error', 'Admin not found.');
        }

        $password     = (string) $this->request->getPost('password');
        $passwordConf = (string) $this->request->getPost('password_confirm');

        if ($password === '' || $passwordConf === '') {
            return redirect()->back()->with('error', 'Please enter and confirm the new password.');
        }

        if ($password !== $passwordConf) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password must be at least 6 characters long.');
        }

        $this->adminModel->update($id, [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/settings')->with('success', 'Admin password updated successfully.');
    }

    public function dashboard()
    {
        $userModel = new UserModel();
        $paymentModel = new PaymentModel();
        $plotModel    = new PlotModel();
        
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
        
        // Payment statistics (amount and status based)
        $data['totalPayments'] = $paymentModel->countAllResults();
        $data['totalAmount']   = $paymentModel->selectSum('amount')->first()['amount'] ?? 0;
        $data['pendingPayments'] = $paymentModel->where('status', 'pending')->countAllResults();
        $pendingFromRecords      = $paymentModel->selectSum('amount')->where('status', 'pending')->first()['amount'] ?? 0;
        $completedFromRecords    = max(($data['totalAmount'] ?? 0) - $pendingFromRecords, 0);
        $data['completedAmount'] = $completedFromRecords;
        $data['pendingAmountOnlyPayments'] = $pendingFromRecords;
        
        // Total pending amount across system = pending from payment records + pending from applications without payment
        $data['pendingAmount'] = $pendingFromRecords + $pendingPaymentAmount;
        
        // Today's statistics
        $today = date('Y-m-d');
        $data['todayPayments'] = $paymentModel->like('created_at', $today)->countAllResults();
        $data['todayAmount'] = $paymentModel->selectSum('amount')->like('created_at', $today)->first()['amount'] ?? 0;

        // Plot statistics
        $data['totalPlots']      = $plotModel->countAllResults();
        $data['availablePlots']  = $plotModel->where('status', 'available')->countAllResults();
        $data['allocatedPlots']  = $plotModel->where('status', 'allocated')->countAllResults();
        $data['reservedPlots']   = $plotModel->where('status', 'reserved')->countAllResults();
        
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

    /**
     * Registered users list (frontend users)
     */
    public function users()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->orderBy('created_at', 'DESC')->findAll();

        return view('layout/admin_header')
            . view('admin/users', $data)
            . view('layout/admin_footer');
    }

    /**
     * Single user detail view
     */
    public function userDetail($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data['user'] = $user;

        return view('layout/admin_header')
            . view('admin/user_detail', $data)
            . view('layout/admin_footer');
    }

    /**
     * Delete a registered user
     */
    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'User deleted successfully.');
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
        // Detect POST by presence of reason field (same pattern as other methods)
        $jsonData = $this->request->getJSON(true);
        $reason = null;
        
        if ($jsonData && isset($jsonData['reason'])) {
            $reason = $jsonData['reason'];
        } else {
            $reason = $this->request->getPost('reason');
        }
        
        // If no reason provided, it's not a POST submission
        if ($reason === null || $reason === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method or missing reason']);
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

        $reason = trim((string)$reason);
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
        // Detect POST by presence of confirmed field (same pattern as other methods)
        $jsonData = $this->request->getJSON(true);
        $confirmed = null;
        
        if ($jsonData && isset($jsonData['confirmed'])) {
            $confirmed = $jsonData['confirmed'];
        } else {
            $confirmed = $this->request->getPost('confirmed');
        }
        
        // If no confirmed field provided, it's not a POST submission
        if ($confirmed === null) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method or missing confirmation']);
        }

        $application = $this->applicationModel->find($id);
        if (!$application) {
            return $this->response->setJSON(['success' => false, 'message' => 'Application not found']);
        }

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
        $paymentModel = new PaymentModel();
        $documentModel = new \App\Models\ApplicationDocumentModel();
        
        // Get all applications that are not verified or rejected (pending verification)
        $allPendingApplications = $this->applicationModel
            ->whereNotIn('status', ['verified', 'rejected'])
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Filter applications that have both payment completed AND documents submitted
        $pendingApplications = [];
        foreach ($allPendingApplications as $app) {
            // Check if payment exists and is successful
            $payment = $paymentModel
                ->where('application_id', $app['id'])
                ->where('status', 'success')
                ->first();
            
            // Check if documents exist
            $documents = $documentModel
                ->where('application_id', $app['id'])
                ->first();
            
            // Only include if both payment and documents exist
            if ($payment && $documents) {
                $pendingApplications[] = $app;
            }
        }
        
        $data['applications'] = $pendingApplications;
        
        return view('layout/admin_header')
            . view('admin/verification', $data)
            . view('layout/admin_footer');
    }

    public function verifiedApplications()
    {
        // Get verified applications
        $verifiedApplications = $this->applicationModel
            ->where('status', 'verified')
            ->orderBy('updated_at', 'DESC')
            ->findAll();
        
        $data['applications'] = $verifiedApplications;
        
        return view('layout/admin_header')
            . view('admin/verified_applications', $data)
            . view('layout/admin_footer');
    }

    public function lottery()
    {
        // List all verified applications with user + category details
        $applications = $this->applicationModel
            ->select('applications.*, users.name as user_name, users.mobile, users.category as user_category')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->where('applications.status', 'verified')
            ->orderBy('applications.created_at', 'ASC')
            ->findAll();

        // Get available plots grouped by category
        $plotModel = new PlotModel();
        $availablePlots = $plotModel
            ->where('status', 'available')
            ->orderBy('category', 'ASC')
            ->findAll();

        $plotsByCategory = [];
        foreach ($availablePlots as $plot) {
            $cat = $plot['category'] ?? 'Unknown';
            if (! isset($plotsByCategory[$cat])) {
                $plotsByCategory[$cat] = [
                    'count' => 0,
                    'examples' => [],
                ];
            }
            $plotsByCategory[$cat]['count']++;
            if (count($plotsByCategory[$cat]['examples']) < 2) {
                $plotsByCategory[$cat]['examples'][] = $plot['plot_number'] ?? $plot['plot_name'] ?? '';
            }
        }

        $data = [
            'applications'     => $applications,
            'plotsByCategory'  => $plotsByCategory,
        ];

        return view('layout/admin_header')
            . view('admin/lottery', $data)
            . view('layout/admin_footer');
    }

    /**
     * Run a lottery round.
     *
     * Picks a random verified application that has a matching plot category
     * and creates an allotment. Also updates plot availability.
     */
    public function runLottery()
    {
        if ($this->request->getPost('round_number') === null) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $roundNumber = trim((string) $this->request->getPost('round_number'));
        $roundName   = trim((string) $this->request->getPost('round_name'));
        $confirmed   = (bool) $this->request->getPost('confirmed');

        if ($roundNumber === '' || $roundName === '') {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Please enter lottery round number and name.']);
        }

        if (! $confirmed) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Please confirm that you want to run the lottery.']);
        }

        $plotModel      = new PlotModel();
        $allotmentModel = new AllotmentModel();

        // Get verified applications with user category (for matching with plot category)
        $applications = $this->applicationModel
            ->select('applications.*, users.name as user_name, users.mobile, users.category as user_category')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->where('applications.status', 'verified')
            ->orderBy('applications.created_at', 'ASC')
            ->findAll();

        if (! $applications) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'No verified applications available for lottery.']);
        }

        // Load available plots indexed by category
        $availablePlots = $plotModel
            ->where('status', 'available')
            ->orderBy('category', 'ASC')
            ->findAll();

        if (! $availablePlots) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'No available plots found. Please add plots first.']);
        }

        $plotsByCategory = [];
        foreach ($availablePlots as $plot) {
            $cat = $plot['category'] ?? null;
            if (! $cat) {
                continue;
            }
            $plotsByCategory[$cat][] = $plot;
        }

        // Filter applications that have at least one matching plot category
        $eligibleApps = [];
        foreach ($applications as $app) {
            $userCategory    = $app['user_category'] ?? null;   // main category from registration
            $serviceCategory = $app['income_category'] ?? null; // service category from application

            $hasMatch = false;
            if ($userCategory && ! empty($plotsByCategory[$userCategory])) {
                $hasMatch = true;
            } elseif ($serviceCategory && ! empty($plotsByCategory[$serviceCategory])) {
                $hasMatch = true;
            }

            if ($hasMatch) {
                $eligibleApps[] = $app;
            }
        }

        if (! $eligibleApps) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'No eligible applications found with matching plot categories.',
                ]);
        }

        // Pick a random eligible application
        $winnerIndex = array_rand($eligibleApps);
        $winner      = $eligibleApps[$winnerIndex];

        // Decide which category we are using for this winner (primary category first, then service category)
        $winnerCat = null;
        if (! empty($winner['user_category']) && ! empty($plotsByCategory[$winner['user_category']])) {
            $winnerCat = $winner['user_category'];
        } elseif (! empty($winner['income_category']) && ! empty($plotsByCategory[$winner['income_category']])) {
            $winnerCat = $winner['income_category'];
        }

        if (! $winnerCat) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'Selected winner does not match any available plot category.',
                ]);
        }

        // Pick a random plot from the winner's category
        $plotsForCat = $plotsByCategory[$winnerCat];
        $plotIndex   = array_rand($plotsForCat);
        $plot        = $plotsForCat[$plotIndex];

        // Create allotment
        // Create allotment using query builder to avoid model field-protection issues
        $db = \Config\Database::connect();
        $allotmentData = [
            'application_id' => $winner['id'],
            'plot_number'    => $plot['plot_number'],
            'block_name'     => $plot['location'] ?? null,
            'status'         => 'provisional',
            'created_at'     => date('Y-m-d H:i:s'),
        ];

        if (! $db->table('allotments')->insert($allotmentData)) {
            $dbError = $db->error();
            $msg = 'Failed to create allotment.';
            if (! empty($dbError['message'])) {
                $msg .= ' DB Error: ' . $dbError['message'];
            }
            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => $msg]);
        }

        $allotmentId = $db->insertID();

        // Mark plot as allotted / decrement available quantity if tracked
        if (array_key_exists('available_quantity', $plot) && $plot['available_quantity'] !== null) {
            $newQty = max(0, (int) $plot['available_quantity'] - 1);
            $update = ['available_quantity' => $newQty];
            if ($newQty === 0) {
                $update['status'] = 'allotted';
            }
            if (! $plotModel->update($plot['id'], $update)) {
                $dbError = $plotModel->db->error();
                $msg = 'Failed to update plot availability.';
                if (! empty($dbError['message'])) {
                    $msg .= ' DB Error: ' . $dbError['message'];
                }
                return $this->response->setStatusCode(500)
                    ->setJSON(['success' => false, 'message' => $msg]);
            }
        } else {
            if (! $plotModel->update($plot['id'], ['status' => 'allotted'])) {
                $dbError = $plotModel->db->error();
                $msg = 'Failed to update plot status.';
                if (! empty($dbError['message'])) {
                    $msg .= ' DB Error: ' . $dbError['message'];
                }
                return $this->response->setStatusCode(500)
                    ->setJSON(['success' => false, 'message' => $msg]);
            }
        }

        // Optionally update application status to selected
        $this->applicationModel->update($winner['id'], ['status' => 'selected']);

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Lottery run successfully.',
            'round_number'  => $roundNumber,
            'round_name'    => $roundName,
            'winner'        => [
                'application_id' => $winner['id'],
                'name'           => $winner['user_name'],
                'mobile'         => $winner['mobile'],
                'category'       => $winner['user_category'],
                'service_cat'    => $winner['income_category'] ?? null,
            ],
            'plot'          => [
                'id'           => $plot['id'],
                'plot_number'  => $plot['plot_number'],
                'category'     => $plot['category'],
                'location'     => $plot['location'] ?? null,
            ],
            'allotment_id'  => $allotmentId,
        ]);
    }

    public function allotments()
    {
        $allotmentModel = new AllotmentModel();

        // Fetch allotments with application and user details
        $allotments = $allotmentModel
            ->select('allotments.*, applications.id as application_id, applications.full_name, users.name as user_name')
            ->join('applications', 'applications.id = allotments.application_id', 'left')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->orderBy('allotments.created_at', 'DESC')
            ->findAll();

        $data['allotments'] = $allotments;

        return view('layout/admin_header')
            . view('admin/allotments', $data)
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
        $paymentModel     = new PaymentModel();
        $applicationModel = $this->applicationModel;
        $plotModel        = new PlotModel();

        // Application status summary
        $data['appStatus'] = [
            'draft'              => $applicationModel->where('status', 'draft')->countAllResults(),
            'submitted'          => $applicationModel->where('status', 'submitted')->countAllResults(),
            'under_verification' => $applicationModel->where('status', 'under_verification')->countAllResults(),
            'verified'           => $applicationModel->where('status', 'verified')->countAllResults(),
            'rejected'           => $applicationModel->where('status', 'rejected')->countAllResults(),
        ];

        // Payment status and amount summary
        $pendingModel   = new PaymentModel();
        $completedModel = new PaymentModel();
        $failedModel    = new PaymentModel();

        $data['paymentStatus'] = [
            'pending'   => [
                'count'  => $pendingModel->where('status', 'pending')->countAllResults(),
                'amount' => $pendingModel->selectSum('amount')->where('status', 'pending')->first()['amount'] ?? 0,
            ],
            'completed' => [
                'count'  => $completedModel->whereIn('status', ['completed', 'success'])->countAllResults(),
                'amount' => $completedModel->selectSum('amount')->whereIn('status', ['completed', 'success'])->first()['amount'] ?? 0,
            ],
            'failed'   => [
                'count'  => $failedModel->where('status', 'failed')->countAllResults(),
                'amount' => $failedModel->selectSum('amount')->where('status', 'failed')->first()['amount'] ?? 0,
            ],
        ];

        // Plot status summary
        $data['plotStatus'] = [
            'available' => $plotModel->where('status', 'available')->countAllResults(),
            'allocated' => $plotModel->where('status', 'allocated')->countAllResults(),
            'reserved'  => $plotModel->where('status', 'reserved')->countAllResults(),
        ];

        // Recent records
        $data['recentApplications'] = $applicationModel->orderBy('created_at', 'DESC')->limit(5)->find();
        $data['recentPayments']     = $paymentModel->orderBy('created_at', 'DESC')->limit(5)->find();
        $data['recentPlots']        = $plotModel->orderBy('created_at', 'DESC')->limit(5)->find();

        return view('layout/admin_header')
            . view('admin/reports', $data)
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

            $category = trim((string) $this->request->getPost('category'));
            $totalQty = (int) ($this->request->getPost('total_quantity') ?? 0);

            if ($category === '' || $totalQty <= 0) {
                return redirect()->back()->withInput()->with('error', lang('App.adminPlotAddFailed'));
            }

            // Build single row: store plain category name, quantity taken from total_quantity
            $data = $baseData;
            $data['category']           = $category;
            $data['quantity']           = $totalQty;
            $data['available_quantity'] = $totalQty;
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

        // Single category + total quantity
        $category = trim((string) $this->request->getPost('category'));
        $totalQty = (int) ($this->request->getPost('total_quantity') ?? 0);

        if ($category === '' || $totalQty <= 0) {
            return redirect()->back()->withInput()->with('error', lang('App.adminPlotUpdateFailed'));
        }

        $data = $baseData;
        $data['category']           = $category;
        $data['quantity']           = $totalQty;
        $data['available_quantity'] = $totalQty;

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


