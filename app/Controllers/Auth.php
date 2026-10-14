<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('registration');
    }

    public function signin()
    {
        return view('signin');
    }

    public function attemptRegister()
    {
        $model = new UserModel();

        $rules = [
            'firstname'       => 'required',
            'lastname'        => 'required',
            'email'           => 'required|valid_email|is_unique[users.email]',
            'username'        => 'required|min_length[4]|is_unique[users.username]',
            'password'        => 'required|min_length[8]',
            'confirmPassword' => 'matches[password]',
            'accountType'     => 'required',
        ];

        if ($this->request->getVar('accountType') === 'employee') {
            $rules['department'] = 'required';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'role'         => $this->request->getVar('accountType') === 'employee' ? 'employee' : 'customer',
            'firstname'    => $this->request->getVar('firstname'),
            'middle_name'  => $this->request->getVar('middle_name'),
            'last_name'    => $this->request->getVar('lastname'),
            'birthdate'    => $this->request->getVar('birthdate'),
            'gender'       => $this->request->getVar('gender'),
            'email'        => $this->request->getVar('email'),
            'phone_number' => $this->request->getVar('phone'),
            'address'      => $this->request->getVar('address'),
            'department'   => $this->request->getVar('department'),
            'username'     => $this->request->getVar('username'),
            'password'     => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/signin')->with('success', 'Account created successfully! Please sign in.');
    }

    public function attemptSignin()
    {
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session = session();
            $session->set([
                'user_id'    => $user['user_id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'name'       => $user['firstname'] . ' ' . $user['last_name'],
                'isLoggedIn' => true,
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($user['role'] === 'employee') {
                return redirect()->to('/employee/dashboard');
            } else {
                return redirect()->to('/customer/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/signin');
    }
}