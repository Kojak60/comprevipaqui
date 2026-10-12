<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')

    <!-- Performance & SEO Meta Tags -->
    <meta name="robots" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <meta name="googlebot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <meta name="bingbot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Author & Copyright -->
    <meta name="author" content="{{ gs()->siteName() }}">
    <meta name="copyright" content="{{ gs()->siteName() }} © {{ date('Y') }}">
    
    <!-- Refresh for duplicate content prevention -->
    <meta http-equiv="refresh" content="0; url={{ url()->current() }}" meta="refresh">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:locale:alternate" content="en">
    <meta property="og:site_name" content="{{ gs()->siteName() }}">
    <meta property="og:title" content="{{ gs()->siteName(__($pageTitle)) }}">
    <meta property="og:description" content="{{ (isset($seoContents->description) && $seoContents->description) ? $seoContents->description : (isset($seo->description) ? $seo->description : '') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $seoImage ?? (isset($seo->image) ? getImage(getFilePath('seo') . '/' . $seo->image) : '') }}">
    <meta property="og:image:secure_url" content="{{ $seoImage ?? (isset($seo->image) ? getImage(getFilePath('seo') . '/' . $seo->image) : '') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ gs()->siteName() }}">
    <meta property="og:image:type" content="image/jpeg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ gs()->siteName() }}">
    <meta name="twitter:creator" content="{{ gs()->siteName() }}">
    <meta name="twitter:title" content="{{ gs()->siteName(__($pageTitle)) }}">
    <meta name="twitter:description" content="{{ (isset($seoContents->description) && $seoContents->description) ? $seoContents->description : (isset($seo->description) ? $seo->description : '') }}">
    <meta name="twitter:image" content="{{ $seoImage ?? (isset($seo->image) ? getImage(getFilePath('seo') . '/' . $seo->image) : '') }}">
    <meta name="twitter:image:alt" content="{{ gs()->siteName() }}">

    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://pagead2.googlesyndication.com">

    <!-- DNS Prefetch for third-party services -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">

    <!-- Resource hints -->
    <link rel="preload" href="{{ asset(activeTemplate(true) . 'css/main.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}" as="script">

    <!-- Favicon -->
    <link type="image/x-icon" href="{{ siteFavicon() }}" rel="shortcut icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ siteLogo() }}">

    <!-- Theme Color -->
    <meta name="theme-color" content="{{ gs('base_color', 'default') }}">
    <meta name="msapplication-TileColor" content="{{ gs('base_color', 'default') }}">

    <!-- Structured Data / JSON-LD -->
    @php
    $shop = [
        '@type' => 'WebSite',
        '@context' => 'https://schema.org',
        'name' => gs()->siteName(),
        'description' => gs()->siteDescription(),
        'url' => url('/')->toString(),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/search?q={search_term_string}')->toString(),
            'query-input' => 'required name=search_term_string',
        ],
    ];
    echo json_encode($shop, JSON_PRETTY_PRINT);
    @endphp

    <!-- Structured Data for Organization -->
    @php
    $organization = [
        '@type' => 'Organization',
        '@context' => 'https://schema.org',
        'name' => gs()->siteName(),
        'url' => url('/')->toString(),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => siteLogo(),
        ],
        'sameAs' => [
            'https://www.facebook.com/yourpage',
            'https://twitter.com/yourpage',
            'https://www.instagram.com/yourpage',
            'https://www.linkedin.com/company/yourpage',
        ],
    ];
    echo json_encode($organization, JSON_PRETTY_PRINT);
    @endphp

    <!-- Schema.org Breadcrumb Navigation -->
    @if(request()->segments())
        @php
        $breadcrumbs = ['<a href="/">Home</a>'];
        for ($i = 1; $i < count(request()->segments()); $i++) {
            $url = '/' . implode('/', array_slice(request()->segments(), 0, $i));
            $segment = request()->segments()[$i];
            $name = ucfirst(str_replace(['-', '_'], ' ', $segment));
            $breadcrumbs[] = '<a href="' . $url . '">' . $name . '</a>';
        }
        echo '<script type="application/ld+json">{
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
        ';
        foreach ($breadcrumbs as $i => $breadcrumb) {
            $position = $i + 1;
            echo '"{
                "@type": "ListItem",
                "position": ' . $position . ',
                "name": "' . ($breadcrumb) . '",
                "item": "' . (
                    $position === 1 ? url('/')->toString() : (
                        $position === count($breadcrumbs) ? request()->url() : '/' . implode('/', array_slice(request()->segments(), 0, $position))
                    )
                ) . '"
            },';
        }
        echo ']}}';
        @endphp
    @endif

    <!-- Preconnect, DNS Prefetch, Prefetch -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ siteFavicon() }}">

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    @stack('style-lib')
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset(activeTemplate(true) . 'css/color.php?color=' . gs('base_color')) }}">
    @stack('style')
    <link href="{{ asset(activeTemplate(true) . 'css/custom.css') }}" rel="stylesheet">

    <!-- Performance optimization -->
    <link rel="preload" href="{{ asset(activeTemplate(true) . 'css/main.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}" as="script">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('assets/images/logo.png') }}" as="image">
</head>

<head>
    @php echo loadExtension('google-analytics') @endphp
</head>

<body>
    <div class="body-overlay" id="body-overlay"></div>
    @include('Template::partials.preloader')

    @yield('app')

    @stack('modal')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset(activeTemplate(true) . 'js/jquery.validate.js') }}"></script>
    @stack('script-lib')
    <script src="{{ asset(activeTemplate(true) . 'js/main.js') }}"></script>
    @php echo loadExtension('tawk-chat') @endphp
    @include('partials.notify')
    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    @stack('script')
</body>

</html>
