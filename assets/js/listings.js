/*
listings.js
Author: Sahil Langoo
function content =>
  1. createListingCard(listing)
  2. createPaginationLink(page, isActive)
  3. createPreviousLink(page)
  4. createNextLink(page, totalPages)
  5. fetchListings(page)
  6. renderListings(listings, total)
  7. handleFilterButtonClick(event)
  8. updateListingsCount(listingsCount, totalListings)
*/
// Create listing card HTML
function createListingCard(listing) {
  const card = document.createElement('div');
  card.classList.add('col-6', 'col-md-3', 'col-lg-3', 'mb-4');
  card.innerHTML = `
    <div class="card card-frame">
      <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
        <a href="./listing.php?listing=${listing.id}" class="d-block">
          <img src="./uploads/business_images/${listing.displayImage}" class="img-fluid border-radius-lg move-on-hover" alt="${listing.displayImage}" loading="lazy">
        </a>
      </div>
      <div class="card-body pt-2">
        <div class="d-flex justify-content-between align-items-center my-md-2">
          <span class="text-capitalize text-xs font-weight-bold text-info text-gradient"><i class="fa-solid fa-shop"></i> ${listing.category}</span>
          <span class="text-capitalize text-xs font-weight-bold text-info text-gradient"><i class="fa-solid fa-location-dot"></i> ${listing.city}</span>
        </div>
        <span class="d-md-none text-gradient text-warning text-uppercase text-xxs my-md-2"><i class="fa-solid fa-star"></i> ${listing.avg_rating} (${listing.reviews_count})</span>
        <div class="d-flex justify-content-between ">
          <a href="./listing.php?listing=${listing.id}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">${listing.businessName}</a>
          <span class="d-none d-md-block text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${listing.avg_rating} (${listing.reviews_count})</span>
        </div>
        <p class="d-none d-md-block card-description text-sm mb-3" id="truncate">${listing.description} ...</p>
        <p class="d-none d-md-block mb-2 text-xxs text-info text-gradient text-capitalize"><span>by―</span> ${listing.user}</p>
        <div class="d-flex justify-content-start my-md-2">
          <a href="./listing.php?listing=${listing.id}" class="text-gradient text-primary text-xs icon-move-right">view details <i class="fas fa-arrow-right " aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  `;
  return card;
}
// Get DOM elements
const listingsDiv = document.getElementById('listings');
const errorMessage = document.getElementById('error-message');
const loadingSpinner = document.getElementById('loading-spinner');
const listingsPagination = document.getElementById('listings-pagination');
const listingsCountElement = document.getElementById('listings-count');
const totalListingsElement = document.getElementById('total-listings');
const sortDropdown = document.getElementById('sortDropdown');
const categoryFilterDropdown = document.getElementById('filterCategory');
const cityFilterDropdown = document.getElementById('cityDropdown');
const filterButtons = document.querySelectorAll('[data-filter]');
const clearFiltersButton = document.querySelector('#clear-filters');
const errorBox = document.createElement('div');
errorBox.classList.add('alert', 'alert-danger', 'mt-3', 'd-none');

// Initialize variables
const listingsPerPage = 12;
let currentPage = 1;
let sortOption = 'featured';
let categoryFilter = '';
let cityFilter = '';

// Get current page from URL or local storage
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('page')) {
  currentPage = parseInt(urlParams.get('page'));
} else if (localStorage.getItem('currentPage')) {
  currentPage = parseInt(localStorage.getItem('currentPage'));
}

// Check if category slug is set in URL
if (urlParams.has('category')) {
  categoryFilter = urlParams.get('category');
  categoryFilterDropdown.value = categoryFilter;
}

// Show loading spinner
loadingSpinner.classList.add('d-flex');

// Event listeners for sort and filter dropdowns
sortDropdown.addEventListener('change', () => {
  sortOption = sortDropdown.value;
  fetchListings(1);
});

categoryFilterDropdown.addEventListener('change', () => {
  categoryFilter = categoryFilterDropdown.value;
  const urlParams = new URLSearchParams(window.location.search);
  if (categoryFilter) {
    urlParams.set('category', categoryFilter);
  } else {
    urlParams.delete('category');
  }
  window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
  fetchListings(1);
});

cityFilterDropdown.addEventListener('change', () => {
  cityFilter = cityFilterDropdown.value;
  const urlParams = new URLSearchParams(window.location.search);
  if (cityFilter) {
    urlParams.set('city', cityFilter);
  } else {
    urlParams.delete('city');
  }
  window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
  fetchListings(1);
});

// Create pagination link HTML
function createPaginationLink(page, isActive) {
  const link = document.createElement('li');
  link.classList.add('page-item', isActive ? 'active' : null);
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.textContent = page;
  linkContent.addEventListener('click', async (event) => {
    event.preventDefault();
    await fetchListings(page);
    localStorage.setItem('currentPage', page);
  });
  link.appendChild(linkContent);
  return link;
}

// Create previous page link HTML
function createPreviousLink(page) {
  const link = document.createElement('li');
  link.classList.add('page-item', page === 1 ? 'disabled' : null);
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.setAttribute('aria-label', 'Previous');
  const icon = document.createElement('span');
  icon.setAttribute('aria-hidden', 'true');
  icon.classList.add('fa-solid', 'fa-angles-left');
  linkContent.appendChild(icon);
  linkContent.addEventListener('click', async (event) => {
    event.preventDefault();
    await fetchListings(page - 1);
    localStorage.setItem('currentPage', page - 1);
  });
  link.appendChild(linkContent);
  return link;
}

