<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    private function checkRole($requiredRole)
    {
        $session = session();
        if (! $session->get('isLoggedIn') || $session->get('role') !== $requiredRole) {
            return redirect()->to('/signin');
        }
        return null;
    }

    public function admin()
    {
        if ($redirect = $this->checkRole('admin')) return $redirect;
        return view('dashboard', ['name' => session()->get('name'), 'role' => 'Admin']);
    }

    public function employee()
    {
        if ($redirect = $this->checkRole('employee')) return $redirect;
        return view('dashboard', ['name' => session()->get('name'), 'role' => 'Employee']);
    }

    public function customer()
    {
        if ($redirect = $this->checkRole('customer')) return $redirect;
        return view('dashboard', ['name' => session()->get('name'), 'role' => 'Customer']);
    }
}