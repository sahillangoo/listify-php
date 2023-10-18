/*
main.js
Author: Sahil Langoo
*/

// debounce function for limiting the number of times a function is called in a given time period
const debounce = (func, delay) => {
  let timeoutId;
  let lastExecuted = 0;

  return (...args) => {
    const now = Date.now();

    if (now - lastExecuted >= delay) {
      func(...args);
      lastExecuted = now;
    } else {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => {
        func(...args);
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
      return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>./])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>./]{8,18}$/;
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

// validateFileInput function for validating file input
const validateFileInput = (input) => {
  const file = input.files[0];
  const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
  const maxSize = 2 * 1024 * 1024; // 2 MB
  const minSize = 50 * 1024; // 50 KB
  const minWidth = 500;
  const minHeight = 500;

  if (!file) {
    return 'Please select a file';
  }

  if (!allowedTypes.includes(file.type)) {
    return 'Please select a valid image file (JPEG, PNG, or GIF)';
  }

  if (file.size > maxSize) {
    return 'File size exceeds the maximum allowed size (2 MB)';
  }

  if (file.size < minSize) {
    return 'File size is less than the minimum allowed size (50 KB)';
  }

  const img = new Image();
  img.src = URL.createObjectURL(file);
  return new Promise((resolve, reject) => {
    img.onload = () => {
      if (img.width < minWidth || img.height < minHeight) {
        reject(
          `Image dimensions are less than the minimum allowed dimensions (${minWidth}x${minHeight})`
        );
      } else {
        resolve();
      }
    };
    img.onerror = () => {
      reject('An error occurred while validating the image file');
    };
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
      fileInput.addEventListener('change', async () => {
        try {
          const result = await validateFileInput(fileInput);
          console.log(result);
        } catch (error) {
          console.error(error);
        }
      });
    }
  }
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

// initializeTooltips function for initializing bootstrap tooltips
const initializeTooltips = () => {
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"], [data-toggle="tooltip"]'
  );
  tooltipTriggerList.forEach((tooltipTriggerEl) => {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
};

// initializeFormValidation function for initializing form validation
const initializeFormValidation = () => {
  addInputValidationListeners();
};

// adjustTextareaSizeOnResize function for adjusting textarea size on window resize
const adjustTextareaSizeOnResize = () => {
  window.addEventListener('resize', () => {
    const description = document.getElementById('description');
    if (description) {
      description.style.height = 'auto';
      description.style.height = `${description.scrollHeight}px`;
    }
  });
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
    errorMessage.textContent = 'An error occurred in Search. Please try again later.';
    errorMessage.classList.remove('d-none');
  }
  console.error(error);
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

// Truncate description text
const truncateDescription = (selector = '#truncate') => {
  const descriptions = document.querySelectorAll(selector);
  descriptions.forEach((description) => {
    // Use substring instead of slice for consistency
    description.textContent = description.textContent.substring(0, 120) + '...';
  });
};
truncateDescription();

// Search functionality
const searchInput = document.querySelector('#search-input');
if (searchInput) {
  const searchFeedback = document.querySelector('#search-feedback');
  const searchResults = document.querySelector('#search-results');
  const searchSpinner = document.querySelector('#search-spinner');

  const displaySearchResults = (results) => {
    searchResults.innerHTML = '';
    searchFeedback.textContent = '';

    if (results && Array.isArray(results.listings)) {
      if (results.listings.length === 0 && searchInput.value.length >= 3) {
        searchFeedback.textContent = 'No results found for this query';
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
      searchFeedback.textContent = `${results.error}`;
      searchInput.classList.add('is-invalid');
    } else {
      searchFeedback.textContent =
        'An error occurred while fetching search results';
      searchInput.classList.add('is-invalid');
    }

    // Use classList.toggle instead of classList.add and classList.remove
    searchResults.classList.toggle(
      'd-none',
      !results || results.listings.length === 0
    );
    searchSpinner.classList.add('d-none');
  };

  const fetchSearchResults = async (searchQuery) => {
    try {
      let results = JSON.parse(
        localStorage.getItem(`searchResults:${searchQuery}`)
      );

      if (!results) {
        const response = await fetch(
          `./api/listingsApi.php?query=${searchQuery}`
        );
        if (!response.ok) {
          throw new Error('An error occurred while fetching search results');
        }
        results = await response.json();
        localStorage.setItem(
          `searchResults:${searchQuery}`,
          JSON.stringify(results)
        );
      }

      return results;
    } catch (error) {
      console.error(error);
      throw new Error('An error occurred while fetching search results');
    }
  };

  const handleSearchInput = () => {
    const searchQuery = searchInput.value.trim();

    if (/^\d+$/.test(searchQuery)) {
      searchFeedback.textContent = 'Search query cannot contain only numbers';
    } else if (!/^[a-zA-Z]{3,20}$/.test(searchQuery)) {
      searchFeedback.textContent =
        'Search query must be between 3 and 20 characters long and contain only letters';
    } else {
      searchSpinner.classList.remove('d-none');
      fetchSearchResults(searchQuery)
        .then(displaySearchResults)
        .catch((error) => {
          searchFeedback.textContent = error.message;
          searchInput.classList.add('is-invalid');
          searchResults.classList.add('d-none');
          searchSpinner.classList.add('d-none');
        });
    }

    if (searchInput.value.trim() === '') {
      searchInput.classList.remove('is-invalid');
      searchFeedback.textContent = '';
      searchResults.classList.add('d-none');
      searchSpinner.classList.add('d-none');
    }

    // Use classList.toggle instead of classList.add and classList.remove
    searchInput.classList.toggle(
      'is-invalid',
      searchFeedback.textContent !== ''
    );
    searchResults.classList.toggle(
      'd-none',
      searchFeedback.textContent !== '' || !searchInput.value
    );
    searchSpinner.classList.toggle(
      'd-none',
      searchFeedback.textContent !== '' || !searchInput.value
    );
  };

  searchInput.addEventListener('input', debounce(handleSearchInput, 500));
}

// Function to get the current location of the user on create-listing and update-listing page
(() => {
  const pathname = window.location.pathname;
  if (pathname !== '/add-listing.php' && pathname !== '/update-listing.php') {
    return;
  }

  const options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0,
  };

  try {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const { latitude, longitude } = position.coords;
        document.getElementById('latitude').value = latitude;
        document.getElementById('longitude').value = longitude;
        console.log(latitude, longitude);
      },
      function (error) {
        if (error.code === error.PERMISSION_DENIED) {
          alert('Enable Geolocation permission for Location.');
        } else if (error.code === error.TIMEOUT) {
          document.getElementById('latitude').value = '0.00';
          document.getElementById('longitude').value = '0.00';
        } else {
          console.error(error);
        }
      },
      options
    );
  } catch (error) {
    console.error(error);
  }

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  forms.forEach((form) => {
    form.addEventListener(
      'submit',
      (event) => {
        try {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add('was-validated');
        } catch (error) {
          console.error(error);
        }
      },
      false
    );
  });
})();
