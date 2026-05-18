<?php
// ============================================================
// app/controllers/AuthController.php
// ============================================================

class AuthController extends Controller {

    public function __construct() {
        // Load required model
    }

    // Show login page
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['user_role']);
        }
        $this->generateCsrf();
        $flash = $this->getFlash();
        $this->render('auth.login', ['flash' => $flash, 'title' => 'Login'], 'auth');
    }

    // Handle login form submission
    public function doLogin() {
        $this->validateCsrf();
        
        $email    = filter_var($this->getPost('email'), FILTER_SANITIZE_EMAIL);
        $password = $this->getPost('password');

        if (empty($email) || empty($password)) {
            $this->setFlash(ALERT_DANGER, 'Email and password are required.');
            $this->redirect('auth/login');
        }

        $userModel = $this->model('UserModel');
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            // Set session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            // Update last login
            $userModel->updateLastLogin($user['id']);

            // Generate CSRF token for new session
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $this->setFlash(ALERT_SUCCESS, 'Welcome back, ' . $user['name'] . '!');
            $this->redirectByRole($user['role']);
        } else {
            $this->setFlash(ALERT_DANGER, 'Invalid email or password.');
            $this->redirect('auth/login');
        }
    }

    // Show registration page
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['user_role']);
        }
        $this->generateCsrf();
        $flash = $this->getFlash();
        $this->render('auth.register', ['flash' => $flash, 'title' => 'Register'], 'auth');
    }

    // Handle registration
    public function doRegister() {
        $this->validateCsrf();

        $name     = htmlspecialchars(trim($this->getPost('name')), ENT_QUOTES, 'UTF-8');
        $email    = filter_var($this->getPost('email'), FILTER_SANITIZE_EMAIL);
        $password = $this->getPost('password');
        $confirm  = $this->getPost('confirm_password');
        $farmName = htmlspecialchars(trim($this->getPost('farm_name')), ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars(trim($this->getPost('location')), ENT_QUOTES, 'UTF-8');
        $farmSize = floatval($this->getPost('farm_size'));

        // Validation
        $errors = [];
        if (empty($name))     $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm)  $errors[] = 'Passwords do not match.';
        if (empty($farmName)) $errors[] = 'Farm name is required.';
        if (empty($location)) $errors[] = 'Location is required.';
        if ($farmSize <= 0)   $errors[] = 'Farm size must be greater than 0.';

        if (!empty($errors)) {
            $this->setFlash(ALERT_DANGER, implode('<br>', $errors));
            $this->redirect('auth/register');
        }

        $userModel = $this->model('UserModel');

        if ($userModel->emailExists($email)) {
            $this->setFlash(ALERT_DANGER, 'Email already registered. Please login.');
            $this->redirect('auth/login');
        }

        // Create user
        $userId = $userModel->register([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'role'     => 'farmer'
        ]);

        if ($userId) {
            // Create farmer profile
            $farmerModel = $this->model('FarmerModel');
            $farmerModel->create([
                'user_id'      => $userId,
                'farm_name'    => $farmName,
                'location'     => $location,
                'farm_size'    => $farmSize,
                'soil_type'    => $this->getPost('soil_type') ?: 'loamy',
                'water_source' => $this->getPost('water_source') ?: 'borewell',
                'phone'        => $this->getPost('phone') ?: '',
            ]);

            // Log user in automatically after registration
            $_SESSION['user_id']    = $userId;
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role']  = 'farmer';
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $userModel->updateLastLogin($userId);
            $this->setFlash(ALERT_SUCCESS, 'Registration successful! Welcome to AquaFarm.');
            $this->redirectByRole('farmer');
        } else {
            $this->setFlash(ALERT_DANGER, 'Registration failed. Please try again.');
            $this->redirect('auth/register');
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: ' . APP_URL . '/auth/login');
        exit();
    }

    // Redirect based on role
    private function redirectByRole($role) {
        if ($role === 'admin') {
            $this->redirect('admin/dashboard');
        } else {
            $this->redirect('dashboard');
        }
    }
}
