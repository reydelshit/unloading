<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Inventory System | Create Unloading</title>
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
        textarea:focus {
            border-color: #FFDC4A;
            box-shadow: 0 0 0 3px rgba(255, 220, 74, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .time-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: flex-end;
        }

        .time-row .input-group {
            flex: 1;
            min-width: 160px;
        }

        hr {
            margin: 1rem 0;
            border: none;
            border-top: 1px solid #fff0cf;
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

        .yellow-rule {
            height: 2px;
            background: #FFEFBF;
            width: 100%;
            margin: 0.5rem 0 1rem 0;
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

        input::placeholder,
        textarea::placeholder {
            color: #cacad2;
            font-weight: 400;
        }

        .weight-hint {
            font-size: 0.7rem;
            color: #b4b4c0;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-header">
            <h2>
                ⚡ Create Unloading Ticket
                <span class="badge-form">Stellar Seeds Corp</span>
            </h2>
            <div class="subnote">
                Fill in unloading details and item rows — all fields sync with monitoring system
            </div>
        </div>

        <form method="POST" action="create_unloading.php" id="unloadingForm">
            <div class="form-section">
                <div class="section-title">
                    <span>📦</span> Unloading Details
                </div>
                <div class="fields-grid">
                    <div class="input-group">
                        <label>Client *</label>
                        <input name="client" placeholder="Enter client name" required>
                    </div>
                    <div class="input-group">
                        <label>Variety / Hybrid</label>
                        <input name="variety_hybrid" placeholder="e.g., HYBRID 101">
                    </div>
                    <div class="input-group">
                        <label>Material Group</label>
                        <input name="material_group" placeholder="Material group code">
                    </div>
                    <div class="input-group">
                        <label>Lot Number</label>
                        <input name="lot_number" placeholder="Lot #">
                    </div>
                    <div class="input-group">
                        <label>Batch Number</label>
                        <input name="batch_number" placeholder="Batch #">
                    </div>
                </div>

                <div class="yellow-rule"></div>

                <div class="fields-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px,1fr));">
                    <div class="input-group">
                        <label>⏱️ Time Start</label>
                        <input type="time" name="time_start">
                    </div>
                    <div class="input-group">
                        <label>⏱️ Time Finished</label>
                        <input type="time" name="time_finished">
                    </div>
                    <div class="input-group">
                        <label>Prepared By</label>
                        <input name="prepared_by" placeholder="Name / ID">
                    </div>
                    <div class="input-group">
                        <label>Checked By</label>
                        <input name="checked_by" placeholder="Name / ID">
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group">
                        <label>Remarks</label>
                        <textarea name="remarks" placeholder="Optional notes, quality flags, observations..."></textarea>
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
                                    <th>JB Pallet</th>
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

    <script>
        let rowCounter = 0;

        function createTableRow(index) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-row-idx', index);
            tr.innerHTML = `
            <td><input name="items[${index}][jb_pallet]" placeholder="Pallet ID" autocomplete="off"></td>
            <td><input name="items[${index}][bags_sacks_no]" type="number" step="1" placeholder="0"></td>
            <td><input name="items[${index}][weight]" type="number" step="0.01" placeholder="0.00"></td>
            <td><input name="items[${index}][total_weight]" type="number" step="0.01" placeholder="0.00"></td>
            <td style="text-align:center"><button type="button" class="remove-btn" onclick="removeRow(this)">✖ Remove</button></td>
        `;
            return tr;
        }

        const tbody = document.getElementById('tableBody');

        function initializeTable() {
            tbody.innerHTML = '';
            rowCounter = 0;
            const firstRow = createTableRow(rowCounter);
            tbody.appendChild(firstRow);
            rowCounter++;
        }

        function addNewRow() {
            const newRow = createTableRow(rowCounter);
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