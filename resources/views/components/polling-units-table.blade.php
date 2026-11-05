@php
    $lgaCount   = $rows->map(fn ($u) => $u->lga->lga_name ?? null)->filter()->unique()->count();
    $wardCount  = $rows->map(fn ($u) => $u->ward->ward_name ?? null)->filter()->unique()->count();
    $withCoords = $rows->filter(fn ($u) => !empty($u->lat) && !empty($u->long))->count();
    $lgaOptions = $rows->map(fn ($u) => $u->lga->lga_name ?? null)->filter()->unique()->sort()->values();
@endphp

<!-- AI ASSISTED: UI redesigned with AI assistance; data and logic are the project's own. -->

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap">
<style>
    #pu-root {
        --gv-ink: #1b1b1b;
        --gv-muted: #565c65;
        --gv-line: #c9c9c9;
        --gv-line-soft: #dfe1e2;
        --gv-bg: #f0f0f0;
        --gv-navy: #162e51;
        --gv-navy-dark: #0f2140;
        --gv-green: #00693e;
        --gv-green-dark: #004d2d;
        --gv-gold: #face00;
        --gv-focus: #2491ff;
        font-family: 'Public Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
        color: var(--gv-ink);
        font-size: 15px;
        line-height: 1.5;
    }
    #pu-root *, #pu-panel * { box-sizing: border-box; }
    #pu-root :focus-visible, #pu-panel :focus-visible { outline: 3px solid var(--gv-focus); outline-offset: 2px; }

    /* Page header */
    .gv-banner { background: var(--gv-navy); color: #fff; border-bottom: 4px solid var(--gv-gold); padding: 18px 24px; border-radius: 2px 2px 0 0; }
    .gv-banner h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: .01em; }
    .gv-banner p { margin: 2px 0 0; font-size: 13px; color: #c5d0e3; }

    /* Summary strip */
    .gv-summary { display: grid; grid-template-columns: repeat(2, 1fr); background: #fff; border: 1px solid var(--gv-line); border-top: 0; }
    @media (min-width: 1024px) { .gv-summary { grid-template-columns: repeat(4, 1fr); } }
    .gv-stat { padding: 14px 20px; border-right: 1px solid var(--gv-line-soft); border-bottom: 1px solid var(--gv-line-soft); }
    .gv-stat:last-child { border-right: 0; }
    .gv-stat dt { font-size: 13px; font-weight: 600; color: var(--gv-muted); margin: 0; }
    .gv-stat dd { margin: 2px 0 0; font-size: 28px; font-weight: 700; color: var(--gv-navy); line-height: 1.15; }
    .gv-stat small { display: block; font-size: 12px; color: var(--gv-muted); }

    /* Filter bar */
    .gv-filters { background: var(--gv-bg); border: 1px solid var(--gv-line); padding: 16px 20px; margin-top: 16px; display: grid; gap: 14px; grid-template-columns: 1fr; align-items: end; }
    @media (min-width: 768px) { .gv-filters { grid-template-columns: 1.6fr 1fr auto; } }
    .gv-field label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 4px; }
    .gv-input, .gv-select { width: 100%; height: 40px; padding: 0 12px; border: 1px solid #565c65; border-radius: 0; background: #fff; font: inherit; font-size: 15px; color: var(--gv-ink); }
    .gv-select { appearance: none; padding-right: 36px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='none' stroke='%23162e51' stroke-width='2' d='M1 1l5 5 5-5'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
    .gv-btn { height: 40px; padding: 0 18px; border: 2px solid var(--gv-navy); background: #fff; color: var(--gv-navy); font: inherit; font-size: 14px; font-weight: 600; border-radius: 4px; cursor: pointer; }
    .gv-btn:hover { background: #e7edf6; }

    /* Table */
    .gv-card { background: #fff; border: 1px solid var(--gv-line); margin-top: 16px; }
    .gv-card-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--gv-line); background: #fff; }
    .gv-card-head strong { font-size: 15px; }
    .gv-card-head span { font-size: 13px; color: var(--gv-muted); }
    .gv-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    .gv-table th { background: var(--gv-navy); color: #fff; font-weight: 600; font-size: 13px; padding: 10px 16px; white-space: nowrap; border-right: 1px solid #2c4672; }
    .gv-table th:last-child { border-right: 0; text-align: right; }
    .gv-table td { padding: 12px 16px; border-bottom: 1px solid var(--gv-line-soft); vertical-align: middle; }
    .gv-table tbody tr:nth-child(even) { background: #f9f9f9; }
    .pu-row { cursor: pointer; }
    .pu-row:hover { background: #e7f2fa !important; }
    .pu-row.is-active { background: #d9e8f6 !important; box-shadow: inset 4px 0 0 var(--gv-navy); }
    .pu-name { font-weight: 600; color: var(--gv-ink); }
    .pu-code { display: inline-block; margin-top: 2px; font-size: 12px; font-weight: 600; color: var(--gv-navy); background: #e7edf6; border: 1px solid #b6c4dc; padding: 0 6px; border-radius: 2px; }
    .pu-viewbtn { display: inline-flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 600; color: var(--gv-navy); text-decoration: underline; text-underline-offset: 3px; }
    .pu-row:hover .pu-viewbtn { color: var(--gv-green); }
    .gv-empty { padding: 40px 20px; text-align: center; color: var(--gv-muted); display: none; }
    .gv-empty strong { display: block; color: var(--gv-ink); font-size: 16px; margin-bottom: 4px; }

    /* Slide-over */
    #pu-overlay { position: fixed; inset: 0; z-index: 9998; background: rgba(0,0,0,.5); opacity: 0; visibility: hidden; transition: opacity .25s ease, visibility 0s linear .25s; }
    #pu-overlay.is-open { opacity: 1; visibility: visible; transition: opacity .25s ease, visibility 0s; }
    #pu-panel { position: fixed; top: 0; right: 0; height: 100%; width: 100%; max-width: 460px; z-index: 9999; background: #fff; border-left: 4px solid var(--gv-navy); display: flex; flex-direction: column; transform: translateX(105%); transition: transform .25s cubic-bezier(.22,.61,.36,1); font-family: 'Public Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; color: #1b1b1b; }
    #pu-panel.is-open { transform: translateX(0); }
    @media (prefers-reduced-motion: reduce) { #pu-panel, #pu-overlay { transition: none; } }

    #pu-panel .pu-head { padding: 16px 20px; background: var(--gv-navy); color: #fff; border-bottom: 4px solid var(--gv-gold); display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; }
    #pu-panel .pu-eyebrow { font-size: 13px; color: #c5d0e3; }
    #pu-panel .pu-title { font-size: 19px; font-weight: 700; word-break: break-word; margin: 2px 0 6px; }
    #pu-panel .pu-badge { display: inline-block; font-size: 12px; font-weight: 600; padding: 1px 8px; border-radius: 2px; background: var(--gv-gold); color: #1b1b1b; }
    #pu-panel .pu-close { border: 1px solid #5d7399; background: transparent; padding: 6px; border-radius: 4px; color: #fff; cursor: pointer; line-height: 0; }
    #pu-panel .pu-close:hover { background: #2c4672; }
    #pu-panel .pu-mapwrap { padding: 16px 20px 8px; background: var(--gv-bg); border-bottom: 1px solid var(--gv-line); }
    #pu-map { height: 230px; border: 1px solid #565c65; overflow: hidden; isolation: isolate; background: #0f172a; }
    #pu-nomap { display: none; height: 100px; border: 1px dashed #71767a; background: #fff; color: var(--gv-muted); font-size: 14px; align-items: center; justify-content: center; }
    #pu-maprow { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 10px; flex-wrap: wrap; }
    #pu-dirbtn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border: 0; border-radius: 4px; background: var(--gv-green); color: #fff; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; }
    #pu-dirbtn:hover { background: var(--gv-green-dark); }
    #pu-dirbtn:disabled { background: #71767a; cursor: wait; }
    #pu-maplink { font-size: 14px; font-weight: 600; color: var(--gv-navy); text-underline-offset: 3px; }
    #pu-maplink:hover { color: var(--gv-green); }
    #pu-routeinfo { display: none; margin: 8px 0; padding: 10px 12px; font-size: 14px; font-weight: 600; border-left: 4px solid; background: #fff; }
    #pu-routeinfo.ok { display: block; border-color: var(--gv-green); color: var(--gv-green-dark); }
    #pu-routeinfo.err { display: block; border-color: #b50909; color: #b50909; }
    #pu-panel .pu-body { flex: 1; overflow-y: auto; padding: 0 20px 16px; }
    #pu-panel .pu-field { display: grid; grid-template-columns: 130px 1fr; gap: 16px; padding: 11px 0; border-bottom: 1px solid var(--gv-line-soft); }
    #pu-panel .pu-label { font-size: 14px; font-weight: 600; color: var(--gv-muted); }
    #pu-panel .pu-value { font-size: 15px; color: #1b1b1b; word-break: break-word; min-width: 0; }
</style>

<div id="pu-root">
    <!-- Page header -->
    <div class="gv-banner">
        <h1>Polling Unit Register</h1>
        <p>Records of polling units by Local Government Area and Ward</p>
    </div>

    <!-- Summary -->
    <dl class="gv-summary">
        <div class="gv-stat">
            <dt>Polling units</dt>
            <dd>{{ number_format($rows->count()) }}</dd>
            <small>Total records</small>
        </div>
        <div class="gv-stat">
            <dt>Local Government Areas</dt>
            <dd>{{ number_format($lgaCount) }}</dd>
            <small>Covered</small>
        </div>
        <div class="gv-stat">
            <dt>Wards</dt>
            <dd>{{ number_format($wardCount) }}</dd>
            <small>Covered</small>
        </div>
        <div class="gv-stat">
            <dt>With coordinates</dt>
            <dd>{{ number_format($withCoords) }}</dd>
            <small>Latitude and longitude recorded</small>
        </div>
    </dl>

    <!-- Search and filter -->
    <form class="gv-filters" role="search" onsubmit="return false;">
        <div class="gv-field">
            <label for="pu-search">Search</label>
            <input id="pu-search" type="search" autocomplete="off" class="gv-input"
                placeholder="Polling unit name, number, LGA or ward">
        </div>
        <div class="gv-field">
            <label for="pu-lga">Local Government Area</label>
            <select id="pu-lga" class="gv-select">
                <option value="">All LGAs</option>
                @foreach($lgaOptions as $lgaName)
                    <option value="{{ strtolower($lgaName) }}">{{ $lgaName }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" id="pu-clear" class="gv-btn">Clear filters</button>
    </form>

    <!-- Table -->
    <div class="gv-card">
        <div class="gv-card-head">
            <strong id="pu-count" aria-live="polite">Showing {{ $rows->count() }} of {{ $rows->count() }} polling units</strong>
            <span class="hidden sm:inline">Select a row to view full details</span>
        </div>

        @if($rows->isEmpty())
            <div style="padding:40px 20px;text-align:center;color:#565c65;">No polling units found in the system.</div>
        @else
            <div style="overflow-x:auto;">
                <table class="gv-table">
                    <thead>
                        <tr>
                            <th scope="col">Polling unit</th>
                            <th scope="col">LGA</th>
                            <th scope="col">Ward</th>
                            <th scope="col">Date entered</th>
                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody id="pu-tbody">
                        @foreach($rows as $unit)
                            @php
                                $search = strtolower(implode(' ', [
                                    $unit->polling_unit_name, $unit->polling_unit_number, $unit->polling_unit_id,
                                    $unit->lga->lga_name ?? '', $unit->ward->ward_name ?? '',
                                ]));
                                $details = [
                                    'name'   => $unit->polling_unit_name,
                                    'number' => $unit->polling_unit_number,
                                    'fields' => [
                                        ['PU ID', $unit->polling_unit_id],
                                        ['LGA Name', $unit->lga->lga_name ?? null],
                                        ['Ward Name', $unit->ward->ward_name ?? null],
                                        ['PU Number', $unit->polling_unit_number],
                                        ['PU Name', $unit->polling_unit_name],
                                        ['PU Description', $unit->polling_unit_description ?? null],
                                        ['Lat', $unit->lat ?? null],
                                        ['Long', $unit->long ?? null],
                                        ['Entered By', $unit->entered_by_user ?? null],
                                        ['Date Entered', $unit->date_entered ?? null],
                                        ['User IP', $unit->user_ip_address ?? null],
                                    ],
                                ];
                            @endphp
                            <tr class="pu-row" tabindex="0"
                                data-search="{{ $search }}"
                                data-lga="{{ strtolower($unit->lga->lga_name ?? '') }}"
                                data-details="{{ json_encode($details) }}">
                                <td>
                                    <div class="pu-name">{{ $unit->polling_unit_name }}</div>
                                    <span class="pu-code">PU {{ $unit->polling_unit_number }}</span>
                                </td>
                                <td>{{ $unit->lga->lga_name ?? '—' }}</td>
                                <td>{{ $unit->ward->ward_name ?? '—' }}</td>
                                <td style="color:#565c65;white-space:nowrap;">{{ $unit->date_entered ?? '—' }}</td>
                                <td style="text-align:right;">
                                    <span class="pu-viewbtn">
                                        View record
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="gv-empty" id="pu-empty">
                <strong>No polling units match your filters</strong>
                Try a different search term, or choose All LGAs.
            </div>
        @endif
    </div>
</div>

<!-- Slide-over (moved to <body> by the script so nothing can clip it) -->
<div id="pu-overlay"></div>
<aside id="pu-panel" aria-hidden="true" role="dialog" aria-label="Polling unit details">
    <div class="pu-head">
        <div style="min-width:0">
            <div class="pu-eyebrow">Polling unit record</div>
            <div class="pu-title" id="pu-title"></div>
            <span class="pu-badge" id="pu-badge"></span>
        </div>
        <button type="button" class="pu-close" id="pu-close" aria-label="Close details">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>
    <div class="pu-mapwrap">
        <div id="pu-map"></div>
        <div id="pu-nomap">No coordinates recorded</div>
        <div id="pu-maprow">
            <button type="button" id="pu-dirbtn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11l18-8-8 18-2-8-8-2z"/></svg>
                <span id="pu-dirbtn-text">Get directions</span>
            </button>
            <a id="pu-maplink" href="#" target="_blank" rel="noopener">Open in Google Maps &#8599;</a>
        </div>
        <div id="pu-routeinfo"></div>
    </div>
    <div class="pu-body" id="pu-body"></div>
</aside>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var overlay = document.getElementById('pu-overlay');
    var panel   = document.getElementById('pu-panel');
    var body    = document.getElementById('pu-body');
    var title   = document.getElementById('pu-title');
    var badge   = document.getElementById('pu-badge');
    var tbody   = document.getElementById('pu-tbody');
    var search  = document.getElementById('pu-search');
    var lgaSel  = document.getElementById('pu-lga');
    var clearBtn = document.getElementById('pu-clear');
    var countEl = document.getElementById('pu-count');
    var emptyEl = document.getElementById('pu-empty');
    var active  = null;

    var mapEl = document.getElementById('pu-map');
    var noMap = document.getElementById('pu-nomap');
    var mapLink = document.getElementById('pu-maplink');
    var map = null, marker = null;
    var mapRow = document.getElementById('pu-maprow');
    var dirBtn = document.getElementById('pu-dirbtn');
    var dirTxt = document.getElementById('pu-dirbtn-text');
    var routeInfo = document.getElementById('pu-routeinfo');
    var routeGroup = null;
    var dest = null;

    function clearRoute() {
        if (routeGroup && map) { map.removeLayer(routeGroup); }
        routeGroup = null;
        routeInfo.className = ''; routeInfo.textContent = '';
        dirTxt.textContent = 'Get directions';
        dirBtn.disabled = false;
    }

    function fmtDuration(sec) {
        var m = Math.round(sec / 60);
        if (m < 60) return m + ' min';
        return Math.floor(m / 60) + ' h ' + (m % 60) + ' min';
    }

    function setErr(msg) {
        routeInfo.className = 'err'; routeInfo.textContent = msg;
        dirTxt.textContent = 'Get directions'; dirBtn.disabled = false;
    }

    function getDirections() {
        if (!dest || !map) return;
        if (!navigator.geolocation) { setErr('This browser does not support location.'); return; }
        if (!window.isSecureContext) { setErr('Location needs HTTPS (or localhost).'); return; }

        dirBtn.disabled = true; dirTxt.textContent = 'Locating you...';
        routeInfo.className = ''; routeInfo.textContent = '';

        navigator.geolocation.getCurrentPosition(function (pos) {
            var from = [pos.coords.latitude, pos.coords.longitude];
            dirTxt.textContent = 'Finding route...';

            var url = 'https://router.project-osrm.org/route/v1/driving/'
                + from[1] + ',' + from[0] + ';' + dest[1] + ',' + dest[0]
                + '?overview=full&geometries=geojson';

            fetch(url).then(function (r) { return r.json(); }).then(function (j) {
                if (!j.routes || !j.routes.length) { setErr('No driving route found to this polling unit.'); return; }
                var route = j.routes[0];
                var pts = route.geometry.coordinates.map(function (c) { return [c[1], c[0]]; });

                if (routeGroup) map.removeLayer(routeGroup);
                routeGroup = L.layerGroup([
                    L.polyline(pts, { color: '#ffffff', weight: 9, opacity: .9 }),
                    L.polyline(pts, { color: '#00693e', weight: 5, opacity: 1 }),
                    L.circleMarker(from, { radius: 8, color: '#ffffff', weight: 3, fillColor: '#2563eb', fillOpacity: 1 })
                ]).addTo(map);
                marker.bringToFront();

                map.invalidateSize();
                map.fitBounds(L.latLngBounds(pts), { padding: [28, 28] });

                routeInfo.className = 'ok';
                routeInfo.textContent = (route.distance / 1000).toFixed(1) + ' km, about ' + fmtDuration(route.duration) + ' by road';
                dirTxt.textContent = 'Refresh route'; dirBtn.disabled = false;
            }).catch(function () { setErr('Could not fetch the route. Check your connection and try again.'); });
        }, function (err) {
            setErr(err.code === 1 ? 'Location permission denied. Allow it in your browser site settings.'
                 : err.code === 3 ? 'Location request timed out. Try again.'
                 : 'Could not get your location.');
        }, { enableHighAccuracy: true, timeout: 15000, maximumAge: 30000 });
    }
    dirBtn.addEventListener('click', getDirections);

    function showMap(lat, lng) {
        var la = parseFloat(lat), lo = parseFloat(lng);
        var ok = isFinite(la) && isFinite(lo) && Math.abs(la) <= 90 && Math.abs(lo) <= 180 && !(la === 0 && lo === 0);

        mapEl.style.display = ok ? 'block' : 'none';
        mapRow.style.display = ok ? 'flex' : 'none';
        routeInfo.className = ''; routeInfo.textContent = '';
        noMap.style.display = ok ? 'none' : 'flex';
        if (!ok) return;

        dest = [la, lo];
        clearRoute();
        mapLink.href = 'https://www.google.com/maps/dir/?api=1&destination=' + la + ',' + lo + '&travelmode=driving';

        if (typeof L === 'undefined') { mapEl.style.display = 'none'; noMap.textContent = 'Map library failed to load'; noMap.style.display = 'flex'; return; }

        if (!map) {
            map = L.map(mapEl, { zoomControl: true, attributionControl: true }).setView([la, lo], 18);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19, attribution: 'Imagery &copy; Esri, Maxar, Earthstar Geographics'
            }).addTo(map);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19
            }).addTo(map);
            marker = L.circleMarker([la, lo], { radius: 9, color: '#ffffff', weight: 3, fillColor: '#162e51', fillOpacity: 1 }).addTo(map);
        } else {
            marker.setLatLng([la, lo]);
            map.setView([la, lo], 18);
        }
        // panel is mid-slide when opened, so re-measure once it settles
        setTimeout(function () { map.invalidateSize(); map.setView([la, lo], 18); }, 340);
    }

    // move overlay + panel to <body>
    document.body.appendChild(overlay);
    document.body.appendChild(panel);

    function val(v) { return (v === null || v === undefined || v === '') ? '\u2014' : String(v); }

    function openPanel(tr) {
        var d;
        try { d = JSON.parse(tr.getAttribute('data-details')); } catch (e) { console.error(e); return; }

        title.textContent = val(d.name);
        badge.textContent = 'PU ' + val(d.number);
        body.innerHTML = '';
        var lat = null, lng = null;
        d.fields.forEach(function (f) {
            if (f[0] === 'Lat') lat = f[1];
            if (f[0] === 'Long') lng = f[1];
        });
        showMap(lat, lng);

        d.fields.forEach(function (f) {
            if (f[0] === 'Lat' || f[0] === 'Long') return;
            var row = document.createElement('div');
            row.className = 'pu-field';
            var l = document.createElement('span'); l.className = 'pu-label'; l.textContent = f[0];
            var v = document.createElement('span'); v.className = 'pu-value'; v.textContent = val(f[1]);
            row.appendChild(l); row.appendChild(v);
            body.appendChild(row);
        });
        body.scrollTop = 0;

        if (active) active.classList.remove('is-active');
        active = tr; tr.classList.add('is-active');

        // force a reflow so the slide transition always plays
        void panel.offsetWidth;
        overlay.classList.add('is-open');
        panel.classList.add('is-open');
        panel.setAttribute('aria-hidden', 'false');
        document.getElementById('pu-close').focus();
    }

    function closePanel() {
        overlay.classList.remove('is-open');
        panel.classList.remove('is-open');
        panel.setAttribute('aria-hidden', 'true');
        if (active) { active.classList.remove('is-active'); active.focus(); active = null; }
    }

    if (tbody) {
        tbody.addEventListener('click', function (e) {
            var tr = e.target.closest('tr.pu-row');
            if (tr) openPanel(tr);
        });
        tbody.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            var tr = e.target.closest('tr.pu-row');
            if (tr) { e.preventDefault(); openPanel(tr); }
        });
    }
    overlay.addEventListener('click', closePanel);
    document.getElementById('pu-close').addEventListener('click', closePanel);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePanel(); });

    // Search + LGA filter (combined)
    function applyFilters() {
        if (!tbody) return;
        var q = search.value.trim().toLowerCase();
        var lga = lgaSel.value;
        var rows = tbody.querySelectorAll('tr.pu-row');
        var shown = 0;

        rows.forEach(function (tr) {
            var matchQ = q === '' || tr.getAttribute('data-search').indexOf(q) !== -1;
            var matchL = lga === '' || tr.getAttribute('data-lga') === lga;
            var visible = matchQ && matchL;
            tr.style.display = visible ? '' : 'none';
            if (visible) shown++;
        });

        countEl.textContent = 'Showing ' + shown + ' of ' + rows.length + ' polling units';
        if (emptyEl) emptyEl.style.display = shown === 0 ? 'block' : 'none';
    }

    if (search && lgaSel) {
        search.addEventListener('input', applyFilters);
        lgaSel.addEventListener('change', applyFilters);
        clearBtn.addEventListener('click', function () {
            search.value = '';
            lgaSel.value = '';
            applyFilters();
            search.focus();
        });
    }
})();
</script>