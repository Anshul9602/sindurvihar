<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\PaymentModel;
use App\Models\PlotModel;
use App\Models\AllotmentModel;
use App\Models\ApplicationDocumentModel;

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
        $userModel        = new UserModel();
        $applicationModel = new ApplicationModel();
        $allotmentModel   = new AllotmentModel();

        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Check if user is a lottery winner (has any allotment)
        $winnerAllotment = $allotmentModel
            ->select('allotments.id')
            ->join('applications', 'applications.id = allotments.application_id', 'left')
            ->where('applications.user_id', $id)
            ->first();

        if ($winnerAllotment) {
            return redirect()->to('/admin/users')
                ->with('error', 'This user cannot be deleted because they are a lottery winner and have an allotment.');
        }

        // Check if user has any application records
        $hasApplication = $applicationModel->where('user_id', $id)->first();
        if ($hasApplication) {
            return redirect()->to('/admin/users')
                ->with('error', 'This user cannot be deleted because they have submitted an application linked to the database.');
        }

        // Safe to delete (no dependent records)
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
            'caste_category' => $this->request->getPost('caste_category') ?: null,
            'is_disabled' => $this->request->getPost('is_disabled') ? 1 : 0,
            'is_single_woman' => $this->request->getPost('is_single_woman') ? 1 : 0,
            'is_transgender' => $this->request->getPost('is_transgender') ? 1 : 0,
            'is_army' => $this->request->getPost('is_army') ? 1 : 0,
            'is_media' => $this->request->getPost('is_media') ? 1 : 0,
            'is_govt_employee' => $this->request->getPost('is_govt_employee') ? 1 : 0,
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

        // Get all available plots
        $plotModel = new PlotModel();
        $availablePlots = $plotModel
            ->where('status', 'available')
            ->orderBy('category', 'ASC')
            ->orderBy('plot_number', 'ASC')
            ->findAll();

        // Also get grouped by category for summary
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
            'plots'            => $availablePlots,
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

        $roundNumber    = trim((string) $this->request->getPost('round_number'));
        $roundName      = trim((string) $this->request->getPost('round_name'));
        $category       = trim((string) $this->request->getPost('category')); // Plot category (ST, SC, etc.)
        $serviceCategory = trim((string) $this->request->getPost('service_category')); // Service category (EWS, LIG, MIG, GOVT, SOLDIER)
        $confirmed     = (bool) $this->request->getPost('confirmed');

        if ($roundNumber === '' || $roundName === '') {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Please enter lottery round number and name.']);
        }

        if (empty($category)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Please select a category.']);
        }

        // NOTE: service_category is OPTIONAL for direct-run category buttons.
        // When present (from the modal), it will be used to narrow applications.

        if (! $confirmed) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Please confirm that you want to run the lottery.']);
        }

        $plotModel      = new PlotModel();
        $allotmentModel = new AllotmentModel();
        $db             = \Config\Database::connect();

        // STEP 1: Filter Applicants by Category and Service Category
        $applications = $this->applicationModel
            ->select('applications.*, users.name as user_name, users.mobile, users.category as user_category')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->where('applications.status', 'verified')
            ->findAll();

        // Filter by caste category (ST, SC, etc.) - use user.category or applications.caste_category
        // Special handling: Army button can match both Army category and Army reservation
        $eligibleApps = [];
        foreach ($applications as $app) {
            $userCategory = $app['user_category'] ?? null;
            $casteCategory = $app['caste_category'] ?? $userCategory; // Fallback to user category
            $appServiceCategory = $app['income_category'] ?? null;

            // Match category based on button clicked
            $casteMatch = false;
            $categoryUpper = strtoupper($category);
            
            // Special reservation categories (not caste-based)
            if ($categoryUpper === 'MEDIA') {
                $casteMatch = !empty($app['is_media']) && $app['is_media'] == 1;
            } elseif ($categoryUpper === 'TRANSGENDER') {
                $casteMatch = !empty($app['is_transgender']) && $app['is_transgender'] == 1;
            } elseif ($categoryUpper === 'ARMY') {
                // Match if: user has Army category OR has Army reservation
                $casteMatch = ($casteCategory && strtoupper($casteCategory) === 'ARMY') ||
                             (!empty($app['is_army']) && $app['is_army'] == 1);
            } elseif ($categoryUpper === 'GOVT') {
                // Match if: caste category is GOVT OR income_category is Govt
                $casteMatch = ($casteCategory && strtoupper($casteCategory) === 'GOVT') ||
                             ($appServiceCategory && strtoupper($appServiceCategory) === 'GOVT');
            } else {
                // For caste-based categories (ST, SC, General), match caste category
                if ($casteCategory && strtoupper($casteCategory) === $categoryUpper) {
                    $casteMatch = true;
                }
            }

            // Match service category (EWS, LIG, MIG, GOVT, SOLDIER)
            // If admin did not specify a service category (direct-run buttons), accept all.
            $serviceMatch = true;
            if (!empty($serviceCategory) && $appServiceCategory) {
                // Handle Soldier category variations
                if (strtoupper($serviceCategory) === 'SOLDIER') {
                    $soldierCategories = ['Soldier', 'Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent', 'Soldier Category', 'Army'];
                    $serviceMatch = in_array($appServiceCategory, $soldierCategories, true);
                } else {
                    $serviceMatch = strtoupper($appServiceCategory) === strtoupper($serviceCategory);
                }
            }

            // For special reservation categories (Media, Transgender, Army), service category is always optional.
            if (in_array(strtoupper($category), ['MEDIA', 'TRANSGENDER', 'ARMY'])) {
                $serviceMatch = true;
            }

            // Match if caste category matches AND service category matches
            if ($casteMatch && $serviceMatch) {
                // Exclude applications that already have allotments
                $existingAllotment = $db->table('allotments')
                    ->where('application_id', $app['id'])
                    ->get()
                    ->getRowArray();
                
                if (!$existingAllotment) {
                    $eligibleApps[] = $app;
                }
            }
        }

        if (empty($eligibleApps)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'No eligible applications found for ' . $category . ' category with ' . $serviceCategory . ' service category.',
                ]);
        }

        // STEP 2: Get ALL Available Plots (allocation will be based on income group, not caste category)
        $allAvailablePlots = $plotModel
            ->where('status', 'available')
            ->findAll();

        if (empty($allAvailablePlots)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'No available plots found.']);
        }

        // Total plots counts for quota calculations
        $totalPlots = count($allAvailablePlots);

        // STEP 3: Calculate Reservation Quotas
        $quota = [
            'disabled'     => floor($totalPlots * 5 / 100),      // 5% for disabled
            'single_woman' => floor($totalPlots * 10 / 100),    // 10% for single woman/widow
            'transgender'  => 0, // Will be calculated if needed
            'army'         => 0, // Will be calculated if needed
            'media'        => 0, // Will be calculated if needed
            'govt'         => 0, // Will be calculated if needed
            'general'      => 0, // Remaining seats
        ];

        // STEP 4: Divide Applicants Into Priority Buckets
        $buckets = [
            'disabled'     => [],
            'single_woman' => [],
            'transgender'  => [],
            'army'         => [],
            'media'        => [],
            'govt'         => [],
            'general'      => [],
        ];

        foreach ($eligibleApps as $app) {
            // Priority 1: Disabled
            if (!empty($app['is_disabled']) && $app['is_disabled'] == 1) {
                $buckets['disabled'][] = $app;
                continue;
            }

            // Priority 2: Single Woman / Widow
            if (!empty($app['is_single_woman']) && $app['is_single_woman'] == 1) {
                $buckets['single_woman'][] = $app;
                continue;
            }

            // Priority 3: Transgender
            if (!empty($app['is_transgender']) && $app['is_transgender'] == 1) {
                $buckets['transgender'][] = $app;
                continue;
            }

            // Priority 4: Army / Ex-serviceman
            if (!empty($app['is_army']) && $app['is_army'] == 1) {
                $buckets['army'][] = $app;
                continue;
            }

            // Priority 5: Media
            if (!empty($app['is_media']) && $app['is_media'] == 1) {
                $buckets['media'][] = $app;
                continue;
            }

            // Priority 6: Govt Employee
            if (!empty($app['is_govt_employee']) && $app['is_govt_employee'] == 1) {
                $buckets['govt'][] = $app;
                continue;
            }

            // Priority 7: General (remaining applicants)
            $buckets['general'][] = $app;
        }

        // STEP 5: Allocate Seats Reservation-Wise with Random Selection
        $winners = [];
        $usedPlots = [];
        $plotIndex = 0;

        // Helper function to randomly select applicants
        $randomPick = function($applicants, $count) {
            if (empty($applicants) || $count <= 0) {
                return [];
            }
            shuffle($applicants); // Random shuffle
            return array_slice($applicants, 0, min($count, count($applicants)));
        };

        // Priority 1: Disabled
        $disabledWinners = $randomPick($buckets['disabled'], $quota['disabled']);
        $winners = array_merge($winners, $disabledWinners);
        $remainingDisabled = $quota['disabled'] - count($disabledWinners);

        // Priority 2: Single Woman
        $singleWomanWinners = $randomPick($buckets['single_woman'], $quota['single_woman']);
        $winners = array_merge($winners, $singleWomanWinners);
        $remainingSingleWoman = $quota['single_woman'] - count($singleWomanWinners);

        // Priority 3: Transgender (if quota exists)
        if ($quota['transgender'] > 0) {
            $transgenderWinners = $randomPick($buckets['transgender'], $quota['transgender']);
            $winners = array_merge($winners, $transgenderWinners);
        }

        // Priority 4: Army (if quota exists)
        if ($quota['army'] > 0) {
            $armyWinners = $randomPick($buckets['army'], $quota['army']);
            $winners = array_merge($winners, $armyWinners);
        }

        // Priority 5: Media (if quota exists)
        if ($quota['media'] > 0) {
            $mediaWinners = $randomPick($buckets['media'], $quota['media']);
            $winners = array_merge($winners, $mediaWinners);
        }

        // Priority 6: Govt Employee (if quota exists)
        if ($quota['govt'] > 0) {
            $govtWinners = $randomPick($buckets['govt'], $quota['govt']);
            $winners = array_merge($winners, $govtWinners);
        }

        // Priority 7: General (remaining seats + overflow from special categories)
        $allocatedSeats = count($winners);
        $remainingSeats = $totalPlots - $allocatedSeats;
        $remainingSeats += $remainingDisabled + $remainingSingleWoman; // Add overflow from special categories

        if ($remainingSeats > 0) {
            // Remove already selected winners from general bucket
            $winnerIds = array_column($winners, 'id');
            $generalApplicants = array_filter($buckets['general'], function($app) use ($winnerIds) {
                return !in_array($app['id'], $winnerIds);
            });
            $generalWinners = $randomPick(array_values($generalApplicants), $remainingSeats);
            $winners = array_merge($winners, $generalWinners);
        }

        if (empty($winners)) {
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'No winners could be selected. Please check applicant data and reservations.',
                ]);
        }

        // STEP 6: Create Allotments for All Winners
        // We assign plots according to the applicant's income group (income_category).
        // First randomize the global list of available plots, then pick best matches per winner.
        $remainingPlots = $allAvailablePlots;
        shuffle($remainingPlots);
        $allotmentIds = [];
        $allotmentDetails = [];

        foreach ($winners as $index => $winner) {
            if (empty($remainingPlots)) {
                break; // No more plots available
            }

            // Try to find a plot whose category matches the winner's income category
            $winnerIncomeCat = strtoupper($winner['income_category'] ?? '');
            $chosenIndex = null;

            if ($winnerIncomeCat !== '') {
                foreach ($remainingPlots as $idx => $plotCandidate) {
                    $plotCat = strtoupper($plotCandidate['category'] ?? '');
                    if ($plotCat === $winnerIncomeCat) {
                        $chosenIndex = $idx;
                        break;
                    }
                }
            }

            // If no exact income group match is found, fall back to the first remaining plot
            if ($chosenIndex === null) {
                // Optional chaining helper for older PHP versions
                $keys = array_keys($remainingPlots);
                if (empty($keys)) {
                    break;
                }
                $chosenIndex = $keys[0];
            }

            $plotIndex = (int) $chosenIndex;
            if (!array_key_exists($plotIndex, $remainingPlots)) {
                // Safety check – if index is missing, stop assigning further plots
                break;
            }

            $plot = $remainingPlots[$plotIndex];
            unset($remainingPlots[$plotIndex]);

            // Create allotment
            $allotmentData = [
                'application_id' => $winner['id'],
                'plot_number'    => $plot['plot_number'],
                'block_name'     => $plot['location'] ?? null,
                'status'         => 'provisional',
                'created_at'     => date('Y-m-d H:i:s'),
            ];

            if ($db->table('allotments')->insert($allotmentData)) {
                $allotmentId = $db->insertID();
                $allotmentIds[] = $allotmentId;

                // Mark plot as allotted
                $plotModel->update($plot['id'], ['status' => 'allotted']);

                // Update application status to selected
                $this->applicationModel->update($winner['id'], ['status' => 'selected']);

                $allotmentDetails[] = [
                    'allotment_id' => $allotmentId,
                    'winner' => [
                        'application_id' => $winner['id'],
                        'name'           => $winner['user_name'],
                        'mobile'         => $winner['mobile'],
                        'category'       => $category,
                        'service_cat'    => $serviceCategory,
                    ],
                    'plot' => [
                        'id'           => $plot['id'],
                        'plot_number'  => $plot['plot_number'],
                        'category'     => $plot['category'],
                        'location'     => $plot['location'] ?? null,
                    ],
                ];
            }
        }

        return $this->response->setJSON([
            'success'       => true,
            'message'       => 'Lottery run successfully. ' . count($allotmentDetails) . ' applicants selected.',
            'round_number'  => $roundNumber,
            'round_name'    => $roundName,
            'category'      => $category,
            'service_category' => $serviceCategory,
            'total_plots'   => $totalPlots,
            'total_winners' => count($allotmentDetails),
            'quota'         => $quota,
            'allotments'   => $allotmentDetails,
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

    public function allotmentDetail($id)
    {
        $allotmentModel = new AllotmentModel();
        $plotModel = new PlotModel();

        // Fetch allotment with application and user details
        $allotment = $allotmentModel
            ->select('allotments.*, applications.id as application_id, applications.full_name, applications.mobile, applications.aadhaar, applications.address, applications.tehsil, applications.district, applications.state, applications.income, applications.income_category, applications.status as application_status, users.name as user_name, users.mobile as user_mobile, users.email as user_email')
            ->join('applications', 'applications.id = allotments.application_id', 'left')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->where('allotments.id', $id)
            ->first();

        if (!$allotment) {
            return redirect()->to('/admin/allotments')->with('error', 'Allotment not found');
        }

        // Fetch plot details if plot_number exists
        $plot = null;
        if (!empty($allotment['plot_number'])) {
            $plot = $plotModel
                ->where('plot_number', $allotment['plot_number'])
                ->first();
        }

        $data['allotment'] = $allotment;
        $data['plot'] = $plot;

        return view('layout/admin_header')
            . view('admin/allotment_detail', $data)
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
        $plotModel      = new PlotModel();
        $applicationModel = $this->applicationModel;
        $documentModel  = new ApplicationDocumentModel();

        // 1) Scheme basic info (using real data where possible)
        $plots = $plotModel->findAll();
        $totalPlots = count($plots);

        $incomeGroups = [
            'EWS' => 0,
            'LIG' => 0,
            'MIG' => 0,
            'HIG' => 0,
        ];

        // Count plots by income group (EWS/LIG/MIG/HIG). MIG aggregates MIG-A and MIG-B.
        foreach ($plots as $plot) {
            $cat = strtoupper($plot['category'] ?? '');
            if ($cat === 'EWS') {
                $incomeGroups['EWS']++;
            } elseif ($cat === 'LIG') {
                $incomeGroups['LIG']++;
            } elseif ($cat === 'MIG' || $cat === 'MIG-A' || $cat === 'MIG-B') {
                $incomeGroups['MIG']++;
            } elseif ($cat === 'HIG') {
                $incomeGroups['HIG']++;
            }
        }

        $data['scheme'] = [
            'name'          => 'Sindoor Vihar',
            'total_plots'   => $totalPlots,
            'income_groups' => $incomeGroups,
            // These dates could later come from a config table; for now keep placeholders
            'last_date'     => '12 Feb 2026',
            'lottery_date'  => null,
            'status'        => 'closed',
        ];

        // 2) Reservation summary per lottery category using current plot counts
        $lotteryCategories = ['Govt', 'ST', 'SC', 'Media', 'Transgender', 'Army', 'General'];
        $categoryPlotCounts = array_fill_keys($lotteryCategories, 0);
        foreach ($plots as $plot) {
            $cat = $plot['category'] ?? '';
            if (isset($categoryPlotCounts[$cat])) {
                $categoryPlotCounts[$cat]++;
            } elseif (strtoupper($cat) === 'RESIDENTIAL') {
                // Treat residential plots as General for summary
                $categoryPlotCounts['General']++;
            }
        }

        // Reservation Summary (booklet-based, not dynamic)
        $reservationSummary = [
            [
                'category'    => 'Govt',
                'total_plots' => 15,
                'disabled'    => 1,
                'single'      => 1,
                'general'     => 13,
            ],
            [
                'category'    => 'ST',
                'total_plots' => 10,
                'disabled'    => 1,
                'single'      => 1,
                'general'     => 8,
            ],
            [
                'category'    => 'SC',
                'total_plots' => 13,
                'disabled'    => 1,
                'single'      => 1,
                'general'     => 11,
            ],
            [
                'category'    => 'Media',
                'total_plots' => 3,
                'disabled'    => 0,
                'single'      => 0,
                'general'     => 3,
            ],
            [
                'category'    => 'Transgender',
                'total_plots' => 3,
                'disabled'    => 0,
                'single'      => 0,
                'general'     => 3,
            ],
            [
                'category'    => 'Army',
                'total_plots' => 15,
                'disabled'    => 1,
                'single'      => 2,
                'general'     => 12,
            ],
            [
                'category'    => 'General',
                'total_plots' => 93,
                'disabled'    => 5,
                'single'      => 9,
                'general'     => 79,
            ],
            [
                'category'    => 'Total',
                'total_plots' => 152,
                'disabled'    => 9,
                'single'      => 14,
                'general'     => 129,
            ],
        ];
        $data['reservationSummary'] = $reservationSummary;

        // 3) Application Status Summary by income group
        $applicationSummary = [];
        foreach ($incomeGroups as $group => $dummy) {
            $total     = $applicationModel->where('income_category', $group)->countAllResults();
            $verified  = $applicationModel->where('income_category', $group)->where('status', 'verified')->countAllResults();
            $rejected  = $applicationModel->where('income_category', $group)->where('status', 'rejected')->countAllResults();
            $applicationSummary[] = [
                'group'     => $group,
                'total'     => $total,
                'verified'  => $verified,
                'rejected'  => $rejected,
            ];
        }
        $data['applicationSummary'] = $applicationSummary;

        // 4) Category-wise applicant count for selected lottery category
        $selectedCategory = $this->request->getGet('category') ?? 'General';
        $catUpper = strtoupper($selectedCategory);

        $applications = $applicationModel
            ->select('applications.*, users.name as user_name, users.mobile, users.category as user_category')
            ->join('users', 'users.id = applications.user_id', 'left')
            ->where('applications.status', 'verified')
            ->findAll();

        $eligibleApps = [];
        foreach ($applications as $app) {
            $userCategory     = $app['user_category'] ?? null;
            $casteCategory    = $app['caste_category'] ?? $userCategory;
            $appServiceCat    = $app['income_category'] ?? null;

            $casteMatch = false;
            if ($catUpper === 'MEDIA') {
                $casteMatch = !empty($app['is_media']) && $app['is_media'] == 1;
            } elseif ($catUpper === 'TRANSGENDER') {
                $casteMatch = !empty($app['is_transgender']) && $app['is_transgender'] == 1;
            } elseif ($catUpper === 'ARMY') {
                $casteMatch = ($casteCategory && strtoupper($casteCategory) === 'ARMY') ||
                              (!empty($app['is_army']) && $app['is_army'] == 1);
            } elseif ($catUpper === 'GOVT') {
                $casteMatch = ($casteCategory && strtoupper($casteCategory) === 'GOVT') ||
                              ($appServiceCat && strtoupper($appServiceCat) === 'GOVT');
            } else {
                if ($casteCategory && strtoupper($casteCategory) === $catUpper) {
                    $casteMatch = true;
                }
            }

            if (! $casteMatch) {
                continue;
            }

            // Service category is not restrictive here (we just want counts)
            $eligibleApps[] = $app;
        }

        $bucketCounts = [
            'disabled'     => 0,
            'single_woman' => 0,
            'transgender'  => 0,
            'army'         => 0,
            'general'      => 0,
        ];
        foreach ($eligibleApps as $app) {
            if (!empty($app['is_disabled'])) {
                $bucketCounts['disabled']++;
            } elseif (!empty($app['is_single_woman'])) {
                $bucketCounts['single_woman']++;
            } elseif (!empty($app['is_transgender'])) {
                $bucketCounts['transgender']++;
            } elseif (!empty($app['is_army'])) {
                $bucketCounts['army']++;
            } else {
                $bucketCounts['general']++;
            }
        }

        $data['categorySummary'] = [
            'category'         => $selectedCategory,
            'buckets'          => $bucketCounts,
            'total_eligible'   => array_sum($bucketCounts),
            'quota_plots'      => $categoryPlotCounts[$selectedCategory] ?? 0,
        ];

        // 5) Lottery readiness indicator
        $verifiedApps = $applicationModel->where('status', 'verified')->findAll();
        $verifiedCount = count($verifiedApps);

        $verifiedWithDocs = 0;
        if ($verifiedCount > 0) {
            foreach ($verifiedApps as $app) {
                $doc = $documentModel
                    ->where('application_id', $app['id'])
                    ->first();
                if ($doc && (
                    !empty($doc['has_identity_proof']) ||
                    !empty($doc['has_income_proof']) ||
                    !empty($doc['has_residence_proof'])
                )) {
                    $verifiedWithDocs++;
                }
            }
        }

        $allDocsVerified = ($verifiedCount > 0 && $verifiedWithDocs === $verifiedCount);
        $plotsAvailable  = $totalPlots > 0;
        $reservationsCalculated = $totalPlots > 0; // since we calculated summary from plots
        $aadhaarPending = $applicationModel->where('status', 'under_verification')->countAllResults();

        $lotteryReady = $plotsAvailable && $verifiedCount > 0 && $reservationsCalculated && $allDocsVerified && ($aadhaarPending === 0);

        $data['readiness'] = [
            'plots_available'        => $plotsAvailable,
            'verified_applications'  => $verifiedCount,
            'all_docs_verified'      => $allDocsVerified,
            'reservations_calculated'=> $reservationsCalculated,
            'aadhaar_pending'        => $aadhaarPending,
            'ready'                  => $lotteryReady,
        ];

        return view('layout/admin_header')
            . view('admin/schemes', $data)
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
        
        // Get filter parameters
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $perPage = (int) ($this->request->getGet('per_page') ?? 25);
        
        // Validate per_page value (only allow 25 to 500, default to 25)
        if ($perPage < 25 || $perPage > 500) {
            $perPage = 25;
        }
        
        // Build query with filters
        $query = $plotModel->orderBy('created_at', 'DESC');
        
        if (!empty($category)) {
            $query->where('category', $category);
        }
        
        if (!empty($status)) {
            $query->where('status', $status);
        }
        
        if (!empty($search)) {
            $query->groupStart()
                  ->like('plot_name', $search)
                  ->orLike('plot_number', $search)
                  ->orLike('location', $search)
                  ->groupEnd();
        }
        
        // Paginate results with configurable per page - use default group
        $data['plots'] = $query->paginate($perPage);
        $data['pager'] = $plotModel->pager;
        
        // Set pagination path to preserve filters
        if ($data['pager']) {
            $data['pager']->setPath('/admin/plots');
        }
        
        // Get category-wise counts (for all plots, not just current page)
        $categories = $plotModel->select('category, SUM(quantity) as total_quantity, SUM(available_quantity) as total_available')
                                ->groupBy('category')
                                ->findAll();
        $data['categoryStats'] = $categories;
        
        // Pass filter values back to view
        $data['filterCategory'] = $category;
        $data['filterStatus'] = $status;
        $data['filterSearch'] = $search;
        $data['filterPerPage'] = $perPage;
        
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


