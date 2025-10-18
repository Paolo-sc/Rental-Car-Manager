(function () {
    'use strict';

    const SELECTORS = {
        form: 'form[name="registration"]',
        firstName: '#first-name',
        lastName: '#last-name',
        phone: '#phone',
        email: '#email', // readonly
        password: '#password',
        passwordConfirm: '#password_confirmation',
        eye: '#eye'
    };

    const CLASSES = {
        invalid: 'invalid',
        inputError: 'input-error-message',
        fieldContainer: 'input-container'
    };


    const TEXT = {
        firstNameRequired: 'Il nome è obbligatorio.',
        firstNameFormat: 'Il nome può contenere solo lettere e apostrofo.',
        lastNameRequired: 'Il cognome è obbligatorio.',
        lastNameFormat: 'Il cognome può contenere solo lettere, spazi o apostrofo.',
        phoneRequired: 'Il telefono è obbligatorio.',
        phoneFormat: 'Inserisci un numero di telefono valido (solo cifre, min 6 max 15).',
        passwordRequired: 'La password è obbligatoria.',
        passwordPolicy: 'La password deve avere almeno 8 caratteri.',
        passwordConfirmRequired: 'Conferma la password.',
        passwordMismatch: 'Le password non coincidono.',
        toggleShow: 'Mostra password',
        toggleHide: 'Nascondi password'
    };

    const REGEX = {
        name: /^[A-Za-zÀ-ÖØ-öø-ÿ'’\-]{2,}$/u,
        lastName: /^[A-Za-zÀ-ÖØ-öø-ÿ'’\- ]{2,}$/u,
        phone: /^[0-9]{6,15}$/
    };

    let form;
    const els = {};

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        form = document.querySelector(SELECTORS.form);
        if (!form) return;

        // Cache elementi
        els.firstName = form.querySelector(SELECTORS.firstName);
        els.lastName = form.querySelector(SELECTORS.lastName);
        els.phone = form.querySelector(SELECTORS.phone);
        els.email = form.querySelector(SELECTORS.email);
        els.password = form.querySelector(SELECTORS.password);
        els.passwordConfirm = form.querySelector(SELECTORS.passwordConfirm);
        els.eye = document.querySelector(SELECTORS.eye);

        marcaErroriServer();
        setupEye();
        attachEvents();
    }

    function marcaErroriServer() {
        form.querySelectorAll(`.${CLASSES.inputError}`).forEach(span => {
            span.dataset.server = 'true';
            // Marca anche il relativo input come invalid per coerenza focus
            const container = span.closest(`.${CLASSES.fieldContainer}`);
            if (container) {
                const input = container.querySelector('input');
                if (input) {
                    input.classList.add(CLASSES.invalid);
                    input.setAttribute('aria-invalid', 'true');
                }
            }
        });
    }

    function setupEye() {
        if (!els.eye || !els.password) return;
        els.eye.style.cursor = 'pointer';
        rendiFocusabile(els.eye);
        els.eye.addEventListener('click', togglePasswordVisibility);
        els.eye.addEventListener('keydown', eyeKeyHandler);
        els.eye.setAttribute('aria-label', TEXT.toggleShow);
        els.eye.setAttribute('alt', TEXT.toggleShow);
    }

    function attachEvents() {
        if (els.firstName) {
            els.firstName.addEventListener('input', () => validateFirstName({ soft: true }));
            els.firstName.addEventListener('blur', () => validateFirstName());
        }
        if (els.lastName) {
            els.lastName.addEventListener('input', () => validateLastName({ soft: true }));
            els.lastName.addEventListener('blur', () => validateLastName());
        }
        if (els.phone) {
            els.phone.addEventListener('input', () => validatePhone({ soft: true }));
            els.phone.addEventListener('blur', () => validatePhone());
        }
        if (els.password) {
            els.password.addEventListener('input', () => {
                validatePassword({ soft: true });
                if (els.passwordConfirm && els.passwordConfirm.value.trim() !== '') {
                    validatePasswordConfirm({ soft: true });
                }
            });
            els.password.addEventListener('blur', () => validatePassword());
        }
        if (els.passwordConfirm) {
            els.passwordConfirm.addEventListener('input', () => validatePasswordConfirm({ soft: true }));
            els.passwordConfirm.addEventListener('blur', () => validatePasswordConfirm());
        }

        form.addEventListener('submit', onSubmit);
    }

    function onSubmit(e) {
        const okFirst = validateFirstName();
        const okLast = validateLastName();
        const okPhone = validatePhone();
        const okPwd = validatePassword();
        const okConf = validatePasswordConfirm();

        if (!(okFirst && okLast && okPhone && okPwd && okConf)) {
            e.preventDefault();
            focusFirstInvalid();
        }
    }

    function validateFirstName(opts = {}) {
        const el = els.firstName;
        if (!el) return true;
        clearFieldError(el);
        const v = el.value.trim();

        if (v === '') {
            if (!opts.soft) setError(el, TEXT.firstNameRequired);
            return mark(el, false, opts);
        }
        if (!REGEX.name.test(v)) {
            if (!opts.soft) setError(el, TEXT.firstNameFormat);
            return mark(el, false, opts);
        }
        return mark(el, true);
    }

    function validateLastName(opts = {}) {
        const el = els.lastName;
        if (!el) return true;
        clearFieldError(el);
        const v = el.value.trim();

        if (v === '') {
            if (!opts.soft) setError(el, TEXT.lastNameRequired);
            return mark(el, false, opts);
        }
        if (!REGEX.lastName.test(v)) {
            if (!opts.soft) setError(el, TEXT.lastNameFormat);
            return mark(el, false, opts);
        }
        return mark(el, true);
    }

    function validatePhone(opts = {}) {
        const el = els.phone;
        if (!el) return true;
        clearFieldError(el);
        const v = el.value.trim();

        if (v === '') {
            if (!opts.soft) setError(el, TEXT.phoneRequired);
            return mark(el, false, opts);
        }
        if (!REGEX.phone.test(v)) {
            if (!opts.soft) setError(el, TEXT.phoneFormat);
            return mark(el, false, opts);
        }
        return mark(el, true);
    }

    function validatePassword(opts = {}) {
        const el = els.password;
        if (!el) return true;
        clearFieldError(el);
        const v = el.value;

        if (v.trim() === '') {
            if (!opts.soft) setError(el, TEXT.passwordRequired);
            return mark(el, false, opts);
        }
        if (v.length < 8) {
            if (!opts.soft) setError(el, TEXT.passwordPolicy);
            return mark(el, false, opts);
        }
        return mark(el, true);
    }

    function validatePasswordConfirm(opts = {}) {
        const el = els.passwordConfirm;
        if (!el) return true;
        clearFieldError(el);
        const v = el.value;
        const p = els.password ? els.password.value : '';

        if (v.trim() === '') {
            if (!opts.soft) setError(el, TEXT.passwordConfirmRequired);
            return mark(el, false, opts);
        }
        if (v !== p) {
            if (!opts.soft) setError(el, TEXT.passwordMismatch);
            return mark(el, false, opts);
        }
        return mark(el, true);
    }

    function mark(input, valid, opts = {}) {
        if (valid) {
            removeInvalid(input);
            return true;
        } else {
            if (!opts.soft) setInvalid(input);
            return false;
        }
    }

    function setError(input, msg) {
        const container = findFieldContainer(input);
        if (!container) return;

        const existing = container.querySelector(`.${CLASSES.inputError}`);
        if (existing && existing.dataset.server === 'true') return;
        if (existing) {
            existing.textContent = msg;
            return;
        }
        const span = document.createElement('span');
        span.className = CLASSES.inputError;
        span.textContent = msg;
        span.setAttribute('role', 'alert');
        span.setAttribute('aria-live', 'assertive');
        container.appendChild(span);
    }

    function clearFieldError(input) {
        const container = findFieldContainer(input);
        if (!container) return;
        const errs = container.querySelectorAll(`.${CLASSES.inputError}`);
        errs.forEach(err => {
            if (err.dataset.server === 'true') return;
            err.remove();
        });
    }

    function setInvalid(input) {
        input.classList.add(CLASSES.invalid);
        input.setAttribute('aria-invalid', 'true');
    }

    function removeInvalid(input) {
        input.classList.remove(CLASSES.invalid);
        input.removeAttribute('aria-invalid');
    }

    function findFieldContainer(input) {
        return input.closest(`.${CLASSES.fieldContainer}`) || input.parentElement;
    }

    function focusFirstInvalid() {
        const first = form.querySelector(`.${CLASSES.invalid}`);
        if (first) first.focus();
    }


    function togglePasswordVisibility() {
        const pwd = els.password;
        const conf = els.passwordConfirm;
        const eye = els.eye;
        if (!pwd || !eye) return;

        const currentlyText = pwd.type === 'text';
        const newType = currentlyText ? 'password' : 'text';

        pwd.type = newType;
        if (conf) conf.type = newType;

        if (currentlyText) {
            aggiornaIcona(eye, true);
        } else {
            aggiornaIcona(eye, false);
        }
    }

    function aggiornaIcona(eye, toHidden) {
        // toHidden = true => passiamo a password nascosta (mostra occhio aperto)
        if (toHidden) {
            eye.src = eye.src.includes('icon-eye-off.svg')
                ? eye.src.replace('icon-eye-off.svg', 'icon-eye.svg')
                : eye.src;
            eye.alt = TEXT.toggleShow;
            eye.setAttribute('aria-label', TEXT.toggleShow);
        } else {
            if (eye.src.includes('icon-eye.svg')) {
                eye.src = eye.src.replace('icon-eye.svg', 'icon-eye-off.svg');
            }
            eye.alt = TEXT.toggleHide;
            eye.setAttribute('aria-label', TEXT.toggleHide);
        }
    }

    function rendiFocusabile(icon) {
        icon.setAttribute('tabindex', '0');
        icon.setAttribute('role', 'button');
    }

    function eyeKeyHandler(e) {
        if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
            togglePasswordVisibility();
        }
    }

})();