<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Unloading Form | Create Unloading</title>
    <link rel="stylesheet" href="./styles/unloading_form.css">

</head>

<body>

    <button class="btn-back" onclick="window.history.back();">
        ← Back
    </button>

    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;" class="form-header">
            <div>
                <h2>
                    ⚡ Unloading Monitoring Form
                    <span class="badge-form">Stellar Seeds Corp</span>
                </h2>
                <div class="subnote">
                    Fill in unloading details and item rows
                </div>
            </div>

            <button style="margin-top: 1rem;" class="btn-add" onclick="showImportModal()">
                📥 Import from Online Database
            </button>
        </div>

        <form method="POST" action="create_unloading.php" id="unloadingForm">
            <div class="form-section">
                <div class="section-title">
                    <span>📦</span> Unloading Details
                </div>
                <div class="fields-grid">
                    <div class="input-group">
                        <label>Client *</label>
                        <input name="client" id="client" placeholder="Enter client name" required>
                    </div>
                    <div class="input-group">
                        <label>Variety / Hybrid</label>
                        <input name="variety_hybrid" id="variety_hybrid" placeholder="e.g., HYBRID 101" required>
                    </div>
                    <div class="input-group">
                        <label>Material Group</label>
                        <input name="material_group" id="material_group" placeholder="Material group code" required>
                    </div>
                    <div class="input-group">
                        <label>Lot Number</label>
                        <input name="lot_number" id="lot_number" placeholder="Lot #" required>
                    </div>
                    <div class="input-group">
                        <label>Batch Number</label>
                        <input name="batch_number" id="batch_number" placeholder="Batch #" required>
                    </div>
                    <div class="input-group">
                        <label>Jumbo or Pallet?</label>
                        <select name="isJB" id="isJB">
                            <option value="0">Pallet</option>
                            <option value="1">JB</option>
                        </select>
                    </div>
                </div>

                <div class="yellow-rule"></div>

                <div class="fields-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px,1fr));">
                    <div class="input-group">
                        <label>⏱️ Time Start</label>
                        <input type="time" name="time_start" id="time_start" required>
                    </div>
                    <div class="input-group">
                        <label>⏱️ Time Finished</label>
                        <input type="time" name="time_finished" id="time_finished" required>
                    </div>
                    <div class="input-group">
                        <label>Prepared By</label>
                        <input name="prepared_by" id="prepared_by" placeholder="Name" required>
                    </div>
                    <div class="input-group">
                        <label>Checked By</label>
                        <input name="checked_by" id="checked_by" placeholder="Name">
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group">
                        <label>Remarks</label>
                        <textarea name="remarks" id="remarks" placeholder="Remarks/Purpose...."></textarea>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <span>📋</span> Unloading Items
                </div>
                <div style="padding: 0 1.5rem 1rem 1.5rem;">
                    <div class="table-wrapper">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <div style="display: flex; align-items: center; gap: 0.3rem;">
                                            <span>JB/Pallet No.</span>
                                            <span class="weight-hint">(Identifier for each pallet or JB)</span>
                                        </div>
                                    </th>
                                    <th>Bags/Sacks</th>
                                    <th>Weight (kg/lbs)</th>
                                    <th>Total Weight</th>
                                    <th style="width: 70px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons" style="margin-top: 1.25rem;">
                        <button type="button" class="btn-add" id="addRowBtn">+ Add new row</button>
                        <span style="font-size: 0.7rem; color: #bdbdc9;">Add multiple items for this unloading</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 0.5rem;">
                <button type="submit" class="btn-save">✓ Save Unloading</button>
            </div>
        </form>
    </div>

    <div id="importModal" class="modal">
        <div class="modal-content">
            <h3>📥 Import from Online Database</h3>
            <div class="modal-sub">Would you like to fetch data using Lot Number and Phase Type?</div>

            <div style="color: red;" class="modal-sub">Note: This requires an active internet connection</div>

            <div class="form-group">
                <label>Lot Number</label>
                <input type="text" id="modal_lot_number" placeholder="Enter lot number">
            </div>
            <div class="form-group">
                <label>Phase Type</label>
                <select id="modal_phase_type">
                    <option value="">Select Phase</option>
                    <option value="1">COM 300</option>
                    <option value="2">COM 500</option>
                </select>
                <span style="font-size: 0.7rem; color: #bdbdc9; display: block; margin-top: 0.25rem;">300 = Phase 1 | 500 = Phase 2</span>
            </div>
            <div class="modal-actions">
                <button class="btn-modal-no" onclick="closeImportModal()">Cancel</button>
                <button class="btn-modal-yes" id="confirmImportBtn">Fetch Data</button>
            </div>


        </div>
    </div>

    <script>
        let rowCounter = 0;

        function createTableRow(index, data = {}) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-row-idx', index);
            tr.innerHTML = `
            <td><input name="items[${index}][jb_pallet]" placeholder="Pallet / JB No." autocomplete="off" value="${escapeHtml(data.jb_pallet || '')}" required></td>
            <td><input name="items[${index}][bags_sacks_no]" type="number" step="1" placeholder="0" value="${data.bags_sacks_no || ''}" required></td>
            <td><input name="items[${index}][weight]" type="number" step="0.01" placeholder="0.00" value="${data.weight || ''}" ></td>
            <td><input name="items[${index}][total_weight]" type="number" step="0.01" placeholder="0.00" value="${data.total_weight || ''}" required></td>
            <td style="text-align:center"><button type="button" class="remove-btn" onclick="removeRow(this)">✖ Remove</button></td>
        `;
            return tr;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        const tbody = document.getElementById('tableBody');

        function initializeTable(items = null) {
            tbody.innerHTML = '';
            rowCounter = 0;
            if (items && items.length > 0) {
                items.forEach((item, idx) => {
                    const row = createTableRow(rowCounter, item);
                    tbody.appendChild(row);
                    rowCounter++;
                });
            } else {
                const firstRow = createTableRow(rowCounter, {});
                tbody.appendChild(firstRow);
                rowCounter++;
            }
        }

        function addNewRow(data = {}) {
            const newRow = createTableRow(rowCounter, data);
            tbody.appendChild(newRow);
            rowCounter++;
        }

        window.removeRow = function(btn) {
            const row = btn.closest('tr');
            if (row) {
                const tbodyRef = row.parentNode;
                if (tbodyRef.children.length <= 1) {
                    const inputs = row.querySelectorAll('input');
                    inputs.forEach(input => {
                        if (input) input.value = '';
                    });
                    return;
                }
                row.remove();
                reindexRows();
            }
        };

        function reindexRows() {
            const rows = tbody.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const nameAttr = input.getAttribute('name');
                    if (nameAttr) {
                        const match = nameAttr.match(/items\[(\d+)\]\[(.*?)\]/);
                        if (match) {
                            const field = match[2];
                            const newName = `items[${i}][${field}]`;
                            input.setAttribute('name', newName);
                        }
                    }
                });
                row.setAttribute('data-row-idx', i);
            }
            rowCounter = rows.length;
        }

        document.getElementById('addRowBtn').addEventListener('click', function() {
            addNewRow();
        });

        initializeTable();

        const form = document.getElementById('unloadingForm');
        form.addEventListener('submit', function(e) {
            const clientInput = form.querySelector('input[name="client"]');
            if (!clientInput.value.trim()) {
                e.preventDefault();
                clientInput.style.borderColor = '#FFB347';
                clientInput.focus();
                alert('Client name is required before saving.');
                return;
            }
        });

        const modal = document.getElementById('importModal');

        function showImportModal() {
            modal.style.display = 'flex';
            document.getElementById('modal_lot_number').value = document.getElementById('lot_number').value || '';
            document.getElementById('modal_phase_type').value = '';
        }

        function closeImportModal() {
            modal.style.display = 'none';
        }

        function showToast(message, isError = false) {
            const toast = document.createElement('div');
            toast.className = 'import-toast';
            toast.style.background = isError ? '#c0392b' : '#1e1e2a';
            toast.innerHTML = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        async function fetchOnlineData(lotNumber, phaseType) {
            try {
                const response = await fetch('fetch_online_unloading_data.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        lot_number: lotNumber,
                        phase_type: phaseType
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Fetch error:', error);
                throw error;
            }
        }

        function populateFormWithData(data) {
            if (data.client) document.getElementById('client').value = data.client;
            if (data.variety_hybrid) document.getElementById('variety_hybrid').value = data.variety_hybrid;
            if (data.material_group) document.getElementById('material_group').value = data.material_group;
            if (data.lot_number) document.getElementById('lot_number').value = data.lot_number;
            if (data.batch_number) document.getElementById('batch_number').value = data.batch_number;
            if (data.isJB !== undefined) document.getElementById('isJB').value = data.isJB;
            if (data.time_start) document.getElementById('time_start').value = data.time_start;
            if (data.time_finished) document.getElementById('time_finished').value = data.time_finished;
            if (data.prepared_by) document.getElementById('prepared_by').value = data.prepared_by;
            if (data.checked_by) document.getElementById('checked_by').value = data.checked_by;
            if (data.remarks) document.getElementById('remarks').value = data.remarks;

            if (data.items && Array.isArray(data.items) && data.items.length > 0) {
                initializeTable(data.items);
            } else if (data.items && data.items.length === 0) {
                initializeTable();
            }
        }

        document.getElementById('confirmImportBtn').addEventListener('click', async function() {
            const lotNumber = document.getElementById('modal_lot_number').value.trim();
            const phaseType = document.getElementById('modal_phase_type').value;

            if (!lotNumber) {
                alert('Please enter a Lot Number');
                return;
            }

            if (!phaseType) {
                alert('Please select a Phase Type (300 or 500)');
                return;
            }

            const originalBtnText = this.innerHTML;
            this.innerHTML = '<span class="loading-spinner"></span> Fetching...';
            this.disabled = true;

            try {
                const result = await fetchOnlineData(lotNumber, phaseType);

                if (result.success) {
                    populateFormWithData(result.data);
                    showToast('✓ Data imported successfully from online database!');
                    closeImportModal();
                } else {
                    showToast(result.message || 'No data found for the given Lot Number and Phase Type', true);
                }
            } catch (error) {
                console.error('Import error:', error);
                showToast('Error connecting to online database. Please try again.', true);
            } finally {
                this.innerHTML = originalBtnText;
                this.disabled = false;
            }
        });

        window.onclick = function(event) {
            if (event.target === modal) {
                closeImportModal();
            }
        }

        setTimeout(() => {
            showImportModal();
        }, 500);

        const clientField = document.querySelector('input[name="client"]');
        if (clientField) {
            clientField.addEventListener('input', function() {
                this.style.borderColor = '#e9e9e2';
            });
        }

        const style = document.createElement('style');
        style.textContent = `
            .items-table input[type="number"] {
                -moz-appearance: textfield;
            }
            .items-table input[type="number"]::-webkit-inner-spin-button, 
            .items-table input[type="number"]::-webkit-outer-spin-button {
                opacity: 0.5;
            }
            button[type="submit"]:active {
                transform: scale(0.98);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>