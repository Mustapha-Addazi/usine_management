<!DOCTYPE html>
<html>
<head>
    <title>Critical Stock Alert</title>
</head>
<body>
    <h1>Hello, {{ $fullname }}</h1>
    <p>We are notifying you that the current stock covers a period of less than 14 days. Please take the necessary steps to replenish the stock and avoid any interruptions in production.</p>
    <p>Details:</p>
    <ul>
        <li><strong>Product:</strong> {{ $product_name }}</li>
        <li><strong>Remaining Quantity:</strong> {{ $remaining_quantity }} {{$unit}}</li>
        <li><strong>Coverage Period:</strong> {{ $coverage_days }} days</li>
    </ul>
    <p>Please click the link below to access the stock management system:</p>
    <p><a href="{{ $url }}">Click here</a></p>
    <p>Thank you for your attention.</p>
</body>
</html>
