<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Unloading Form | Create Unloading</title>
    <link rel="stylesheet" href="./styles/unloading_form.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, sans-serif;
            line-height: 1.5;
            color: #1e1e2a;
            padding: 2rem 1.5rem;
        }

        .form-container {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.02);
            border: 1px solid #f0f0ee;
            overflow: hidden;
        }

        .form-header {
            background: #ffffff;
            padding: 1.8rem 2rem 1rem 2rem;
            border-bottom: 2px solid #FFE770;
        }

        .form-header h2 {
            font-size: 1.65rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, #1e1e2a 0%, #2c2c3a 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .badge-form {
            display: inline-block;
            background: #FFF7D6;
            padding: 0.2rem 0.7rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #b17f00;
            margin-left: 0.75rem;
            vertical-align: middle;
        }

        .subnote {
            color: #5e5e6e;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            border-left: 3px solid #FFDC4A;
            padding-left: 0.8rem;
        }

        form {
            padding: 2rem 2rem 2.5rem 2rem;
        }

        .form-section {
            background: #ffffff;
            border-radius: 1.25rem;
            margin-bottom: 2rem;
            border: 1px solid #f3f3eb;
            transition: all 0.2s ease;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            color: #1e1e2a;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px dashed #fff0c0;
            margin-bottom: 1.25rem;
        }

        .section-title span {
            background: #FFFAE6;
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #dba000;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .fields-grid {
            padding: 0 1.5rem 1.5rem 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.25rem;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .input-group label {
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8e8e9e;
        }

        input,
        textarea,
        select {
            font-family: inherit;
            background: #ffffff;
            border: 1px solid #e9e9e2;
            border-radius: 1rem;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            color: #1a1a24;
            outline: none;
            width: 100%;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #FFDC4A;
            box-shadow: 0 0 0 3px rgba(255, 220, 74, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .yellow-rule {
            height: 2px;
            background: #FFEFBF;
            width: 100%;
            margin: 0.5rem 0 1rem 0;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 1.25rem;
            margin-top: 0.5rem;
            border: 1px solid #f0f0e8;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            background: white;
        }

        .items-table th {
            text-align: left;
            padding: 1rem 0.8rem;
            background-color: #FFFDF5;
            font-weight: 600;
            color: #4a4a5a;
            border-bottom: 1px solid #FFEAB0;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .items-table td {
            padding: 0.7rem 0.8rem;
            border-bottom: 1px solid #f5f5ed;
            vertical-align: middle;
        }

        .items-table input {
            border-radius: 0.75rem;
            padding: 0.5rem 0.7rem;
            width: 100%;
            min-width: 100px;
        }

        .remove-btn {
            background: none;
            border: 1px solid #ffe0b3;
            border-radius: 2rem;
            padding: 0.35rem 1rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: #b87c00;
            cursor: pointer;
            transition: all 0.2s;
            background: #FFFCF0;
        }

        .remove-btn:hover {
            background: #FFEDB5;
            border-color: #FFD966;
            color: #8b5e00;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
            align-items: center;
        }

        .btn-add {
            background: #ffffff;
            border: 1px solid #FFE078;
            border-radius: 2rem;
            padding: 0.6rem 1.4rem;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            color: #c28b00;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .btn-add:hover {
            background: #FFFBE6;
            border-color: #FFD44A;
            transform: translateY(-1px);
        }

        .btn-save {
            background: #1e1e2a;
            border: none;
            border-radius: 2rem;
            padding: 0.7rem 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            color: white;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-save:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.15);
        }

        .btn-back {
            background: #1e1e2a;
            border: none;
            border-radius: 2rem;
            padding: 0.7rem 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            color: white;
            transition: all 0.2s;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .btn-back:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
        }

        .weight-hint {
            font-size: 0.7rem;
            color: #b4b4c0;
            margin-top: 4px;
        }

        @media (max-width: 760px) {
            body {
                padding: 1rem;
            }

            form {
                padding: 1.2rem;
            }

            .fields-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-header {
                padding: 1.2rem 1.2rem 0.8rem 1.2rem;
            }

            .section-title {
                padding: 0.8rem 1rem 0.2rem 1rem;
            }

            .fields-grid {
                padding: 0 1rem 1rem 1rem;
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 1.5rem;
            max-width: 500px;
            width: 90%;
            padding: 2rem;
            box-shadow: 0 30px 40px -20px rgba(0, 0, 0, 0.3);
            border: 1px solid #f0f0e8;
        }

        .modal-content h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e1e2a;
            border-left: 4px solid #FFDC4A;
            padding-left: 0.8rem;
        }

        .modal-sub {
            color: #6e6e7e;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            padding-left: 1.2rem;
        }

        .modal .form-group {
            margin-bottom: 1.2rem;
        }

        .modal .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8e8e9e;
            margin-bottom: 0.3rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .modal-actions button {
            padding: 0.6rem 1.5rem;
            border-radius: 2rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-modal-yes {
            background: #1e1e2a;
            color: white;
        }

        .btn-modal-yes:hover {
            background: #2c2c3a;
            transform: translateY(-1px);
        }

        .btn-modal-no {
            background: #ffffff;
            border: 1px solid #e9e9e2;
            color: #7a7a8a;
        }

        .btn-modal-no:hover {
            background: #f5f5ef;
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #FFDC4A;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .import-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1e1e2a;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            z-index: 1001;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: fadeInOut 3s ease;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            15% {
                opacity: 1;
                transform: translateY(0);
            }

            85% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(20px);
            }
        }
    </style>
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