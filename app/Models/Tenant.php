<?php

namespace App\Models;

use App\Core\Model;

class Tenant extends Model {
    public function getAllTenants() {
        $this->db->query('SELECT tenants.*, apartments.apartment_number, apartments.floor 
                          FROM tenants 
                          JOIN apartments ON tenants.apartment_id = apartments.id 
                          ORDER BY tenants.created_at DESC');
        return $this->db->resultSet();
    }

    public function getTenantById($id) {
        $this->db->query('SELECT * FROM tenants WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addTenant($data) {
        $this->db->query('INSERT INTO tenants (apartment_id, name, nid, phone, rent_amount, start_date, end_date) VALUES (:apartment_id, :name, :nid, :phone, :rent_amount, :start_date, :end_date)');
        $this->db->bind(':apartment_id', $data['apartment_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':nid', $data['nid']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':rent_amount', $data['rent_amount']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);

        return $this->db->execute();
    }

    public function updateTenant($data) {
        $this->db->query('UPDATE tenants SET apartment_id = :apartment_id, name = :name, nid = :nid, phone = :phone, rent_amount = :rent_amount, start_date = :start_date, end_date = :end_date WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':apartment_id', $data['apartment_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':nid', $data['nid']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':rent_amount', $data['rent_amount']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);

        return $this->db->execute();
    }

    public function deleteTenant($id) {
        $this->db->query('DELETE FROM tenants WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countTenants() {
        $this->db->query('SELECT COUNT(*) as count FROM tenants');
        $row = $this->db->single();
        return $row->count;
    }
}
