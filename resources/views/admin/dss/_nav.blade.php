<style>
    .dss-tabs { gap: 6px; flex-wrap: wrap; }
    .dss-tabs .nav-link { color: var(--adm-text-2); font-weight: 600; font-size: 14px; border-radius: var(--adm-radius-sm); padding: 8px 16px; }
    .dss-tabs .nav-link:hover { background: var(--adm-primary-light); color: var(--adm-primary); }
    .dss-tabs .nav-link.active { background: var(--adm-primary); color: #fff; }
    .dss-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .dss-box { background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: var(--adm-radius-lg); padding: 16px 18px; }
    .dss-box__label { font-size: 12px; color: var(--adm-text-3); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; margin-bottom: 4px; }
    .dss-box__value { font-size: 26px; font-weight: 800; color: var(--adm-text-1); line-height: 1.2; overflow-wrap: anywhere; }
    .dss-box__sub { font-size: 12px; color: var(--adm-text-2); margin-top: 2px; overflow-wrap: anywhere; }
    .dss-bar { height: 8px; border-radius: 99px; background: var(--adm-border); overflow: hidden; min-width: 70px; }
    .dss-bar > span { display: block; height: 100%; background: var(--adm-primary); border-radius: 99px; }
    .dss-score { font-weight: 800; color: var(--adm-text-1); white-space: nowrap; }
    .dss-chart { position: relative; height: 320px; width: 100%; }
    .dss-note { background: var(--adm-surface-2); border: 1px solid var(--adm-border); border-radius: var(--adm-radius-md); padding: 14px 16px; font-size: 13px; color: var(--adm-text-2); }
    .dss-note summary { cursor: pointer; font-weight: 700; color: var(--adm-text-1); }
    .dss-chip { display: inline-block; padding: 4px 10px; margin: 0 6px 6px 0; border-radius: 99px; background: var(--adm-primary-light); color: var(--adm-primary); font-size: 12px; font-weight: 600; text-decoration: none; }
    a.dss-chip:hover { background: var(--adm-primary); color: #fff; }
    .dss-muted { color: var(--adm-text-3); }
</style>

<ul class="nav nav-pills dss-tabs mb-4" aria-label="Menu hasil DSS">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dss.hasil.*') ? 'active' : '' }}" href="{{ route('admin.dss.hasil.index') }}">
            <i class="bi bi-trophy me-1" aria-hidden="true"></i>Peringkat Dosen
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dss.peta') ? 'active' : '' }}" href="{{ route('admin.dss.peta') }}">
            <i class="bi bi-diagram-3 me-1" aria-hidden="true"></i>Peta Kompetensi Smart City
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dss.bobot.*') ? 'active' : '' }}" href="{{ route('admin.dss.bobot.index') }}">
            <i class="bi bi-sliders me-1" aria-hidden="true"></i>Pembobotan AHP
        </a>
    </li>
</ul>