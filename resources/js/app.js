

import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

import * as bootstrap from 'bootstrap';
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.bootstrap = bootstrap;

Alpine.start();


// Mobile sidebar
document.addEventListener('DOMContentLoaded', () => {

    const sidebar = document.getElementById('sidebar');
    const closeButton = document.getElementById('sidebarClose');

    if (sidebar && closeButton) {

        closeButton.addEventListener('click', () => {

            const sidebarInstance =
                bootstrap.Offcanvas.getOrCreateInstance(sidebar);

            sidebarInstance.hide();

        });

    }

});




document.addEventListener('DOMContentLoaded', () => {

    // Sidebar close button
    const sidebar = document.getElementById('sidebar');
    const closeButton = document.getElementById('sidebarClose');

    if (sidebar && closeButton) {
        closeButton.addEventListener('click', () => {

            const sidebarInstance =
                bootstrap.Offcanvas.getOrCreateInstance(sidebar);

            sidebarInstance.hide();
        });
    }


    // Payment collection chart
    const paymentChart =
        document.getElementById('paymentCollectionChart');

    if (paymentChart) {

        new Chart(paymentChart, {
            type: 'bar',

            data: {
                labels: [
                    'M-Pesa',
                    'Cash',
                    'Bank',
                    'Cheque'
                ],

                datasets: [
                    {
                        label: 'Amount Collected (KSh)',

                        data: [
                            Number(paymentChart.dataset.mpesa),
                            Number(paymentChart.dataset.cash),
                            Number(paymentChart.dataset.bank),
                            Number(paymentChart.dataset.cheque)
                        ]
                    }
                ]
            },

            options: {
                responsive: true,

                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

});