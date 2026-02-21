<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class Dashboard extends Controller {
    public function __construct() {
        Session::requireLogin();
        // Load Models for stats
        $this->apartmentModel = $this->model('Apartment');
        $this->tenantModel = $this->model('Tenant');
        $this->paymentModel = $this->model('Payment');
    }

    public function index() {
        // Collect Stats
        $totalApartments = $this->apartmentModel->countApartments();
        $vacantApartments = $this->apartmentModel->countVacant();
        $occupiedApartments = $this->apartmentModel->countOccupied();
        $totalTenants = $this->tenantModel->countTenants();
        $totalRevenue = $this->paymentModel->totalRevenue();
        $pendingPayments = $this->paymentModel->totalPending();

        $data = [
            'active' => 'dashboard',
            'total_apartments' => $totalApartments,
            'vacant_apartments' => $vacantApartments,
            'occupied_apartments' => $occupiedApartments,
            'total_tenants' => $totalTenants,
            'total_revenue' => $totalRevenue,
            'pending_payments' => $pendingPayments
        ];

        $this->view('dashboard/index', $data);
    }
}
