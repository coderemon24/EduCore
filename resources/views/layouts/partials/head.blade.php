<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')</title>
<meta name="description" content="@yield('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের অফিসিয়াল পোর্টাল — নোটিশ, রুটিন, ফলাফল ও প্রাতিষ্ঠানিক তথ্য।')">

<!-- Institutional Favicon -->
<link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">

<!-- Google Fonts: Noto Sans Bengali (Bengali) & Plus Jakarta Sans (English Fallback) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome 6 Free Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Swiper Slider CSS (Local Asset) -->
<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">

<!-- Main Frontend CSS (Local Asset: public/assets/css/style.css) -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

<!-- Dedicated Responsive CSS (Local Asset: public/assets/css/responsive.css) -->
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

@stack('styles')
