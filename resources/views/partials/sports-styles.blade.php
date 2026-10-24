@once
<style>
  .sp-card{background:#fff;border:1px solid #e3e8e5;border-radius:16px;overflow:hidden;height:100%;
           transition:transform .2s,box-shadow .2s}
  .sp-card:hover{transform:translateY(-4px);box-shadow:0 14px 34px rgba(20,48,31,.12)}
  .sp-cover{height:170px;overflow:hidden}
  .sp-cover img{width:100%!important;height:100%!important;object-fit:cover}
  .sp-body{padding:22px}
  .sp-top{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
  .sp-comp{background:#e9f7d2;color:#14301f;font-size:13px;font-weight:700;padding:5px 12px;border-radius:999px}
  .sp-date{font-size:13px;color:#6b7570}
  .sp-match{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:12px;text-align:center}
  .sp-team{display:flex;flex-direction:column;align-items:center;gap:8px;color:#3d544d}
  .sp-team strong{font-size:16px;line-height:1.5}
  .sp-team.is-winner strong{color:#14301f}
  .sp-logo{width:68px;height:68px;border-radius:50%;background:#f4f6f5;border:1px solid #e3e8e5;
           display:flex;align-items:center;justify-content:center;overflow:hidden;color:#9aa6a0;font-size:26px}
  .sp-logo img{width:100%!important;height:100%!important;object-fit:contain;padding:6px}
  .sp-score{display:flex;align-items:center;gap:8px;font-size:2.4rem;font-weight:800;color:#8a948f;white-space:nowrap}
  .sp-score .win{color:#14301f}
  .sp-score.sp-upcoming-label{font-size:1.15rem;color:#7ac70c;letter-spacing:1px}
  .sp-score small{font-size:1.6rem;opacity:.6}
  .sp-place{margin:18px 0 0;text-align:center;font-size:14px;color:#6b7570}
  .sp-place i{color:#7ac70c;margin-inline-end:4px}
  @media (max-width:575px){
    .sp-logo{width:52px;height:52px}
    .sp-score{font-size:1.8rem}
    .sp-team strong{font-size:14px}
  }
</style>
@endonce
