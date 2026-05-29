(function () {
  'use strict';

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  function getFormMethod(form) {
    const declared = (form.getAttribute('method') || 'GET').toUpperCase();

    return ['GET', 'POST'].includes(declared) ? declared : 'POST';
  }

  function ensureSpoofedMethod(form, formData) {
    const declared = (form.getAttribute('method') || 'GET').toUpperCase();

    if (!['GET', 'POST'].includes(declared) && !formData.has('_method')) {
      formData.append('_method', declared);
    }
  }

  function clearFieldErrors(form) {
    form.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
    form.querySelectorAll('[data-js-error]').forEach((error) => error.remove());
  }

  function showFieldError(field, message) {
    if (!field) return;

    field.classList.add('is-invalid');

    const error = document.createElement('div');
    error.className = 'invalid-feedback d-block';
    error.dataset.jsError = 'true';
    error.textContent = message;

    const container = field.closest('.form-floating') || field.parentElement;
    container.appendChild(error);
  }

  function validateForm(form) {
    clearFieldErrors(form);

    let valid = true;

    form.querySelectorAll('input, select, textarea').forEach((field) => {
      if (field.disabled || field.type === 'hidden') return;

      const label = form.querySelector(`label[for="${field.id}"]`)?.textContent?.trim() || field.name || 'Campo';
      const value = (field.value || '').trim();

      if (field.required && !value) {
        showFieldError(field, `${label} é obrigatório.`);
        valid = false;
        return;
      }

      if (field.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        showFieldError(field, 'Informe um e-mail válido.');
        valid = false;
        return;
      }

      if (field.minLength > 0 && value && value.length < field.minLength) {
        showFieldError(field, `${label} deve ter pelo menos ${field.minLength} caracteres.`);
        valid = false;
      }
    });

    form.classList.add('was-validated');

    return valid;
  }

  function showMessage(message, type = 'success') {
    const host = document.querySelector('#main .alert, main .alert')?.parentElement || document.querySelector('#main') || document.querySelector('main') || document.body;
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    `;

    host.prepend(alert);

    setTimeout(() => {
      bootstrap.Alert.getOrCreateInstance(alert).close();
    }, 5000);
  }

  function closeModal(form) {
    const modalEl = form.closest('.modal');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    const modal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
  }

  async function refreshTargets(selectorList) {
    if (!selectorList) return;

    const selectors = selectorList.split(',').map((value) => value.trim()).filter(Boolean);
    if (!selectors.length) return;

    const response = await fetch(window.location.href, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
    });
    const html = await response.text();
    const doc = new DOMParser().parseFromString(html, 'text/html');

    selectors.forEach((selector) => {
      const current = document.querySelector(selector);
      const fresh = doc.querySelector(selector);

      if (current && fresh) {
        current.replaceWith(fresh);
      }
    });
  }

  function setLoading(button, loading) {
    if (!button) return;

    if (loading) {
      button.dataset.originalText = button.innerHTML;
      button.disabled = true;
      button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Aguarde...';
      return;
    }

    button.disabled = false;
    button.innerHTML = button.dataset.originalText || button.innerHTML;
  }

  function enhancePasswordToggles() {
    document.querySelectorAll('input[type="password"], input[data-password-visible="true"]').forEach((field) => {
      if (field.dataset.passwordToggleReady === 'true') return;
      if (field.closest('.password-toggle-wrap')?.querySelector('.password-toggle')) return;
      if (field.parentElement?.querySelector('.eye-toggle')) return;

      field.dataset.passwordToggleReady = 'true';
      field.classList.add('password-toggle-input');

      const wrapper = field.parentElement;
      if (wrapper && !wrapper.classList.contains('position-relative')) {
        wrapper.classList.add('password-toggle-wrap');
      }

      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'password-toggle';
      button.setAttribute('aria-label', 'Mostrar senha');
      button.innerHTML = '<i class="bi bi-eye"></i>';

      button.addEventListener('click', () => {
        const visible = field.type === 'text';
        field.type = visible ? 'password' : 'text';
        field.dataset.passwordVisible = visible ? 'false' : 'true';
        button.setAttribute('aria-label', visible ? 'Mostrar senha' : 'Ocultar senha');
        button.innerHTML = visible ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
      });

      field.insertAdjacentElement('afterend', button);
    });
  }

  async function submitAjax(form) {
    const submitter = form.querySelector('[type="submit"]');
    const formData = new FormData(form);
    ensureSpoofedMethod(form, formData);

    setLoading(submitter, true);

    try {
      const response = await fetch(form.action, {
        method: getFormMethod(form),
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrf,
        },
      });

      const data = await response.json().catch(() => ({}));

      if (response.status === 422 && data.errors) {
        Object.entries(data.errors).forEach(([name, messages]) => {
          showFieldError(form.querySelector(`[name="${name}"]`), messages[0]);
        });
        showMessage('Verifique os campos destacados.', 'danger');
        return;
      }

      if (!response.ok) {
        showMessage(data.message || 'Não foi possível concluir a operação.', 'danger');
        return;
      }

      if (form.dataset.ajaxReset === 'true') {
        form.reset();
        form.classList.remove('was-validated');
      }

      closeModal(form);
      await refreshTargets(form.dataset.ajaxRefresh);
      showMessage(data.message || 'Operação concluída com sucesso.');
    } finally {
      setLoading(submitter, false);
    }
  }

  document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    if (!validateForm(form)) {
      event.preventDefault();
      event.stopPropagation();
      return;
    }

    if (form.dataset.ajax === 'true') {
      event.preventDefault();

      const question = form.dataset.ajaxConfirm;
      if (question && !window.confirm(question)) return;

      submitAjax(form);
    }
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', enhancePasswordToggles);
  } else {
    enhancePasswordToggles();
  }

  document.addEventListener('shown.bs.modal', enhancePasswordToggles);
})();
