$(document).ready(function () {

    // Fetch e-wallet accounts for this branch and populate selector
    function loadEwalletAccounts() {
        $.ajax({
            url: "../api/fetch_active_e-wallet.php",
            dataType: "json",
            success: function (response) {
                const $selector = $('select[name="e_wallet_account"]');
                $selector.empty().append('<option value="">Select E-wallet Account</option>');
                if (response.data && response.data.length) {
                    response.data.forEach(wallet => {
                        $selector.append(`<option value="${wallet.id}" data-name="${wallet.account_name}">${wallet.account_name} (${wallet.account_number})</option>`);
                    });
                }
            },
            error: function () {
                alert("Failed to load e-wallet accounts.");
            }
        });
    }

    loadEwalletAccounts(); // populate on modal open
    // Auto-adjust Transaction Fee Thru options based on selected wallet
    $('select[name="e_wallet_account"]').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        const walletName = selectedOption.data('name');
        const $feeThru = $('select[name="transaction_fee_thru"]');
        $feeThru.empty().append('<option value="">Select Type</option>');

        if (!walletName) return;

        if (walletName) {
            $feeThru.append(`<option value="Cash">Cash</option>`);
            $feeThru.append(`<option value="${walletName}">${walletName}</option>`);
        }
        else {
            $feeThru.append('<option value="Cash">Cash</option>');
        }

        $feeThru.val('');
        handleTenderedState();
    });

    //  NEW: Handle tendered amount behavior
    function handleTenderedState() {
        const transType = $('input[name="transaction_type"]:checked').val();
        const feeThru = $('select[name="transaction_fee_thru"]').val();
        const $tendered = $('input[name="tendered_amount"]');

        if (
            (transType === 'Cash-out') &&
            feeThru &&
            feeThru !== 'Cash'
        ) {
            $tendered.val(0).prop('readonly', true);
        } else {
            $tendered.prop('readonly', false);
        }

        calculateChange();
    }

    // Function to calculate change based on transaction type
    function calculateChange() {
        const amount = parseFloat($('input[name="amount"]').val()) || 0;
        const charge = parseFloat($('input[name="transaction_charge"]').val()) || 0;
        const tendered = parseFloat($('input[name="tendered_amount"]').val()) || 0;
        const transType = $('input[name="transaction_type"]:checked').val();
        const transFeeThru = $('select[name="transaction_fee_thru"]').val();

        let change = 0;

        if (transType === "Cash-in") {
            // Cash-in: total = amount + transaction fee
            const total = amount + charge;
            change = tendered - total;

            if (transFeeThru && transFeeThru !== 'Cash') {
                // If fee is thru e-wallet, tendered must cover only amount
                change = tendered - amount;

            }

        } else if (transType === "Cash-out") {
            // Cash-out: customer only pays transaction fee
            change = tendered - charge;

            if (transFeeThru && transFeeThru !== 'Cash') {
                // If fee is thru e-wallet, tendered must cover only amount
                change = tendered - amount;

            }
        }

        $('input[name="change_amount"]').val(change >= 0 ? change.toFixed(2) : '0.00');
    }

    // Trigger calculation & tendered logic
    $('input[name="amount"], input[name="transaction_charge"], input[name="tendered_amount"]')
        .on('input', calculateChange);

    $('input[name="transaction_type"]').on('change', handleTenderedState);
    $('select[name="transaction_fee_thru"]').on('change', handleTenderedState);

    // Show confirmation modal on form submit
    $('#addTransactionForm').on('submit', function (e) {
        e.preventDefault();


        // Get form values
        const walletName = $('select[name="e_wallet_account"] option:selected').data('name') || '';
        const referenceNo = $('input[name="reference_no"]').val();
        const amount = parseFloat($('input[name="amount"]').val() || 0).toFixed(2);
        const charge = parseFloat($('input[name="transaction_charge"]').val() || 0).toFixed(2);
        const tendered = parseFloat($('input[name="tendered_amount"]').val() || 0).toFixed(2);
        const change = parseFloat($('input[name="change_amount"]').val() || 0).toFixed(2);
        const total = (parseFloat(amount) + parseFloat(charge)).toFixed(2);
        const feeThru = $('select[name="transaction_fee_thru"]').val();
        const transType = $('input[name="transaction_type"]:checked').val();



        // add validation before showing confirmation modal
        // if feThru is e-wallet and tendered is less than amount for cash-in
        if (
            (transType === 'Cash-in') &&
            (feeThru && feeThru !== 'Cash') &&
            (parseFloat(tendered) < parseFloat(amount))
        ) {
            let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${"Insufficient tendered amount."}
                                    </div>
                                `;

            $("#addTransactionModal .modal-body").prepend(alertHtml);
            setTimeout(() => $(".alert").alert('close'), 3000);
            return;
        }

        if (
            (transType === 'Cash-in') &&
            (feeThru && feeThru === 'Cash') &&
            (parseFloat(tendered) < parseFloat(total))
        ) {
            let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${"Insufficient tendered amount."}
                                    </div>
                                `;

            $("#addTransactionModal .modal-body").prepend(alertHtml);
            setTimeout(() => $(".alert").alert('close'), 3000);
            return;
        }



        // Populate confirmation modal
        $('#c_eWallet').text(walletName);
        $('#c_reference').text(referenceNo);
        $('#c_amount').text(amount);
        $('#c_charge').text(charge);
        $('#c_total').text(total);
        $('#c_feeThru').text(feeThru);
        $('#c_transType').text(transType);
        $('#c_tendered').text(tendered);
        $('#c_change').text(change);

        // close add modal
        $('#addTransactionModal').modal('hide');
        // Show modal
        $('#confirmAddModal').modal('show');
    });


    // Final confirm save transaction
    $('#confirmAddBtn').on('click', function () {
        const formData = $('#addTransactionForm').serialize();
        $.ajax({
            url: "../processes/add_transaction.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#confirmAddModal, #addTransactionModal').modal('hide');
                    // alert("Transaction saved successfully.");

                    // Show global alert
                    let alertHtml = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message || "Transaction added successfully."}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `;
                    $("#globalAlertArea").html(alertHtml);

                    setTimeout(() => $(".alert").alert('close'), 3000);

                    // Optionally reload table or update dashboard
                    // reset form modals
                    $('#addTransactionForm')[0].reset();
                    $('#addTransactionModal').modal('hide');

                    // reload datatable
                    $('#transactionsTable').DataTable().ajax.reload();
                } else {
                    // alert(response.message || "Failed to save transaction.");
                    //show the add transaction modal again with error
                    // the div alert must show inside add modal

                    let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${response.message || "Failed to add transaction."}
                                    </div>
                                `;

                    $("#addTransactionModal .modal-body").prepend(alertHtml);
                    setTimeout(() => $(".alert").alert('close'), 3000);
                    $('#confirmAddModal').modal('hide');
                    $('#addTransactionModal').modal('show');

                }
            },
            error: function () {
                // alert("An error occurred while saving transaction.");
                let alertHtml = `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${response.message || "An error occurred while saving transaction"}
                                    </div>
                                `;

                $("#addTransactionModal .modal-body").prepend(alertHtml);
                setTimeout(() => $(".alert").alert('close'), 3000);
                $('#confirmAddModal').modal('hide');
                $('#addTransactionModal').modal('show');

            }
        });
    });

});




