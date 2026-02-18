<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\UserModel;
use App\Models\EligibilityModel;
use App\Models\PaymentModel;
use App\Models\ApplicationDocumentModel;
use App\Models\AadhaarOtpModel;

class UserPortal extends BaseController
{
    protected $userModel;
    protected $applicationModel;
    protected $eligibilityModel;
    protected $paymentModel;
    protected $documentModel;
    protected $aadhaarOtpModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->applicationModel = new ApplicationModel();
        $this->eligibilityModel = new EligibilityModel();
        $this->paymentModel = new PaymentModel();
        $this->documentModel = new ApplicationDocumentModel();
        $this->aadhaarOtpModel = new AadhaarOtpModel();
    }

    /**
     * Return list of required annexure forms (JPGs) based on applicant category, caste category, and reservation categories.
     * This mirrors the summary table from the Sindoor Vihar booklet.
     */
    private function resolveFormsForCategory(?string $category = null, ?string $casteCategory = null, array $reservationCategories = []): array
    {
        $category   = trim((string) ($category ?? ''));
        $casteCategory = trim((string) ($casteCategory ?? ''));
        $formsBase  = base_url('assets/documentform/') . '/';

        // Base annexures (I–VII) we currently have as JPGs
        $annexI  = ['label' => 'Annexure I – Self Declaration / Affidavit (All)',              'url' => $formsBase . 'BookLet Sindoor Vihar_page-0015.jpg'];
        $annexII = ['label' => 'Annexure II – Income Certificate (All)',                        'url' => $formsBase . 'BookLet Sindoor Vihar_page-0016.jpg'];
        $annexIII= ['label' => 'Annexure III – SC/ST Certificate (SC/ST)',                     'url' => $formsBase . 'BookLet Sindoor Vihar_page-0017.jpg'];
        $annexIV = ['label' => 'Annexure IV – Soldier Certificate (Serving/Ex‑Serviceman)',    'url' => $formsBase . 'BookLet Sindoor Vihar_page-0018.jpg'];
        $annexV  = ['label' => 'Annexure V – Soldier Family Affidavit (Widow/Dependent)',      'url' => $formsBase . 'BookLet Sindoor Vihar_page-0019.jpg'];
        $annexVI = ['label' => 'Annexure VI – Soldier Undertaking (Soldier)',                  'url' => $formsBase . 'BookLet Sindoor Vihar_page-0020.jpg'];
        $annexVII= ['label' => 'Annexure VII – Disability Certificate (Divyang / PwD)',        'url' => $formsBase . 'BookLet Sindoor Vihar_page-0021.jpg'];

        // Use associative array to avoid duplicates
        $required = [];
        $required['annexure-i'] = $annexI;
        $required['annexure-ii'] = $annexII;

        // SC / ST from caste category
        if ($casteCategory === 'SC' || $casteCategory === 'ST') {
            $required['annexure-iii'] = $annexIII;
        }

        // Soldier related categories from income category
        if (in_array($category, ['Soldier', 'Soldier Category', 'Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent', 'Army'], true)) {
            $required['annexure-iv'] = $annexIV;
            $required['annexure-v'] = $annexV;
            $required['annexure-vi'] = $annexVI;
        }

        // Disabled reservation category
        if (!empty($reservationCategories['is_disabled'])) {
            $required['annexure-vii'] = $annexVII;
        }

        // Return as indexed array
        return array_values($required);
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to access your dashboard');
        }

        $userId = session()->get('user_id');
        
        // Get user's application if exists
        $application = $this->applicationModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->first();

        // Get eligibility data
        $eligibility = $this->eligibilityModel
            ->where('user_id', $userId)
            ->where('is_eligible', 1)
            ->first();
        $eligibilityDone = (bool) $eligibility;

        // Get payment and documents data
        $documentsDone = false;
        $paymentDone   = false;
        $payment = null;
        $documents = null;

        if ($application) {
            $documents = $this->documentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->first();
            $documentsDone = (bool) $documents;

            $payment = $this->paymentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->where('status', 'success')
                ->orderBy('created_at', 'DESC')
                ->first();
            $paymentDone = (bool) $payment;
        }
        
        $data['user'] = [
            'id' => session()->get('user_id'),
            'name' => session()->get('user_name'),
            'mobile' => session()->get('user_mobile'),
            'email' => session()->get('user_email')
        ];
        $data['application'] = $application;
        $data['eligibility'] = $eligibility;
        $data['payment'] = $payment;
        $data['documents'] = $documents;
        $data['steps'] = [
            'eligibility' => [
                'completed' => $eligibilityDone,
            ],
            'application' => [
                'completed' => (bool) $application,
            ],
            'documents' => [
                'completed' => $documentsDone,
            ],
            'payment' => [
                'completed' => $paymentDone,
            ],
        ];

        return view('layout/header')
            . view('user/dashboard', $data)
            . view('layout/footer');
    }

    public function eligibility()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to check eligibility');
        }

        $userId = session()->get('user_id');

        // Follow same pattern as AuthPortal (check POST by presence of field)
        if ($this->request->getPost('age') !== null) {
            $age            = (int) $this->request->getPost('age');
            $income         = (int) $this->request->getPost('income');
            $residency      = (string) $this->request->getPost('residency');
            $propertyStatus = (string) $this->request->getPost('property');

            // Basic rule derived from booklet-style conditions:
            // - Age between 18 and 70
            // - Some positive income
            // - Resident of state
            // - No existing residential property in scheme area
            $isEligible = $age >= 18
                && $age <= 70
                && $income > 0
                && $residency === 'state'
                && $propertyStatus === 'none';

            // Only store in DB when user is actually eligible
            if ($isEligible) {
                // Check if an eligibility record already exists for this user
                $existing = $this->eligibilityModel
                    ->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->first();

                $payload = [
                    'user_id'         => $userId,
                    'age'             => $age,
                    'income'          => $income,
                    'residency'       => $residency,
                    'property_status' => $propertyStatus,
                    'is_eligible'     => 1,
                ];

                if ($existing) {
                    $result = $this->eligibilityModel->update($existing['id'], $payload);
                } else {
                    $result = $this->eligibilityModel->insert($payload);
                }

                if (! $result) {
                    $dbError  = $this->eligibilityModel->db->error();
                    $errorMsg = $dbError['message'] ?? 'Failed to save eligibility details.';

                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Eligibility could not be saved: ' . $errorMsg);
                }

                return redirect()->to('/user/application')
                    ->with('success', 'आप योजना के लिए पात्र हैं। कृपया आवेदन फॉर्म भरें।');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'आप दिए गए विवरण के आधार पर इस योजना के लिए पात्र नहीं हैं।');
        }

        // GET: Pre-fill form if eligibility already exists
        $existing = $this->eligibilityModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        $data = [
            'eligibility' => $existing,
        ];

        return view('layout/header')
            . view('user/eligibility', $data)
            . view('layout/footer');
    }

    public function application()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to submit application');
        }

        // Require eligibility to be completed and successful
        $userId = session()->get('user_id');
        $eligible = $this->eligibilityModel
            ->where('user_id', $userId)
            ->where('is_eligible', 1)
            ->first();

        if (! $eligible) {
            return redirect()->to('/user/eligibility')->with('error', 'Please complete eligibility check before filling the application form.');
        }

        // Check if user already has an application - redirect to edit if exists
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        // If application exists and can be edited, redirect to edit page
        if ($application && in_array($application['status'], ['draft', 'submitted', 'paid', 'under_verification', 'clarification'])) {
            return redirect()->to('/user/application/edit');
        }

        // Get user's data from registration
        $user = $this->userModel->find($userId);
        $userCategory = $user['category'] ?? null;

        // Get verified Aadhaar KYC data for auto-filling
        $verifiedAadhaar = $this->aadhaarOtpModel
            ->where('user_id', $userId)
            ->where('verified', 1)
            ->orderBy('updated_at', 'DESC')
            ->first();

        // New application form - no existing application or cannot edit
        // Pass user and eligibility data for auto-filling
        $data = [
            'application' => null,
            'isEditMode' => false,
            'userCategory' => $userCategory,
            'user' => $user, // For auto-filling name and mobile
            'eligibility' => $eligible, // For auto-filling age and income
            'verifiedAadhaar' => $verifiedAadhaar, // For auto-filling from Aadhaar KYC
        ];

        return view('layout/header')
            . view('user/application', $data)
            . view('layout/footer');
    }

    public function editApplication()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to edit application');
        }

        // Require eligibility to be completed and successful
        $userId = session()->get('user_id');
        $eligible = $this->eligibilityModel
            ->where('user_id', $userId)
            ->where('is_eligible', 1)
            ->first();

        if (! $eligible) {
            return redirect()->to('/user/eligibility')->with('error', 'Please complete eligibility check before editing the application form.');
        }

        // Load latest application for editing
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$application) {
            return redirect()->to('/user/application')->with('error', 'No application found. Please create a new application first.');
        }

        // Check if application can be edited
        if (!in_array($application['status'], ['draft', 'submitted', 'paid', 'under_verification', 'clarification'])) {
            return redirect()->to('/user/dashboard')->with('error', 'Application cannot be edited in its current status.');
        }

        // Get user's data from registration (use it if application doesn't have caste_category)
        $user = $this->userModel->find($userId);
        $userCategory = $user['category'] ?? null;

        // Get verified Aadhaar KYC data for auto-filling
        $verifiedAadhaar = $this->aadhaarOtpModel
            ->where('user_id', $userId)
            ->where('verified', 1)
            ->orderBy('updated_at', 'DESC')
            ->first();

        $data = [
            'application' => $application,
            'isEditMode' => true,
            'userCategory' => $userCategory,
            'user' => $user, // For auto-filling name and mobile
            'eligibility' => $eligible, // For auto-filling age and income
            'verifiedAadhaar' => $verifiedAadhaar, // For auto-filling from Aadhaar KYC
        ];

        return view('layout/header')
            . view('user/application', $data)
            . view('layout/footer');
    }

    public function submitApplication()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Please login to submit application']);
            }
            return redirect()->to('/auth/login')->with('error', 'Please login to submit application');
        }

        $userId = session()->get('user_id');
        $applicationId = $this->request->getPost('application_id');
        $aadhaar = (string) $this->request->getPost('aadhaar');

        // Verify Aadhaar is verified before allowing submission
        if (!empty($aadhaar)) {
            $verified = $this->aadhaarOtpModel
                ->where('user_id', $userId)
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 1)
                ->first();

            if (!$verified) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Please verify your Aadhaar number before submitting the application.'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Please verify your Aadhaar number before submitting the application.');
            }
        }

        $data = [
            'full_name'                => (string) $this->request->getPost('full_name'),
            'aadhaar'                  => $aadhaar,
            'father_husband_name'      => (string) $this->request->getPost('father_husband_name'),
            'age'                      => (int) $this->request->getPost('age'),
            'mobile'                   => (string) $this->request->getPost('mobile'),
            'address'                  => (string) $this->request->getPost('address'),
            'tehsil'                   => (string) $this->request->getPost('tehsil'),
            'district'                 => (string) $this->request->getPost('district'),
            'city'                     => (string) ($this->request->getPost('city') ?: ''),
            'state'                    => (string) ($this->request->getPost('state') ?: 'Rajasthan'),
            'income'                   => (string) $this->request->getPost('income'),
            'income_category'          => (string) $this->request->getPost('income_category'),
            'caste_category'           => (string) ($this->request->getPost('caste_category') ?: null),
            'is_disabled'              => $this->request->getPost('is_disabled') ? 1 : 0,
            'is_single_woman'          => $this->request->getPost('is_single_woman') ? 1 : 0,
            'is_transgender'           => $this->request->getPost('is_transgender') ? 1 : 0,
            'is_army'                  => $this->request->getPost('is_army') ? 1 : 0,
            'is_media'                 => $this->request->getPost('is_media') ? 1 : 0,
            'is_govt_employee'         => $this->request->getPost('is_govt_employee') ? 1 : 0,
            'declaration_truth'        => $this->request->getPost('declaration_truth') ? 1 : 0,
            'declaration_cancellation' => $this->request->getPost('declaration_cancellation') ? 1 : 0,
        ];

        // If application_id is provided, this is an update
        if (!empty($applicationId)) {
            $existingApp = $this->applicationModel
                ->where('id', $applicationId)
                ->where('user_id', $userId)
                ->first();

            if (!$existingApp) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Application not found or you do not have permission to edit it.');
            }

            // Only allow editing for certain statuses
            // Allow editing for: draft, submitted, paid, under_verification, clarification
            if (!in_array($existingApp['status'], ['draft', 'submitted', 'paid', 'under_verification', 'clarification'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Application cannot be edited in its current status.');
            }

            // If status was 'submitted' or later, keep it as 'submitted' after update
            // This ensures the application doesn't go backwards in the workflow
            if (in_array($existingApp['status'], ['submitted', 'paid', 'under_verification', 'clarification'])) {
                $data['status'] = 'submitted'; // Keep as submitted after edit
            }

            // Check for duplicates (excluding current application) - only Aadhaar must be unique
            $duplicate = $this->applicationModel
                ->where('id !=', $applicationId)
                ->where('aadhaar', $data['aadhaar'])
                ->first();

            if ($duplicate) {
                $errorMsg = 'An application has already been submitted with this Aadhaar number.';

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg,
                    ]);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }

            // Update existing application
            if ($this->applicationModel->update($applicationId, $data)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Application updated successfully',
                        'application_id' => $applicationId
                    ]);
                }
                return redirect()->to('/user/application')->with('success', 'Application updated successfully.');
            } else {
                $dbError  = $this->applicationModel->db->error();
                $errorMsg = $dbError['message'] ?? 'Failed to update application';

                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg,
                    ]);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }
        }

        // New application submission
        $data['user_id'] = $userId;
        $data['status'] = 'draft';

        // Prevent multiple applications with same Aadhaar (only Aadhaar must be unique)
        $existing = $this->applicationModel
            ->where('aadhaar', $data['aadhaar'])
            ->first();

        if ($existing) {
            $errorMsg = 'An application has already been submitted with this Aadhaar number.';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMsg,
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }

        if ($this->applicationModel->insert($data)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Application submitted successfully',
                    'application_id' => $this->applicationModel->getInsertID()
                ]);
            }
            return redirect()->to('/user/payment')->with('success', 'Application submitted successfully. Please proceed to payment.');
        } else {
            // Capture DB error for debugging
            $dbError  = $this->applicationModel->db->error();
            $errorMsg = $dbError['message'] ?? 'Failed to submit application';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMsg,
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }
    }

    public function applicationStatus()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view application status');
        }

        $userId = session()->get('user_id');

        // Latest application for this user (if any)
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        // Determine step completion based on DB records
        $eligibilityDone = (bool) $this->eligibilityModel
            ->where('user_id', $userId)
            ->where('is_eligible', 1)
            ->first();

        $documentsDone = false;
        $paymentDone   = false;

        if ($application) {
            $documentsDone = (bool) $this->documentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->first();

            $paymentDone = (bool) $this->paymentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->where('status', 'success')
                ->first();
        }

        $applicationDone = (bool) $application;

        // Overall status label - check application status from database first
        if (! $application) {
            $overallStatus = 'none';
        } elseif ($application['status'] === 'verified') {
            $overallStatus = 'verified';
        } elseif ($application['status'] === 'rejected') {
            $overallStatus = 'rejected';
        } elseif ($eligibilityDone && $applicationDone && $documentsDone && $paymentDone) {
            $overallStatus = 'submitted';
        } else {
            $overallStatus = 'pending';
        }

        $data = [
            'application'     => $application,
            'overallStatus'   => $overallStatus,
            'steps'           => [
                'eligibility' => ['completed' => $eligibilityDone],
                'application' => ['completed' => $applicationDone],
                'documents'   => ['completed' => $documentsDone],
                'payment'     => ['completed' => $paymentDone],
            ],
        ];

        return view('layout/header')
            . view('user/application_status', $data)
            . view('layout/footer');
    }

    public function documents()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to upload documents');
        }

        $userId = session()->get('user_id');

        // Require application and payment to be completed
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (! $application) {
            return redirect()->to('/user/application')->with('error', 'Please submit your application form before uploading documents.');
        }

        // Check if payment is completed
        $payment = $this->paymentModel
            ->where('user_id', $userId)
            ->where('application_id', $application['id'])
            ->where('status', 'success')
            ->first();

        if (! $payment) {
            return redirect()->to('/user/payment')->with('error', 'Please complete payment before uploading documents.');
        }

        // Follow same pattern as eligibility: detect POST by presence of a field
        // instead of relying only on getMethod(), to avoid any method-mismatch issues.
        if ($this->request->getPost('notes') !== null || $this->request->getPost('has_identity_proof') !== null) {
            $data = [
                'user_id'            => $userId,
                'application_id'     => $application['id'],
                'has_identity_proof' => $this->request->getPost('has_identity_proof') ? 1 : 0,
                'has_income_proof'   => $this->request->getPost('has_income_proof') ? 1 : 0,
                'has_residence_proof'=> $this->request->getPost('has_residence_proof') ? 1 : 0,
                'notes'              => (string) $this->request->getPost('notes'),
            ];

            // Handle multiple file uploads for each document type
            $uploadBasePath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR;
            if (! is_dir($uploadBasePath)) {
                mkdir($uploadBasePath, 0775, true);
            }

            // Check existing documents to see if files already exist
            $existing = $this->documentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->first();

            $existingIdentityFiles = [];
            $existingIncomeFiles = [];
            $existingResidenceFiles = [];

            if ($existing) {
                if (!empty($existing['identity_files'])) {
                    $existingIdentityFiles = json_decode($existing['identity_files'], true) ?? [];
                }
                if (!empty($existing['income_files'])) {
                    $existingIncomeFiles = json_decode($existing['income_files'], true) ?? [];
                }
                if (!empty($existing['residence_files'])) {
                    $existingResidenceFiles = json_decode($existing['residence_files'], true) ?? [];
                }
            }

            $identityFiles  = [];
            $incomeFiles    = [];
            $residenceFiles = [];
            $annexureFiles  = [];

            // Validate: If checkbox is checked, must have files (either new uploads or existing)
            $hasIdentityProof = $this->request->getPost('has_identity_proof') ? 1 : 0;
            $hasIncomeProof = $this->request->getPost('has_income_proof') ? 1 : 0;
            $hasResidenceProof = $this->request->getPost('has_residence_proof') ? 1 : 0;

            $identityUploads = $this->request->getFileMultiple('identity_files');
            if ($identityUploads) {
                foreach ($identityUploads as $file) {
                    if ($file->isValid() && ! $file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move($uploadBasePath, $newName);
                        $identityFiles[] = 'uploads/documents/' . $userId . '/' . $newName;
                    }
                }
            }

            $incomeUploads = $this->request->getFileMultiple('income_files');
            if ($incomeUploads) {
                foreach ($incomeUploads as $file) {
                    if ($file->isValid() && ! $file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move($uploadBasePath, $newName);
                        $incomeFiles[] = 'uploads/documents/' . $userId . '/' . $newName;
                    }
                }
            }

            $residenceUploads = $this->request->getFileMultiple('residence_files');
            if ($residenceUploads) {
                foreach ($residenceUploads as $file) {
                    if ($file->isValid() && ! $file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move($uploadBasePath, $newName);
                        $residenceFiles[] = 'uploads/documents/' . $userId . '/' . $newName;
                    }
                }
            }

            // Validation: If checkbox is checked, must have files (new or existing)
            if ($hasIdentityProof && empty($identityFiles) && empty($existingIdentityFiles)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', lang('App.docIdentityFileRequired') ?? 'Please upload at least one file for identity proof.');
            }

            if ($hasIncomeProof && empty($incomeFiles) && empty($existingIncomeFiles)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', lang('App.docIncomeFileRequired') ?? 'Please upload at least one file for income proof.');
            }

            if ($hasResidenceProof && empty($residenceFiles) && empty($existingResidenceFiles)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', lang('App.docResidenceFileRequired') ?? 'Please upload at least one file for residence proof.');
            }

            // Merge new files with existing files
            $allIdentityFiles = array_merge($existingIdentityFiles, $identityFiles);
            $allIncomeFiles = array_merge($existingIncomeFiles, $incomeFiles);
            $allResidenceFiles = array_merge($existingResidenceFiles, $residenceFiles);

            if (! empty($allIdentityFiles)) {
                $data['identity_files'] = json_encode($allIdentityFiles);
            }
            if (! empty($allIncomeFiles)) {
                $data['income_files'] = json_encode($allIncomeFiles);
            }
            if (! empty($allResidenceFiles)) {
                $data['residence_files'] = json_encode($allResidenceFiles);
            }

            $annexureUploads = $this->request->getFileMultiple('annexure_files');
            if ($annexureUploads) {
                foreach ($annexureUploads as $file) {
                    if ($file->isValid() && ! $file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move($uploadBasePath, $newName);
                        $annexureFiles[] = 'uploads/documents/' . $userId . '/' . $newName;
                    }
                }
            }

            if (! empty($annexureFiles)) {
                $data['annexure_files'] = json_encode($annexureFiles);
            }

            // Use the $existing variable we already fetched above
            if ($existing) {
                $ok = $this->documentModel->update($existing['id'], $data);
            } else {
                $ok = $this->documentModel->insert($data);
            }

            if (! $ok) {
                $dbError  = $this->documentModel->db->error();
                $errorMsg = $dbError['message'] ?? 'Failed to save document details.';

                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMsg);
            }

            return redirect()->to('/user/application/status')
                ->with('success', 'Documents details saved successfully.');
        }

        $documents = $this->documentModel
            ->where('user_id', $userId)
            ->where('application_id', $application['id'])
            ->first();

        // Determine required downloadable forms based on application category, caste category, and reservation categories
        $reservationCategories = [
            'is_disabled' => !empty($application['is_disabled']),
            'is_single_woman' => !empty($application['is_single_woman']),
            'is_transgender' => !empty($application['is_transgender']),
            'is_army' => !empty($application['is_army']),
            'is_media' => !empty($application['is_media']),
            'is_govt_employee' => !empty($application['is_govt_employee']),
        ];
        
        $requiredForms = $this->resolveFormsForCategory(
            $application['income_category'] ?? null,
            $application['caste_category'] ?? null,
            $reservationCategories
        );

        $data = [
            'documents'     => $documents,
            'requiredForms' => $requiredForms,
            'application'   => $application,
        ];

        return view('layout/header')
            . view('user/documents', $data)
            . view('layout/footer');
    }

    public function payment()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to make payment');
        }

        $userId = session()->get('user_id');

        // Require application to be completed
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (! $application) {
            return redirect()->to('/user/application')->with('error', 'Please submit your application form before payment.');
        }

        // Check if payment already exists
        $existingPayment = $this->paymentModel
            ->where('user_id', $userId)
            ->where('application_id', $application['id'])
            ->where('status', 'success')
            ->orderBy('created_at', 'DESC')
            ->first();

        // Detect POST by presence of amount field (same pattern as documents method)
        if ($this->request->getPost('amount') !== null) {
            $amount = (int) $this->request->getPost('amount');

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'अमान्य भुगतान राशि।');
            }

            try {
                // Check if payment already exists, update instead of insert
                if ($existingPayment) {
                    $result = $this->paymentModel->update($existingPayment['id'], [
                        'amount'         => $amount,
                        'status'         => 'success',
                    ]);
                } else {
                    $result = $this->paymentModel->insert([
                        'user_id'        => $userId,
                        'application_id' => $application['id'],
                        'amount'         => $amount,
                        'status'         => 'success',
                        'transaction_ref'=> null,
                    ]);
                }

                if (!$result) {
                    $dbError = $this->paymentModel->db->error();
                    $errorMsg = $dbError['message'] ?? 'Failed to save payment.';
                    log_message('error', 'Payment save failed: ' . $errorMsg);
                    return redirect()->back()->withInput()->with('error', $errorMsg);
                }

                // Mark application as paid so that admin flow matches booklet-style process
                $this->applicationModel->update($application['id'], [
                    'status' => 'paid',
                ]);

                // Redirect back to same payment page with success message
                return redirect()->to('/user/payment')
                    ->with('success', 'Payment completed successfully! You can now proceed to upload documents.');
            } catch (\Exception $e) {
                log_message('error', 'Payment exception: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Payment failed: ' . $e->getMessage());
            }
        }

        $data = [
            'payment' => $existingPayment,
            'application' => $application,
        ];

        return view('layout/header')
            . view('user/payment', $data)
            . view('layout/footer');
    }

    public function profile()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view profile');
        }

        $userId = session()->get('user_id');

        // If form submitted, update profile in users table
        if ($this->request->getPost('name') !== null) {
            $name     = (string) $this->request->getPost('name');
            $email    = (string) $this->request->getPost('email');
            $language = (string) $this->request->getPost('language') ?: 'en';
            $category = (string) $this->request->getPost('category');

            $updateData = [
                'name'     => $name,
                'email'    => $email,
                'language' => $language,
            ];

            if ($category !== '') {
                $updateData['category'] = $category;
            }

            $this->userModel->update($userId, $updateData);

            // Update session so header/dashboard show latest values
            session()->set([
                'user_name'  => $name,
                'user_email' => $email,
            ]);

            return redirect()->to('/user/profile')->with('success', 'Profile updated successfully.');
        }

        // Load current user data from DB
        $user = $this->userModel->find($userId);

        $data['user'] = $user ?: [
            'name'     => session()->get('user_name'),
            'email'    => session()->get('user_email'),
            'language' => 'en',
            'category' => null,
        ];

        return view('layout/header')
            . view('user/profile', $data)
            . view('layout/footer');
    }

    public function lotteryResults()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view lottery results');
        }

        $userId = session()->get('user_id');
        $allotmentModel = new \App\Models\AllotmentModel();
        
        // Get user's application
        $userApplication = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();
        
        // Get all allotments (lottery winners) with application and user details
        $allAllotments = $allotmentModel
            ->select('allotments.*, applications.full_name, applications.mobile, applications.user_id')
            ->join('applications', 'applications.id = allotments.application_id', 'left')
            ->orderBy('allotments.created_at', 'DESC')
            ->findAll();
        
        // Check if current user won (has an allotment)
        $userWon = false;
        $userAllotment = null;
        if ($userApplication) {
            $userAllotment = $allotmentModel
                ->where('application_id', $userApplication['id'])
                ->first();
            $userWon = !empty($userAllotment);
        }
        
        $data = [
            'allAllotments' => $allAllotments,
            'userWon' => $userWon,
            'userAllotment' => $userAllotment,
            'userApplication' => $userApplication,
        ];

        return view('layout/header')
            . view('user/lottery_results', $data)
            . view('layout/footer');
    }

    public function allotment()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view allotment');
        }

        $userId = session()->get('user_id');

        $allotmentModel = new \App\Models\AllotmentModel();

        // Step 1: latest allotment with application info (no plots join to avoid collation issues)
        $allotment = $allotmentModel
            ->select('allotments.*, applications.id as application_id, applications.full_name')
            ->join('applications', 'applications.id = allotments.application_id', 'left')
            ->where('applications.user_id', $userId)
            ->orderBy('allotments.created_at', 'DESC')
            ->first();

        // Step 2: enrich with plot info in a separate query (no cross-collation JOIN)
        if ($allotment && ! empty($allotment['plot_number'])) {
            $plotModel = new \App\Models\PlotModel();
            $plot = $plotModel
                ->where('plot_number', $allotment['plot_number'])
                ->first();

            if ($plot) {
                $allotment['plot_category'] = $plot['category'] ?? null;
                $allotment['location']      = $plot['location'] ?? null;
                $allotment['dimensions']    = $plot['dimensions'] ?? null;
            }
        }

        $data['allotment'] = $allotment;

        return view('layout/header')
            . view('user/allotment', $data)
            . view('layout/footer');
    }

    public function refundStatus()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view refund status');
        }

        return view('layout/header')
            . view('user/refund_status')
            . view('layout/footer');
    }

    /**
     * Generate Aadhaar OTP via TruthScreen API
     */
    public function generateAadhaarOtp()
    {
        // Allow Aadhaar verification for both logged-in users and registration flow
        $userId = session()->has('user_id') ? session()->get('user_id') : 0; // 0 for registration flow
        
        // Get Aadhaar from POST or JSON
        $jsonData = $this->request->getJSON(true);
        $aadhaar = trim((string) ($this->request->getPost('aadhaar') ?? $jsonData['aadhaar'] ?? ''));

        // Remove any spaces or dashes from Aadhaar
        $aadhaar = preg_replace('/[\s\-]/', '', $aadhaar);

        // Validate Aadhaar number (12 digits)
        if (empty($aadhaar)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aadhaar number is required'
            ]);
        }

        if (!preg_match('/^[0-9]{12}$/', $aadhaar)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please enter a valid 12-digit Aadhaar number (You entered ' . strlen($aadhaar) . ' digits)'
            ]);
        }

        // Check if Aadhaar is already verified by any user
        $existingVerified = $this->aadhaarOtpModel
            ->where('aadhaar_number', $aadhaar)
            ->where('verified', 1)
            ->first();

        if ($existingVerified) {
            // For registration flow (userId = 0), check if Aadhaar is already linked to a registered user
            if ($userId == 0) {
                // Registration flow - check if Aadhaar is already linked to a registered user
                if ($existingVerified['user_id'] > 0) {
                    $existingUser = $this->userModel->find($existingVerified['user_id']);
                    if ($existingUser) {
                        // Aadhaar already registered by another user
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => lang('App.appAadhaarAlreadyUsed'),
                            'already_used' => true
                        ]);
                    }
                }
                // Aadhaar verified but not linked to a user - allow registration
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('App.appAadhaarAlreadyVerified'),
                    'verified' => true,
                    'same_user' => true
                ]);
            } else {
                // Logged-in user flow
                if ($existingVerified['user_id'] == $userId) {
                    // Same user - allow and show as verified
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => lang('App.appAadhaarAlreadyVerified'),
                        'verified' => true,
                        'same_user' => true
                    ]);
                } else {
                    // Different user - show error
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => lang('App.appAadhaarAlreadyUsed'),
                        'already_used' => true
                    ]);
                }
            }
        }

        // Check if current user/registration has unverified OTP for this Aadhaar
        // For registration (userId = 0), check by aadhaar_number only
        if ($userId == 0) {
            $existing = $this->aadhaarOtpModel
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 0)
                ->where('user_id', 0) // Only unverified records with user_id = 0 (registration flow)
                ->first();
        } else {
            $existing = $this->aadhaarOtpModel
                ->where('user_id', $userId)
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 0)
                ->first();
        }

        // Check if table exists
        $db = \Config\Database::connect();
        if (!$db->tableExists('aadhaar_otps')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database table not found. Please run migration: php spark migrate',
            ]);
        }

        // Call TruthScreen API (or fallback to demo mode if not configured)
        $clientId     = getenv('TRUTHSCREEN_CLIENT_ID');
        $clientSecret = getenv('TRUTHSCREEN_CLIENT_SECRET');
        $baseUrl      = rtrim(getenv('TRUTHSCREEN_BASE_URL') ?? 'https://api.truthscreen.com', '/');

        try {
            if (empty($clientId) || empty($clientSecret)) {
                // DEMO MODE: No credentials configured – behave like before (fixed OTP, not sent to UIDAI)
                $otpValue    = '123456';
                $apiResponse = [
                    'status'  => 'success',
                    'message' => 'Demo mode: OTP generated locally.',
                ];
                $requestId = null;
            } else {
                $url  = $baseUrl . '/eaadhaar/otp';
                $data = [
                    'aadhaar_number' => $aadhaar,
                    'consent'        => 'Y',
                    'purpose'        => 'Housing Lottery Verification',
                ];

                $headers = [
                    'client-id: ' . $clientId,
                    'client-secret: ' . $clientSecret,
                    'Content-Type: application/json',
                ];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response  = curl_exec($ch);
                $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);

                if ($curlError) {
                    log_message('error', 'TruthScreen Aadhaar OTP curl error: ' . $curlError);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to contact Aadhaar verification service. Please try again later.',
                    ]);
                }

                $apiResponse = json_decode($response, true) ?? [];

                if ($httpCode !== 200 || ($apiResponse['status'] ?? '') !== 'success') {
                    $msg = $apiResponse['message'] ?? 'Unable to send OTP. Please try again later.';
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $msg,
                    ]);
                }

                // TruthScreen returns a request_id that must be used on verify
                $requestId = $apiResponse['request_id'] ?? null;
                if (empty($requestId)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'OTP could not be initiated: missing request reference.',
                    ]);
                }

                // In real mode we NEVER know or store the OTP value – it's sent directly to Aadhaar-linked mobile.
                $otpValue = null;
            }

            // Store request + (optional demo OTP) in database (upsert - one record per user per Aadhaar)
            // Use the $existing variable we already found above (unverified record for this user)
            $existingRecord = $existing;

            $otpData = [
                'user_id'        => $userId,
                'aadhaar_number' => $aadhaar,
                'otp'            => $otpValue,          // null in real mode
                'verified'       => 0,
                'request_id'     => $requestId,
                'api_response'   => json_encode($apiResponse),
            ];

            // Skip validation to avoid issues
            $this->aadhaarOtpModel->skipValidation(true);

            if ($existingRecord) {
                $result = $this->aadhaarOtpModel->update($existingRecord['id'], $otpData);
                if (!$result) {
                    $dbError = $this->aadhaarOtpModel->db->error();
                    log_message('error', 'Aadhaar OTP Update Failed: ' . json_encode($dbError));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to update OTP in database. Error: ' . ($dbError['message'] ?? 'Unknown error'),
                    ]);
                }
            } else {
                $result = $this->aadhaarOtpModel->insert($otpData);
                if (!$result) {
                    $dbError = $this->aadhaarOtpModel->db->error();
                    log_message('error', 'Aadhaar OTP Insert Failed: ' . json_encode($dbError));
                    log_message('error', 'OTP Data: ' . json_encode($otpData));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to save OTP in database. Error: ' . ($dbError['message'] ?? 'Unknown error'),
                        'debug' => $dbError, // Remove in production
                    ]);
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => lang('App.appAadhaarOtpGenerated'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Aadhaar OTP Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Verify Aadhaar OTP
     */
    public function verifyAadhaarOtp()
    {
        // Allow Aadhaar verification for both logged-in users and registration flow
        $userId = session()->has('user_id') ? session()->get('user_id') : 0; // 0 for registration flow
        $json   = $this->request->getJSON(true);

        $aadhaar = trim((string) ($this->request->getPost('aadhaar') ?? $json['aadhaar'] ?? ''));
        $otp     = trim((string) ($this->request->getPost('otp') ?? $json['otp'] ?? ''));

        if ($aadhaar === '' || $otp === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aadhaar number and OTP are required'
            ]);
        }

        // Find OTP record (for registration, check by aadhaar_number only with user_id = 0)
        if ($userId == 0) {
            $record = $this->aadhaarOtpModel
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 0)
                ->where('user_id', 0) // Registration flow
                ->orderBy('created_at', 'DESC')
                ->first();
        } else {
            $record = $this->aadhaarOtpModel
                ->where('user_id', $userId)
                ->where('aadhaar_number', $aadhaar)
                ->where('verified', 0)
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        if (!$record) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'OTP not found or already verified. Please generate a new OTP.'
            ]);
        }

        // Check if OTP is expired (15 minutes)
        $createdAt = strtotime($record['created_at']);
        $now = time();
        if (($now - $createdAt) > 900) { // 15 minutes
            return $this->response->setJSON([
                'success' => false,
                'message' => 'OTP has expired. Please generate a new OTP.'
            ]);
        }

        // Prepare TruthScreen verify-OTP call
        $clientId     = getenv('TRUTHSCREEN_CLIENT_ID');
        $clientSecret = getenv('TRUTHSCREEN_CLIENT_SECRET');
        $baseUrl      = rtrim(getenv('TRUTHSCREEN_BASE_URL') ?? 'https://api.truthscreen.com', '/');

        if (empty($clientId) || empty($clientSecret)) {
            // DEMO MODE: fall back to local OTP compare
            if ($otp !== (string) ($record['otp'] ?? '')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid OTP entered. Please try again.'
                ]);
            }

            $kycData = [];
        } else {
            $requestId = $record['request_id'] ?? null;
            if (empty($requestId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'OTP session expired. Please generate a new OTP.'
                ]);
            }

            $url  = $baseUrl . '/eaadhaar/verifyOtp';
            $data = [
                'request_id' => $requestId,
                'otp'        => $otp,
            ];

            $headers = [
                'client-id: ' . $clientId,
                'client-secret: ' . $clientSecret,
                'Content-Type: application/json',
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                log_message('error', 'TruthScreen Aadhaar verifyOtp curl error: ' . $curlError);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to contact Aadhaar verification service. Please try again later.',
                ]);
            }

            $apiResponse = json_decode($response, true) ?? [];

            if ($httpCode !== 200 || ($apiResponse['status'] ?? '') !== 'success') {
                $msg = $apiResponse['message'] ?? 'Invalid OTP entered. Please try again.';
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $msg,
                ]);
            }

            $kycData = $apiResponse['data'] ?? [];
        }

        // Build KYC fields – store only allowed data (no full Aadhaar)
        $aadhaarLast4 = substr($aadhaar, -4);

        $updateData = [
            'verified'      => 1,
            'aadhaar_last4' => $aadhaarLast4,
            'user_id'      => $userId, // Update user_id (0 for registration, actual user_id for logged-in users)
        ];

        if (!empty($kycData)) {
            $updateData['kyc_name']    = $kycData['name']    ?? null;
            $updateData['kyc_dob']     = $kycData['dob']     ?? null;
            $updateData['kyc_gender']  = $kycData['gender']  ?? null;
            $updateData['kyc_address'] = $kycData['address'] ?? null;
            $updateData['kyc_pincode'] = $kycData['pincode'] ?? null;
        }

        $this->aadhaarOtpModel->update($record['id'], $updateData);

        // Store verification in session for form submission (only if logged in)
        if ($userId > 0) {
            session()->set('aadhaar_verified', true);
            session()->set('aadhaar_number', $aadhaar);
        }

        return $this->response->setJSON([
            'success'  => true,
            'message'  => lang('App.appAadhaarVerifiedSuccess'),
            'verified' => true,
            'kyc'      => $kycData ?? [],
        ]);
    }

    /**
     * Check Aadhaar verification status
     */
    public function checkAadhaarVerification()
    {
        // Allow check for both logged-in users and registration flow
        $userId = session()->has('user_id') ? session()->get('user_id') : 0; // 0 for registration flow
        $jsonData = $this->request->getJSON(true);
        $aadhaar = trim((string) ($this->request->getPost('aadhaar') ?? $jsonData['aadhaar'] ?? ''));
        $aadhaar = preg_replace('/[\s\-]/', '', $aadhaar);

        if (empty($aadhaar) || !preg_match('/^[0-9]{12}$/', $aadhaar)) {
            return $this->response->setJSON([
                'success' => false,
                'verified' => false,
            ]);
        }

        // Check if verified by any user
        $verified = $this->aadhaarOtpModel
            ->where('aadhaar_number', $aadhaar)
            ->where('verified', 1)
            ->first();

        if ($verified) {
            // Check if same user
            if ($verified['user_id'] == $userId) {
                return $this->response->setJSON([
                    'success' => true,
                    'verified' => true,
                    'same_user' => true,
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'verified' => false,
                    'already_used' => true,
                    'message' => 'This Aadhaar number is already submitted by another user.',
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'verified' => false,
        ]);
    }
}


