<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Corte Z - {{ $cash_register->code }}</title>
    <style>
        @page {
            margin: 15mm;
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
            line-height: 1.3;
            color: #333;
        }
        
        /* Header Section */
        .header {
            background: #DC2626;
            color: white;
            padding: 18px 25px;
            margin: -15mm -15mm 15px -15mm;
        }
        
        .header-content {
            display: table;
            width: 100%;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .company-details {
            font-size: 8px;
            line-height: 1.5;
            opacity: 0.95;
        }
        
        .report-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }
        
        .report-type {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .report-code {
            background: white;
            color: #DC2626;
            padding: 6px 12px;
            display: inline-block;
            font-weight: bold;
            font-size: 12px;
            border-radius: 3px;
        }
        
        /* Info Sections */
        .info-section {
            background: #f8f9fa;
            border: 1.5px solid #e0e0e0;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        
        .section-title {
            color: #DC2626;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #DC2626;
            padding-bottom: 4px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #555;
            padding: 3px 0;
            width: 40%;
            font-size: 9px;
        }
        
        .info-value {
            display: table-cell;
            color: #333;
            padding: 3px 0;
            font-size: 9px;
        }
        
        /* Stats Cards */
        .stats-section {
            margin: 12px 0;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        
        .stat-card {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px 8px;
            border-radius: 4px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
        }
        
        .stat-label {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #DC2626;
        }
        
        /* Payment Methods */
        .methods-section {
            margin: 12px 0;
        }
        
        .methods-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
        }
        
        .method-card {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            background: #f8f9fa;
            border: 1.5px solid #e0e0e0;
            border-radius: 4px;
            text-align: center;
        }
        
        .method-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 4px;
            font-weight: 600;
        }
        
        .method-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .method-count {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
        }
        
        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            background: white;
            border: 1.5px solid #e0e0e0;
        }
        
        .summary-table tr {
            border-bottom: 1px solid #e0e0e0;
        }
        
        .summary-table tr:last-child {
            border-bottom: none;
        }
        
        .summary-table td {
            padding: 8px 12px;
            font-size: 9px;
        }
        
        .summary-table td:first-child {
            font-weight: 600;
            color: #555;
        }
        
        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
        
        .summary-highlight {
            background: #fef2f2 !important;
            border-top: 2px solid #DC2626 !important;
        }
        
        .summary-difference-positive {
            background: #f0fdf4 !important;
            color: #22c55e !important;
        }
        
        .summary-difference-negative {
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }
        
        /* Transactions Table */
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 8px;
        }
        
        .transactions-table thead {
            background: #DC2626;
            color: white;
        }
        
        .transactions-table th {
            padding: 6px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .transactions-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .transactions-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        /* Footer */
        .footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 2px solid #DC2626;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="company-info">
                <div class="company-name">ACADEMIA AUTÉNTICA</div>
                <div class="company-details">
                    RUC: 123456-1-123456 | Dirección: Ciudad de Panamá<br>
                    Teléfono: 507-1234-5678 | Email: info@academiaautentica.com
                </div>
            </div>
            <div class="report-info">
                <div class="report-type">CORTE Z</div>
                <div class="report-code">{{ $cash_register->code }}</div>
            </div>
        </div>
    </div>

    <!-- Opening Info -->
    <div class="info-section">
        <div class="section-title">APERTURA DE CAJA</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Abierto por:</div>
                <div class="info-value">{{ $cash_register->openedBy->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha/Hora Apertura:</div>
                <div class="info-value">{{ $cash_register->opened_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fondo Inicial:</div>
                <div class="info-value font-bold">B/. {{ number_format($cash_register->opening_amount, 2) }}</div>
            </div>
            @if($cash_register->opening_notes)
            <div class="info-row">
                <div class="info-label">Notas:</div>
                <div class="info-value">{{ $cash_register->opening_notes }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Closing Info -->
    @if($cash_register->status === 'cerrada')
    <div class="info-section">
        <div class="section-title">CIERRE DE CAJA</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Cerrado por:</div>
                <div class="info-value">{{ $cash_register->closedBy->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha/Hora Cierre:</div>
                <div class="info-value">{{ $cash_register->closed_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Monto Contado:</div>
                <div class="info-value font-bold">B/. {{ number_format($cash_register->closing_amount, 2) }}</div>
            </div>
            @if($cash_register->closing_notes)
            <div class="info-row">
                <div class="info-label">Notas:</div>
                <div class="info-value">{{ $cash_register->closing_notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Statistics -->
    <div class="stats-section">
        <div class="section-title">ESTADÍSTICAS</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Transacciones</div>
                <div class="stat-value">{{ $stats['total_transactions'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Recaudado</div>
                <div class="stat-value">B/. {{ number_format($stats['total_collected'], 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Promedio</div>
                <div class="stat-value">B/. {{ number_format($stats['average_transaction'], 2) }}</div>
            </div>
            @if($cash_register->status === 'cerrada')
            <div class="stat-card">
                <div class="stat-label">Diferencia</div>
                <div class="stat-value" style="color: {{ $cash_register->difference >= 0 ? '#22c55e' : '#dc2626' }}">
                    B/. {{ number_format($cash_register->difference, 2) }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="methods-section">
        <div class="section-title">DESGLOSE POR MÉTODO DE PAGO</div>
        <table style="width: 100%; border-collapse: separate; border-spacing: 6px;">
            <tr>
                @php $counter = 0; @endphp
                @foreach($payments_by_method as $method => $data)
                    @if($counter % 3 == 0 && $counter > 0)
                        </tr><tr>
                    @endif
                    <td style="width: 33.33%;">
                        <div class="method-card">
                            <div class="method-label">
                                @switch($method)
                                    @case('efectivo') EFECTIVO @break
                                    @case('transferencia') TRANSFERENCIA @break
                                    @case('tarjeta_debito') TARJETA DÉBITO @break
                                    @case('tarjeta_credito') TARJETA CRÉDITO @break
                                    @case('yappy') YAPPY @break
                                    @default {{ strtoupper($method) }} @break
                                @endswitch
                            </div>
                            <div class="method-value">B/. {{ number_format($data['total'], 2) }}</div>
                            <div class="method-count">{{ $data['count'] }} transacciones</div>
                        </div>
                    </td>
                    @php $counter++; @endphp
                @endforeach
                @while($counter % 3 != 0)
                    <td style="width: 33.33%;"></td>
                    @php $counter++; @endphp
                @endwhile
            </tr>
        </table>
    </div>

    <!-- Summary -->
    @if($cash_register->status === 'cerrada')
    <div class="info-section">
        <div class="section-title">RESUMEN FINAL</div>
        <table class="summary-table">
            <tr>
                <td>Fondo Inicial</td>
                <td>B/. {{ number_format($cash_register->opening_amount, 2) }}</td>
            </tr>
            <tr style="background: #f0fdf4;">
                <td>+ Total Recaudado</td>
                <td style="color: #22c55e;">B/. {{ number_format($stats['total_collected'], 2) }}</td>
            </tr>
            <tr>
                <td>= Total Esperado</td>
                <td>B/. {{ number_format($cash_register->expected_amount, 2) }}</td>
            </tr>
            <tr class="summary-highlight">
                <td>Monto Contado</td>
                <td style="color: #DC2626;">B/. {{ number_format($cash_register->closing_amount, 2) }}</td>
            </tr>
            <tr class="{{ $cash_register->difference >= 0 ? 'summary-difference-positive' : 'summary-difference-negative' }}" style="border-top: 2px solid {{ $cash_register->difference >= 0 ? '#22c55e' : '#dc2626' }};">
                <td style="font-size: 10px;"><strong>DIFERENCIA</strong></td>
                <td style="font-size: 11px;">
                    <strong>B/. {{ number_format($cash_register->difference, 2) }}</strong>
                    <span style="font-size: 8px; margin-left: 5px;">
                        ({{ $cash_register->difference > 0 ? 'SOBRANTE' : ($cash_register->difference < 0 ? 'FALTANTE' : 'EXACTO') }})
                    </span>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Transactions List -->
    @if($payments->count() > 0)
    <div style="margin-top: 15px;">
        <div class="section-title">DETALLE DE TRANSACCIONES ({{ $payments->count() }})</div>
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>HORA</th>
                    <th>CÓDIGO</th>
                    <th>ESTUDIANTE</th>
                    <th>MÉTODO</th>
                    <th style="text-align: right;">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('H:i') }}</td>
                    <td>{{ $payment->payment_code }}</td>
                    <td>{{ $payment->enrollment->student->full_name }}</td>
                    <td>{{ $payment->payment_method_label }}</td>
                    <td style="text-align: right; font-weight: bold;">B/. {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</strong></p>
        <p>Academia Auténtica - Sistema ERP</p>
        @if($cash_register->status === 'cerrada')
        <p style="margin-top: 8px;">
            <strong>CAJA CERRADA</strong> - Este documento constituye el corte Z oficial de la caja {{ $cash_register->code }}
        </p>
        @endif
    </div>
</body>
</html>