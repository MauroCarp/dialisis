<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica - {{ $paciente->nombre }} {{ $paciente->apellido }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .clinic-info {
            font-size: 10px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .patient-info {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            padding: 3px 0;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
        }
        .observations {
            min-height: 200px;
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .signature-area {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 0 auto;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CENTRO DE DIÁLISIS</div>
        <div class="clinic-info">
            Dirección: Av. Principal #123 - Teléfono: (555) 123-4567<br>
            Email: info@centrodialisis.com - Ciudad, País
        </div>
    </div>

    <div class="title">Historia Clínica</div>

    <div class="section">
        <div class="section-title">DATOS DEL PACIENTE</div>
        <div class="patient-info">
            <div class="info-row">
                <div class="info-label">Nombres:</div>
                <div class="info-value">{{ $paciente->nombre }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Apellidos:</div>
                <div class="info-value">{{ $paciente->apellido }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Documento:</div>
                <div class="info-value">{{ $paciente->documento }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Nacimiento:</div>
                <div class="info-value">{{ $paciente->fechanacimiento ? $paciente->fechanacimiento->format('d/m/Y') : 'No registrada' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $paciente->telefono ?? 'No registrado' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Dirección:</div>
                <div class="info-value">{{ $paciente->domicilio ?? 'No registrada' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DE LA HISTORIA CLÍNICA</div>
        <div class="patient-info">
            <div class="info-row">
                <div class="info-label">Fecha:</div>
                <div class="info-value">{{ $historia->fechahistoriaclinica->format('d/m/Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Profesional:</div>
                <div class="info-value">{{ auth()->user()->name ?? 'Sistema' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <div class="observations">
            {{ $historia->observaciones ?? 'Sin observaciones registradas' }}
        </div>
    </div>

    <div class="signature-area">
        <div class="signature-line">
            Firma del Profesional
        </div>
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} - Sistema de Gestión de Pacientes
    </div>
</body>
</html>
