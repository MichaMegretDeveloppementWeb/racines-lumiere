<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
@unless ($isIndexable())
    <meta name="robots" content="noindex, nofollow">
@endunless
<link rel="canonical" href="{{ $canonicalUrl() }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="Racines &amp; Lumière">
<meta property="og:locale" content="fr_FR">
<meta property="og:url" content="{{ $canonicalUrl() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $shareImageUrl() }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Un bol d'eau et un flacon d'huile posés sur un drap de lin, dans une lumière dorée">
<meta name="twitter:card" content="summary_large_image">
