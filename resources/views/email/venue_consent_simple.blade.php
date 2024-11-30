@component('mail::message')
# Exam Venue Consent Request

Dear {{ $venueName }} Administration,

We are requesting your consent to use your venue for an upcoming examination.

## Exam Details
- **Exam Name**: {{ $examName }}
- **Exam Date**: {{ $examDate }}

## Venue Information
- **Venue Name**: {{ $venueName }}
- **Venue Address**: {{ $venueAddress }}
- **Required Halls**: {{ $requiredHalls }}

@component('mail::button', ['url' => $loginUrl])
Login to Respond
@endcomponent

Please log in to our website to:
- Accept the venue request
- Decline the request

If you have any questions, please contact our examination coordination team.

Best regards,  
Examination Department
@endcomponent