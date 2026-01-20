<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cuentas por Cobrar - {{ now()->format('d/m/Y') }}</title>
    <style>
        @page {
            margin: 30px;
            size: letter landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #333;
        }

        .header {
            background: #DC2626;
            color: white;
            padding: 15px 20px;
            margin: -30px -30px 20px -30px;
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
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .report-data {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
        }

        .stat-label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 3px;
        }

        .stat-value.total { color: #2563eb; }
        .stat-value.current { color: #059669; }
        .stat-value.overdue { color: #dc2626; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table thead th {
            background: #f3f4f6;
            border-top: 2px solid #333;
            border-bottom: 1px solid #333;
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .data-table tbody td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 8px;
        }

        .data-table tfoot td {
            padding: 8px 4px;
            border-top: 2px solid #333;
            font-weight: bold;
            background: #f9fafb;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }

        .row-overdue {
            background: #fef2f2 !important;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-table">
            <div class="company-info">
                <div class="company-name">ACADEMIA AUTÉNTICA</div>
                <div style="font-size: 9px; opacity: 0.9;">
                    Sistema de Gestión Académica
                </div>
            </div>
            <div class="report-data">
                <div class="report-title">Cuentas por Cobrar</div>
                <div style="font-size: 9px; margin-top: 3px;">
                    Generado: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total por Cobrar</div>
            <div class="stat-value total">B/. {{ number_format($totalReceivable, 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Vigente (No Vencido)</div>
            <div class="stat-value current">B/. {{ number_format($totalCurrent, 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Vencido (Atrasado)</div>
            <div class="stat-value overdue">B/. {{ number_format($totalOverdue, 2) }}</div>
        </div>
    </div>

    <!-- Accounts Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Estudiante</th>
                <th style="width: 18%;">Curso</th>
                <th style="width: 8%;">Plan</th>
                <th style="width: 11%; text-align: right;">Total</th>
                <th style="width: 11%; text-align: right;">Pagado</th>
                <th style="width: 11%; text-align: right;">Saldo</th>
                <th style="width: 13%;">Próx. Venc.</th>
                <th style="width: 10%; text-align: center;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentPlans as $plan)
            <tr class="{{ $plan->schedules->where('status', 'vencido')->isNotEmpty() ? 'row-overdue' : '' }}">
                <td>
                    <div class="bold">{{ $plan->enrollment->student->full_name }}</div>
                    <div style="font-size: 7px; color: #6b7280;">{{ $plan->enrollment->student->identification }}</div>
                </td>

                <td>
                    <div class="bold">{{ $plan->enrollment->courseOffering->course->name }}</div>
                    <div style="font-size: 7px; color: #6b7280;">{{ $plan->enrollment->courseOffering->location }}</div>
                </td>

                <td>
                    {{ ucfirst($plan->payment_type) }}
                    @if($plan->payment_type === 'cuotas')
                    <br><span style="font-size: 7px; color: #6b7280;">{{ $plan->number_of_installments }} cuotas</span>
                    @endif
                </td>

                <td class="text-right">
                    B/. {{ number_format($plan->total_amount, 2) }}
                </td>

                <td class="text-right" style="color: #059669;">
                    B/. {{ number_format($plan->total_paid, 2) }}
                </td>

                <td class="text-right bold" style="color: #dc2626; font-size: 10px;">
                    B/. {{ number_format($plan->balance, 2) }}
                </td>

                <td>
                    @php
                        $nextSchedule = $plan->schedules->sortBy('due_date')->first();
                    @endphp
                    @if($nextSchedule)
                        {{ $nextSchedule->due_date->format('d/m/Y') }}<br>
                        <span style="font-size: 7px; color: #6b7280;">B/. {{ number_format($nextSchedule->balance, 2) }}</span>
                    @else
                        <span style="color: #9ca3af;">N/A</span>
                    @endif
                </td>

                <td class="text-center">
                    @php
                        $hasOverdue = $plan->schedules->where('status', 'vencido')->isNotEmpty();
                    @endphp
                    <span class="badge {{ $hasOverdue ? 'badge-danger' : 'badge-warning' }}">
                        {{ $hasOverdue ? 'Vencido' : 'Vigente' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-size: 10px;">TOTAL A COBRAR:</td>
                <td class="text-right bold" style="color: #dc2626; font-size: 14px;">
                    B/. {{ number_format($totalReceivable, 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Este reporte incluye {{ $paymentPlans->count() }} planes de pago con saldo pendiente</p>
        <p style="margin-top: 3px;">Academia Auténtica - Sistema de Gestión Académica</p>
    </div>

</body>
</html>
