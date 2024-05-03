<!DOCTYPE html>
<html>

<head>
    <title>Ticket Created</title>
</head>

<body>
    <h1>Your Ticket Has Been Created</h1>
    <p>Ticket Status: {{ $ticketStatus }}</p>
    <p>Ticket Number: {{ $ticketNumber }}</p>
    <p>Ticket Info:</p>
    <ul>
        <li>First Name: {{ $ticketInfo['first_name'] }}</li>
        <li>Middle Name: {{ $ticketInfo['middle_name'] }}</li>
        <li>Last Name: {{ $ticketInfo['last_name'] }}</li>
        <li>Address: {{ $ticketInfo['address'] }}</li>
        <li>Number: {{ $ticketInfo['number'] }}</li>
        <li>Email: {{ $ticketInfo['email'] }}</li>
        <li>Ticket Type: {{ $ticketInfo['ticket_type']['name'] }}</li>
        <li>Category: {{ $ticketInfo['category']['type'] }}</li>
        <li>Subcategory: {{ $ticketInfo['sub_category']['type'] }}</li>
        <li>Subject: {{ $ticketInfo['subject'] }}</li>
        <li>Reference Number: {{ $ticketInfo['ref_no'] }}</li>
        <li>Concern: {{ $ticketInfo['concern'] }}</li>
    </ul>

    <p>Track your ticket here test.com/track</p>
</body>

</html>
