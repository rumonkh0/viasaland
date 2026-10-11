<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Adiba Global - Premium Visa Processing & Education Consultancy' }}</title>
  <meta name="description" content="{{ $description ?? 'Premier international visa processing and educational consultancy firm. Student, Work, Tourist, Business & Umrah visas.' }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  @stack('styles')
</head>
<body>
  @include('partials.topbar')
  @include('partials.navbar', ['activePage' => $activePage ?? ''])

  @yield('content')

  @include('partials.footer')
  @include('partials.consultation-modal')
  @include('partials.article-modal')

  <script>
    window.visaRequirements = @json($visaRequirements ?? []);
  </script>
  <script src="{{ asset('script.js') }}"></script>
  @stack('scripts')
</body>
</html>
