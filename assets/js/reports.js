$(document).ready(function () {
    const CURRENT_BRANCH = window.CURRENT_BRANCH_NAME || 'My Branch';
    const CURRENT_ROLE = window.CURRENT_ROLE || '';

    const footerTotals = {
        daily: { amount: 0, charge: 0, total: 0, tendered: 0, change: 0 },
        monthly: { amount: 0, charge: 0, total: 0, tendered: 0, change: 0 },
        custom: { amount: 0, charge: 0, total: 0, tendered: 0, change: 0 },
        summary: { amount: 0, charge: 0, total: 0 }
    };

    function getSelectedText(selector, fallback) {
        const el = $(selector);
        if (!el.length) return fallback;
        const text = el.find('option:selected').text();
        return text && text.trim() !== '' ? text.trim() : fallback;
    }

    function getBranchId(type) {
        const selectorMap = {
            daily: '#dailyReport #branchFilter',
            monthly: '#monthlyReport #branchFilter',
            custom: '#customReport #branchFilter',
            summary: '#summaryReport #branchFilter'
        };
        const el = $(selectorMap[type]);
        return el.length ? el.val() : null;
    }

    function buildReportMeta(type) {
        const branch = getBranchId(type) ? getSelectedText(`#${type}Report #branchFilter`, 'All Branches') : CURRENT_BRANCH;
        let wallet = '', transactionType = '', dateRange = '';

        switch (type) {
            case 'daily':
                wallet = getSelectedText('#dailyEwallet', 'All E-wallets');
                transactionType = getSelectedText('#dailyTransactionType', 'All Types');
                dateRange = $('#dailyDate').val() || 'All Dates';
                break;
            case 'monthly':
                wallet = getSelectedText('#monthlyEwallet', 'All E-wallets');
                transactionType = getSelectedText('#monthlyTransactionType', 'All Types');
                dateRange = $('#monthlyMonth').val() || 'All Dates';
                break;
            case 'custom':
                wallet = getSelectedText('#customEwallet', 'All E-wallets');
                transactionType = getSelectedText('#customTransactionType', 'All Types');
                dateRange = ($('#customDateFrom').val() || '') + ' to ' + ($('#customDateTo').val() || '');
                break;
        }

        return {
            title: `Report - ${branch} | ${wallet} | ${transactionType} | ${dateRange}`,
            filename: `Report_${branch}_${wallet}_${transactionType}_${dateRange}`.replace(/\s+/g, '_').replace(/[^\w\-]/g, '')
        };
    }

    function initDataTable(tableSelector, ajaxUrl, type) {
        return $(tableSelector).DataTable({
            processing: true,
            serverSide: false,
            dom: `<'row mb-3'<'col-md-6'B><'col-md-6'f>>rt<'row mt-3'<'col-md-5'i><'col-md-7'p>>`,
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }, // include all columns
                    title: () => buildReportMeta(type).title,
                    filename: () => buildReportMeta(type).filename,
                    customize: function (xlsx) {
                        const sheet = xlsx.xl.worksheets['sheet1.xml'];

                        // find last row index
                        const rowNodes = sheet.getElementsByTagName('row');
                        const lastRow = rowNodes[rowNodes.length - 1];

                        // create new row for totals
                        const totalAmount = $('#' + type + 'TotalAmount').text().replace(/₱|,/g, '');
                        const totalCharge = $('#' + type + 'TotalCharge').text().replace(/₱|,/g, '');
                        const grandTotal = $('#' + type + 'GrandTotal').text().replace(/₱|,/g, '');

                        // Append a new row with totals
                        const newRowIndex = parseInt(lastRow.getAttribute('r')) + 1;
                        const newRow = `<row r="${newRowIndex}">
                            <c t="inlineStr" r="F${newRowIndex}"><is><t>${totalAmount}</t></is></c>
                            <c t="inlineStr" r="G${newRowIndex}"><is><t>${totalCharge}</t></is></c>
                            <c t="inlineStr" r="H${newRowIndex}"><is><t>${grandTotal}</t></is></c>
                        </row>`;

                        sheet.getElementsByTagName('sheetData')[0].innerHTML += newRow;
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
                    filename: () => buildReportMeta(type).filename,
                    customize: function (doc) {
                        doc.content.splice(0, 1);
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.defaultStyle.fontSize = 7;
                        doc.styles.tableHeader.fontSize = 8;

                        const table = doc.content.find(c => c.table);
                        table.layout = {
                            hLineWidth: () => 0.5,
                            vLineWidth: () => 0.5,
                            hLineColor: () => '#ccc',
                            vLineColor: () => '#ccc',
                            paddingLeft: () => 2,
                            paddingRight: () => 2,
                            paddingTop: () => 2,
                            paddingBottom: () => 2
                        };

                        const colCount = table.table.body[0].length;
                        table.table.widths = Array(colCount).fill('*');

                        doc.content.unshift({
                            text: buildReportMeta(type).title,
                            style: 'header',
                            alignment: 'center',
                            margin: [0, 0, 0, 10],
                            fontSize: 10,
                            bold: true
                        });

                        // append footer totals (exclude Tendered & Change)
                        const footerRow = [
                            { text: 'Totals:', colSpan: 5, alignment: 'right' }, {}, {}, {}, {},
                            { text: $('#' + type + 'TotalAmount').text(), alignment: 'right' },
                            { text: $('#' + type + 'TotalCharge').text(), alignment: 'right' },
                            { text: $('#' + type + 'GrandTotal').text(), alignment: 'right' },
                            {}, {} // empty cells for Tendered & Change
                        ];
                        table.table.body.push(footerRow);
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] },
                    customize: function (win) {
                        $(win.document.body).find('h1').remove();
                        $(win.document.body).prepend(`<h4 style="text-align:center;margin-bottom:15px">${buildReportMeta(type).title}</h4>`);

                        const table = $(win.document.body).find('table');
                        const footerHtml = `<tr>
                        <th colspan="5" style="text-align:right">Totals:</th>
                        <th>${$('#' + type + 'TotalAmount').text()}</th>
                        <th>${$('#' + type + 'TotalCharge').text()}</th>
                        <th>${$('#' + type + 'GrandTotal').text()}</th>
                        <th colspan="2"></th>
                    </tr>`;
                        table.append(footerHtml);
                    }
                }
            ],
            ajax: {
                url: ajaxUrl,
                type: 'GET',
                data: function (d) {
                    const branchId = getBranchId(type);
                    if (branchId) d.branch_id = branchId;

                    if (type === 'daily') {
                        d.date = $('#dailyDate').val();
                        d.wallet_id = $('#dailyEwallet').val();
                        d.transaction_type = $('#dailyTransactionType').val();
                    } else if (type === 'monthly') {
                        d.month = $('#monthlyMonth').val();
                        d.wallet_id = $('#monthlyEwallet').val();
                        d.transaction_type = $('#monthlyTransactionType').val();
                    } else if (type === 'custom') {
                        d.date_from = $('#customDateFrom').val();
                        d.date_to = $('#customDateTo').val();
                        d.wallet_id = $('#customEwallet').val();
                        d.transaction_type = $('#customTransactionType').val();
                    }
                },
                dataSrc: 'data'
            },
            order: [[1, 'desc']],
            footerCallback: function (row, data) {
                let totalAmount = 0, totalCharge = 0, grandTotal = 0;

                data.forEach(item => {
                    totalAmount += parseFloat(item.amount || 0);
                    totalCharge += parseFloat(item.charge || 0);
                    grandTotal += parseFloat(item.total || 0);
                });

                const table = $(row).closest('table');
                if (table.attr('id') === 'dailyReportTable') {
                    $('#dailyTotalAmount').text('₱' + totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#dailyTotalCharge').text('₱' + totalCharge.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#dailyGrandTotal').text('₱' + grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                } else if (table.attr('id') === 'monthlyReportTable') {
                    $('#monthlyTotalAmount').text('₱' + totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#monthlyTotalCharge').text('₱' + totalCharge.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#monthlyGrandTotal').text('₱' + grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                } else if (table.attr('id') === 'customReportTable') {
                    $('#customTotalAmount').text('₱' + totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#customTotalCharge').text('₱' + totalCharge.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#customGrandTotal').text('₱' + grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                } else if (table.attr('id') === 'summaryReportTable') {
                    $('#summaryTotalAmount').text('₱' + totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#summaryTotalCharge').text('₱' + totalCharge.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                    $('#summaryGrandTotal').text('₱' + grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
                }
            },
            columns: [
                { data: null, render: (d, t, r, m) => m.row + 1 },
                { data: 'created_at' },
                { data: 'reference_no' },
                {
                    data: 'wallet_name', render: d => {
                        if (!d) return '';
                        const badgeClass = d.toLowerCase() === 'gcash' ? 'bg-info' : d.toLowerCase() === 'maya' ? 'bg-success' : 'bg-warning';
                        return `<span class="badge ${badgeClass}">${d}</span>`;
                    }
                },
                {
                    data: 'type', render: d => {
                        if (!d) return '';
                        const badgeClass = d === 'Cash-in' ? 'bg-secondary' : d === 'Cash-out' ? 'bg-danger' : 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${d}</span>`;
                    }
                },
                { data: 'amount', render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
                { data: 'charge', render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
                { data: 'total', render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
                { data: 'tendered_amount', render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
                { data: 'change_amount', render: (d, t) => t === 'sort' ? d : '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) }
            ]
        });
    }

    const dailyTable = initDataTable('#dailyReportTable', '../api/fetch_report.php', 'daily');
    const monthlyTable = initDataTable('#monthlyReportTable', '../api/fetch_report.php', 'monthly');
    const customTable = initDataTable('#customReportTable', '../api/fetch_report.php', 'custom');

    // Summary table remains the same (totals: amount, charge, total)
    // Summary table (branch filtering inside tab)
    const summaryTable = $('#summaryReportTable').DataTable({
        processing: true,
        serverSide: false,
        dom: `<'row mb-3'<'col-md-6'B><'col-md-6'f>>rt<'row mt-3'<'col-md-5'i><'col-md-7'p>>`,
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // exclude Tendered and Change
                },
                customize: function (xlsx) {
                    const sheet = xlsx.xl.worksheets['sheet1.xml'];
                    const rowNodes = sheet.getElementsByTagName('row');
                    const newRowIndex = parseInt(rowNodes[rowNodes.length - 1].getAttribute('r')) + 1;

                    const newRow = `<row r="${newRowIndex}">
                        <c t="inlineStr" r="D${newRowIndex}"><is><t>${$('#summaryTotalAmount').text()}</t></is></c>
                        <c t="inlineStr" r="E${newRowIndex}"><is><t>${$('#summaryTotalCharge').text()}</t></is></c>
                        <c t="inlineStr" r="F${newRowIndex}"><is><t>${$('#summaryGrandTotal').text()}</t></is></c>
                    </row>`;
                    sheet.getElementsByTagName('sheetData')[0].innerHTML += newRow;
                },

                title: 'Summary Transactions Report',
                filename: `Summary_Report_${new Date().toISOString().slice(0, 10)}`
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // exclude Tendered and Change
                },
                filename: `Summary_Report_${new Date().toISOString().slice(0, 10)}`,
                customize: function (doc) {
                    doc.pageMargins = [10, 10, 10, 10];
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    const table = doc.content.find(c => c.table);
                    table.table.body.push([
                        { text: 'Totals:', colSpan: 3, alignment: 'right' }, {}, {},
                        { text: $('#summaryTotalAmount').text(), alignment: 'right' },
                        { text: $('#summaryTotalCharge').text(), alignment: 'right' },
                        { text: $('#summaryGrandTotal').text(), alignment: 'right' }
                    ]);
                    table.layout = {
                        hLineWidth: () => 0.5,
                        vLineWidth: () => 0.5,
                        hLineColor: () => '#ccc',
                        vLineColor: () => '#ccc',
                        paddingLeft: () => 2,
                        paddingRight: () => 2,
                        paddingTop: () => 2,
                        paddingBottom: () => 2
                    };

                    const colCount = table.table.body[0].length;
                    table.table.widths = Array(colCount).fill('*');

                    doc.content.unshift({
                        text: 'Summary Transactions Report',
                        style: 'header',
                        alignment: 'center',
                        margin: [0, 0, 0, 10],
                        fontSize: 10,
                        bold: true
                    });
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Print',
                className: 'btn btn-secondary btn-sm',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }, // exclude Tendered and Change
                customize: function (win) {
                    $(win.document.body).find('h1').remove();
                    $(win.document.body).prepend(`<h4 style="text-align:center;margin-bottom:15px">Summary Transactions Report</h4>`);
                }
            }
        ],
        ajax: {
            url: '../api/fetch_summary_report.php',
            type: 'GET',
            data: function (d) {
                const branchId = getBranchId('summary');
                if (branchId) d.branch_id = branchId;
                d.wallet_id = $('#summaryEwallet').val();
                d.transaction_type = $('#summaryTransactionType').val();
                d.date_from = $('#summaryDateFrom').val();
                d.date_to = $('#summaryDateTo').val();
            },
            dataSrc: 'data'
        },
        order: [[1, 'asc']],
        footerCallback: function (row, data) {
            let totalAmount = 0, totalCharge = 0, grandTotal = 0;
            data.forEach(item => {
                totalAmount += parseFloat(item.amount || 0);
                totalCharge += parseFloat(item.charge || 0);
                grandTotal += parseFloat(item.total || 0);
            });
            $('#summaryTotalAmount').text('₱' + totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $('#summaryTotalCharge').text('₱' + totalCharge.toLocaleString(undefined, { minimumFractionDigits: 2 }));
            $('#summaryGrandTotal').text('₱' + grandTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: 'wallet_name' },
            { data: 'type' },
            { data: 'amount', render: d => '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
            { data: 'charge', render: d => '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) },
            { data: 'total', render: d => '₱' + Number(d).toLocaleString(undefined, { minimumFractionDigits: 2 }) }
        ]
    });

    // Apply filters
    $('#dailyApplyFilters').on('click', () => dailyTable.ajax.reload());
    $('#monthlyApplyFilters').on('click', () => monthlyTable.ajax.reload());
    $('#customApplyFilters').on('click', () => customTable.ajax.reload());
    $('#summaryApplyFilters').on('click', () => summaryTable.ajax.reload());

    // Reset filters
    $('#dailyResetFilters').on('click', () => {
        $('#dailyDate').val('');
        $('#dailyEwallet').val('');
        $('#dailyTransactionType').val('');
        $('#dailyReport #branchFilter').val('all');
        dailyTable.ajax.reload();
    });

    $('#monthlyResetFilters').on('click', () => {
        $('#monthlyMonth').val('');
        $('#monthlyEwallet').val('');
        $('#monthlyTransactionType').val('');
        $('#monthlyReport #branchFilter').val('all');
        monthlyTable.ajax.reload();
    });

    $('#customResetFilters').on('click', () => {
        $('#customDateFrom').val('');
        $('#customDateTo').val('');
        $('#customEwallet').val('');
        $('#customTransactionType').val('');
        $('#customReport #branchFilter').val('all');
        customTable.ajax.reload();
    });

    $('#summaryResetFilters').on('click', () => {
        $('#summaryDateFrom').val('');
        $('#summaryDateTo').val('');
        $('#summaryEwallet').val('');
        $('#summaryTransactionType').val('');
        $('#summaryReport #branchFilter').val('all');
        summaryTable.ajax.reload();
    });

    // Load E-wallets for each tab
    function loadEwallets(selector) {
        $.getJSON('../api/fetch_e-wallets.php', {}, function (res) {
            let options = '<option value="">All E-wallets</option>';
            res.data.forEach(w => options += `<option value="${w.id}">${w.account_name}</option>`);
            $(selector).html(options);
        });
    }

    loadEwallets('#dailyEwallet');
    loadEwallets('#monthlyEwallet');
    loadEwallets('#customEwallet');
    loadEwallets('#summaryEwallet');
});
