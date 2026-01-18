$(document).ready(function () {

    /*********************************************************
     * GLOBAL STATE (NEW)
     *********************************************************/
    let CURRENT_COH = 0;
    let CURRENT_WALLET_BALANCE = 0;
    let HAS_DAILY_BALANCE = true;

    /*********************************************************
     * HELPER: SHOW ERROR INSIDE ADD MODAL (NEW)
     *********************************************************/
    function showAddModalError(message) {
        $("#addTransactionModal .alert").remove();

        $("#addTransactionModal .modal-body").prepend(`
            <div class="alert alert-danger fade show" role="alert">
                ${message}
            </div>
        `);
        setTimeout(() => $(".alert").alert('close'), 5000);
    }

    function clearAddModalError() {
        $("#addTransactionModal .alert").remove();
    }

    /*********************************************************
     * FETCH DAILY BALANCE STATUS + COH (NEW)
     *********************************************************/
    function loadBranchBalances() {
        $.getJSON('../api/fetch_current_cash_wallet_logs.php', function (res) {
            if (!res.success) {
                HAS_DAILY_BALANCE = false;
                showAddModalError(res.message || 'Please set initial COH and e-wallet balance for today first before adding transaction.');
                return;
            }

            CURRENT_COH = parseFloat(res.coh) || 0;
            HAS_DAILY_BALANCE = true;
            clearAddModalError();
        });
    }

    /*********************************************************
     * FETCH WALLET BALANCE (NEW)
     *********************************************************/
    function loadWalletBalance(walletId) {
        if (!walletId) return;

        $.getJSON('../api/fetch_wallet_balance.php', { wallet_id: walletId }, function (res) {
            if (res.success) {
                CURRENT_WALLET_BALANCE = parseFloat(res.balance) || 0;
            }
        });
    }

    /*********************************************************
     * LIVE BALANCE VALIDATION (NEW)
     *********************************************************/
    function validateBalancesLive() {
        const type = $('input[name="transaction_type"]:checked').val();
        const amount = parseFloat($('input[name="amount"]').val()) || 0;

        if (!HAS_DAILY_BALANCE) {
            showAddModalError('Please set initial COH and e-wallet balance for today first before adding transaction.');
            return false;
        }

        if (type === 'Cash-out') {
            if (amount > CURRENT_COH) {
                showAddModalError('Insufficient cash on hand. Current COH: ' + '₱' + CURRENT_COH.toFixed(2));
                return false;
            }
        }

        if (type === 'Cash-in') {
            if (amount > CURRENT_WALLET_BALANCE) {
                showAddModalError('Insufficient e-wallet balance. Current Wallet Balance: ' + '₱' + CURRENT_WALLET_BALANCE.toFixed(2));
                return false;
            }
        }


        // live validation for tendered amount
        const feeThru = $('select[name="transaction_fee_thru"]').val();
        const tendered = parseFloat($('input[name="tendered_amount"]').val());
        const charge = parseFloat($('input[name="transaction_charge"]').val());


        // add validation to check transaction fee based on the entered amount, charge must not exceed the computation of charge amount

        function getMaxCharge(amount) {
            if (amount <= 0) return 0;

            if (amount <= 200) {
                return 4.00;
            } else if (amount <= 500) {
                return 8.00;
            } else if (amount <= 800) {
                return 12.00;
            } else if (amount <= 1000) {
                return 15.00;
            } else if (amount < 10000) {
                return Math.ceil(amount / 1000) * 15.00;
            } else {
                return Math.ceil(amount / 1000) * 12.00;
            }
        }


        const maxCharge = getMaxCharge(amount);

        if (charge > maxCharge) {
            showAddModalError('Transaction charge exceeds the maximum allowed amount.');
            return false;
        }


        // Guard: wait until inputs are meaningful
        if (
            !feeThru ||
            isNaN(tendered) ||
            isNaN(amount) ||
            isNaN(charge)
        ) {
            return true; // skip validation for now
        }

        /**
         * CASH-IN (Cash payment)
         * tendered >= amount + charge
         */
        if (
            type === 'Cash-in' &&
            feeThru !== 'Cash' &&
            tendered < amount
        ) {
            showAddModalError('Tendered amount must be equal or more than transaction amount.');
            return false;
        }

        if (
            type === 'Cash-in' &&
            feeThru === 'Cash' &&
            tendered < (amount + charge)
        ) {
            showAddModalError('Tendered amount must be equal or more than transaction amount.');
            return false;
        }

        /**
         * CASH-OUT (Cash payment)
         * tendered >= charge
         */
        if (
            type === 'Cash-out' &&
            feeThru === 'Cash' &&
            tendered < charge
        ) {
            showAddModalError('Tendered amount must be equal or more than transaction charge.');
            return false;
        }



        clearAddModalError();
        return true;



    }

    /*********************************************************
     * FETCH E-WALLET ACCOUNTS (UNCHANGED)
     *********************************************************/
    function loadEwalletAccounts() {
        $.ajax({
            url: "../api/fetch_active_e-wallet.php",
            dataType: "json",
            success: function (response) {
                const $selector = $('select[name="e_wallet_account"]');
                $selector.empty().append('<option value="">Select E-wallet Account</option>');
                if (response.data && response.data.length) {
                    response.data.forEach(wallet => {
                        $selector.append(`
                            <option value="${wallet.id}" data-name="${wallet.account_name}">
                                ${wallet.account_name} (${wallet.account_number})
                            </option>
                        `);
                    });
                }
            },
            error: function () {
                alert("Failed to load e-wallet accounts.");
            }
        });
    }

    loadEwalletAccounts();

    /*********************************************************
     * WALLET CHANGE (EXTENDED  SAFE)
     *********************************************************/
    $('select[name="e_wallet_account"]').on('change', function () {
        const selectedOption = $(this).find('option:selected');
        const walletName = selectedOption.data('name');
        const walletId = $(this).val();
        const $feeThru = $('select[name="transaction_fee_thru"]');

        loadWalletBalance(walletId); // NEW

        $feeThru.empty().append('<option value="">Select Type</option>');

        if (!walletName) return;

        $feeThru.append('<option value="Cash">Cash</option>');
        $feeThru.append(`<option value="${walletName}">${walletName}</option>`);

        $feeThru.val('');
        handleTenderedState();
        validateBalancesLive(); // NEW
    });

    /*********************************************************
     * TENDERED STATE (UNCHANGED)
     *********************************************************/
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

    /*********************************************************
     * CHANGE CALCULATION (UNCHANGED)
     *********************************************************/
    function calculateChange() {
        const amount = parseFloat($('input[name="amount"]').val()) || 0;
        const charge = parseFloat($('input[name="transaction_charge"]').val()) || 0;
        const tendered = parseFloat($('input[name="tendered_amount"]').val()) || 0;
        const transType = $('input[name="transaction_type"]:checked').val();
        const transFeeThru = $('select[name="transaction_fee_thru"]').val();

        let change = 0;

        if (transType === "Cash-in") {
            const total = amount + charge;
            change = tendered - total;

            if (transFeeThru && transFeeThru !== 'Cash') {
                change = tendered - amount;
            }

        } else if (transType === "Cash-out") {
            change = tendered - charge;

            if (transFeeThru && transFeeThru !== 'Cash') {
                change = tendered - amount;
            }
        }

        $('input[name="change_amount"]').val(change >= 0 ? change.toFixed(2) : '0.00');
    }

    /*********************************************************
     * LIVE TRIGGERS (EXTENDED)
     *********************************************************/
    $('input[name="amount"], input[name="transaction_charge"], input[name="tendered_amount"]')
        .on('input', function () {
            calculateChange();
            validateBalancesLive();
        });

    $('input[name="transaction_type"]').on('change', function () {
        handleTenderedState();
        validateBalancesLive();
    });

    $('select[name="transaction_fee_thru"]').on('change', handleTenderedState);

    /*********************************************************
     * MODAL OPEN (NEW)
     *********************************************************/
    $('#addTransactionModal').on('shown.bs.modal', function () {
        loadBranchBalances();
    });

    /*********************************************************
     * FORM SUBMIT (MINIMALLY EXTENDED)
     *********************************************************/
    $('#addTransactionForm').on('submit', function (e) {
        e.preventDefault();

        if (!validateBalancesLive()) {
            return;
        }



        const walletName = $('select[name="e_wallet_account"] option:selected').data('name') || '';
        const referenceNo = $('input[name="reference_no"]').val();
        const amount = parseFloat($('input[name="amount"]').val() || 0).toFixed(2);
        const charge = parseFloat($('input[name="transaction_charge"]').val() || 0).toFixed(2);
        const tendered = parseFloat($('input[name="tendered_amount"]').val() || 0).toFixed(2);
        const change = parseFloat($('input[name="change_amount"]').val() || 0).toFixed(2);
        const total = (parseFloat(amount) + parseFloat(charge)).toFixed(2);
        const feeThru = $('select[name="transaction_fee_thru"]').val();
        const transType = $('input[name="transaction_type"]:checked').val();

        // if (
        //     (transType === 'Cash-in') &&
        //     (feeThru && feeThru !== 'Cash') &&
        //     (parseFloat(tendered) < parseFloat(amount))
        // ) {
        //     showAddModalError("Insufficient tendered amount.");
        //     return;
        // }

        // if (
        //     (transType === 'Cash-in') &&
        //     (feeThru && feeThru === 'Cash') &&
        //     (parseFloat(tendered) < parseFloat(total))
        // ) {
        //     showAddModalError("Insufficient tendered amount.");
        //     return;
        // }

        $('#c_eWallet').text(walletName);
        $('#c_reference').text(referenceNo);
        $('#c_amount').text(amount);
        $('#c_charge').text(charge);
        $('#c_total').text(total);
        $('#c_feeThru').text(feeThru);
        $('#c_transType').text(transType);
        $('#c_tendered').text(tendered);
        $('#c_change').text(change);

        $('#addTransactionModal').modal('hide');
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
                <c r="F${lastRow}" t="inlineStr"><is><t>₱${footerTotals.amount.toFixed(2)}</t></is></c>
                <c r="G${lastRow}" t="inlineStr"><is><t>₱${footerTotals.charge.toFixed(2)}</t></is></c>
                <c r="H${lastRow}" t="inlineStr"><is><t>₱${footerTotals.total.toFixed(2)}</t></is></c>
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

                    /* =========================
                       PAGE SETUP
                    ========================== */
                    doc.pageMargins = [40, 60, 40, 40]; // left, top, right, bottom

                    /* =========================
                       REMOVE DEFAULT TITLE
                    ========================== */
                    doc.content.splice(0, 1);

                    /* =========================
                       HEADER TITLE
                    ========================== */
                    doc.content.unshift({
                        text: meta.title,
                        style: 'header',
                        alignment: 'center',
                        margin: [0, 0, 0, 15]
                    });

                    /* =========================
                       TABLE LAYOUT CONTROL
                    ========================== */
                    const table = doc.content.find(c => c.table);
                    table.alignment = 'center';

                    table.layout = {
                        hLineWidth: () => 0.8,
                        vLineWidth: () => 0.8,
                        hLineColor: () => '#ccc',
                        vLineColor: () => '#ccc',
                        paddingLeft: () => 6,
                        paddingRight: () => 6,
                        paddingTop: () => 4,
                        paddingBottom: () => 4
                    };

                    /* =========================
                       COLUMN WIDTHS (FIT A4)
                       TOTAL = 100%
                    ========================== */
                    table.table.widths = [
                        '5%',   // No
                        '12%',  // Date
                        '15%',  // Reference
                        '12%',  // E-wallet
                        '10%',  // Type
                        '10%',  // Amount
                        '10%',  // Fee
                        '10%',  // Total
                        '11%'   // Fee Thru
                    ];

                    /* =========================
                       FONT & HEADER STYLE
                    ========================== */
                    doc.styles.tableHeader = {
                        fontSize: 9,
                        bold: true,
                        alignment: 'center'
                    };

                    doc.defaultStyle.fontSize = 8;

                    /* =========================
                       ADD FOOTER TOTAL
                    ========================== */
                    table.table.body.push([
                        { text: 'TOTAL', colSpan: 5, alignment: 'right', bold: true }, {}, {}, {}, {},
                        { text: '₱' + footerTotals.amount.toFixed(2), alignment: 'right', bold: true },
                        { text: '₱' + footerTotals.charge.toFixed(2), alignment: 'right', bold: true },
                        { text: '₱' + footerTotals.total.toFixed(2), alignment: 'right', bold: true },
                        { text: '', alignment: 'center' }
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
                render: row => {

                    const canDelete = window.CURRENT_ROLE === 'super_admin';

                    return `
                    <button class="btn btn-sm btn-outline-primary view-btn" data-id="${row.id}">
                        <i class="bi bi-eye"></i>
                    </button>
                    ${canDelete ? `
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}">
                            <i class="bi bi-trash"></i>
                        </button>
                        ` : ''}
                    `;
                }
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


    // delete transaction function
    let deleteId = null;

    $('#transactionsTable').on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#deleteTransactionId').val(deleteId);
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function () {

        const id = $('#deleteTransactionId').val();

        $.post('../processes/delete_transaction.php', { id }, function (res) {

            if (res.success) {
                $('#deleteModal').modal('hide');
                // show global alert
                let alertHtml = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    ${res.message || 'Transaction deleted successfully.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
                $('#transactionsTable').DataTable().ajax.reload(null, false);
                $("#globalAlertArea").html(alertHtml);
                setTimeout(() => $(".alert").alert('close'), 3000);


            } else {
                let alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${res.message || 'Failed to delete transaction.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
                $("#deleteModal .modal-body").prepend(alertHtml);
                setTimeout(() => $(".alert").alert('close'), 3000);
            }

        }, 'json');
    });



});
