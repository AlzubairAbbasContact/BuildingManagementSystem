<?php

namespace App\Models;

use App\Core\Model;

class Payment extends Model {
    public function getAllPayments() {
        $this->db->query('SELECT payments.*, tenants.name as tenant_name 
                          FROM payments 
                          JOIN tenants ON payments.tenant_id = tenants.id 
                          ORDER BY payments.created_at DESC');
        return $this->db->resultSet();
    }

    public function getPaymentById($id) {
        $this->db->query('SELECT * FROM payments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addPayment($data) {
        $this->db->query('INSERT INTO payments (tenant_id, amount_paid, amount_remaining, payment_date, notes) VALUES (:tenant_id, :amount_paid, :amount_remaining, :payment_date, :notes)');
        $this->db->bind(':tenant_id', $data['tenant_id']);
        $this->db->bind(':amount_paid', $data['amount_paid']);
        $this->db->bind(':amount_remaining', $data['amount_remaining']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function deletePayment($id) {
        $this->db->query('DELETE FROM payments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Stats
    public function totalRevenue() {
        $this->db->query('SELECT SUM(amount_paid) as total FROM payments');
        $row = $this->db->single();
        return $row->total ?: 0;
    }

    public function totalPending() {
        $this->db->query('SELECT SUM(amount_remaining) as total FROM payments');
        $row = $this->db->single();
        return $row->total ?: 0;
    }
}