// populate transactions datatable
$(document).ready(function () {

    let footerTotals = {
        amount: 0,
        charge: 0,
        total: 0
    };

    function getSelectedText(selector, fallback) {
        const el = $(selector);
        if (!el.length) return fallback;
        const text = el.find('option:selected').text();
        return text && text.trim() !== '' ? text.trim() : fallback;
    }

    function buildReportMeta() {

        // determine branch from currentBranchName() if branchFilter not exists

        const branch = $('#branchFilter').length
            ? getSelectedText('#branchFilter', 'All Branches')
            : (window.CURRENT_BRANCH_NAME || 'My Branch');


        const wallet = getSelectedText('#e-walletFilter', 'All E-wallets');
        const type = getSelectedText('#transactionTypeFilter', 'All Types');

        const from = $('#dateFilterFrom').val();
        const to = $('#dateFilterTo').val();

        const dateRange = from && to
            ? `${from}_to_${to}`
            : from
                ? `From_${from}`
                : to
                    ? `Up_to_${to}`
                    : 'All_Dates';

        return {
            title: `Transactions Report - ${branch} | ${wallet} | ${type}`,
            filename: `Transactions_${branch}_${wallet}_${type}_${dateRange}`
                .replace(/\s+/g, '_')
                .replace(/[^\w\-]/g, '')
        };
    }


    const table = $('#transactionsTable').DataTable({
        processing: true,
        serverSide: false,
        dom: `
        <'row mb-3'
            <'col-md-6'B>
            <'col-md-6'f>
        >
        rt
        <'row mt-3'
            <'col-md-5'i>
            <'col-md-7'p>
        >
    `,
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: { columns: ':not(:last-child)' },
                title: function () {
                    return buildReportMeta().title;
                },
                filename: function () {
                    return buildReportMeta().filename;
                },
                customize: function (xlsx) {
                    // footerTotals already available
                    let sheet = xlsx.xl.worksheets['sheet1.xml'];
                    let rows = $('row', sheet);
                    let lastRow = rows.length + 1;

                    sheet.childNodes[0].childNodes[1].innerHTML += `
            <row r="${lastRow}">
                <c r="A${lastRow}" t="inlineStr"><is><t>TOTAL</t></is></c>
                <c r="F${lastRow}" t="inlineStr"><is><t>${footerTotals.amount.toFixed(2)}</t></is></c>
                <c r="G${lastRow}" t="inlineStr"><is><t>${footerTotals.charge.toFixed(2)}</t></is></c>
                <c r="H${lastRow}" t="inlineStr"><is><t>${footerTotals.total.toFixed(2)}</t></is></c>
            </row>
        `;
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':not(:last-child)' },

                filename: function () {
                    return buildReportMeta().filename;
                },

                customize: function (doc) {

                    const meta = buildReportMeta();

                    // Remove default title
                    doc.content.splice(0, 1);

                    // Insert dynamic title
                    doc.content.unshift({
                        text: meta.title,
                        style: 'header',
                        alignment: 'center',
                        margin: [0, 0, 0, 12]
                    });

                    let body = doc.content[1].table.body;

                    body.push([
                        { text: 'TOTAL', colSpan: 5, alignment: 'right', bold: true }, {}, {}, {}, {},
                        footerTotals.amount.toFixed(2),
                        footerTotals.charge.toFixed(2),
                        footerTotals.total.toFixed(2),
                        ''
                    ]);
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Print',
                className: 'btn btn-secondary btn-sm',
                exportOptions: { columns: ':not(:last-child)' },

                customize: function (win) {

                    const meta = buildReportMeta();

                    // Replace default title
                    $(win.document.body).find('h1').remove();

                    $(win.document.body).prepend(`
            <h4 style="text-align:center;margin-bottom:15px">
                ${meta.title}
            </h4>
        `);

                    $(win.document.body).find('table').append(`
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align:right">TOTAL</th>
                    <th>₱${footerTotals.amount.toFixed(2)}</th>
                    <th>₱${footerTotals.charge.toFixed(2)}</th>
                    <th>₱${footerTotals.total.toFixed(2)}</th>
                    <th></th>
                </tr>
            </tfoot>
        `);
                }
            }


        ],
        ajax: {
            url: '../api/fetch_transactions.php',
            type: 'GET',
            data: function (d) {
                d.branch_id = $('#branchFilter').length ? $('#branchFilter').val() : '';
                d.date_from = $('#dateFilterFrom').val();
                d.date_to = $('#dateFilterTo').val();
                d.wallet_id = $('#e-walletFilter').val();
                d.transaction_type = $('#transactionTypeFilter').val();
            },
            dataSrc: 'data'
        },
        order: [[1, 'desc']],
        footerCallback: function (row, data) {

            footerTotals.amount = 0;
            footerTotals.charge = 0;
            footerTotals.total = 0;

            data.forEach(item => {
                footerTotals.amount += parseFloat(item.amount || 0);
                footerTotals.charge += parseFloat(item.charge || 0);
                footerTotals.total += parseFloat(item.total || 0);
            });

            $('#totalAmount').text(
                '₱' + footerTotals.amount.toLocaleString(undefined, { minimumFractionDigits: 2 })
            );

            $('#totalCharge').text(
                '₱' + footerTotals.charge.toLocaleString(undefined, { minimumFractionDigits: 2 })
            );

            $('#grandTotal').text(
                '₱' + footerTotals.total.toLocaleString(undefined, { minimumFractionDigits: 2 })
            );
        },

        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'created_at' },
            { data: 'reference_no' },
            {

                data: 'wallet_name',
                render: d => {
                    if (!d) return '';
                    const badgeClass = d.toLowerCase() === 'gcash' ? 'bg-info' : d.toLowerCase() === 'maya' ? 'bg-success' : 'bg-warning';
                    return `<span class="badge ${badgeClass}">${d}</span>`;
                }

            },
            {
                data: 'type',
                render: d => {
                    if (!d) return '';
                    const badgeClass = d === 'Cash-in' ? 'bg-secondary' : d === 'Cash-out' ? 'bg-danger' : 'bg-secondary';
                    return `<span class="badge ${badgeClass}">${d}</span>`;
                }
            },
            {
                data: 'amount',
                render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
            },
            {
                data: 'charge',
                render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
            },
            {
                data: 'total',
                render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 })
            },
            { data: 'payment_thru' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: row => `
                <button class="btn btn-sm btn-outline-primary view-btn" data-id="${row.id}">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}">
                    <i class="bi bi-trash"></i>
                </button>
            `
            }
        ]
    });

    //  View Transaction
    $('#transactionsTable').on('click', '.view-btn', function () {
        const rowData = table.row($(this).parents('tr')).data();

        $('#viewDate').text(rowData.created_at);
        $('#viewRef').text(rowData.reference_no);
        $('#viewEwallet').text(rowData.wallet_name);
        $('#viewType').text(rowData.type);
        $('#viewAmount').text(Number(rowData.amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#viewCharge').text(Number(rowData.charge).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#viewTotal').text(Number(rowData.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#viewTendered').text(Number(rowData.tendered_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#viewChange').text(Number(rowData.change_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        $('#viewPaymentMode').text(rowData.payment_thru);

        $('#viewTransactionModal').modal('show');
    });


    /* =========================
    APPLY FILTERS
    ========================== */
    $('#applyFilters').on('click', function () {
        table.ajax.reload();
    });

    /* =========================
       RESET FILTERS
    ========================== */
    $('#resetFilters').on('click', function () {

        $('#dateFilterFrom').val('');
        $('#dateFilterTo').val('');
        $('#transactionTypeFilter').val('');
        $('#e-walletFilter').val('');

        if ($('#branchFilter').length) {
            $('#branchFilter').val('all');
        }

        table.ajax.reload();
    });

    /* =========================
       LOAD E-WALLETS
    ========================== */
    function loadEwallets(branchId = '') {
        $.getJSON('../api/fetch_e-wallets.php', { branch_id: branchId }, function (res) {

            let options = '<option value="">All E-wallets</option>';

            res.data.forEach(w => {
                options += `<option value="${w.id}">${w.account_name}</option>`;
            });

            $('#e-walletFilter').html(options);
        });
    }

    // Initial load
    loadEwallets();

    // Reload wallets when branch changes (super_admin only)
    $(document).on('change', '#branchFilter', function () {
        loadEwallets($(this).val());
        table.ajax.reload();
    });


});
