<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo de Pago - {{ $payment->payment_code }}</title>
    <style>
        @page {
            margin: 0;
            size: letter portrait;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 40px;
        }

        /* Header Rojo Profesional */
        .header {
            background: #DC2626;
            color: white;
            padding: 25px 30px;
            margin: -40px -40px 25px -40px; /* Extiende el color hasta los bordes */
        }

        .header-table {
            width: 100%;
            display: table;
        }

        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .company-details {
            font-size: 9px;
            opacity: 0.9;
        }

        .receipt-data {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .receipt-badge {
            background: white;
            color: #DC2626;
            padding: 4px 10px;
            display: inline-block;
            font-weight: bold;
            border-radius: 3px;
        }

        /* Información del Estudiante */
        .info-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .section-label {
            font-size: 9px;
            font-weight: bold;
            color: #DC2626;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-cell-label {
            display: table-cell;
            width: 100px;
            font-weight: bold;
            padding: 3px 0;
        }

        .info-cell-value {
            display: table-cell;
            padding: 3px 0;
        }

        /* Tabla de Ítems */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table thead th {
            background: #f8f8f8;
            border-top: 2px solid #333;
            border-bottom: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }

        .items-table tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        /* Totales */
        .summary-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .totals-table {
            width: 250px;
            margin-left: auto;
        }

        .total-row td {
            padding: 5px 0;
        }

        .total-label {
            text-align: right;
            font-weight: bold;
            padding-right: 15px;
        }

        .total-value {
            text-align: right;
            border-bottom: 1px solid #ddd;
            width: 90px;
        }

        .grand-total {
            color: #DC2626;
            font-size: 11px;
            border-top: 2px solid #DC2626;
            margin-top: 5px;
        }

        /* Firmas y Responsables */
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 40px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
            padding-top: 5px;
        }

        .signature-role {
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
            color: #666;
        }

        /* Notas y Advertencias */
        .legal-notice {
            margin-top: 30px;
            padding: 12px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 4px;
            text-align: center;
            font-size: 9px;
            color: #991b1b;
        }

        .observations {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }

        .bold { font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-table">
            <div class="company-info">
                <div class="company-name">ACADEMIA AUTÉNTICA</div>
                <div class="company-details">
                    RUC: 123456-1-123456 | Ciudad de Panamá<br>
                    T: 507-1234-5678 | E: info@academiaautentica.com
                </div>
            </div>
            <div class="receipt-data">
                <div class="receipt-title">Recibo de Pago</div>
                <div class="receipt-badge">DOC #{{ $payment->payment_code }}</div>
                <div style="font-size: 9px; margin-top: 5px;">Fecha: {{ $payment->payment_date->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <span class="section-label">Información del Cliente</span>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell-label">Estudiante:</div>
                <div class="info-cell-value bold">{{ $payment->enrollment->student->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell-label">Cédula / ID:</div>
                <div class="info-cell-value">{{ $payment->enrollment->student->identification }}</div>
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">Cant.</th>
                <th style="width: 50%;">Descripción / Curso</th>
                <th style="width: 20%; text-align: right;">Método Pago</th>
                <th style="width: 20%; text-align: right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.00</td>
                <td>
                    <span class="bold">{{ $payment->enrollment->courseOffering->course->name }}</span><br>
                    <span style="font-size: 8px; color: #777;">Inscripción ID: {{ $payment->enrollment->enrollment_code }}</span>
                </td>
                <td class="text-right">{{ $payment->payment_method_label }}</td>
                <td class="text-right">B/. {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary-wrapper">
        <table class="totals-table">
            <tr class="total-row">
                <td class="total-label">Subtotal Transacción:</td>
                <td class="total-value">B/. {{ number_format($payment->amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="total-label" style="color: #666; font-weight: normal;">Total del Curso:</td>
                <td class="total-value">B/. {{ number_format($payment->paymentPlan->total_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="total-label" style="color: #666; font-weight: normal;">Total Abonado:</td>
                <td class="total-value">B/. {{ number_format($payment->paymentPlan->total_paid, 2) }}</td>
            </tr>
            <tr class="total-row grand-total">
                <td class="total-label">SALDO PENDIENTE:</td>
                <td class="total-value bold">B/. {{ number_format($payment->paymentPlan->balance, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">{{ $payment->receivedBy->name }}</div>
            <div class="signature-role">Generado por (Usuario)</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">{{ $payment->enrollment->student->full_name }}</div>
            <div class="signature-role">Recibido por (Estudiante)</div>
        </div>
    </div>

    <div class="legal-notice">
        <strong>Este documento representa un comprobante de pago oficial emitido por Academia Auténtica. No es un documento fiscal.</strong>
    </div>

    @if($payment->notes)
    <div class="observations">
        <span class="bold">OBSERVACIONES:</span> {{ $payment->notes }}
    </div>
    @endif

    <div style="margin-top: 30px; text-align: center; font-size: 8px; color: #999;">
        Generado automáticamente por el sistema de gestión escolar el {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>