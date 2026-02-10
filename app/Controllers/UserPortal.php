<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use App\Models\UserModel;
use App\Models\EligibilityModel;
use App\Models\PaymentModel;
use App\Models\ApplicationDocumentModel;

class UserPortal extends BaseController
{
    protected $userModel;
    protected $applicationModel;
    protected $eligibilityModel;
    protected $paymentModel;
    protected $documentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->applicationModel = new ApplicationModel();
        $this->eligibilityModel = new EligibilityModel();
        $this->paymentModel = new PaymentModel();
        $this->documentModel = new ApplicationDocumentModel();
    }

    /**
     * Return list of required annexure forms (JPGs) based on applicant category.
     * This mirrors the summary table from the Sindoor Vihar booklet.
     */
    private function resolveFormsForCategory(?string $category): array
    {
        $category   = trim((string) $category);
        $formsBase  = base_url('assets/documentform/') . '/';

        // Base annexures (I–VII) we currently have as JPGs
        $annexI  = ['label' => 'Annexure I – Self Declaration / Affidavit (All)',              'url' => $formsBase . 'BookLet Sindoor Vihar_page-0015.jpg'];
        $annexII = ['label' => 'Annexure II – Income Certificate (All)',                        'url' => $formsBase . 'BookLet Sindoor Vihar_page-0016.jpg'];
        $annexIII= ['label' => 'Annexure III – SC/ST Certificate (SC/ST)',                     'url' => $formsBase . 'BookLet Sindoor Vihar_page-0017.jpg'];
        $annexIV = ['label' => 'Annexure IV – Soldier Certificate (Serving/Ex‑Serviceman)',    'url' => $formsBase . 'BookLet Sindoor Vihar_page-0018.jpg'];
        $annexV  = ['label' => 'Annexure V – Soldier Family Affidavit (Widow/Dependent)',      'url' => $formsBase . 'BookLet Sindoor Vihar_page-0019.jpg'];
        $annexVI = ['label' => 'Annexure VI – Soldier Undertaking (Soldier)',                  'url' => $formsBase . 'BookLet Sindoor Vihar_page-0020.jpg'];
        $annexVII= ['label' => 'Annexure VII – Disability Certificate (Divyang / PwD)',        'url' => $formsBase . 'BookLet Sindoor Vihar_page-0021.jpg'];

        // Categories where only general annexures (I & II) apply
        $generalOnly = [
            'General',
            'EWS',
            'LIG',
            'MIG-A',
            'MIG-B',
            'HIG',
            'Central Govt Employee',
            'State Govt Employee',
            'PSU Employee',
            'Destitute Woman',
            'Landless Woman',
            'Single Woman/Widow',
        ];

        // Start with common annexures for "All"
        $required = [$annexI, $annexII];

        if (in_array($category, $generalOnly, true) || $category === '') {
            return $required;
        }

        // SC / ST additional certificate
        if ($category === 'SC' || $category === 'ST') {
            $required[] = $annexIII;
            return $required;
        }

        // Soldier related categories
        if (in_array($category, ['Serving Soldier', 'Ex-Serviceman', 'Soldier Widow/Dependent'], true)) {
            $required[] = $annexIV;
            $required[] = $annexV;
            $required[] = $annexVI;
            return $required;
        }

        // Divyang
        if ($category === 'Divyang (PwD)') {
            $required[] = $annexVII;
            return $required;
        }

        // Accredited Journalist, Transgender, etc. currently only need general forms
        return $required;
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
        
        $data['user'] = [
            'id' => session()->get('user_id'),
            'name' => session()->get('user_name'),
            'mobile' => session()->get('user_mobile'),
            'email' => session()->get('user_email')
        ];
        $data['application'] = $application;
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

        // Load latest application (if any) for pre-filling the form
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        $data = [
            'application' => $application,
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

        $data = [
            'user_id'                  => session()->get('user_id'),
            'full_name'                => (string) $this->request->getPost('full_name'),
            'aadhaar'                  => (string) $this->request->getPost('aadhaar'),
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
            'declaration_truth'        => $this->request->getPost('declaration_truth') ? 1 : 0,
            'declaration_cancellation' => $this->request->getPost('declaration_cancellation') ? 1 : 0,
            'status'                   => 'submitted',
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

        // Overall status label (Pending vs Submitted) for this page
        if (! $application) {
            $overallStatus = 'none';
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

        // Require application to exist
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (! $application) {
            return redirect()->to('/user/application')->with('error', 'Please submit your application form before uploading documents.');
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

            $identityFiles  = [];
            $incomeFiles    = [];
            $residenceFiles = [];
            $annexureFiles  = [];

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

            if (! empty($identityFiles)) {
                $data['identity_files'] = json_encode($identityFiles);
            }
            if (! empty($incomeFiles)) {
                $data['income_files'] = json_encode($incomeFiles);
            }
            if (! empty($residenceFiles)) {
                $data['residence_files'] = json_encode($residenceFiles);
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

            $existing = $this->documentModel
                ->where('user_id', $userId)
                ->where('application_id', $application['id'])
                ->first();

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

            return redirect()->to('/user/payment')
                ->with('success', 'Documents details saved successfully. Please proceed to payment.');
        }

        $documents = $this->documentModel
            ->where('user_id', $userId)
            ->where('application_id', $application['id'])
            ->first();

        // Determine required downloadable forms based on application category.
        $requiredForms = $this->resolveFormsForCategory($application['income_category'] ?? null);

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

        // Require documents step to be completed
        $application = $this->applicationModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (! $application) {
            return redirect()->to('/user/application')->with('error', 'Please submit your application form before payment.');
        }

        $documents = $this->documentModel
            ->where('user_id', $userId)
            ->where('application_id', $application['id'])
            ->first();

        if (! $documents) {
            return redirect()->to('/user/documents')->with('error', 'Please submit your document details before payment.');
        }

        if ($this->request->getMethod() === 'post') {
            $amount = (int) $this->request->getPost('amount');

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'अमान्य भुगतान राशि।');
            }

            $this->paymentModel->insert([
                'user_id'        => $userId,
                'application_id' => $application['id'],
                'amount'         => $amount,
                'status'         => 'success',
                'transaction_ref'=> null,
            ]);

            // Mark application as paid so that admin flow matches booklet-style process
            $this->applicationModel->update($application['id'], [
                'status' => 'paid',
            ]);

            return redirect()->to('/user/application/status')
                ->with('success', 'भुगतान सफलतापूर्वक दर्ज हो गया है।');
        }

        return view('layout/header')
            . view('user/payment')
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

            $updateData = [
                'name'     => $name,
                'email'    => $email,
                'language' => $language,
            ];

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

        return view('layout/header')
            . view('user/lottery_results')
            . view('layout/footer');
    }

    public function allotment()
    {
        // Check if user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to view allotment');
        }

        return view('layout/header')
            . view('user/allotment')
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
}


