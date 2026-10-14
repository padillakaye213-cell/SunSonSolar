<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = [
        'role', 'firstname', 'middle_name', 'last_name', 'birthdate',
        'gender', 'email', 'phone_number', 'address', 'department',
        'username', 'password',
    ];

    protected $useTimestamps = false;
}