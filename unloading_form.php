<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Inventory System | Create Unloading</title>
    <link rel="stylesheet" href="./styles/unloading_form.css">
</head>

<body>

    <button class="btn btn-save" onclick="window.history.back();">
        Back
    </button>


    <div class="form-container">
        <div class="form-header">
            <h2>
                ⚡ Unloading Monitoring Form
                <span class="badge-form">Stellar Seeds Corp</span>
            </h2>
            <div class="subnote">
                Fill in unloading details and item rows
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

                    <div class="input-group">
                        <label>
                            Jumbo or Pallet?
                        </label>
                        <select name="isJB">
                            <option value="0"> Pallet</option>
                            <option value="1"> JB</option>
                        </select>

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
                        <input name="prepared_by" placeholder="Name ">
                    </div>
                    <div class="input-group">
                        <label>Checked By</label>
                        <input name="checked_by" placeholder="Name ">
                    </div>
                </div>

                <div class="fields-grid">
                    <div class="input-group">
                        <label>Remarks</label>
                        <textarea name="remarks" placeholder="Remarks/Purpose...."></textarea>
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

    <script>
        let rowCounter = 0;

        function createTableRow(index) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-row-idx', index);
            tr.innerHTML = `
            <td><input name="items[${index}][jb_pallet]" placeholder="Pallet / JB No." autocomplete="off"></td>
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