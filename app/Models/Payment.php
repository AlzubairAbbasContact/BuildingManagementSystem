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
        // Insert payment without storing amount_remaining (calculated dynamically)
        $this->db->query('INSERT INTO payments (tenant_id, amount_paid, payment_date, notes, status) VALUES (:tenant_id, :amount_paid, :payment_date, :notes, :status)');
        $this->db->bind(':tenant_id', $data['tenant_id']);
        $this->db->bind(':amount_paid', $data['amount_paid']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':status', isset($data['status']) ? $data['status'] : 'active');

        return $this->db->execute();
    }

    // Cancel (soft-delete) a payment
    public function cancelPayment($id, $reason = null) {
        $this->db->query('UPDATE payments SET status = "canceled", cancellation_reason = :reason WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':reason', $reason);
        return $this->db->execute();
    }

    // Backwards-compatible alias (deprecated)
    public function deletePayment($id) {
        return $this->cancelPayment($id, null);
    }

    // Stats
    public function totalRevenue() {
        $this->db->query('SELECT SUM(amount_paid) as total FROM payments WHERE status = "active"');
        $row = $this->db->single();
        return $row->total ?: 0;
    }

    // Total pending across all active tenants (rent - sum(active payments))
    public function totalPending() {
        $sql = "SELECT SUM(x.remaining) as total FROM (
                  SELECT t.id, (t.rent_amount - COALESCE(SUM(p.amount_paid),0)) as remaining
                  FROM tenants t
                  LEFT JOIN payments p ON p.tenant_id = t.id AND p.status = 'active'
                  WHERE t.status = 'active'
                  GROUP BY t.id
                ) x";
        $this->db->query($sql);
        $row = $this->db->single();
        return $row->total ?: 0;
    }

    // Sum of active payments for a tenant
    public function sumActivePaymentsByTenant($tenant_id) {
        $this->db->query('SELECT COALESCE(SUM(amount_paid),0) as total FROM payments WHERE tenant_id = :tid AND status = "active"');
        $this->db->bind(':tid', $tenant_id);
        $row = $this->db->single();
        return $row->total ?: 0;
    }
}
