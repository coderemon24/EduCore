<style>
    @page {
        size: a4 portrait;
        margin: 0;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #0f172a;
        /* background-color: #ffffff; */
        font-size: 9px;
        line-height: 1.25;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Fixed Watermark Background for DomPDF & Print */
    #page-background {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: -1000;
    }

    #page-background img {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* Action bar on preview screen (hidden in print & PDF) */
    .no-print-bar {
        background-color: #1e293b;
        color: #ffffff;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .btn {
        display: inline-block;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        border: none;
    }

    .btn-print {
        background-color: #7c3aed;
        color: #ffffff;
    }
    .btn-print:hover { background-color: #6d28d9; }

    .btn-back {
        background-color: rgba(255,255,255,0.15);
        color: #ffffff;
    }
    .btn-back:hover { background-color: rgba(255,255,255,0.25); }

    /* Web Preview Screen Container */
    .marksheet-page-wrapper {
        display: flex;
        justify-content: center;
        padding: 20px 10px;
        background-color: #f1f5f9;
    }

    /* Container sizing */
    .marksheet-card {
        background-color: transparent;
        position: relative;
        page-break-after: avoid;
        page-break-inside: avoid;
    }

    .preview-card {
        width: 210mm;
        min-height: 297mm;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        background-color: #ffffff;
        box-sizing: border-box;
    }

    /* Multi-page container for bulk download */
    .bulk-student-page {
        page-break-after: always;
        page-break-inside: avoid;
        position: relative;
    }
    .bulk-student-page:last-child {
        page-break-after: avoid;
    }

    /* Layout Tables (DomPDF 100% compliant) */
    table {
        border-collapse: collapse;
    }

    .w-full {
        width: 100%;
    }

    .ms-header-table td {
        vertical-align: middle;
    }

    .ms-school-name {
        font-family: 'Times New Roman', Times, serif;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #0f172a;
        line-height: 1.2;
    }

    .ms-exam-title {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #1e293b;
        margin-top: 2px;
    }

    .ms-photo-box {
        width: 65px;
        height: 75px;
        text-align: center;
        vertical-align: middle;
        overflow: hidden;
    }

    .ms-photo-box-border{
        border: 1px solid #0f172a;
        background-color: #f8fafc;
    }

    .ms-photo-box img {
        max-width: 63px;
        max-height: 73px;
        vertical-align: middle;
    }

    /* Student Meta 3-Column Block */
    .ms-meta-table {
        width: 100%;
        border: 1px solid #0f172a;
        border-collapse: collapse;
        margin-bottom: 6px;
        font-size: 8.5px;
        line-height: 1.35;
        /* background-color: rgba(255, 255, 255, 0.95); */
    }

    .ms-meta-table td {
        padding: 4px 6px;
        vertical-align: top;
    }

    .ms-student-name {
        font-size: 11px;
        font-weight: bold;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    /* Section Banner */
    .ms-section-banner {
        text-align: center;
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 2.5px 0;
        border: 1px solid #0f172a;
        /* background-color: #f1f5f9; */
        margin-bottom: 6px;
    }

    /* Main Subject Table */
    .ms-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8.5px;
        margin-bottom: 6px;
        /* background-color: rgba(255, 255, 255, 0.95); */
    }

    .ms-table th, .ms-table td {
        border: 1px solid #0f172a;
        padding: 2.5px 3.5px;
        text-align: center;
        vertical-align: middle;
    }

    .ms-table th {
        font-weight: bold;
        text-transform: uppercase;
        /* background-color: #f8fafc; */
    }

    .ms-table td.subject-name-td {
        text-align: left;
        font-weight: bold;
        padding-left: 5px;
    }

    .ms-table tr.total-row {
        font-weight: bold;
        /* background-color: #f8fafc; */
    }

    /* Bottom Analytical Panels (DomPDF Table-based) */
    .ms-bottom-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }

    .ms-panel-box {
        border: 1px solid #0f172a;
        /* background-color: rgba(255, 255, 255, 0.95); */
    }

    .ms-panel-title {
        text-align: center;
        font-size: 8.5px;
        font-weight: bold;
        text-transform: uppercase;
        padding: 2px 4px;
        border-bottom: 1px solid #0f172a;
        /* background-color: #f1f5f9; */
    }

    .ms-subtable {
        width: 100%;
        border-collapse: collapse;
        font-size: 7.5px;
    }

    .ms-subtable th, .ms-subtable td {
        border: 1px solid #0f172a;
        padding: 1.5px 2px;
        text-align: center;
    }

    .ms-subtable th {
        font-weight: bold;
        /* background-color: #f8fafc; */
    }

    .activity-item {
        font-size: 7.5px;
        padding: 2px 4px;
        border-bottom: 1px dashed #cbd5e1;
    }

    /* Signatures Table */
    .ms-signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .ms-signature-table td {
        text-align: center;
        vertical-align: bottom;
        padding: 0 15px;
    }

    .ms-sign-line {
        border-top: 1px solid #0f172a;
        margin-top: 3px;
        padding-top: 2px;
        font-size: 9px;
        font-weight: bold;
        color: #0f172a;
    }

    .ms-sig-img {
        max-height: 32px;
        display: block;
        margin: 0 auto 3px auto;
    }

    @media print {
        body {
            background: transparent !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .no-print-bar {
            display: none !important;
        }
        .marksheet-page-wrapper {
            padding: 0 !important;
            background: transparent !important;
        }
        .marksheet-card {
            box-shadow: none !important;
            width: 210mm !important;
            height: 297mm !important;
            max-height: 297mm !important;
            page-break-after: avoid !important;
            page-break-inside: avoid !important;
        }
    }
</style>
