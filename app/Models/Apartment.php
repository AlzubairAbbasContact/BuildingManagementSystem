<?php

namespace App\Models;

use App\Core\Model;

class Apartment extends Model {
    public function getAllApartments() {
        $this->db->query('SELECT * FROM apartments ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getApartmentById($id) {
        $this->db->query('SELECT * FROM apartments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addApartment($data) {
        $this->db->query('INSERT INTO apartments (apartment_number, floor, status, notes) VALUES (:apartment_number, :floor, :status, :notes)');
        $this->db->bind(':apartment_number', $data['apartment_number']);
        $this->db->bind(':floor', $data['floor']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function updateApartment($data) {
        $this->db->query('UPDATE apartments SET apartment_number = :apartment_number, floor = :floor, status = :status, notes = :notes WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':apartment_number', $data['apartment_number']);
        $this->db->bind(':floor', $data['floor']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function deleteApartment($id) {
        // Soft-delete behavior: mark apartment as vacant instead of hard delete
        $this->db->query('UPDATE apartments SET status = "vacant" WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Stats
    public function countApartments() {
        $this->db->query('SELECT COUNT(*) as count FROM apartments');
        $row = $this->db->single();
        return $row->count;
    }

    public function countVacant() {
        $this->db->query('SELECT COUNT(*) as count FROM apartments WHERE status = "vacant"');
        $row = $this->db->single();
        return $row->count;
    }

    public function countOccupied() {
        $this->db->query('SELECT COUNT(*) as count FROM apartments WHERE status = "occupied"');
        $row = $this->db->single();
        return $row->count;
    }

    // Check if apartment exists
    public function checkDuplicate($number, $floor) {
        $this->db->query('SELECT * FROM apartments WHERE apartment_number = :number AND floor = :floor');
        $this->db->bind(':number', $number);
        $this->db->bind(':floor', $floor);
        
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Get only vacant apartments
    public function getVacantApartments() {
        $this->db->query('SELECT * FROM apartments WHERE status = "vacant" ORDER BY apartment_number ASC');
        return $this->db->resultSet();
    }
}
