/*
main.js
Author: Sahil Langoo
File Content =>
1. debounce function for limiting the number of times a function is called in a given time period
2. setAttributes function for setting multiple attributes on an element
3. togglePassword function for toggling password visibility
4. validateInput function for validating input fields
5. getRegexForInput function for getting regex for input fields
6. validateDescription function for validating description textarea
7. initializeTooltips function for initializing bootstrap tooltips
8. addInputGroupClickHandler function for adding click handler to input groups
9. addInputValidationListeners function for adding input validation listeners
10. addTextareaValidationListener function for adding textarea validation listener
11. adjustTextareaSizeOnResize function for adjusting textarea size on window resize
12. addFileInputValidationListener function for adding file input validation listener
13. initializeFormValidation function for initializing form validation
14. initializePage function for initializing page
15. handleError function for handling errors
16. displaySearchResults function for displaying search results
17. fetchSearchResults function for fetching search results
18. handleSearchInput function for handling search input
19. clearSearchInput function for clearing search input
20. truncateDescription function for truncating description text
*/

// debounce function for limiting the number of times a function is called in a given time period
const debounce = (func, delay) => {
  let timeoutId;
  let lastExecuted = 0;

  return (...args) => {
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
};

// setAttributes function for setting multiple attributes on an element
const setAttributes = (el, options) => {
  Object.keys(options).forEach((attr) => {
    el.setAttribute(attr, options[attr]);
  });
};

// togglePassword function for toggling password visibility
const togglePassword = () => {
  const password = document.querySelector('#password');
  const toggle = document.querySelector('#toggle-password');
  password.type = password.type === 'password' ? 'text' : 'password';
  toggle.classList.toggle('fa-eye-slash');
  toggle.classList.toggle('fa-eye');
};

// validateInput function for validating input fields
const validateInput = (input, regex) => {
  const value = input.value.trim();
  const isValid = regex.test(value);
  input.classList.toggle('is-valid', isValid);
  input.classList.toggle('is-invalid', !isValid);
  return isValid;
};

// getRegexForInput function for getting regex for input fields
const getRegexForInput = (input) => {
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
};

// validateDescription function for validating description textarea
const validateDescription = () => {
  const description = document.getElementById('description');
  const counter = document.getElementById('counter');
  const maxChars = 999;
  const chars = description.value.length;
  const charsLeft = maxChars - chars;
  counter.textContent = `${charsLeft} Characters left!`;

  const regex = getRegexForInput(description);
  const isValid = validateInput(description, regex);

  description.style.height = 'auto';
  description.style.height = `${description.scrollHeight}px`;
  description.classList.toggle('is-valid', isValid);
  description.classList.toggle('is-invalid', !isValid);
};

// initializeTooltips function for initializing bootstrap tooltips
const initializeTooltips = () => {
  const tooltipTriggerList = [
    ...document.querySelectorAll(
      '[data-bs-toggle="tooltip"], [data-toggle="tooltip"]'
    ),
  ];
  tooltipTriggerList.forEach((tooltipTriggerEl) => {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
};

// addInputGroupClickHandler function for adding click handler to input groups
const addInputGroupClickHandler = () => {
  document.addEventListener('click', (event) => {
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
  });
};

// addInputValidationListeners function for adding input validation listeners
const addInputValidationListeners = () => {
  const form = document.querySelector('form');
  if (form) {
    const inputs = [...form.querySelectorAll('input')];
    inputs.forEach((input) => {
      input.addEventListener(
        'input',
        debounce(() => {
          const regex = getRegexForInput(input);
          if (regex) {
            validateInput(input, regex);
          }
        }, 500),
        false
      );
    });
    const description = document.getElementById('description');
    if (description) {
      description.addEventListener('input', debounce(validateDescription, 500));
    }
    const fileInput = document.getElementById('business_image');
    if (fileInput) {
      fileInput.addEventListener('change', validateFileInput);
    }
  }
};

// adjustTextareaSizeOnResize function for adjusting textarea size on window resize
const adjustTextareaSizeOnResize = () => {
  window.addEventListener('resize', () => {
    const description = document.getElementById('description');
    description.style.height = 'auto';
    description.style.height = `${description.scrollHeight}px`;
  });
};

// initializeFormValidation function for initializing form validation
const initializeFormValidation = () => {
  addInputValidationListeners();
};

// initializePage function for initializing page
const initializePage = () => {
  initializeTooltips();
  addInputGroupClickHandler();
  initializeFormValidation();
  adjustTextareaSizeOnResize();
};

// handleError function for handling errors
const handleError = (error) => {
  const errorMessage = document.getElementById('error-message');
  if (errorMessage) {
    errorMessage.textContent = 'An error occurred. Please try again later.';
    errorMessage.classList.remove('d-none');
  }
};

// handle unhandledrejection and error events
window.addEventListener('unhandledrejection', (event) => {
  event.preventDefault();
  handleError(event.reason);
});

window.addEventListener('error', (event) => {
  event.preventDefault();
  handleError(event.error);
});

// initialize page on DOMContentLoaded event
document.addEventListener('DOMContentLoaded', initializePage);

// search functionality
const searchInput = document.getElementById('search-input');
const searchFeedback = document.getElementById('search-feedback');
const searchResults = document.getElementById('search-results');
const searchSpinner = document.getElementById('search-spinner');

// display search results
const displaySearchResults = (results) => {
  searchResults.innerHTML = '';
  searchFeedback.innerHTML = '';

  if (Array.isArray(results.listings)) {
    if (results.listings.length === 0 && searchInput.value.length >= 3) {
      searchFeedback.innerHTML = `No results found for this query`;
      searchInput.classList.add('is-invalid');
    } else {
      results.listings
        .slice(0, 6)
        .forEach(
          ({
            id,
            businessName,
            category,
            city,
            address,
            avg_rating,
            reviews_count,
          }) => {
            const resultElement = document.createElement('a');
            resultElement.href = `./listing.php?listing=${id}`;
            resultElement.classList.add(
              'list-group-item',
              'list-group-item-action'
            );
            resultElement.innerHTML = `
          <div class="d-flex w-100 justify-content-between align-items-center">
            <h6 class="text-gradient text-primary font-weight-bold h6 mb-1">${businessName}</h6>
            <span class="text-body-secondary text-gradient text-warning text-uppercase text-xxs mt-1"><i class="fa-solid fa-star"></i> ${
              avg_rating?.toFixed(2) || 0
            } (${reviews_count})</span>
            <span class="text-body-secondary text-capitalize text-xxs font-weight-bold"><i class="fa-solid fa-shop"></i> ${category}</span>
            <span class="text-body-secondary text-capitalize text-xxs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${address}, ${city}</span>
          </div>
        `;
            searchResults.appendChild(resultElement);
          }
        );
      searchInput.classList.remove('is-invalid');
    }
  } else if (results?.error) {
    searchFeedback.innerHTML = `${results.error}`;
    searchInput.classList.add('is-invalid');
  }

  searchResults.classList.toggle('d-none', results.listings.length === 0);
  searchSpinner.classList.add('d-none');
};

// fetch search results
const fetchSearchResults = async (searchQuery) => {
  const response = await fetch(`./api/listingsApi.php?query=${searchQuery}`);
  return response.json();
};

// handle search input
const handleSearchInput = () => {
  const searchQuery = searchInput.value.trim();

  if (/^\d+$/.test(searchQuery)) {
    searchFeedback.innerHTML = `Search query cannot contain only numbers`;
  } else if (!/^[a-zA-Z]{3,20}$/.test(searchQuery)) {
    searchFeedback.innerHTML = `Search query must be between 3 and 20 characters long and contain only letters`;
  } else {
    searchSpinner.classList.remove('d-none');
    fetchSearchResults(searchQuery)
      .then(displaySearchResults)
      .catch(() => {
        searchFeedback.innerHTML = `An error occurred while fetching search results`;
      });
  }

  searchInput.classList.toggle('is-invalid', searchFeedback.innerHTML !== '');
  searchResults.classList.toggle('d-none', searchFeedback.innerHTML !== '');
  searchSpinner.classList.toggle('d-none', searchFeedback.innerHTML !== '');
};

// clear search input
const clearSearchInput = () => {
  searchInput.value = '';
  searchInput.classList.remove('is-invalid');
  searchResults.innerHTML = '';
  searchResults.classList.add('d-none');
};

// add event listeners for search functionality
searchInput.addEventListener('input', debounce(handleSearchInput, 500));
document
  .getElementById('clear-search-input')
  .addEventListener('click', clearSearchInput);

// truncate description text
const truncateDescription = (selector = '#truncate') => {
  const descriptions = document.querySelectorAll(selector);
  descriptions.forEach((description) => {
    description.textContent = description.textContent.slice(0, 120) + '...';
  });
};

truncateDescription();

(() => {
  document.addEventListener('DOMContentLoaded', function (event) {
    const options = {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0,
    };
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;
        console.log(latitude, longitude);
      },
      function (error) {
        if (error.code === error.PERMISSION_DENIED) {
          alert('Enable Geolocation permission for Location.');
        }
        console.log(error);
      },
      options
    );
  });
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      'submit',
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      },
      false
    );
  });
})();
