<?php

namespace App\Controllers\Web;
use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function process()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validate input
        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $client = \Config\Services::curlrequest();

        try {
            $response = $client->request('POST', env('API_BASE_URL'). '/login', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ],
                'verify' => false,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() !== 200) {
                $errorBody = json_decode($response->getBody());
                $pesanError = $errorBody->message ?? 'Email atau password salah.';
                return redirect()->back()->with('error', 'Login gagal! ' . $pesanError);
            }

            $body = json_decode($response->getBody());

            if (isset($body->refreshToken)) {
                session()->set('refreshToken', $body->refreshToken);
                session()->set('email', $email);

                return redirect()->to('/master');
            }

        } catch (\Exception $e) {
            log_message('error', 'Login Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sistem sedang gangguan. Silakan coba lagi.');
        }
    }
    public function logout()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('refreshToken');

        try {
            $client->request('POST', env('API_BASE_URL').'/logout', [
                'json' => [
                    'refreshToken' => $token
                ],
                'verify' => false,
                'http_errors' => false
            ]);
        } catch (\Exception $e) {
            log_message('warning', 'Logout API call failed: ' . $e->getMessage());
        }

        session()->destroy();

        return redirect()->to('/')->with('success', 'Anda telah keluar dari sistem.');
    }
}
