const searchInput = document.getElementById('search-input')
const searchFeedback = document.getElementById('search-feedback')
const searchResults = document.getElementById('search-results')
const searchSpinner = document.getElementById('search-spinner')

function debounce(func, delay) {
	let timeoutId
	return function (...args) {
		clearTimeout(timeoutId)
		timeoutId = setTimeout(() => {
			func.apply(null, args)
		}, delay)
	}
}

function displaySearchResults(results) {
	searchResults.innerHTML = ''
	searchFeedback.innerHTML = ''
	if (Array.isArray(results) && results.length === 0 && searchInput.value.length >= 3) {
		searchFeedback.innerHTML = `No results found for this query`
		searchInput.classList.add('is-invalid')
	} else if (Array.isArray(results)) {
		results.slice(0, 6).forEach((result) => {
			const resultElement = document.createElement('a')
			resultElement.href = `./listing.php?listing=${result.id}`
			resultElement.classList.add('list-group-item', 'list-group-item-action')
			resultElement.innerHTML = `
				<div class="d-flex w-100 justify-content-between align-items-center">
					<h5 class="text-gradient text-primary font-weight-bold h5 mb-1">${result.businessName}</h5>
					<span class="text-body-secondary text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${result.avg_rating?.toFixed(2) || 0} (${result.reviews_count})</span>
					<span class="text-body-secondary text-capitalize text-xs font-weight-bold"><i class="fa-solid fa-shop"></i> ${result.category}</span>
					<span class="text-body-secondary text-capitalize text-xs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${result.address}, ${result.city}</span>
				</div>
			`
			searchResults.appendChild(resultElement)
		})
		searchInput.classList.remove('is-invalid')
	} else if (results.error) {
		searchFeedback.innerHTML = `${results.error}`
		searchInput.classList.add('is-invalid')
	}
	searchResults.classList.toggle('d-none', results.length === 0)
	searchSpinner.classList.add('d-none')
}

function search() {
	const searchQuery = searchInput.value
	searchSpinner.classList.remove('d-none')
	fetch(`search.php?q=${searchQuery}`)
		.then((response) => response.json())
		.then((data) => displaySearchResults(data))
		.catch((error) => console.error(error))
}

searchInput.addEventListener('input', debounce(search, 500))
document.getElementById('search-input').addEventListener('input', debounce(search, 500))
document.addEventListener('click', inputGroupClickHandler, false)

document.getElementById('clear-search-input').addEventListener('click', () => {
	searchInput.value = ''
	searchInput.classList.remove('is-invalid')
	searchResults.innerHTML = ''
	searchResults.classList.add('d-none')
})