// Create next page link HTML
function createNextLink(page, totalPages) {
  const link = document.createElement('li');
  link.classList.add('page-item', page === totalPages ? 'disabled' : null);
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.setAttribute('aria-label', 'Next');
  const icon = document.createElement('span');
  icon.setAttribute('aria-hidden', 'true');
  icon.classList.add('fa-solid', 'fa-angles-right');
  linkContent.appendChild(icon);
  linkContent.addEventListener('click', async (event) => {
    event.preventDefault();
    await fetchListings(page + 1);
    localStorage.setItem('currentPage', page + 1);
  });
  link.appendChild(linkContent);
  return link;
}

// Fetch listings from API
async function fetchListings(page) {
  try {
    // Show loading spinner
    loadingSpinner.classList.remove('d-none');
    // Clear listings and pagination links
    listingsDiv.innerHTML = '';
    listingsPagination.innerHTML = '';
    // Remove sort options from URL
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete('featured');
    urlParams.delete('most_rated');
    urlParams.delete('most_reviewed');
    // Set sort option in URL and local storage
    if (sortOption) {
      urlParams.set(sortOption, '1');
      localStorage.setItem('sortOption', sortOption);
    } else {
      localStorage.removeItem('sortOption');
    }
    // Set category filter in URL
    if (categoryFilter) {
      urlParams.set('category', categoryFilter);
    } else {
      urlParams.delete('category');
    }
    // Set city filter in URL
    if (cityFilter) {
      urlParams.set('city', cityFilter);
    } else {
      urlParams.delete('city');
    }
    // Construct API URL
    const url = `./api/listingsApi.php?page=${page}&${urlParams.toString()}`;
    // Check if response is cached
    const cachedResponse = localStorage.getItem(url);
    if (cachedResponse) {
      const { listings, total } = JSON.parse(cachedResponse);
      renderListings(listings, total);
    } else {
      // Fetch listings from API
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const { listings, total } = await response.json();
      renderListings(listings, total);
      // Cache all pages of listings
      const totalPages = Math.ceil(total / listingsPerPage);
      const pagePromises = [];
      for (let i = 1; i <= totalPages; i++) {
        const pageUrl = `./api/listingsApi.php?page=${i}&${urlParams.toString()}`;
        const pagePromise = fetch(pageUrl).then((response) => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        });
        pagePromises.push(pagePromise);
      }
      const allPages = await Promise.all(pagePromises);
      allPages.forEach(({ listings }, i) => {
        localStorage.setItem(`listings-${i + 1}`, JSON.stringify(listings));
      });
      // Cache current page and total pages
      localStorage.setItem('currentPage', currentPage);
      localStorage.setItem('totalPages', totalPages);
      // Cache listings and total listings count
      localStorage.setItem(url, JSON.stringify({ listings, total }));
      localStorage.setItem('totalListings', total);
      // Update URL with new parameters
      window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
    }
  } catch (error) {
    console.error(error);
    // Show error message
    errorBox.textContent = 'An error occurred while fetching listings. Please try again later.';
    errorBox.classList.remove('d-none');
  } finally {
    // Hide loading spinner
    loadingSpinner.classList.add('d-none');
  }
}

// Handle filter button click
function handleFilterButtonClick(event) {
  event.preventDefault();
  sortOption = event.target.getAttribute('data-filter');
  fetchListings(1);
}

// Add event listeners for filter buttons
Array.from(filterButtons).forEach((button) => {
  button.addEventListener('click', handleFilterButtonClick);
});

// Update listings count
function updateListingsCount(listingsCount, totalListings) {
  listingsCountElement.textContent = listingsCount;
  totalListingsElement.textContent = totalListings;
}

// Get total listings count and set total pages in local storage
fetch('./api/listingsApi.php?total')
  .then((response) => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(({ total }) => {
    const totalPages = Math.ceil(total / listingsPerPage);
    localStorage.setItem('totalPages', totalPages);
  })
  .catch((error) => {
    console.error(error);
    // Show error message
    errorBox.textContent = 'An error occurred in listing count. Please try again later.';
    errorBox.classList.remove('d-none');
  });

// Render listings and pagination links
function renderListings(listings, total) {
  if (listings.length === 0) {
    // Show message if no listings found
    listingsDiv.innerHTML = `<p class="text-center text-bolder text-2xl text-info text-gradient my-5">Ugh! No listings found.</p>`;
    listingsPagination.innerHTML = '';
    // Hide loading spinner
    loadingSpinner.classList.add('d-none');
    updateListingsCount(0, total);
    return;
  }
  listings.forEach((listing) => {
    if (categoryFilter && listing.category !== categoryFilter) {
      return;
    }
    const card = createListingCard(listing);
    listingsDiv.appendChild(card);
  });
  const totalPages = parseInt(localStorage.getItem('totalPages'));
  for (let i = 1; i <= totalPages; i++) {
    const link = createPaginationLink(i, i === currentPage);
    listingsPagination.appendChild(link);
  }
  const previousLink = createPreviousLink(currentPage);
  listingsPagination.insertBefore(previousLink, listingsPagination.firstChild);
  const nextLink = createNextLink(currentPage, totalPages);
  listingsPagination.appendChild(nextLink);
  // Hide loading spinner
  loadingSpinner.classList.add('d-none');
  // Update URL with new parameters
  window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
  updateListingsCount(listingsDiv.children.length, total);
}

// Fetch listings on page load
fetchListings(currentPage);

function clearQueryParams() {
  const url = new URL(window.location.href);
  url.search = '';
  window.location.href = url.toString();
}

// Add event listener to clear filters button
clearFiltersButton.addEventListener('click', () => {
  // Reset filter values to their default state
  sortDropdown.value = 'featured';
  categoryFilterDropdown.value = '';
  cityFilterDropdown.value = '';
  // Remove query parameters from URL
  clearQueryParams();
});

// Add error box to the DOM
listingsDiv.insertAdjacentElement('afterend', errorBox);
