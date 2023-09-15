<?php
// Path: listings.php
include_once './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>All Listings</title>
  <?php include_once './includes/_head.php'; ?>
</head>

<body class="index-page">
  <?php
  // include the header file
  include_once './includes/_navbar.php';
  ?>

  <!-- ========== Start Heading ========== -->
  <section class="pt-5 ">
    <div class="container">
      <div class="row">
        <div class="col-md-12 pt-5">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
              <li class="breadcrumb-item"><a href="./categories.php">Categories</a></li>
            </ol>
          </nav>
          <div class="heading text-center">
            <h2 class="h2 text-gradient text-primary ">All Listings</h2>
            <p class="lead text-secondary text-sm">"Bringing order to the digital chaos"</p>
          </div>
        </div>

        <div class="col-lg-6 text-center mx-auto">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search" aria-hidden="true"></i></span>
            <label for="search-input" class="visually-hidden">Search for Business</label>
            <input class="form-control" id="search-input" name="search" placeholder="Search for a business by name" type="text">
            <button class="btn  btn-transparent rounded mb-0" id="clear-search-input" type="button"><i class="fas fa-times text" aria-hidden="true"></i></button>
            <div class="invalid-feedback" id="search-feedback"></div>
          </div>
          <div class="list-group text-center align-items-center" id="search-results">
            <div id="search-spinner" class="spinner-border text-primary d-none" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== End Heading ========== -->


  <!-- ========== Start listing grid with pagination  ========== -->
  <section class="pt-5">
    <div class="container">
      <div class="row">

        <div class="col-auto d-inline-flex justify-content-center align-items-center mb-3 gap-3 flex-wrap filter-dropdowns">
          <!-- filter dropdown -->
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Filter Listings
            </button>
            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
              <li><button class="dropdown-item" type="button" data-filter="featured">Featured</button></li>
              <li><button class="dropdown-item" type="button" data-filter="most_rated">Top Rated</button></li>
              <li><button class="dropdown-item" type="button" data-filter="most_reviewed">Most Reviewed</button></li>
            </ul>
          </div>
          <!-- select city dropdown -->
          <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="cityDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Select City
            </button>
            <ul class="dropdown-menu" aria-labelledby="cityDropdown">
              <li><button class="dropdown-item" type="button">Srinagar</button></li>
              <li><button class="dropdown-item" type="button">Anantnag</button></li>
              <li><button class="dropdown-item" type="button">Baramulla</button></li>
              <li><button class="dropdown-item" type="button">Budgam</button></li>
              <li><button class="dropdown-item" type="button">Bandipora</button></li>
              <li><button class="dropdown-item" type="button">Pulwama</button></li>
              <li><button class="dropdown-item" type="button">Kupwara</button></li>
              <li><button class="dropdown-item" type="button">Kulgam</button></li>
              <li><button class="dropdown-item" type="button">Shopian</button></li>
              <li><button class="dropdown-item" type="button">Ganderbal</button></li>
            </ul>
          </div>
          <span>
            <!-- no of listings  -->
            <span class="text-secondary text-sm">Showing <span id="listings-count">0</span> of <span id="total-listings">0</span> listings</span>
          </span>
        </div>
        <!-- all listings -->

        <div class="col-md-12">
          <div class="row" id="listings">
            <!-- listings will be displayed here -->
          </div>
          <div class="alert alert-danger d-none" id="error-message" role="alert">
            Error fetching listings. Please try again later.
          </div>
          <div class="d-flex justify-content-center my-5" id="loading-spinner">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
          <nav aria-label="Listings pagination" class="d-flex justify-content-center">
            <ul class="pagination pagination-primary" id="listings-pagination">
              <!-- pagination links will be displayed here -->
            </ul>
          </nav>
        </div>

        <script>
          
          // listing card
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

          const listingsDiv = document.getElementById('listings');
          const errorMessage = document.getElementById('error-message');
          const loadingSpinner = document.getElementById('loading-spinner');
          const listingsPagination = document.getElementById('listings-pagination');

          loadingSpinner.classList.add('d-flex');

          function fetchListings(page, sortOption) {
            loadingSpinner.classList.remove('d-none');
            listingsDiv.innerHTML = '';
            listingsPagination.innerHTML = '';
            let url = `./api/listingsApi.php?page=${page}`;
            if (sortOption) {
              url += `&${sortOption}`;
              localStorage.setItem('sortOption', sortOption); // store sort option in local storage
            } else {
              localStorage.removeItem('sortOption'); // remove sort option from local storage
            }
            const cachedResponse = localStorage.getItem(url); // check if response is cached
            if (cachedResponse) {
              const data = JSON.parse(cachedResponse);
              data.listings.forEach(listing => {
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
              const urlParams = new URLSearchParams(window.location.search);
              urlParams.set('page', page);
              if (sortOption) {
                urlParams.set(sortOption, '1');
              } else {
                urlParams.delete('featured');
                urlParams.delete('most_rated');
                urlParams.delete('most_reviewed');
              }
              window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
            } else {
              fetch(url) // fetch listings from the API
                .then(response => response.json())
                .then(data => {
                  data.listings.forEach(listing => {
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
                  const urlParams = new URLSearchParams(window.location.search);
                  urlParams.set('page', page);
                  if (sortOption) {
                    urlParams.set(sortOption, '1');
                  } else {
                    urlParams.delete('featured');
                    urlParams.delete('most_rated');
                    urlParams.delete('most_reviewed');
                  }
                  window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
                  localStorage.setItem(url, JSON.stringify(data)); // cache response in local storage
                })
                .catch(error => {
                  console.error(error);
                  errorMessage.classList.remove('d-none');
                  loadingSpinner.classList.add('d-none');
                });
            }
          }

          const urlParams = new URLSearchParams(window.location.search);
          const currentPage = parseInt(urlParams.get('page')) || parseInt(localStorage.getItem('currentPage')) || 1;
          const totalPages = parseInt(localStorage.getItem('totalPages')) || 1;
          if (currentPage < 1 || currentPage > totalPages) {
            fetchListings(1);
          } else {
            fetchListings(currentPage);
          }

          const filterButtons = document.querySelectorAll('[data-filter]');
          filterButtons.forEach(button => {
            button.addEventListener('click', (event) => {
              event.preventDefault();
              const sortOption = event.target.getAttribute('data-filter');
              fetchListings(1, sortOption);
            });
          });
        </script>

      </div>
    </div>

  </section>


  <!-- ========== End listing grid with pagination  ========== -->


  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>
</body>

</html>
