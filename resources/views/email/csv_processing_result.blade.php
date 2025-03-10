<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['status'] }} - CSV Processing</title>
</head>
<body>
    <h1>CSV Processing {{ ucfirst($data['status']) }}</h1>
    
    <p>Status: {{ ucfirst($data['status']) }}</p>
    <p>Successful Inserts: {{ $data['successfulInserts'] }}</p>
    <p>Failed Rows: {{ $data['failedCount'] }}</p>

    @if ($data['failedCsvLink'])
        <p>Failed Rows CSV: <a href="{{ $data['failedCsvLink'] }}">Download</a></p>
    @endif

    @if ($data['errorMessage'])
        <p>Error: {{ $data['errorMessage'] }}</p>
    @endif

    <p>Uploaded CSV: <a href="{{ $data['uploadedCsvLink'] }}">View File</a></p>
</body>
</html>