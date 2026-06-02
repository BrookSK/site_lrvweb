/**
 * LRV Web - JavaScript do Painel Administrativo
 */

'use strict';

// === CSRF Token Helper ===
function getCsrfToken() {
    const meta = document.querySelector('input[name="_token"]');
    return meta ? meta.value : '';
}

// === Fetch Helper ===
async function apiRequest(url, method = 'GET', data = null) {
    const options = {
        method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    };

    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    const response = await fetch(url, options);
    const result = await response.json();

    if (!response.ok) {
        throw new Error(result.message || 'Erro na requisição');
    }

    return result;
}

// === Confirm Delete ===
function confirmDelete(url, message = 'Tem certeza que deseja excluir?') {
    if (confirm(message)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = `
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="${getCsrfToken()}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// === Toast Notifications ===
function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500',
    };

    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white shadow-lg ${colors[type]} animate-slide-in`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// === Format Currency ===
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value);
}

// === Sidebar Mobile Toggle ===
document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
    const sidebar = document.querySelector('aside');
    sidebar.classList.toggle('hidden');
    sidebar.classList.toggle('fixed');
    sidebar.classList.toggle('inset-0');
    sidebar.classList.toggle('z-50');
});

// === Auto-close dropdowns on click outside ===
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('user-dropdown');
    const menu = document.getElementById('user-menu');
    if (dropdown && menu && !menu.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// === Datatable Search ===
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);

    if (!input || !table) return;

    input.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
}

console.log('%c LRV Web Admin %c v1.0.0 ', 'background: #2563eb; color: white; padding: 4px 8px; border-radius: 4px 0 0 4px;', 'background: #1e293b; color: white; padding: 4px 8px; border-radius: 0 4px 4px 0;');
