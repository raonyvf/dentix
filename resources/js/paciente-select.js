export default class TomSelect {
  constructor(el, opts = {}) {
    this.el = el;
    this.opts = opts;
    this.el.style.display = 'none';
    this.input = document.createElement('input');
    this.input.type = 'text';
    this.input.className = el.className;
    this.input.placeholder = opts.placeholder || '';
    el.parentNode.insertBefore(this.input, el);
    this.list = document.createElement('ul');
    this.list.className = 'absolute z-10 w-full bg-white border rounded mt-1 max-h-40 overflow-auto hidden';
    el.parentNode.insertBefore(this.list, el.nextSibling);
    this.timer = null;
    this.input.addEventListener('input', e => {
      const term = e.target.value.trim();
      if (term.length < 2) {
        this.list.classList.add('hidden');
        this.list.innerHTML = '';
        return;
      }
      clearTimeout(this.timer);
      this.timer = setTimeout(() => {
        if (this.opts.load) {
          this.opts.load(term, items => this.render(items));
        }
      }, this.opts.loadThrottle || 300);
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
      this.opts.load(query, items => this.render(items));
    }
  }
  render(items) {
    this.list.innerHTML = '';
    if (!Array.isArray(items) || !items.length) return;
    items.slice(0, 10).forEach(p => {
      const li = document.createElement('li');
      li.className = 'px-2 py-1 cursor-pointer hover:bg-gray-100';
      li.textContent = p[this.opts.labelField || 'name'];
      li.addEventListener('click', () => {
        this.el.value = p[this.opts.valueField || 'id'];
        this.input.value = p[this.opts.labelField || 'name'];
        this.list.classList.add('hidden');
      });
      this.list.appendChild(li);
    });
    this.list.classList.remove('hidden');
  }
}
