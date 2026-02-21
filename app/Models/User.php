<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    // Register User
    // Register User (Now used by Admin to add users)
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, phone, image, role, is_active) VALUES (:name, :email, :password, :phone, :image, :role, :is_active)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':role', isset($data['role']) ? $data['role'] : 'user');
        $this->db->bind(':is_active', isset($data['is_active']) ? $data['is_active'] : 1);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        
        if($row) {
             $hashed_password = $row->password;
             if (password_verify($password, $hashed_password)) {
                 return $row;
             } else {
                 return false;
             }
        }
        return false;
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateProfile($data) {
        $this->db->query('UPDATE users SET name = :name, email = :email, phone = :phone, image = :image WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':image', $data['image']);
        return $this->db->execute();
    }

    // Admin: Get All Users
    public function getAllUsers() {
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Admin: Update User Role and Status
    public function updateUserStatusRole($id, $role, $is_active) {
        $this->db->query('UPDATE users SET role = :role, is_active = :is_active WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':role', $role);
        $this->db->bind(':is_active', $is_active);
        return $this->db->execute();
    }
}
