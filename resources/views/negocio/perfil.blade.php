@extends('layouts.perfil-negocio')
@section('title', $negocio->neg_nombre_comercial ?? 'Perfil del Negocio')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    @import url('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');

    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --card-2: #fafbff;
        --text: #0f172a;
        --muted: #64748b;
        --line: #e8ebf3;
        --primary: #5a31d7;
        --primary-dark: #4a22b5;
        --primary-light: #7b5ce0;
        --accent: #32ccbc;
        --accent-light: #90f7ec;
        --success: #16a34a;
        --warning: #f59e0b;
        --shadow: 0 18px 45px rgba(15, 23, 42, .08);
        --radius: 22px;
        --radius-sm: 14px;
        --max: 1260px;
        --gray-50: #f6f7fb;
        --gray-100: #f1f5f9;
        --gray-200: #e8ebf3;
        --gray-400: #94a3b8;
        --gray-600: #64748b;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
    }

    * { box-sizing: border-box; }
    body {
        font-family: 'IBM Plex Sans', system-ui, -apple-system, sans-serif;
        background: radial-gradient(circle at top left, rgba(90,49,215,.12), transparent 32%),
                    radial-gradient(circle at top right, rgba(50,204,188,.08), transparent 26%),
                    var(--bg);
    }

    /* ===== CONTAINER ===== */
    .container-perfil { max-width: var(--max); margin: 0 auto; padding: 0 24px; }

    /* ===== BUTTONS ===== */
    .btn-ref { display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:14px 18px; border-radius:14px; font-weight:700; border:1px solid transparent; cursor:pointer; transition:.2s ease; font-size:.92rem; text-decoration:none; }
    .btn-ref-primary { background:var(--primary); color:#fff; box-shadow:0 14px 30px rgba(90,49,215,.28); }
    .btn-ref-primary:hover { background:var(--primary-dark); transform:translateY(-1px); }
    .btn-ref-light { background:#fff; border-color:var(--line); color:var(--text); }
    .btn-ref-light:hover { background:#f8fafc; }

    /* ===== SECTION CARD ===== */
    .section-card { background:var(--card); border:1px solid var(--line); box-shadow:var(--shadow); border-radius:24px; padding:24px; }
    .section-head { display:flex; justify-content:space-between; align-items:end; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
    .eyebrow { font-size:12px; text-transform:uppercase; letter-spacing:.12em; color:var(--primary-dark); font-weight:800; margin-bottom:8px; }
    .section-title { margin:0; font-size:29px; font-weight:800; letter-spacing:-.03em; color:var(--text); line-height:1.1; }
    .section-copy { margin:6px 0 0; color:var(--muted); line-height:1.7; font-size:15px; }
    .tag-ref { padding:8px 10px; border-radius:999px; background:#f8fafc; border:1px solid var(--line); font-size:13px; font-weight:700; color:#334155; display:inline-flex; align-items:center; gap:5px; }

    /* ===== FULLCALENDAR ===== */
    .fc-day-past { background-color: #f1f5f9 !important; opacity: 0.6; cursor: not-allowed !important; }
    .fc-day-past .fc-daygrid-day-number { color: #94a3b8 !important; text-decoration: line-through; }
    .fc-day-blocked { background-color: #fee2e2 !important; cursor: not-allowed !important; }
    .fc-day-blocked .fc-daygrid-day-number { color: #dc2626 !important; font-weight: bold; }
    .fc .fc-daygrid-day:not(.fc-day-past):not(.fc-day-blocked):hover { background-color: #f3f0ff !important; cursor: pointer; }
    .fc-event { border-radius: 6px; padding: 2px 6px; font-size: 0.75rem; font-weight: 500; cursor: pointer; }
    .fc .fc-toolbar-title { font-size: 1rem !important; font-weight: 700 !important; color: var(--gray-800); }
    .fc .fc-button { background: #5a31d7 !important; border-color: #5a31d7 !important; font-size: 0.75rem !important; padding: 4px 10px !important; border-radius: 8px !important; }
    .fc .fc-button:hover { background: #5a31d7 !important; }
    .fc .fc-col-header-cell { background: #f8faff; }
    .fc .fc-col-header-cell-cushion { color: #5a31d7; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none; }

    /* ===== SCROLLBAR ===== */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* ===== HERO GRID ===== */
    .hero-grid {
        display: grid;
        grid-template-columns: 1.3fr 0.82fr;
        gap: 24px;
        align-items: start;
    }

    /* ===== HERO CARD (left) ===== */
    .hero-card {
        border-radius: 28px;
        overflow: hidden;
        background: var(--card);
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
    }
    .hero-cover-ref {
        position: relative;
        height: 330px;
        display: flex;
        align-items: flex-end;
        padding: 28px;
        overflow: hidden;
        background: #e8ebf3;
    }
    .hero-cover-ref img.hero-cover-img {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        object-fit: cover; object-position: center;
        display: block;
    }
    .hero-cover-ref .cover-pattern {
        position: absolute; inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
        background-size: 20px 20px;
        z-index: 1; pointer-events: none;
    }
    .hero-cover-ref .cover-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(15,23,42,.08), rgba(15,23,42,.48));
        z-index: 1;
    }
    .hero-overlay-ref {
        position: relative; z-index: 2;
        width: 100%;
        display: flex; justify-content: space-between; gap: 20px; align-items: end; flex-wrap: wrap;
    }
    .hero-title-cover {
        color: #fff; font-size: 38px; line-height: 1.05;
        margin: 14px 0 12px; font-weight: 800; letter-spacing: -.03em;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }
    .hero-meta-pills { display: flex; gap: 10px; flex-wrap: wrap; }
    .pill-ref {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 12px; border-radius: 999px;
        background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.18);
        color: #fff; font-size: 13px; font-weight: 700;
        backdrop-filter: blur(10px);
    }
    .meta-chip-ref {
        padding: 10px 12px; border-radius: 999px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
        color: #fff; font-size: 14px; font-weight: 600;
    }
    .hero-bottom-ref {
        padding: 22px 26px 24px;
        background: #fff;
        display: grid; grid-template-columns: 1fr auto; gap: 18px; align-items: center;
    }
    .hero-bottom-left { display: flex; align-items: center; gap: 18px; }
    .hero-avatar {
        width: 72px; height: 72px; min-width: 72px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 4px 16px rgba(90,49,215,0.2);
        overflow: hidden; flex-shrink: 0;
    }
    .hero-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .hero-desc { color: var(--muted); font-size: 15px; line-height: 1.7; }
    .hero-desc strong { color: var(--text); }

    /* ===== BOOKING CARD (right, sticky) ===== */
    .booking-card {
        position: sticky;
        top: 96px;
        background: #fff;
        border: 1px solid #e8ebf3;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15,23,42,.08);
        padding: 22px;
    }
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 18px;
    }
    .booking-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #0f172a;
    }
    .booking-subtitle {
        color: #64748b;
        font-size: 14px;
        margin-top: 6px;
    }
    .booking-badge {
        background: #ecfdf5;
        color: #16a34a;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* Stepper */
    .booking-stepper {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
    }
    .booking-stepper-box {
        flex: 1;
        padding: 10px;
        border-radius: 12px;
        background: #fafbff;
        border: 1px solid #e8ebf3;
        text-align: center;
    }
    .booking-stepper-box strong {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        display: block;
    }
    .booking-stepper-box span {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: block;
    }

    /* Booking fields */
    .booking-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }
    .booking-field {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        border: 1px solid #e8ebf3;
        background: #fff;
        font-family: inherit;
        font-size: 14px;
        color: #0f172a;
        outline: none;
        cursor: pointer;
        transition: border-color .2s, box-shadow .2s;
        margin-bottom: 16px;
    }
    .booking-field:focus {
        border-color: #d4c8f5;
        box-shadow: 0 0 0 4px rgba(90,49,215,.12);
    }

    /* Flatpickr overrides */
    .flatpickr-calendar {
        font-family: 'IBM Plex Sans', system-ui, sans-serif !important;
        border-radius: 18px !important;
        box-shadow: 0 18px 45px rgba(15,23,42,.12) !important;
        border: 1px solid #e8ebf3 !important;
        overflow: hidden;
    }
    .flatpickr-months { background: #5a31d7; padding: 4px 0; }
    .flatpickr-months .flatpickr-month,
    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year { color: #fff !important; }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month { fill: #fff !important; color: #fff !important; }
    .flatpickr-months .flatpickr-prev-month:hover svg,
    .flatpickr-months .flatpickr-next-month:hover svg { fill: #e8e0fb !important; }
    span.flatpickr-weekday { color: #5a31d7 !important; font-weight: 700 !important; font-size: 12px !important; }
    .flatpickr-day { border-radius: 10px !important; font-weight: 600 !important; }
    .flatpickr-day:hover { background: #f3f0ff !important; border-color: #d4c8f5 !important; }
    .flatpickr-day.selected,
    .flatpickr-day.selected:hover { background: #5a31d7 !important; border-color: #5a31d7 !important; color: #fff !important; }
    .flatpickr-day.today { border-color: #5a31d7 !important; color: #5a31d7 !important; }
    .flatpickr-day.today.selected { color: #fff !important; }
    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
        color: #cbd5e1 !important;
        background: #f8f9fa !important;
        text-decoration: line-through !important;
        cursor: not-allowed !important;
        border-color: transparent !important;
    }

    /* Time slots */
    .booking-slots-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin: 6px 0 16px;
    }
    .booking-slot {
        padding: 12px;
        border-radius: 14px;
        border: 1px solid #e8ebf3;
        background: #fff;
        text-align: center;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
        color: #0f172a;
    }
    .booking-slot:hover,
    .booking-slot.active {
        border-color: #d4c8f5;
        background: #f3f0ff;
        color: #5a31d7;
    }

    /* Summary */
    .booking-summary-card {
        padding: 14px;
        border-radius: 18px;
        background: linear-gradient(180deg, #f9f7ff, #f3f0ff);
        border: 1px solid #e8e0fb;
        margin: 0 0 16px;
    }
    .booking-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 8px 0;
        font-size: 14px;
        color: #334155;
        border-bottom: 1px solid #e8ebf3;
    }
    .booking-summary-row:last-child {
        border-bottom: none;
    }
    .booking-summary-row strong {
        font-weight: 700;
        color: #0f172a;
        text-align: right;
    }

    /* Submit button */
    .booking-btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #5a31d7, #32ccbc);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        border-radius: 16px;
        cursor: pointer;
        font-family: inherit;
        box-shadow: 0 14px 30px rgba(90,49,215,.3);
        transition: all .2s;
    }
    .booking-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 20px 40px rgba(90,49,215,.4);
    }

    .booking-footnote {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 10px;
        text-align: center;
    }

    /* ===== HIDE PARENT NAVBAR ===== */
    .clx-navbar { display: none !important; }

    /* ===== PROFILE NAV ===== */
    .profile-nav {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 50;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(232,235,243,.7);
        box-shadow: 0 1px 12px rgba(15,23,42,0.06);
        transition: box-shadow 0.3s ease;
    }
    .profile-nav.scrolled {
        box-shadow: 0 4px 20px rgba(15,23,42,0.1);
    }
    .profile-nav-inner {
        max-width: var(--max);
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        align-items: center;
        height: 64px;
        gap: 8px;
    }
    .profile-nav-logo {
        height: 28px;
        width: auto;
        flex-shrink: 0;
    }
    .profile-nav-logo-text {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--primary-dark);
        letter-spacing: -0.03em;
        text-decoration: none;
    }
    .profile-nav-links {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: auto;
    }
    .nav-link {
        background: none;
        border: none;
        padding: 9px 14px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .nav-link:hover { background: #f1f5f9; color: var(--text); }
    .nav-link.active { background: #f3f0ff; color: var(--primary); }
    .nav-cta {
        margin-left: 8px;
    }

    /* Hide nav links on mobile, show hamburger */
    @media (max-width: 860px) {
        .profile-nav-links .nav-link { display: none; }
        .profile-nav-links .nav-cta { margin-left: auto; }
        .nav-mobile-toggle { display: flex; }
    }
    @media (min-width: 861px) {
        .nav-mobile-toggle { display: none; }
    }
    .nav-mobile-toggle {
        align-items: center;
        justify-content: center;
        width: 40px; height: 40px;
        border: none; background: none;
        color: var(--text); font-size: 1.2rem;
        cursor: pointer; border-radius: 10px;
        transition: background 0.2s;
    }
    .nav-mobile-toggle:hover { background: #f1f5f9; }

    /* Mobile dropdown */
    .nav-mobile-menu {
        display: none;
        position: fixed;
        top: 64px; left: 0; right: 0;
        z-index: 49;
        background: rgba(255,255,255,0.97);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--line);
        box-shadow: 0 12px 32px rgba(15,23,42,0.1);
        padding: 12px 16px;
    }
    .nav-mobile-menu.open { display: flex; flex-direction: column; gap: 4px; }
    .nav-mobile-menu .nav-link-mobile {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 16px; border-radius: 12px;
        font-size: 0.92rem; font-weight: 600;
        color: var(--text); text-decoration: none;
        border: none; background: none; cursor: pointer;
        width: 100%; text-align: left;
        transition: background 0.2s;
    }
    .nav-mobile-menu .nav-link-mobile:hover { background: #f1f5f9; }
    .nav-mobile-menu .nav-link-mobile.active { background: #f3f0ff; color: var(--primary); }

    /* Scroll sections offset for fixed nav */
    .scroll-section { scroll-margin-top: 80px; }

    /* ===== ACERCA FLOW ===== */
    .acerca-flow {
        display: flex; flex-direction: column; gap: 24px;
        max-width: var(--max); margin: 0 auto;
        padding: 24px;
    }

    /* ===== REVIEWS ===== */
    .rev-section-card {
        background: #fff; border-radius: 24px; border: 1px solid #e8ebf3;
        box-shadow: 0 18px 45px rgba(15,23,42,.08); padding: 24px; margin-bottom: 24px;
    }
    .rev-header { margin-bottom: 18px; }
    .rev-eyebrow {
        font-size: 11px; text-transform: uppercase; letter-spacing: .12em;
        color: #5a31d7; font-weight: 800; margin-bottom: 8px;
        background: #f3f0ff; padding: 4px 10px; border-radius: 6px; display: inline-block;
    }
    .rev-title { font-size: 1.75rem; font-weight: 800; letter-spacing: -.03em; color: #0f172a; margin: 0; }
    .rev-copy { color: #64748b; font-size: 0.9rem; line-height: 1.7; margin: 6px 0 0; }
    .rev-grid { display: grid; grid-template-columns: 1.1fr 1fr 1fr; gap: 16px; }
    .rev-grid.no-score { grid-template-columns: 1fr 1fr; }
    .rev-card {
        background: #fff; border-radius: 20px; border: 1px solid #e8ebf3;
        padding: 22px; box-shadow: 0 4px 16px rgba(15,23,42,.06);
    }
    .rev-score-num { font-size: 56px; font-weight: 800; line-height: 1; letter-spacing: -.04em; color: #0f172a; }
    .rev-stars { color: #f59e0b; font-size: 20px; letter-spacing: 2px; }
    .rev-verified { color: #64748b; font-size: 14px; margin-top: 4px; }
    .rev-score-text { color: #64748b; font-size: 0.87rem; line-height: 1.65; margin-top: 14px; }
    .rev-quote { color: #334155; font-size: 0.9rem; line-height: 1.75; }
    .rev-author { font-weight: 800; font-size: 0.95rem; color: #0f172a; margin-top: 14px; }
    .rev-date { color: #64748b; font-size: 13px; margin-top: 3px; }
    .rev-reply {
        margin-top: 12px; padding: 10px 14px;
        border-left: 3px solid #5a31d7;
        background: linear-gradient(135deg, #f9f7ff, #f3f0ff);
        border-radius: 0 12px 12px 0;
    }
    .rev-reply-label { font-size: 11px; font-weight: 800; color: #5a31d7; margin-bottom: 4px; }
    .rev-reply-text { font-size: 0.83rem; color: #64748b; margin: 0; }
    .rev-empty { text-align: center; padding: 3rem 1rem; }
    .rev-empty i { font-size: 2rem; color: #e2e8f0; }
    .rev-empty p { color: #64748b; font-size: 0.9rem; margin-top: 8px; }

    /* ===== GALLERY ===== */
    .gal-section-card {
        background: #fff; border-radius: 24px; border: 1px solid #e8ebf3;
        box-shadow: 0 18px 45px rgba(15,23,42,.08); padding: 24px; margin-bottom: 24px;
    }
    .gal-header { margin-bottom: 18px; }
    .gal-eyebrow {
        font-size: 11px; text-transform: uppercase; letter-spacing: .12em;
        color: #5a31d7; font-weight: 800; margin-bottom: 8px;
        background: #f3f0ff; padding: 4px 10px; border-radius: 6px; display: inline-block;
    }
    .gal-title { font-size: 1.75rem; font-weight: 800; letter-spacing: -.03em; color: #0f172a; margin: 0; }
    .gal-copy { color: #64748b; font-size: 0.9rem; line-height: 1.7; margin: 6px 0 0; }
    .gal-grid {
        display: grid; grid-template-columns: 1.2fr 0.9fr 0.9fr; gap: 14px;
    }
    .gal-item {
        border-radius: 18px; overflow: hidden; position: relative;
        cursor: pointer; min-height: 180px;
    }
    .gal-item.gal-featured { grid-row: span 2; min-height: 340px; }
    .gal-item img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .4s ease;
    }
    .gal-item:hover img { transform: scale(1.06); }
    .gal-label {
        position: absolute; inset: auto 0 0 0; padding: 16px 18px;
        background: linear-gradient(180deg, transparent, rgba(15,23,42,.72));
        color: #fff; font-weight: 700; font-size: 0.85rem; letter-spacing: -.01em;
        z-index: 2;
    }
    .gal-hover-overlay {
        position: absolute; inset: 0; background: rgba(90,49,215,0);
        transition: background .25s; border-radius: 18px; pointer-events: none; z-index: 1;
    }
    .gal-item:hover .gal-hover-overlay { background: rgba(90,49,215,.18); }
    .gal-empty { text-align: center; padding: 3rem 1rem; }
    .gal-empty i { font-size: 2rem; color: #e2e8f0; }
    .gal-empty p { color: #64748b; font-size: 0.9rem; margin-top: 8px; }

    /* ===== LIGHTBOX ===== */
    .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); align-items: center; justify-content: center; z-index: 999; animation: lbIn 0.2s ease; }
    @keyframes lbIn { from { opacity: 0; } to { opacity: 1; } }
    .lightbox.open { display: flex; }

    /* ===== INFO GRID ===== */
    .info-grid-ref { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .info-list-ref { display: grid; gap: 0; }
    .info-item-ref {
        display: flex; justify-content: space-between; gap: 16px;
        padding: 14px 0; border-bottom: 1px solid var(--line);
        font-size: 14px; color: var(--muted);
    }
    .info-item-ref:last-child { border-bottom: none; }
    .info-item-ref strong { color: var(--text); font-weight: 700; text-align: right; }

    /* ===== MAPA ===== */
    .mapa-container {
        position: relative; border-radius: 18px;
        overflow: hidden; height: 100%; min-height: 290px;
        border: 1px solid var(--line);
    }
    .mapa-container #mapaUbicacion { width: 100%; height: 100%; z-index: 1; }
    .mapa-direccion-overlay {
        position: absolute; bottom: 0; left: 0; right: 0; z-index: 2;
        background: linear-gradient(to top, rgba(255,255,255,0.97) 60%, transparent 100%);
        padding: 28px 14px 12px;
        display: flex; align-items: flex-end; justify-content: space-between; gap: 8px;
    }
    .mapa-direccion-text { font-size: 0.78rem; font-weight: 800; color: var(--text); line-height: 1.35; flex: 1; min-width: 0; }
    .mapa-btn-ir {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 8px 14px; background: var(--primary-dark); color: #fff;
        font-size: 0.72rem; font-weight: 700; border-radius: 10px;
        text-decoration: none; white-space: nowrap; flex-shrink: 0;
        transition: all 0.2s; box-shadow: 0 4px 12px rgba(90,49,215,0.25);
    }
    .mapa-btn-ir:hover { background: var(--primary); transform: translateY(-1px); color: #fff; }

    /* ===== TEAM GRID ===== */
    .team-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }
    @media (max-width: 640px) { .team-grid { grid-template-columns: 1fr; } }
    .team-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e8ebf3;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 16px;
        align-items: center;
        transition: all .2s;
    }
    .team-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(90,49,215,.12);
        border-color: #d4c8f5;
    }
    .team-avatar {
        width: 64px; height: 64px;
        border-radius: 18px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .team-avatar img {
        width: 100%; height: 100%;
        object-fit: cover; display: block;
    }
    .team-avatar-initials {
        background: linear-gradient(135deg, #e8e0fb, #d4c8f5);
        display: grid; place-items: center;
        font-weight: 800; color: #5a31d7; font-size: 1.1rem;
    }
    .team-name {
        font-size: 1rem; font-weight: 800;
        letter-spacing: -.02em; color: #0f172a; margin: 0;
    }
    .team-role {
        font-size: 0.83rem; color: #64748b; margin: 4px 0 10px;
    }
    .team-tags {
        display: flex; gap: 8px; flex-wrap: wrap;
    }
    .team-tag {
        padding: 6px 12px; border-radius: 999px;
        background: #f8fafc; border: 1px solid #e8ebf3;
        font-size: 12px; font-weight: 700; color: #334155;
    }
    .team-btn {
        padding: 11px 18px; border-radius: 14px;
        background: #fff; color: #0f172a;
        font-weight: 700; font-size: 0.82rem;
        border: 1px solid #e8ebf3;
        cursor: pointer; white-space: nowrap;
        font-family: inherit; transition: all .2s;
    }
    .team-btn:hover {
        background: #f8fafc; border-color: #d4c8f5;
    }
    @media (max-width: 760px) {
        .team-card { grid-template-columns: auto 1fr; }
        .team-btn { grid-column: 1 / -1; }
    }

    /* ===== SERVICIOS SECTION ===== */
    .servicios-section { max-width: var(--max); margin: 0 auto; padding: 24px; }
    .srv-section-card {
        background: #fff; border-radius: 24px;
        border: 1px solid #e8ebf3;
        box-shadow: 0 18px 45px rgba(15,23,42,.08);
        padding: 24px; margin-bottom: 24px;
    }
    .srv-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        gap: 16px; flex-wrap: wrap; margin-bottom: 18px;
    }
    .srv-eyebrow {
        font-size: 11px; text-transform: uppercase; letter-spacing: .12em;
        color: #5a31d7; font-weight: 800; margin-bottom: 8px;
        background: #f3f0ff; padding: 4px 10px; border-radius: 6px;
        display: inline-block;
    }
    .srv-title {
        font-size: 1.75rem; font-weight: 800; letter-spacing: -.03em;
        color: #0f172a; margin: 0;
    }
    .srv-copy {
        color: #64748b; font-size: 0.9rem; line-height: 1.7; margin: 6px 0 0;
    }
    .srv-btn-outline {
        padding: 12px 18px; border-radius: 14px;
        background: #fff; color: #0f172a;
        font-weight: 700; font-size: 0.85rem;
        border: 1px solid #e8ebf3;
        cursor: pointer; font-family: inherit;
        transition: all .2s;
    }
    .srv-btn-outline:hover { background: #f8fafc; }
    .srv-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px;
    }
    @media (max-width: 900px) { .srv-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .srv-grid { grid-template-columns: 1fr; } }
    .srv-card {
        background: #fff; border-radius: 20px; padding: 20px;
        border: 1px solid #e8ebf3;
        box-shadow: 0 4px 16px rgba(15,23,42,.06);
        display: flex; flex-direction: column; gap: 14px;
        position: relative; overflow: hidden;
        cursor: pointer; transition: all .2s;
    }
    .srv-card::after {
        content: ""; position: absolute; inset: auto -20px -26px auto;
        width: 130px; height: 130px;
        background: radial-gradient(circle, rgba(90,49,215,.1), transparent 70%);
        pointer-events: none;
    }
    .srv-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 16px 40px rgba(90,49,215,.16);
        transform: translateY(-3px);
    }
    .srv-card-top {
        display: flex; justify-content: space-between; gap: 12px;
    }
    .srv-card-eyebrow {
        font-size: 11px; color: #5a31d7; font-weight: 800;
        text-transform: uppercase; letter-spacing: .08em;
    }
    .srv-card-name {
        font-size: 1.05rem; font-weight: 800; margin-top: 5px;
        letter-spacing: -.02em; color: #0f172a;
    }
    .srv-card-price {
        font-size: 1.5rem; font-weight: 800; color: #0f172a;
        white-space: nowrap;
    }
    .srv-card-tags { display: flex; gap: 8px; flex-wrap: wrap; }
    .srv-tag {
        padding: 7px 12px; border-radius: 999px;
        background: #f8fafc; border: 1px solid #e8ebf3;
        font-size: 12px; font-weight: 700; color: #334155;
    }
    .srv-tag-accent {
        padding: 7px 12px; border-radius: 999px;
        background: #f3f0ff; border: 1px solid #d4c8f5;
        font-size: 12px; font-weight: 700; color: #5a31d7;
    }
    .srv-card-desc {
        color: #64748b; font-size: 0.85rem; line-height: 1.65; flex: 1;
    }
    .srv-card-btn {
        width: 100%; padding: 13px;
        background: linear-gradient(135deg, #5a31d7, #32ccbc);
        color: #fff; font-weight: 700; font-size: 0.9rem;
        border: none; border-radius: 14px; cursor: pointer;
        font-family: inherit;
        box-shadow: 0 8px 20px rgba(90,49,215,.25);
        transition: all .2s; position: relative; z-index: 1;
    }
    .srv-card-btn:hover {
        background: linear-gradient(135deg, #3d1fa0, #5a31d7);
        transform: translateY(-1px);
    }

    /* ===== STICKY MÓVIL ===== */
    .sticky-bar {
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 40;
        padding: 12px 16px;
        background: rgba(255,255,255,0.96); backdrop-filter: blur(14px);
        border-top: 1px solid var(--line);
        box-shadow: 0 -8px 32px rgba(15,23,42,0.1);
    }
    @media (min-width: 1024px) { .sticky-bar { display: none; } }
    .btn-agendar-full {
        width: 100%; padding: 15px;
        background: linear-gradient(135deg, #5a31d7, #32ccbc);
        color: #fff; font-weight: 700; font-size: 1rem;
        border: none; border-radius: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 14px 30px rgba(90,49,215,0.3); transition: all 0.2s;
    }
    .btn-agendar-full:hover { transform: translateY(-1px); box-shadow: 0 18px 36px rgba(90,49,215,0.4); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1100px) {
        .hero-grid { grid-template-columns: 1fr; }
        .booking-card { position: static; top: auto; }
        .info-grid-ref, .rev-grid, .rev-grid.no-score { grid-template-columns: 1fr; }
        .hero-bottom-ref { grid-template-columns: 1fr; }
        .gal-grid { grid-template-columns: 1fr 1fr; }
        .gal-item.gal-featured { grid-row: auto; min-height: 260px; }
    }
    @media (max-width: 760px) {
        .hero-cover-ref { height: 280px; padding: 20px; }
        .hero-title-cover { font-size: 28px; }
        .section-card { border-radius: 18px; padding: 18px; }
        .section-title { font-size: 22px; }
        .container-perfil, .acerca-flow, .servicios-section { padding: 16px; }
        .booking-card { padding: 16px; border-radius: 18px; }
        .booking-stepper { gap: 6px; }
        .booking-slots-grid { gap: 8px; }
        .hero-bottom-ref { padding: 18px; }
        .hero-bottom-left { flex-direction: column; align-items: flex-start; }
        .rev-score-num { font-size: 40px; }
        .gal-grid { grid-template-columns: 1fr; }
        .profile-nav-inner { height: 56px; padding: 0 16px; }
    }
    @media (max-width: 900px) {
        .rev-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 580px) {
        .rev-grid, .rev-grid.no-score { grid-template-columns: 1fr; }
    }
</style>

{{-- Leaflet CSS --}}
@if($negocio->neg_latitud && $negocio->neg_longitud)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .marker-pin {
        width: 36px; height: 36px;
        border-radius: 50% 50% 50% 0;
        background: #5a31d7;
        position: absolute;
        transform: rotate(-45deg);
        left: 50%; top: 50%;
        margin: -36px 0 0 -18px;
        box-shadow: 0 4px 12px rgba(90,49,215,0.4);
    }
    .marker-pin::after {
        content: '';
        width: 20px; height: 20px;
        margin: 8px 0 0 8px;
        background: #fff;
        position: absolute;
        border-radius: 50%;
    }
    .marker-pin-icon {
        position: absolute;
        width: 36px; font-size: 14px;
        left: 0; top: 3px;
        text-align: center;
        color: #5a31d7; z-index: 1;
    }
    .leaflet-control-zoom a {
        border-radius: 8px !important; border: none !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
        color: var(--gray-800) !important; font-weight: 700 !important;
    }
    .leaflet-control-zoom { border: none !important; border-radius: 10px !important; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.12) !important; }
    .leaflet-control-attribution { font-size: 9px !important; opacity: 0.5; }
</style>
@endif

@include('empresa.partials.modal-agendar', [
    'negocio'      => $negocio,
    'servicios'    => $negocio->servicios,
    'trabajadores' => $trabajadores
])

@php
    $resolveImg = function($path) {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, '/')) return $path;
        return asset('storage/' . $path);
    };
    $imgPortada = $resolveImg($negocio->neg_portada);
    $imgPerfil  = $resolveImg($negocio->neg_imagen) ?? asset('images/default-user.png');

    $hoyDia = now()->dayOfWeekIso;
    $horarioHoy = $negocio->horarios->where('dia_semana', $hoyDia)->where('activo', 1)->first();
    $abierto = false;
    if ($horarioHoy && $horarioHoy->hora_inicio && $horarioHoy->hora_fin) {
        $ahora = now()->format('H:i:s');
        $abierto = $ahora >= $horarioHoy->hora_inicio && $ahora <= $horarioHoy->hora_fin;
    }
@endphp

{{-- ===== HERO SECTION ===== --}}
<section class="hero">
<div class="container-perfil" style="padding-top:84px;padding-bottom:10px;">
    <div class="hero-grid">
        {{-- LEFT: Hero Card --}}
        <div class="hero-card">
            {{-- Cover --}}
            <div class="hero-cover-ref">
                <div id="portadaFallback"
                     style="position:absolute;inset:0;background:linear-gradient(135deg,#3d1fa0 0%,#5a31d7 40%,#32ccbc 100%);z-index:0;{{ $imgPortada ? 'display:none;' : '' }}">
                </div>
                @if($imgPortada)
                    <img src="{{ $imgPortada }}" class="hero-cover-img"
                         alt="Portada"
                         onerror="this.style.display='none';document.getElementById('portadaFallback').style.display='block';">
                @endif
                <div class="cover-pattern"></div>
                <div class="cover-gradient"></div>

                <div class="hero-overlay-ref">
                    {{-- Left block --}}
                    <div>
                        @if($horarioHoy)
                            @if($abierto)
                                <span class="pill-ref"><span style="width:8px;height:8px;background:#4ade80;border-radius:50%;"></span> Abierto ahora</span>
                            @else
                                <span class="pill-ref"><i class="fas fa-clock" style="font-size:11px;"></i> Cerrado</span>
                            @endif
                        @endif
                        <h1 class="hero-title-cover">{{ $negocio->neg_nombre_comercial ?? $negocio->neg_nombre }}</h1>
                        <div class="hero-meta-pills">
                            @if($promedioRating)
                                <span class="meta-chip-ref">★ {{ $promedioRating }} · {{ $totalResenas }} reseñas</span>
                            @endif
                            @if(is_array($negocio->neg_categorias))
                                @foreach($negocio->neg_categorias as $cat)
                                    <span class="meta-chip-ref">{{ $cat }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- Right block --}}
                    @if($negocio->neg_direccion)
                        <span class="pill-ref"><i class="fas fa-map-marker-alt" style="font-size:11px;"></i> {{ $negocio->neg_direccion }}</span>
                    @endif
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="hero-bottom-ref">
                <div class="hero-bottom-left">
                    <div>
                        @if($negocio->neg_descripcion)
                            <div class="hero-desc">{{ $negocio->neg_descripcion }}</div>
                        @else
                            <div class="hero-desc"><strong>{{ $negocio->neg_nombre_comercial ?? $negocio->neg_nombre }}</strong></div>
                        @endif
                    </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <button onclick="window.openAgendarModal()" class="btn-ref btn-ref-primary">
                        <i class="fas fa-calendar-plus"></i> Agendar cita
                    </button>
                    @if($negocio->neg_telefono)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $negocio->neg_telefono) }}" target="_blank" class="btn-ref btn-ref-light">
                            <i class="fab fa-whatsapp" style="color:#25D366;"></i> Escribir por WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Booking Card (sticky) --}}
        <aside class="booking-card" id="reserva">
            {{-- 1. Cabecera --}}
            <div class="booking-header">
                <div>
                    <h2 class="booking-title">Reserva tu cita</h2>
                    <p class="booking-subtitle">Elige servicio, profesional y horario</p>
                </div>
                @if($abierto)
                    <span class="booking-badge">Hoy disponible</span>
                @endif
            </div>

            {{-- 2. Stepper visual --}}
            <div class="booking-stepper">
                <div class="booking-stepper-box"><strong>1</strong><span>Servicio</span></div>
                <div class="booking-stepper-box"><strong>2</strong><span>Profesional</span></div>
                <div class="booking-stepper-box"><strong>3</strong><span>Fecha</span></div>
                <div class="booking-stepper-box"><strong>4</strong><span>Hora</span></div>
            </div>

            {{-- 3. Select Servicio --}}
            <label class="booking-label" for="heroServicio">Servicio</label>
            <select class="booking-field" id="heroServicio">
                @foreach($negocio->servicios as $servicio)
                    <option value="{{ $servicio->id }}"
                            data-precio="{{ $servicio->precio }}"
                            data-nombre="{{ $servicio->nombre }}"
                            data-duracion="{{ $servicio->duracion }}">
                        {{ $servicio->nombre }} · ${{ number_format($servicio->precio, 0, ',', '.') }}{{ $servicio->duracion ? ' · '.$servicio->duracion.' min' : '' }}
                    </option>
                @endforeach
            </select>

            {{-- 4. Select Profesional --}}
            <label class="booking-label" for="heroProfesional">Profesional</label>
            <select class="booking-field" id="heroProfesional">
                <option value="">Cualquier profesional</option>
                @foreach($trabajadores as $trab)
                    <option value="{{ $trab->id }}"
                            data-nombre="{{ $trab->nombre }}">
                        {{ $trab->nombre }}{{ $trab->especialidades ? ' · '.$trab->especialidades : '' }}
                    </option>
                @endforeach
            </select>

            {{-- 5. Input Fecha (Flatpickr) --}}
            <label class="booking-label" for="heroFecha">Fecha</label>
            <input type="text" class="booking-field" id="heroFecha" readonly
                   placeholder="Seleccionar fecha"
                   style="cursor:pointer;background:#fff;">

            {{-- 6. Slots de hora (dinámicos según horario del negocio) --}}
            <label class="booking-label">Horario disponible</label>
            <div class="booking-slots-grid" id="heroSlotsGrid">
                {{-- Se llena por JS según la fecha seleccionada --}}
            </div>
            <p id="heroSlotsEmpty" class="hidden" style="text-align:center;color:#94a3b8;font-size:0.82rem;padding:12px 0;">
                <i class="fas fa-calendar-times" style="margin-right:4px;"></i> No hay horarios para esta fecha
            </p>

            {{-- 7. Resumen --}}
            <div class="booking-summary-card">
                <div class="booking-summary-row">
                    <span>Servicio</span>
                    <strong id="summaryServicio">—</strong>
                </div>
                <div class="booking-summary-row">
                    <span>Profesional</span>
                    <strong id="summaryProfesional">—</strong>
                </div>
                <div class="booking-summary-row">
                    <span>Fecha & hora</span>
                    <strong id="summaryFecha">—</strong>
                </div>
                <div class="booking-summary-row">
                    <span>Total</span>
                    <strong id="summaryTotal">—</strong>
                </div>
            </div>

            {{-- 8. Botón continuar --}}
            <button id="btnContinuarReserva" class="booking-btn-submit">Continuar reserva →</button>

            {{-- 9. Texto inferior --}}
            <p class="booking-footnote">Sin registro previo · Confirmación inmediata</p>
        </aside>
    </div>
</div>
</section>

{{-- ===== PROFILE NAV ===== --}}
<nav class="profile-nav" id="profileNav">
    <div class="profile-nav-inner">
        <a href="{{ url('/') }}" style="display:flex;align-items:center;text-decoration:none;">
            <img src="{{ asset('images/morado.png') }}"
                 alt="Calendarix"
                 style="height:30px;width:auto;"
                 onerror="this.outerHTML='<span class=\'profile-nav-logo-text\'>Calendarix</span>'">
            <span style="font-size:1.1rem;font-weight:800;color:#5a31d7;letter-spacing:-.02em;margin-left:6px;">Calendarix</span>
        </a>
        <div class="profile-nav-links">
            <a href="#seccion-servicios" class="nav-link" data-section="seccion-servicios">Servicios</a>
            <a href="#seccion-equipo" class="nav-link" data-section="seccion-equipo">Equipo</a>
            <a href="#seccion-galeria" class="nav-link" data-section="seccion-galeria">Galería</a>
            <a href="#seccion-reviews" class="nav-link" data-section="seccion-reviews">Reviews</a>
            <a href="#seccion-contacto" class="nav-link" data-section="seccion-contacto">Contacto</a>
            <button onclick="window.openAgendarModal()" class="btn-ref btn-ref-primary nav-cta" style="padding:10px 18px;font-size:.85rem;">
                Reservar ahora
            </button>
            <button class="nav-mobile-toggle" id="navMobileToggle" aria-label="Menú">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile dropdown menu --}}
<div class="nav-mobile-menu" id="navMobileMenu">
    <a href="#seccion-servicios" class="nav-link-mobile" data-section="seccion-servicios"><i class="fas fa-concierge-bell" style="width:18px;text-align:center;color:var(--primary);"></i> Servicios</a>
    <a href="#seccion-equipo" class="nav-link-mobile" data-section="seccion-equipo"><i class="fas fa-users" style="width:18px;text-align:center;color:var(--primary);"></i> Equipo</a>
    <a href="#seccion-galeria" class="nav-link-mobile" data-section="seccion-galeria"><i class="fas fa-images" style="width:18px;text-align:center;color:var(--primary);"></i> Galería</a>
    <a href="#seccion-reviews" class="nav-link-mobile" data-section="seccion-reviews"><i class="fas fa-star" style="width:18px;text-align:center;color:var(--primary);"></i> Reviews</a>
    <a href="#seccion-contacto" class="nav-link-mobile" data-section="seccion-contacto"><i class="fas fa-map-marker-alt" style="width:18px;text-align:center;color:var(--primary);"></i> Contacto</a>
</div>

{{-- ===== SECTIONS ===== --}}
<div style="background:var(--bg);padding-top:8px;">
    <div class="acerca-flow">

        {{-- ===== SERVICIOS ===== --}}
        <div id="seccion-servicios" class="scroll-section">
            <div class="srv-section-card">
                {{-- Cabecera --}}
                <div class="srv-header">
                    <div>
                        <span class="srv-eyebrow">Servicios destacados</span>
                        <h2 class="srv-title">Elegir debería tomar segundos</h2>
                        <p class="srv-copy">Cada servicio con precio, duración y reserva directa.</p>
                    </div>
                    <button class="srv-btn-outline" onclick="window.openAgendarModal()">Ver disponibilidad</button>
                </div>

                {{-- Grid --}}
                @if($negocio->servicios->count())
                    <div class="srv-grid">
                        @foreach($negocio->servicios as $servicio)
                            <article class="srv-card" onclick="window.openAgendarModal({serviceId: {{ $servicio->id }}})">
                                <div class="srv-card-top">
                                    <div>
                                        <div class="srv-card-eyebrow">{{ is_array($negocio->neg_categorias) && count($negocio->neg_categorias) ? $negocio->neg_categorias[0] : 'Servicio' }}</div>
                                        <div class="srv-card-name">{{ $servicio->nombre }}</div>
                                    </div>
                                    <div class="srv-card-price">${{ number_format($servicio->precio, 0, ',', '.') }}</div>
                                </div>
                                <div class="srv-card-tags">
                                    @if($servicio->duracion)
                                        <span class="srv-tag">{{ $servicio->duracion }} min</span>
                                    @endif
                                    <span class="srv-tag-accent">Reservar</span>
                                </div>
                                @if($servicio->descripcion)
                                    <div class="srv-card-desc">{{ Str::limit($servicio->descripcion, 100) }}</div>
                                @endif
                                <button class="srv-card-btn">Reservar</button>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div style="padding:3rem 0;text-align:center;">
                        <i class="fas fa-concierge-bell" style="font-size:2rem;color:#e8ebf3;margin-bottom:12px;"></i>
                        <p style="color:#64748b;font-size:0.9rem;">Este negocio aún no ha agregado servicios.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== EQUIPO ===== --}}
        <div id="seccion-equipo" class="scroll-section">
            <div style="background:#fff;border-radius:24px;border:1px solid #e8ebf3;box-shadow:0 18px 45px rgba(15,23,42,.08);padding:24px;margin-bottom:24px;">
                {{-- Cabecera --}}
                <div style="margin-bottom:18px;">
                    <span style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#5a31d7;font-weight:800;background:#f3f0ff;padding:4px 10px;border-radius:6px;display:inline-block;margin-bottom:8px;">Equipo</span>
                    <h2 style="font-size:1.75rem;font-weight:800;letter-spacing:-.03em;color:#0f172a;margin:0;">Deja que el cliente elija a quién atenderse</h2>
                    <p style="color:#64748b;font-size:0.9rem;line-height:1.7;margin:6px 0 0;">Mostrar el profesional mejora confianza y conversión, especialmente en barbería, estética y bienestar.</p>
                </div>

                @if($trabajadores->count())
                    <div class="team-grid">
                        @foreach($trabajadores as $trab)
                            <div class="team-card">
                                {{-- Avatar --}}
                                @if($trab->foto)
                                    <div class="team-avatar">
                                        <img src="{{ asset('storage/' . $trab->foto) }}" alt="{{ $trab->nombre }}">
                                    </div>
                                @else
                                    <div class="team-avatar team-avatar-initials">
                                        {{ strtoupper(substr($trab->nombre, 0, 2)) }}
                                    </div>
                                @endif

                                {{-- Info --}}
                                <div>
                                    <div class="team-name">{{ $trab->nombre }}</div>
                                    @if($trab->especialidades)
                                        <div class="team-role">{{ $trab->especialidades }}</div>
                                    @endif
                                    @if($trab->especialidades)
                                        <div class="team-tags">
                                            <span class="team-tag">{{ $trab->especialidades }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Botón --}}
                                <button class="team-btn" onclick="window.openAgendarModal({workerId: {{ $trab->id }}})">
                                    Reservar con {{ explode(' ', $trab->nombre)[0] }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align:center;padding:3rem 1rem;">
                        <i class="fas fa-users" style="font-size:2rem;color:#e2e8f0;margin-bottom:12px;display:block;"></i>
                        <p style="color:#64748b;font-size:0.9rem;margin:0;">Este negocio aún no ha agregado miembros del equipo.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Galería --}}
        <div id="seccion-galeria" class="scroll-section">
            <div class="gal-section-card">
                <div class="gal-header">
                    <span class="gal-eyebrow">Galería</span>
                    <h2 class="gal-title">Más visual, más confianza</h2>
                    <p class="gal-copy">Una galería editorial ayuda mucho: muestra el local, el estilo y el nivel del trabajo antes de reservar.</p>
                </div>

                @if($negocio->fotos->count())
                <div class="gal-grid">
                    @foreach($negocio->fotos as $i => $foto)
                        <div class="gal-item{{ $i === 0 ? ' gal-featured' : '' }}" onclick="document.getElementById('lb{{ $foto->id }}').classList.add('open')">
                            <img src="{{ asset('storage/' . $foto->ruta) }}" alt="Foto {{ $i + 1 }}">
                            <div class="gal-label">Foto {{ $i + 1 }}</div>
                            <div class="gal-hover-overlay"></div>
                        </div>
                        <div id="lb{{ $foto->id }}" class="lightbox" onclick="this.classList.remove('open')">
                            <img src="{{ asset('storage/' . $foto->ruta) }}"
                                 style="max-width:92vw;max-height:88vh;border-radius:18px;box-shadow:0 30px 60px rgba(0,0,0,0.6);display:block;"
                                 alt="Foto">
                        </div>
                    @endforeach
                </div>
                @else
                <div class="gal-empty">
                    <i class="fas fa-images"></i>
                    <p>Este negocio aún no ha agregado fotos.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Reseñas --}}
        <div id="seccion-reviews" class="scroll-section">
            <div class="rev-section-card">
                <div class="rev-header">
                    <span class="rev-eyebrow">Reseñas</span>
                    <h2 class="rev-title">Prueba social visible desde arriba</h2>
                    <p class="rev-copy">Las reviews no deberían quedar escondidas. En este tipo de negocio venden casi tanto como las fotos.</p>
                </div>

                @if($resenas->count())
                <div class="rev-grid{{ !$promedioRating ? ' no-score' : '' }}">
                    {{-- Score card --}}
                    @if($promedioRating)
                    <article class="rev-card">
                        <div style="display:flex;align-items:flex-end;gap:14px;">
                            <div class="rev-score-num">{{ $promedioRating }}</div>
                            <div>
                                <div class="rev-stars">★★★★★</div>
                                <div class="rev-verified">{{ $totalResenas }} opiniones verificadas</div>
                            </div>
                        </div>
                        <p class="rev-score-text">Excelente atención y resultados consistentes. Este bloque da autoridad inmediata.</p>
                    </article>
                    @endif
                    {{-- Individual reviews --}}
                    @foreach($resenas->take(2) as $resena)
                    <article class="rev-card">
                        <div class="rev-quote">&ldquo;{{ $resena->comentario }}&rdquo;</div>
                        <div class="rev-author">{{ $resena->user->name ?? $resena->nombre_cliente ?? 'Cliente' }}</div>
                        <div class="rev-date">{{ $resena->created_at->format('d/m/Y') }}</div>
                        @if($resena->respuesta_negocio)
                            <div class="rev-reply">
                                <div class="rev-reply-label">Respuesta del negocio</div>
                                <p class="rev-reply-text">{{ $resena->respuesta_negocio }}</p>
                            </div>
                        @endif
                    </article>
                    @endforeach
                </div>
                @else
                <div class="rev-empty">
                    <i class="fas fa-star"></i>
                    <p>Este negocio aún no tiene reseñas.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== UBICACIÓN & CONTACTO ===== --}}
        <div id="seccion-contacto" class="section-card scroll-section">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Ubicación & contacto</div>
                    <h2 class="section-title">Toda la información</h2>
                </div>
            </div>
            <div class="info-grid-ref">
                <div>
                    <div class="info-list-ref">
                        @if($negocio->neg_direccion)
                            <div class="info-item-ref"><span>Dirección</span><strong>{{ $negocio->neg_direccion }}</strong></div>
                        @endif
                        @if($negocio->neg_telefono)
                            <div class="info-item-ref"><span>Teléfono</span><strong><a href="tel:{{ $negocio->neg_telefono }}" style="color:var(--text);text-decoration:none;">{{ $negocio->neg_telefono }}</a></strong></div>
                        @endif
                        @if($negocio->neg_email)
                            <div class="info-item-ref"><span>Email</span><strong><a href="mailto:{{ $negocio->neg_email }}" style="color:var(--text);text-decoration:none;word-break:break-all;">{{ $negocio->neg_email }}</a></strong></div>
                        @endif
                        @if($negocio->neg_pais)
                            <div class="info-item-ref"><span>País</span><strong>{{ $negocio->neg_pais }}</strong></div>
                        @endif
                        @if($negocio->horarios->count())
                            @foreach($negocio->horarios as $h)
                                @php $esHoy = $h->dia_semana == now()->dayOfWeekIso; @endphp
                                <div class="info-item-ref" style="{{ $esHoy ? 'background:#f3f0ff;border-radius:8px;padding:14px 8px;' : '' }}{{ $h->activo ? '' : 'opacity:0.4;' }}">
                                    <span style="{{ $esHoy ? 'color:var(--primary-dark);font-weight:700;' : '' }}">
                                        @if($esHoy)<i class="fas fa-circle" style="font-size:5px;vertical-align:middle;margin-right:4px;color:var(--primary);"></i>@endif
                                        {{ \Carbon\Carbon::create()->startOfWeek()->addDays($h->dia_semana - 1)->locale('es')->isoFormat('dddd') }}
                                    </span>
                                    @if($h->activo && $h->hora_inicio && $h->hora_fin)
                                        <strong style="{{ $esHoy ? 'color:var(--primary-dark);' : '' }}">{{ substr($h->hora_inicio,0,5) }} – {{ substr($h->hora_fin,0,5) }}</strong>
                                    @else
                                        <strong style="color:#ef4444;">Cerrado</strong>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
                        <button onclick="window.openAgendarModal()" class="btn-ref btn-ref-primary">
                            <i class="fas fa-calendar-plus"></i> Agendar cita
                        </button>
                        @if($negocio->neg_telefono)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $negocio->neg_telefono) }}" target="_blank" class="btn-ref btn-ref-light">
                                <i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp
                            </a>
                        @endif
                        @if($negocio->neg_instagram)
                            <a href="{{ $negocio->neg_instagram }}" target="_blank" class="btn-ref btn-ref-light">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($negocio->neg_facebook)
                            <a href="{{ $negocio->neg_facebook }}" target="_blank" class="btn-ref btn-ref-light">
                                <i class="fab fa-facebook"></i>
                            </a>
                        @endif
                        @if($negocio->neg_sitio_web)
                            <a href="{{ $negocio->neg_sitio_web }}" target="_blank" class="btn-ref btn-ref-light">
                                <i class="fas fa-globe"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Mapa --}}
                @if($negocio->neg_latitud && $negocio->neg_longitud)
                <div>
                    <div class="mapa-container">
                        <div id="mapaUbicacion"></div>
                        <div class="mapa-direccion-overlay">
                            <div class="mapa-direccion-text">
                                <i class="fas fa-location-dot" style="color:var(--primary);margin-right:5px;"></i>
                                {{ $negocio->neg_direccion ?? 'Ver en el mapa' }}
                            </div>
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $negocio->neg_latitud }},{{ $negocio->neg_longitud }}"
                               target="_blank" class="mapa-btn-ir">
                                <i class="fas fa-directions"></i> Cómo llegar
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div></div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- STICKY MÓVIL --}}
<div class="sticky-bar">
    <button onclick="window.openAgendarModal()" class="btn-agendar-full">
        <i class="fas fa-calendar-plus"></i> Agendar Cita
    </button>
</div>

@php
    $horariosMap = $negocio->horarios
        ->where('activo', 1)
        ->groupBy('dia_semana')
        ->map(function ($items) {
            return $items->map(function ($h) {
                return [
                    'inicio' => $h->hora_inicio ? substr($h->hora_inicio, 0, 5) : null,
                    'fin'    => $h->hora_fin    ? substr($h->hora_fin, 0, 5) : null,
                ];
            })->values();
        })->toArray();
@endphp
<div id="horariosData" data-map='@json($horariosMap)' style="display:none;"></div>
<div id="agendaData" data-negocio-id="{{ $negocio->id }}" style="display:none;"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const horariosEl = document.getElementById('horariosData');
  let HORARIOS = {};
  if (horariosEl && typeof horariosEl.dataset.map === 'string') {
    try { HORARIOS = JSON.parse(horariosEl.dataset.map || '{}'); } catch { HORARIOS = {}; }
  }
  const fix = v => (v||'').toString().slice(0,5);
  const normalizeRangeArray = arr => (Array.isArray(arr) ? arr : [])
    .map(r => ({ inicio: fix(r.inicio ?? r.hora_inicio), fin: fix(r.fin ?? r.hora_fin) }))
    .filter(r => r.inicio && r.fin);
  const NORM = {};
  Object.keys(HORARIOS).forEach(k => { NORM[k] = normalizeRangeArray(HORARIOS[k]); });
  window.NEGOCIO_HORARIOS = NORM;

  const agendaEl = document.getElementById('agendaData');
  const NEGOCIO_ID = agendaEl?.dataset?.negocioId || null;
  window.TRABAJADOR_HORARIOS = window.TRABAJADOR_HORARIOS || {};
  window.TRABAJADOR_BLOQUEOS = window.TRABAJADOR_BLOQUEOS || {};
  window.RESERVAS = window.RESERVAS || {};

  const pad2 = n => String(n).padStart(2, '0');
  const toYMD = d => `${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;

  window.t2m = function(hhmm) { const [h,m]=(hhmm||'0:0').split(':').map(Number); return h*60+m; };
  window.m2t = function(total) { const h=Math.floor(total/60),m=total%60; return `${pad2(h)}:${pad2(m)}`; };
  window.normalizeIntervals = function(raw) {
    return (raw||[]).map(r=>({inicio:fix(r.inicio??r.hora_inicio),fin:fix(r.fin??r.hora_fin)})).filter(r=>r.inicio&&r.fin&&r.fin>r.inicio);
  };
  window.overlapsAny = function(aStart,aEnd,intervals) {
    const A=t2m(aStart),B=t2m(aEnd);
    for(const it of (intervals||[])){const C=t2m(it.inicio),D=t2m(it.fin);if(A<D&&C<B)return true;}
    return false;
  };
  window.generateFreeQuarterSlots = function(minHHMM,maxHHMM,reservas) {
    const out=[],min=t2m(minHHMM),max=t2m(maxHHMM);
    for(let t=min;t<=max;t+=15){const s=m2t(t);if(!overlapsAny(s,m2t(t+15),reservas))out.push(s);}
    return out;
  };
  window.generateValidEnds = function(startHHMM,maxHHMM,reservas) {
    const out=[],start=t2m(startHHMM),max=t2m(maxHHMM);
    for(let t=start+15;t<=max;t+=15){const cand=m2t(t);if(!overlapsAny(startHHMM,cand,reservas))out.push(cand);}
    return out;
  };

  window.cargarCitasDelDia = async function(fecha) {
    if(!NEGOCIO_ID)return;
    try {
      const res=await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-dia?fecha=${fecha}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const json=await res.json();
      window.RESERVAS[fecha]={};
      for(const c of (Array.isArray(json.items)?json.items:[])){
        const tid=c.trabajador_id??'0';
        if(!window.RESERVAS[fecha][tid])window.RESERVAS[fecha][tid]=[];
        window.RESERVAS[fecha][tid].push({inicio:(c.hora_inicio||'').toString().slice(0,5),fin:(c.hora_fin||'').toString().slice(0,5)});
      }
    } catch(e){console.error('Error cargando citas del día:',e);}
  };

  async function cargarCitasMes(year,month) {
    if(!NEGOCIO_ID)return;
    try {
      const res=await fetch(`/negocios/${NEGOCIO_ID}/agenda/citas-mes?year=${year}&month=${month}`,{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const json=await res.json();
      if(json.ok&&Array.isArray(json.events)&&window.calendar){
        window.calendar.getEvents().filter(ev=>ev.extendedProps?.type==='cita').forEach(ev=>ev.remove());
        json.events.forEach(ev=>{window.calendar.addEvent(ev);});
      }
    } catch(e){console.error('Error cargando citas del mes:',e);}
  }

  const calendarEl=document.getElementById('calendarioCitas');
  if(calendarEl){
    const calendar=new FullCalendar.Calendar(calendarEl,{
      initialView:'dayGridMonth',
      locale:'es',
      height:520,
      headerToolbar:{left:'prev,next today',center:'title',right:''},
      events:[
        @foreach($negocio->bloqueos as $bloqueo)
        {title:'Bloqueado',start:'{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',allDay:true,color:'#dc2626',extendedProps:{blocked:true}},
        @endforeach
      ],
      dayCellClassNames:function(info){
        const today=new Date();today.setHours(0,0,0,0);
        const cell=new Date(info.date);cell.setHours(0,0,0,0);
        const ymd=toYMD(cell);
        const blocked=calendar.getEvents().some(ev=>ev.extendedProps?.blocked&&toYMD(ev.start)===ymd);
        if(cell<today)return['fc-day-past'];
        if(blocked)return['fc-day-blocked'];
        return[];
      },
      datesSet:function(di){cargarCitasMes(di.start.getFullYear(),di.start.getMonth()+1);},
      dateClick:async function(info){
        const ymd=toYMD(info.date);
        const today=new Date();today.setHours(0,0,0,0);
        const clicked=new Date(info.date);clicked.setHours(0,0,0,0);
        if(clicked<today)return;
        const blocked=calendar.getEvents().some(ev=>ev.extendedProps?.blocked&&toYMD(ev.start)===ymd);
        if(blocked)return;
        await window.cargarCitasDelDia(ymd);
        if(typeof window.openAgendarModal==='function')window.openAgendarModal({date:ymd});
      },
    });
    calendar.render();
    window.calendar=calendar;
  }
});
</script>

@if($negocio->neg_latitud && $negocio->neg_longitud)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapaEl = document.getElementById('mapaUbicacion');
    if (!mapaEl) return;

    const lat = {{ $negocio->neg_latitud }};
    const lng = {{ $negocio->neg_longitud }};

    const map = L.map('mapaUbicacion', {
        center: [lat, lng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: true,
        attributionControl: true,
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
    }).addTo(map);

    const markerIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div class="marker-pin"></div><i class="fas fa-store marker-pin-icon"></i>',
        iconSize: [36, 46],
        iconAnchor: [18, 46],
        popupAnchor: [0, -40],
    });

    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
    marker.bindPopup(
        '<div style="text-align:center;padding:4px 2px;">' +
        '<strong style="color:#5a31d7;font-size:0.85rem;">{{ addslashes($negocio->neg_nombre_comercial ?? $negocio->neg_nombre) }}</strong>' +
        @if($negocio->neg_direccion)
        '<br><span style="font-size:0.75rem;color:#6b7280;">{{ addslashes($negocio->neg_direccion) }}</span>' +
        @endif
        '</div>',
        { closeButton: false, className: 'custom-popup' }
    );
});
</script>
@endif

{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

{{-- Booking Card Logic --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

  const selServicio    = document.getElementById('heroServicio');
  const selProfesional = document.getElementById('heroProfesional');
  const inputFecha     = document.getElementById('heroFecha');
  const slotsGrid      = document.getElementById('heroSlotsGrid');
  const slotsEmpty     = document.getElementById('heroSlotsEmpty');
  const btnContinuar   = document.getElementById('btnContinuarReserva');

  let horaSeleccionada = null;

  function actualizarResumen() {
    // Servicio
    var optS = selServicio.options[selServicio.selectedIndex];
    document.getElementById('summaryServicio').textContent =
      optS ? optS.dataset.nombre : '—';
    document.getElementById('summaryTotal').textContent =
      optS && optS.dataset.precio
        ? '$' + Number(optS.dataset.precio).toLocaleString('es')
        : '—';

    // Profesional
    var optP = selProfesional.options[selProfesional.selectedIndex];
    document.getElementById('summaryProfesional').textContent =
      optP && optP.dataset.nombre ? optP.dataset.nombre : 'Cualquier profesional';

    // Fecha + hora
    var fecha = inputFecha.value;
    if (fecha) {
      var parts = fecha.split('-');
      var meses = ['Ene','Feb','Mar','Abr','May','Jun',
                   'Jul','Ago','Sep','Oct','Nov','Dic'];
      var fechaStr = parts[2] + ' ' + meses[parseInt(parts[1]) - 1];
      document.getElementById('summaryFecha').textContent =
        fechaStr + (horaSeleccionada ? ' · ' + horaSeleccionada : ' · --:--');
    } else {
      document.getElementById('summaryFecha').textContent = '—';
    }
  }

  // Generar slots dinámicos según horarios reales del negocio
  function generarSlotsHero() {
    if (!slotsGrid) return;
    slotsGrid.innerHTML = '';
    slotsEmpty.classList.add('hidden');
    horaSeleccionada = null;

    var fecha = inputFecha.value;
    if (!fecha || !window.NEGOCIO_HORARIOS) return;

    var dt = new Date(fecha + 'T12:00:00');
    var jsDay = dt.getDay();
    var laravelDay = jsDay === 0 ? 7 : jsDay;
    var ranges = window.NEGOCIO_HORARIOS[laravelDay] || [];

    if (!ranges.length) {
      slotsEmpty.classList.remove('hidden');
      return;
    }

    // Duración del servicio seleccionado (default 30 min)
    var duracion = 30;
    var optS = selServicio.options[selServicio.selectedIndex];
    if (optS && optS.dataset.duracion) {
      var durStr = optS.dataset.duracion.toLowerCase();
      var match = durStr.match(/(\d+)\s*(min|hora|hr|h)/);
      if (match) {
        duracion = match[2].startsWith('h') ? parseInt(match[1]) * 60 : parseInt(match[1]);
      } else {
        var numOnly = durStr.match(/^(\d+)$/);
        if (numOnly) duracion = parseInt(numOnly[1]);
      }
    }

    // Generar slots cada 30 minutos (vista rápida)
    var count = 0;
    ranges.forEach(function(range) {
      var minT = window.t2m(range.inicio);
      var maxT = window.t2m(range.fin);
      for (var t = minT; t + duracion <= maxT; t += 30) {
        if (count >= 6) return; // máximo 6 slots visibles
        var hhmm = window.m2t(t);
        var slot = document.createElement('div');
        slot.className = 'booking-slot';
        slot.dataset.hora = hhmm;
        slot.textContent = hhmm;
        slot.addEventListener('click', function() {
          slotsGrid.querySelectorAll('.booking-slot').forEach(function(s) { s.classList.remove('active'); });
          this.classList.add('active');
          horaSeleccionada = this.dataset.hora;
          actualizarResumen();
        });
        slotsGrid.appendChild(slot);
        count++;
      }
    });

    if (count === 0) {
      slotsEmpty.classList.remove('hidden');
    }

    actualizarResumen();
  }

  // --- Días bloqueados (fechas específicas) ---
  const diasBloqueados = [
    @foreach($negocio->bloqueos as $bloqueo)
      '{{ \Carbon\Carbon::parse($bloqueo->fecha_bloqueada)->format('Y-m-d') }}',
    @endforeach
  ];

  // --- Qué días de semana están cerrados (sin horarios) ---
  function esDiaCerrado(date) {
    const jsDay = date.getDay(); // 0=Dom
    const laravelDay = jsDay === 0 ? 7 : jsDay;
    const rangos = window.NEGOCIO_HORARIOS[laravelDay];
    return (!rangos || rangos.length === 0);
  }

  // --- Inicializar Flatpickr ---
  const fp = flatpickr(inputFecha, {
    locale: 'es',
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'j M Y',
    minDate: 'today',
    disableMobile: true,
    disable: [
      // Deshabilitar días de semana cerrados
      function(date) {
        return esDiaCerrado(date);
      },
      // Deshabilitar fechas bloqueadas
      ...diasBloqueados.map(function(f) { return f; })
    ],
    onChange: function(selectedDates, dateStr) {
      generarSlotsHero();
      actualizarResumen();
    },
    onReady: function(selectedDates, dateStr, instance) {
      // Seleccionar el próximo día abierto automáticamente
      const hoy = new Date();
      hoy.setHours(12,0,0,0);
      let d = new Date(hoy);
      for (let i = 0; i < 90; i++) {
        const ymd = d.getFullYear() + '-' +
          String(d.getMonth()+1).padStart(2,'0') + '-' +
          String(d.getDate()).padStart(2,'0');
        if (!esDiaCerrado(d) && !diasBloqueados.includes(ymd)) {
          instance.setDate(d, true);
          break;
        }
        d.setDate(d.getDate() + 1);
      }
    }
  });

  // Cambios en selects y fecha → regenerar slots
  selServicio.addEventListener('change', function() {
    actualizarResumen();
    generarSlotsHero();
  });
  selProfesional.addEventListener('change', actualizarResumen);

  // Botón continuar
  btnContinuar.addEventListener('click', function() {
    var serviceId = selServicio.value;
    var workerId  = selProfesional.value;
    var date      = inputFecha.value;
    if (typeof window.openAgendarModal === 'function') {
      window.openAgendarModal({ serviceId: serviceId, workerId: workerId, date: date });
    }
  });

  // Ejecutar al cargar para poblar el resumen y generar slots iniciales
  actualizarResumen();
  generarSlotsHero();

});
</script>

{{-- Nav scroll & active state --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var nav = document.getElementById('profileNav');
    var mobileToggle = document.getElementById('navMobileToggle');
    var mobileMenu = document.getElementById('navMobileMenu');
    var allLinks = document.querySelectorAll('.nav-link[data-section], .nav-link-mobile[data-section]');
    var sections = document.querySelectorAll('.scroll-section');

    // Scroll shadow on nav
    window.addEventListener('scroll', function() {
        if (window.scrollY > 20) { nav.classList.add('scrolled'); }
        else { nav.classList.remove('scrolled'); }
    });

    // Mobile menu toggle
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
            var icon = mobileToggle.querySelector('i');
            if (mobileMenu.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        });
    }

    // Smooth scroll on link click
    allLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var sectionId = this.getAttribute('data-section');
            var target = document.getElementById(sectionId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            // Close mobile menu
            if (mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
                var icon = mobileToggle.querySelector('i');
                icon.className = 'fas fa-bars';
            }
        });
    });

    // IntersectionObserver for active state
    if (sections.length) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var id = entry.target.id;
                    allLinks.forEach(function(l) { l.classList.remove('active'); });
                    allLinks.forEach(function(l) {
                        if (l.getAttribute('data-section') === id) l.classList.add('active');
                    });
                }
            });
        }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

        sections.forEach(function(s) { observer.observe(s); });
    }

    // Calendar resize after layout (no longer tab-based)
    if (window.calendar) {
        setTimeout(function() { window.calendar.updateSize(); }, 100);
    }
});
</script>
@endpush
