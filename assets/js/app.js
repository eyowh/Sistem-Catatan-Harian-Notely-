(function(){
  const root = document.documentElement;
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) root.setAttribute('data-theme', savedTheme);
  const themeBtn = document.getElementById('toggleTheme');
  if (themeBtn) themeBtn.addEventListener('click', ()=>{
    const cur = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', cur);
    localStorage.setItem('theme', cur);
  });

  // Feather icons init
  if (window.feather && typeof window.feather.replace === 'function') {
    window.feather.replace();
  }

  // Sidebar toggle (mobile)
  const st = document.getElementById('sidebarToggle');
  const sb = document.getElementById('sidebar');
  if (st && sb) st.addEventListener('click', ()=>{ sb.classList.toggle('open'); });

  // Toggle pin from list
  document.querySelectorAll('.toggle-pin').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      const id = btn.getAttribute('data-id');
      const csrf = document.querySelector('meta[name="csrf-token"]').content;
      const form = new URLSearchParams();
      form.set('action','toggle_pin');
      form.set('id', id);
      form.set('csrf_token', csrf);
      await fetch('/uts pemograman/api/notes_api.php', { method:'POST', headers:{ 'X-CSRF-Token': csrf }, body: form });
      location.reload();
    });
  });

  const toggleView = document.getElementById('toggleView');
  if (toggleView) toggleView.addEventListener('click', ()=>{
    const params = new URLSearchParams(location.search);
    const next = (document.getElementById('notes')?.classList.contains('grid')) ? 'list' : 'grid';
    params.set('view', next);
    document.cookie = `viewMode=${next}; path=/`;
    location.search = params.toString();
  });

  // Toggle favorite from list
  document.querySelectorAll('.toggle-fav').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      const id = btn.getAttribute('data-id');
      const csrf = document.querySelector('meta[name="csrf-token"]').content;
      await fetch('/uts pemograman/actions/toggle_favorite.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrf }, body: `id=${encodeURIComponent(id)}` });
      location.reload();
    });
  });

  // Editor autosave (contenteditable)
  const titleEl = document.getElementById('title');
  const contentEl = document.getElementById('content');
  const idEl = document.getElementById('noteId');
  const favBtn = document.getElementById('favBtn');
  const saveStatus = document.getElementById('saveStatus');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  let isFav = favBtn ? (favBtn.textContent === '⭐') : false;
  if (favBtn) {
    favBtn.addEventListener('click', async ()=>{
      isFav = !isFav; favBtn.textContent = isFav ? '⭐' : '☆';
      await doSaveNow();
    });
  }

  // Rich text toolbar
  document.querySelectorAll('.editor-format [data-cmd]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const cmd = btn.getAttribute('data-cmd');
      const val = btn.getAttribute('data-value') || undefined;
      if (cmd === 'formatBlock') {
        document.execCommand(cmd, false, val);
      } else {
        document.execCommand(cmd, false, val);
      }
      contentEl && contentEl.focus();
      doSave();
    });
  });
  const addLinkBtn = document.getElementById('addLink');
  if (addLinkBtn) addLinkBtn.addEventListener('click', ()=>{
    const url = prompt('URL:');
    if (url) { document.execCommand('createLink', false, url); doSave(); }
  });

  function debounce(fn, ms){ let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); }; }
  const doSave = debounce(async ()=>{
    if (!titleEl || !contentEl || !idEl) return;
    saveStatus && (saveStatus.textContent = 'Menyimpan...');
    const form = new URLSearchParams();
    form.set('id', idEl.value || '0');
    form.set('title', titleEl.value);
    form.set('content', contentEl.innerHTML);
    form.set('is_favorite', isFav ? '1' : '0');
    form.set('csrf_token', csrfToken);
    const res = await fetch('/uts pemograman/actions/save_note.php', { method: 'POST', headers: { 'X-CSRF-Token': csrfToken }, body: form });
    const data = await res.json();
    if (data?.id && idEl.value !== String(data.id)) { idEl.value = String(data.id); }
    saveStatus && (saveStatus.textContent = 'Tersimpan otomatis');
    // Clear offline draft after successful save
    try { localStorage.removeItem(offKey()); } catch {}
  }, 500);
  const doSaveNow = async()=>{ const f = doSave; await f(); };

  [titleEl, contentEl].forEach(el => el && el.addEventListener('input', doSave));

  // Offline drafts
  function offKey(){ return 'draft:' + (idEl?.value || 'new'); }
  function loadDraft(){
    try {
      const d = JSON.parse(localStorage.getItem(offKey()) || 'null');
      if (!d) return;
      if (titleEl && d.title) titleEl.value = d.title;
      if (contentEl && d.content) contentEl.innerHTML = d.content;
    } catch {}
  }
  function saveDraft(){
    try {
      const d = { title: titleEl?.value || '', content: contentEl?.innerHTML || '' , ts: Date.now() };
      localStorage.setItem(offKey(), JSON.stringify(d));
    } catch {}
  }
  [titleEl, contentEl].forEach(el => el && el.addEventListener('input', saveDraft));
  loadDraft();
})();
