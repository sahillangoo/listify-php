/*
main.js
Author: Sahil Langoo
File Content
- truncate description
- Tooltips
- validation for forms
- helper for adding on all elements multiple attributes
- toggle password
*/
// debounce function for limiting the number of times a function is called in a given time period
function debounce(func, delay) {
  let timeoutId;
  let lastExecuted = 0;

  return function (...args) {
    const now = Date.now();

    if (now - lastExecuted >= delay) {
      func.apply(this, args);
      lastExecuted = now;
    } else {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        func.apply(this, args);
        lastExecuted = Date.now();
      }, delay - (now - lastExecuted));
    }
  };
}

document.addEventListener('DOMContentLoaded', () => {
  // function for truncate description
  const descriptions = document.querySelectorAll('#truncate');
  descriptions.forEach((description) => {
    description.textContent = description.textContent.slice(0, 120) + '...';
  });

  // initialization of Tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll(
      '[data-bs-toggle="tooltip"], [data-toggle="tooltip"]'
    )
  );
  const tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // validation for forms
  const inputs = [...document.querySelectorAll('input')];
  inputs.forEach((input) => {
    input.addEventListener(
      'input',
      debounce(async (event) => {
        const regex = getRegexForInput(input);
        await validateInput(input, regex);
      }, 500),
      false
    );
  });

  function validateInput(input, regex) {
    const value = input.value.trim();
    return Promise.resolve(regex.test(value))
      .then((isValid) => {
        input.classList.toggle('is-valid', isValid);
        input.classList.toggle('is-invalid', !isValid);
      })
      .catch(console.error);
  }
  const regexPatterns = {
    username: /^(?<username>[a-zA-Z0-9._]{6,20})$/,
    email:
      /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
    phone: /^[0-9]{10}$/,
    password:
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>/])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>/]{8,18}$/,
    businessName: /^[a-zA-Z0-9 ]{3,20}$/,
    businessAddress: /^[a-zA-Z0-9 ]{3,20}$/,
    about: /^[a-zA-Z0-9 .,?!'":;()@#$%&*+-/]{10,200}$/,
    pincode: /^[0-9]{6}$/,
    facebookid: /^[a-zA-Z0-9._]{3,20}$/,
    website:
      /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-zA-Z0-9]+([a-zA-Z0-9-]+)?(\.[a-zA-Z0-9-]+)+([\/?].*)?$/,
    gst: /^[0-9]{15}$/,
    review: /^[a-zA-Z0-9 .,?!'":;()@#$%&*+-/]{10,150}$/,
    rating: /^[1-5]{1}$/,
  };

  function getRegexForInput(input) {
    return regexPatterns[input.name] || null;
  }
});

const inputGroupClickHandler = (event) => {
  try {
    const parent = event.target.closest('.input-group');
    if (event.target.classList.contains('form-control')) {
      const focus = document.querySelectorAll('.input-group.focused');
      focus.forEach((el) => el.classList.remove('focused'));
      parent.classList.add('focused');
    }
    const focus = document.querySelectorAll('.input-group.focused');
    if (focus && event.target != parent && event.target.parentNode != parent) {
      focus.forEach((el) => el.classList.remove('focused'));
    }
  } catch (error) {
    console.error(error);
  }
};

// helper for adding on all elements multiple attributes
function setAttributes(el, options) {
  try {
    Object.keys(options).forEach((attr) => {
      el.setAttribute(attr, options[attr]);
    });
  } catch (error) {
    console.error(error);
  }
}

function togglePassword() {
  const password = document.querySelector('#password');
  const toggle = document.querySelector('#toggle-password');
  if (password.type === 'password') {
    password.type = 'text';
    toggle.classList.replace('fa-eye-slash', 'fa-eye');
  } else {
    password.type = 'password';
    toggle.classList.replace('fa-eye', 'fa-eye-slash');
  }
}
