export default class PacienteSelect {
  constructor(el, opts = {}) {
    this.el = el;
    this.opts = opts;
    this.el.style.display = 'none';
    this.dropdownParent = opts.dropdownParent || el.parentNode;
    if (getComputedStyle(this.dropdownParent).overflow === 'hidden') {
      this.dropdownParent.style.overflow = 'visible';
    }
    this.dropdownParent.style.position = this.dropdownParent.style.position || 'relative';
    this.input = document.createElement('input');
    this.input.type = 'text';
    this.input.className = el.className;
    this.input.placeholder = opts.placeholder || '';
    el.parentNode.insertBefore(this.input, el);
    el.tomselect = this;
    this.list = document.createElement('ul');
    this.list.className = 'absolute w-full bg-white border rounded mt-1 max-h-40 overflow-auto hidden';
    this.list.style.zIndex = opts.zIndex || '999999';
    this.dropdownParent.appendChild(this.list);
    if (el.value) {
      const opt = el.querySelector(`option[value="${el.value}"]`);
      if (opt) this.input.value = opt.textContent;
    }
    this.timer = null;
    this.input.addEventListener('input', e => {
      const term = e.target.value.trim();
      if (term.length < 1) {
        this.list.classList.add('hidden');
        this.list.innerHTML = '';
        return;
      }
      clearTimeout(this.timer);
      this.timer = setTimeout(() => {
        if (this.opts.load) {
          this.showLoading();
          this.opts
            .load(term)
            .then(items => this.render(items))
            .catch(() => this.showError());
        }
      }, this.opts.loadThrottle || 300);
    });
    const positionList = () => {
      const rect = this.input.getBoundingClientRect();
      const parentRect = this.dropdownParent.getBoundingClientRect();
      this.list.style.left = rect.left - parentRect.left + 'px';
      this.list.style.top = rect.bottom - parentRect.top + 'px';
      this.list.style.width = rect.width + 'px';
    };
    this.input.addEventListener('focus', () => {
      positionList();
      this.list.classList.remove('hidden');
    });
    this.input.addEventListener('blur', () => {
      setTimeout(() => this.list.classList.add('hidden'), 200);
    });
  }
  on(event, cb) {
    if (event === 'dropdown_open') {
      this.input.addEventListener('focus', cb);
    }
  }
  load(query) {
    if (this.opts.load) {
      this.showLoading();
      this.opts
        .load(query)
        .then(items => this.render(items))
        .catch(() => this.showError());
    }
  }

  showLoading() {
    this.list.innerHTML = '<li class="px-2 py-1 text-gray-500">Carregando...</li>';
    this.list.classList.remove('hidden');
  }

  showError() {
    this.list.innerHTML = '<li class="px-2 py-1 text-red-500">Erro ao buscar pacientes</li>';
    this.list.classList.remove('hidden');
  }
  render(items) {
    this.list.innerHTML = '';
    if (!Array.isArray(items) || !items.length) return;
    items.slice(0, 10).forEach(p => {
      const li = document.createElement('li');
      li.className = 'px-2 py-1 cursor-pointer hover:bg-gray-100';
      li.textContent = p[this.opts.labelField || 'name'];
      li.addEventListener('click', () => {
        const val = p[this.opts.valueField || 'id'];
        const label = p[this.opts.labelField || 'name'];
        this.el.innerHTML = '';
        const option = document.createElement('option');
        option.value = val;
        option.textContent = label;
        option.selected = true;
        this.el.appendChild(option);
        this.el.value = val;
        this.el.dispatchEvent(new Event('change'));
        this.input.value = label;
        this.list.classList.add('hidden');
      });
      this.list.appendChild(li);
    });
    this.list.classList.remove('hidden');
  }

  destroy() {
    this.input.remove();
    this.list.remove();
    this.el.style.display = '';
  }
}
