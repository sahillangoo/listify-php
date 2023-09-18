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
// Get DOM elements
const listingsDiv = document.getElementById('listings');
const errorMessage = document.getElementById('error-message');
const loadingSpinner = document.getElementById('loading-spinner');
const listingsPagination = document.getElementById('listings-pagination');
const listingsCountElement = document.getElementById('listings-count');
const totalListingsElement = document.getElementById('total-listings');
const sortDropdown = document.getElementById('sortDropdown');
const categoryFilterDropdown = document.getElementById('filteCategory');
const cityFilterDropdown = document.getElementById('cityDropdown');
const filterButtons = document.querySelectorAll('[data-filter]');
const clearFiltersButton = document.querySelector('#clear-filters');
const errorBox = document.createElement('div');
errorBox.classList.add('alert', 'alert-danger', 'mt-3', 'd-none');

let currentPage = 1;
let sortOption = '';
let categoryFilter = '';
let cityFilter = '';

const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('page')) {
  currentPage = parseInt(urlParams.get('page'));
} else if (localStorage.getItem('currentPage')) {
  currentPage = parseInt(localStorage.getItem('currentPage'));
}

loadingSpinner.classList.add('d-flex');

sortDropdown.addEventListener('change', () => {
  sortOption = sortDropdown.value;
  fetchListings(1);
});

categoryFilterDropdown.addEventListener('change', () => {
  categoryFilter = categoryFilterDropdown.value;
  fetchListings(1);
});

cityFilterDropdown.addEventListener('change', () => {
  cityFilter = cityFilterDropdown.value;
  fetchListings(1);
});

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

function createPaginationLink(page, isActive) {
  const link = document.createElement('li');
  link.classList.add('page-item');
  if (isActive) {
    link.classList.add('active');
  }
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.textContent = page;
  linkContent.addEventListener('click', (event) => {
    event.preventDefault();
    fetchListings(page);
    localStorage.setItem('currentPage', page);
  });
  link.appendChild(linkContent);
  return link;
}

function createPreviousLink(page) {
  const link = document.createElement('li');
  link.classList.add('page-item');
  if (page === 1) {
    link.classList.add('disabled');
  }
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.setAttribute('aria-label', 'Previous');
  const icon = document.createElement('span');
  icon.setAttribute('aria-hidden', 'true');
  icon.classList.add('fa-solid', 'fa-angles-left');
  linkContent.appendChild(icon);
  linkContent.addEventListener('click', (event) => {
    event.preventDefault();
    fetchListings(page - 1);
    localStorage.setItem('currentPage', page - 1);
  });
  link.appendChild(linkContent);
  return link;
}

function createNextLink(page, totalPages) {
  const link = document.createElement('li');
  link.classList.add('page-item');
  if (page === totalPages) {
    link.classList.add('disabled');
  }
  const linkContent = document.createElement('a');
  linkContent.classList.add('page-link');
  linkContent.href = `#`;
  linkContent.setAttribute('aria-label', 'Next');
  const icon = document.createElement('span');
  icon.setAttribute('aria-hidden', 'true');
  icon.classList.add('fa-solid', 'fa-angles-right');
  linkContent.appendChild(icon);
  linkContent.addEventListener('click', (event) => {
    event.preventDefault();
    fetchListings(page + 1);
    localStorage.setItem('currentPage', page + 1);
  });
  link.appendChild(linkContent);
  return link;
}

async function fetchListings(page) {
  try {
    loadingSpinner.classList.remove('d-none');
    listingsDiv.innerHTML = '';
    listingsPagination.innerHTML = '';
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete('featured');
    urlParams.delete('most_rated');
    urlParams.delete('most_reviewed');
    if (sortOption) {
      urlParams.set(sortOption, '1');
      localStorage.setItem('sortOption', sortOption);
    } else {
      localStorage.removeItem('sortOption');
    }
    if (categoryFilter) {
      urlParams.set('category', categoryFilter);
    } else {
      urlParams.delete('category');
    }
    if (cityFilter) {
      urlParams.set('city', cityFilter);
    } else {
      urlParams.delete('city');
    }
    const url = `./api/listingsApi.php?page=${page}&${urlParams.toString()}`;
    const cachedResponse = localStorage.getItem(url);
    if (cachedResponse) {
      const { listings, total } = JSON.parse(cachedResponse);
      renderListings(listings, total);
      if (total !== localStorage.getItem('totalListings')) {
        localStorage.setItem('totalListings', total);
        localStorage.removeItem(`listings-${page + 1}`);
      }
    } else {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      const { listings, total } = await response.json();
      renderListings(listings, total);
      localStorage.setItem(url, JSON.stringify({ listings, total }));
      if (total !== localStorage.getItem('totalListings')) {
        localStorage.setItem('totalListings', total);
        for (let i = page + 1; i <= Math.ceil(total / 12); i++) {
          const nextPageUrl = `./api/listingsApi.php?page=${i}&${urlParams.toString()}`;
          const nextPageResponse = await fetch(nextPageUrl);
          if (!nextPageResponse.ok) {
            throw new Error('Network response was not ok');
          }
          const { listings } = await nextPageResponse.json();
          localStorage.setItem(`listings-${i}`, JSON.stringify(listings));
        }
      }
      window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
    }
  } catch (error) {
    console.error(error);
    errorBox.textContent = 'An error occurred while fetching listings. Please try again later.';
    errorBox.classList.remove('d-none');
  } finally {
    loadingSpinner.classList.add('d-none');
  }
}

function handleFilterButtonClick(event) {
  event.preventDefault();
  sortOption = event.target.getAttribute('data-filter');
  fetchListings(1);
}

filterButtons.forEach((button) => {
  button.addEventListener('click', handleFilterButtonClick);
});

function updateListingsCount(listingsCount, totalListings) {
  listingsCountElement.textContent = listingsCount;
  totalListingsElement.textContent = totalListings;
}

fetch('./api/listingsApi.php?total')
  .then((response) => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then((data) => {
    const totalListings = data.total;
    const listingsPerPage = 12;
    const totalPages = Math.ceil(totalListings / listingsPerPage);
    localStorage.setItem('totalPages', totalPages);
  })
  .catch((error) => {
    console.error(error);
    errorBox.textContent = 'An error occurred in listing count. Please try again later.';
    errorBox.classList.remove('d-none');
  });

function renderListings(listings, total) {
  if (listings.length === 0) {
    listingsDiv.innerHTML = '<p class="text-center text-bolder text-2xl text-info text-gradient my-5">Ugh! No listings found.</p>';
    listingsPagination.innerHTML = '';
    loadingSpinner.classList.add('d-none');
    updateListingsCount(0, total);
    return;
  }
  listings.forEach((listing) => {
    const card = createListingCard(listing);
    listingsDiv.appendChild(card);
  });
  const listingperPage = 12;
  const totalPages = Math.ceil(total / listingperPage);
  for (let i = 1; i <= totalPages; i++) {
    const link = createPaginationLink(i, i === currentPage);
    listingsPagination.appendChild(link);
  }
  const previousLink = createPreviousLink(currentPage);
  listingsPagination.insertBefore(previousLink, listingsPagination.firstChild);
  const nextLink = createNextLink(currentPage, totalPages);
  listingsPagination.appendChild(nextLink);
  loadingSpinner.classList.add('d-none');
  localStorage.setItem('currentPage', currentPage);
  localStorage.setItem('totalPages', totalPages);
  window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
  updateListingsCount(listings.length, total);
}

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
