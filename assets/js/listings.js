/*
listings.js

Author: Sahil Langoo

File Content =>
1. Variables
2. Functions:
  2.1 createListingCard(listing)
  2.2 createPaginationLink(page, isActive)
  2.3 createPreviousLink(page)
  2.4 createNextLink(page, totalPages)
  2.5 fetchListings(page, sortOption)
  2.6 handleFilterButtonClick(event)
*/
// strict = true;
// ('use strict');
// Get DOM elements
const listingsDiv = document.getElementById('listings');
const errorMessage = document.getElementById('error-message');
const loadingSpinner = document.getElementById('loading-spinner');
const listingsPagination = document.getElementById('listings-pagination');
const listingsCountElement = document.getElementById('listings-count');
const totalListingsElement = document.getElementById('total-listings');

// Get current page number from URL or local storage
const urlParams = new URLSearchParams(window.location.search);
const currentPage = parseInt(urlParams.get('page')) || parseInt(localStorage.getItem('currentPage')) || 1;

// Initialize loading spinner
loadingSpinner.classList.add('d-flex');

// Create listing card HTML
function createListingCard(listing) {
  const card = document.createElement('div');
  card.classList.add('col-md-3', 'col-lg-3', 'mb-4');
  card.innerHTML = `
    <div class="card card-frame">
      <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
        <a href="./listing.php?listing=${listing.id}" class="d-block">
          <img src="./uploads/business_images/${listing.displayImage}" class="img-fluid border-radius-lg move-on-hover" alt="${listing.displayImage}" loading="lazy">
        </a>
      </div>
      <div class="card-body pt-2">
        <div class="d-flex justify-content-between align-items-center my-2">
          <span class="text-uppercase text-xxs font-weight-bold"><i class="fa-solid fa-shop"></i> ${listing.category}</span>
          <span class="text-uppercase text-xxs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${listing.city}</span>
        </div>
        <div class="d-flex justify-content-between ">
          <a href="./listing.php?listing=${listing.id}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">${listing.businessName}</a>
          <span class="text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${listing.avg_rating} (${listing.reviews_count})</span>
        </div>
        <p class="card-description text-sm mb-3" id="truncate">${listing.description} ...</p>
        <p class="mb-2 text-xxs font-weight-bolder text-warning text-gradient text-uppercase"><span>By―</span> ${listing.user}</p>
        <div class="d-flex justify-content-start my-2">
          <a href="./listing.php?listing=${listing.id}" class="text-primary text-sm icon-move-right">View details <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  `;
  return card;
}

// Create pagination link HTML
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

// Create previous page link HTML
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

// Create next page link HTML
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

// Fetch listings from API
function fetchListings(page, sortOption) {
  loadingSpinner.classList.remove('d-none');
  listingsDiv.innerHTML = '';
  listingsPagination.innerHTML = '';
  let url = `./api/listingsApi.php?page=${page}`;
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.delete('featured');
  urlParams.delete('most_rated');
  urlParams.delete('most_reviewed');
  if (sortOption) {
    urlParams.set(sortOption, '1');
    localStorage.setItem('sortOption', sortOption); // store sort option in local storage
  } else {
    localStorage.removeItem('sortOption'); // remove sort option from local storage
  }
  url += `&${urlParams.toString()}`;
  const cachedResponse = localStorage.getItem(url); // check if response is cached
  if (cachedResponse) {
    const data = JSON.parse(cachedResponse);
    data.listings.forEach((listing) => {
      const card = createListingCard(listing);
      listingsDiv.appendChild(card);
    });
    const listingperPage = 12; // number of listings per page
    const totalPages = Math.ceil(data.total / listingperPage);
    for (let i = 1; i <= totalPages; i++) {
      const link = createPaginationLink(i, i === page);
      listingsPagination.appendChild(link);
    }
    const previousLink = createPreviousLink(page);
    listingsPagination.insertBefore(previousLink, listingsPagination.firstChild);
    const nextLink = createNextLink(page, totalPages);
    listingsPagination.appendChild(nextLink);
    loadingSpinner.classList.add('d-none');
    localStorage.setItem('currentPage', page);
    localStorage.setItem('totalPages', totalPages);
    window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
    updateListingsCount(data.listings.length, data.total);
  } else {
    Promise.all([
      fetch(url), // fetch listings from the API
      fetch('./api/listingsApi.php'), // fetch total number of listings from the API
    ])
      .then(([listingsResponse, totalResponse]) => {
        if (!listingsResponse.ok || !totalResponse.ok) {
          throw new Error('Network response was not ok');
        }
        return Promise.all([listingsResponse.json(), totalResponse.json()]);
      })
      .then(([data, totalData]) => {
        data.listings.forEach((listing) => {
          const card = createListingCard(listing);
          listingsDiv.appendChild(card);
        });
        const listingperPage = 12; // number of listings per page
        const totalPages = Math.ceil(totalData.total / listingperPage);
        for (let i = 1; i <= totalPages; i++) {
          const link = createPaginationLink(i, i === page);
          listingsPagination.appendChild(link);
        }
        const previousLink = createPreviousLink(page);
        listingsPagination.insertBefore(previousLink, listingsPagination.firstChild);
        const nextLink = createNextLink(page, totalPages);
        listingsPagination.appendChild(nextLink);
        loadingSpinner.classList.add('d-none');
        localStorage.setItem('currentPage', page);
        localStorage.setItem('totalPages', totalPages);
        window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
        updateListingsCount(data.listings.length, totalData.total);
        localStorage.setItem(url, JSON.stringify(data)); // cache response in local storage
      })
      .catch((error) => {
        console.error(error);
        errorMessage.classList.remove('d-none');
        loadingSpinner.classList.add('d-none');
      });
  }
}

// Handle filter button click
function handleFilterButtonClick(event) {
  event.preventDefault();
  const sortOption = event.target.getAttribute('data-filter');
  fetchListings(1, sortOption);
}

// Add event listeners to filter buttons
const filterButtons = document.querySelectorAll('[data-filter]');
filterButtons.forEach((button) => {
  button.addEventListener('click', handleFilterButtonClick);
});

// Fetch listings on page load
if (currentPage < 1) {
  fetchListings(1);
} else {
  fetchListings(currentPage);
}

// Update listings count
function updateListingsCount(listingsCount, totalListings) {
  listingsCountElement.textContent = listingsCount;
  totalListingsElement.textContent = totalListings;
}

// Get the total number of listings from the API
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
  });
