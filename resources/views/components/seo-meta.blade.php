@props([
    'title',
    'description',
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
    'publishedTime' => null,
    'modifiedTime' => null,
    'author' => null,
])

@php
    $seoTitle = trim($title);
    $seoDescription = \Illuminate\Support\Str::limit(
        trim(preg_replace('/\s+/', ' ', strip_tags($description))),
        160,
        ''
    );
    $seoCanonical = $canonical ?: url()->current();
    $seoImage = $image ?: asset('images/naas-logo.jpeg');
@endphp

<meta name="description" content="{{ $seoDescription }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:site_name" content="NAAS Shopping">
<meta property="og:locale" content="en_PK">
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:image:alt" content="{{ $seoTitle }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">

@if($type === 'article' && $publishedTime)
    <meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if($type === 'article' && $modifiedTime)
    <meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif
@if($type === 'article' && $author)
    <meta property="article:author" content="{{ $author }}">
@endif
