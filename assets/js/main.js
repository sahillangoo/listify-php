/*
main.js
Author: Sahil Langoo
File Content
- truncate description
- Tooltips
- helper for adding on all elements multiple attributes
- toggle password
- validation for forms
- textarea validation
- textarea size adjustment on window resize
- file validation
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

// helper for adding on all elements multiple attributes
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

// toggle password
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

// validation for forms
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  if (form) {
    const inputs = [...form.querySelectorAll('input')];
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
  }

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
});

// validation for forms
async function validateInput(input, regex) {
  const value = input.value.trim();
  try {
    const isValid = await Promise.resolve(regex.test(value));
    input.classList.toggle('is-valid', isValid);
    input.classList.toggle('is-invalid', !isValid);
  } catch (error) {
    console.error(error);
  }
}

function getRegexForInput(input) {
  switch (input.name) {
    case 'username':
      return /^(?<username>[a-z0-9._-]{6,20})$/;
    case 'email':
      return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    case 'phone':
      return /^[0-9]{10}$/;
    case 'password':
      return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>/])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>/]{8,18}$/;
    case 'businessName':
      return /^[a-zA-Z0-9 ]{3,30}$/;
    case 'address':
      return /^[a-zA-Z0-9 -,_&]{8,30}$/;
      // puctuation marks are allowed with alphabets and numbers and spaces and min 10 and max 999 characters
    case 'description':
      return /^[\w\s!?'"&().:;,-]{10,999}$/;
    case 'pincode':
      return /^[0-9]{6}$/;
    case 'whatsapp':
      return /^[0-9]{10}$/;
    case 'instagramId':
      return /^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/;
    case 'facebookId':
      return /^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{3,30}$/;
    case 'website':
      return /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-zA-Z0-9]+([a-zA-Z0-9-]+)?(\.[a-zA-Z0-9-]+)+([\/?].*)?$/;
    case 'gst':
      return /^[0-9]{15}$/;
    case 'review':
      return /^[a-zA-Z0-9 .,?!'":;()@#$%&*+-/]{10,150}$/;
    case 'rating':
      return /^[1-5]{1}$/;
    default:
      return null;
  }
}

// textarea validation
const description = document.getElementById('description');
const counter = document.getElementById('counter');
const maxChars = 999;

const validateDescription = () => {
  const chars = description.value.length;
  const charsLeft = maxChars - chars;
  counter.textContent = `${charsLeft} Characters left!`;

  if (charsLeft >= 0) {
    counter.parentElement.classList.remove('d-none');
    description.classList.add('is-valid');
    description.classList.remove('is-invalid');
  } else {
    counter.parentElement.classList.add('d-none');
    description.classList.add('is-invalid');
    description.classList.remove('is-valid');
  }

  const regex = getRegexForInput(description);
  validateInput(description, regex);

  // textarea size adjustment on input
  description.style.height = 'auto';
  description.style.height = `${description.scrollHeight}px`;
};

description.addEventListener('input', debounce(validateDescription, 500));

// textarea size adjustment on window resize
window.addEventListener('resize', () => {
  description.style.height = 'auto';
  description.style.height = `${description.scrollHeight}px`;
});

const fileInput = document.getElementById('business_image');
const validExtensions = ['jpg', 'jpeg', 'png'];
const maxSize = 2 * 1024 * 1024; // 2MB
const minWidth = 500;
const minHeight = 500;

fileInput.addEventListener('change', function () {
  const file = fileInput.files[0];
  const extension = file.name.split('.').pop().toLowerCase();

  if (!validExtensions.includes(extension)) {
    fileInput.setCustomValidity('Only PNG, JPG, and JPEG files are allowed.');
  } else if (file.size > maxSize) {
    fileInput.setCustomValidity('File size must be less than 2MB.');
  } else {
    const img = new Image();
    img.onload = function () {
      if (img.width < minWidth || img.height < minHeight) {
        fileInput.setCustomValidity(
          `Image dimensions must be at least ${minWidth}x${minHeight}px.`
        );
      } else {
        fileInput.setCustomValidity('');
      }
    };
    img.src = URL.createObjectURL(file);
  }

  fileInput.reportValidity();
});
