export default function pacienteSelect(el, opts = {}) {
  // hide original select and create text input
  el.style.display = 'none';
  const parent = opts.dropdownParent || el.parentNode;
  if (getComputedStyle(parent).position === 'static') {
    parent.style.position = 'relative';
  }
  parent.style.overflow = 'visible';

  const input = document.createElement('input');
  input.type = 'text';
  input.className = el.className;
  input.placeholder = opts.placeholder || '';
  parent.insertBefore(input, el);

  const list = document.createElement('ul');
  list.className = 'absolute z-50 bg-white border rounded max-h-40 overflow-auto hidden';
  parent.appendChild(list);

  let timer = null;

  function positionList() {
    const inputRect = input.getBoundingClientRect();
    const parentRect = parent.getBoundingClientRect();
    list.style.top = `${inputRect.bottom - parentRect.top}px`;
    list.style.left = `${inputRect.left - parentRect.left}px`;
    list.style.width = `${inputRect.width}px`;
  }

  function fetchItems(term) {
    if (typeof opts.load === 'function') {
      return opts.load(term);
    }
    if (opts.searchUrl) {
      const url = `${opts.searchUrl}?q=${encodeURIComponent(term)}`;
      return fetch(url).then(r => r.json());
    }
    return Promise.resolve([]);
  }

  function showMessage(msg, cls = 'text-gray-500') {
    list.innerHTML = `<li class="px-2 py-1 ${cls}">${msg}</li>`;
    list.classList.remove('hidden');
    positionList();
  }

  function render(items) {
    list.innerHTML = '';
    if (!Array.isArray(items) || !items.length) {
      list.classList.add('hidden');
      return;
    }
    items.slice(0, 10).forEach(p => {
      const li = document.createElement('li');
      li.className = 'px-2 py-1 cursor-pointer hover:bg-gray-100';
      const label = p[opts.labelField || 'text'] || p.name;
      li.textContent = label;
      li.addEventListener('click', () => {
        el.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = p[opts.valueField || 'id'];
        opt.textContent = label;
        opt.selected = true;
        el.appendChild(opt);
        el.value = opt.value;
        el.dispatchEvent(new Event('change'));
        input.value = label;
        list.classList.add('hidden');
      });
      list.appendChild(li);
    });
    list.classList.remove('hidden');
    positionList();
  }

  function search(term) {
    showMessage('Carregando...');
    fetchItems(term)
      .then(render)
      .catch(() => showMessage('Erro ao buscar pacientes', 'text-red-500'));
  }

  input.addEventListener('input', e => {
    const term = e.target.value.trim();
    clearTimeout(timer);
    if (!term) {
      list.classList.add('hidden');
      return;
    }
    timer = setTimeout(() => search(term), opts.loadThrottle || 300);
  });

  input.addEventListener('focus', () => {
    positionList();
    if (!input.value) {
      search('');
    }
  });

  input.addEventListener('blur', () => {
    setTimeout(() => list.classList.add('hidden'), 200);
  });

  window.addEventListener('resize', positionList);
  window.addEventListener('scroll', positionList, true);

  const api = {
    input,
    list,
    destroy() {
      window.removeEventListener('resize', positionList);
      window.removeEventListener('scroll', positionList, true);
      input.remove();
      list.remove();
      el.style.display = '';
    },
  };

  el.tomselect = api;
  return api;
}

