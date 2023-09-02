const debounce = (func, delay) => {
	let timeoutId
	return (...args) => {
		clearTimeout(timeoutId)
		timeoutId = setTimeout(() => {
			func.apply(null, args)
		}, delay)
	}
}

document.addEventListener('DOMContentLoaded', () => {
	// function for truncate description
	const descriptions = document.querySelectorAll('#truncate')
	descriptions.forEach((description) => {
		description.textContent = description.textContent.slice(0, 120) + '...'
	})

	// initialization of Tooltips
	const tooltipTriggerList = [].slice.call(
		document.querySelectorAll('[data-bs-toggle="tooltip"], [data-toggle="tooltip"]'),
	)
	const tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})

	// validation for forms
	const inputs = [...document.querySelectorAll('input')]
	inputs.forEach((input) => {
		input.addEventListener(
			'input',
			debounce(async (event) => {
				const regex = getRegexForInput(input)
				await validateInput(input, regex)
			}, 500),
			false,
		)
	})

	function validateInput(input, regex) {
		const value = input.value.trim()
		return Promise.resolve(regex.test(value))
			.then((isValid) => {
				input.classList.toggle('is-valid', isValid)
				input.classList.toggle('is-invalid', !isValid)
			})
			.catch(console.error)
	}
	const regexPatterns = {
		username: /^(?<username>[a-zA-Z0-9._]{6,20})$/,
		email: /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
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
	}

	function getRegexForInput(input) {
		return regexPatterns[input.name] || null
	}
})

const inputGroupClickHandler = (event) => {
	try {
		const parent = event.target.closest('.input-group')
		if (event.target.classList.contains('form-control')) {
			const focus = document.querySelectorAll('.input-group.focused')
			focus.forEach((el) => el.classList.remove('focused'))
			parent.classList.add('focused')
		}
		const focus = document.querySelectorAll('.input-group.focused')
		if (focus && event.target != parent && event.target.parentNode != parent) {
			focus.forEach((el) => el.classList.remove('focused'))
		}
	} catch (error) {
		console.error(error)
	}
}

// helper for adding on all elements multiple attributes
function setAttributes(el, options) {
	try {
		Object.keys(options).forEach((attr) => {
			el.setAttribute(attr, options[attr])
		})
	} catch (error) {
		console.error(error)
	}
}

function togglePassword() {
	const password = document.querySelector('#password')
	const toggle = document.querySelector('#toggle-password')
	if (password.type === 'password') {
		password.type = 'text'
		toggle.classList.replace('fa-eye-slash', 'fa-eye')
	} else {
		password.type = 'password'
		toggle.classList.replace('fa-eye', 'fa-eye-slash')
	}
}

// search bar
const searchInput = document.getElementById('search-input')
const searchFeedback = document.getElementById('search-feedback')
const searchResults = document.getElementById('search-results')
const searchSpinner = document.getElementById('search-spinner')

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
					<span class="text-body-secondary text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> ${
						result.avg_rating?.toFixed(2) || 0
					} (${result.reviews_count})</span>
					<span class="text-body-secondary text-capitalize text-xs font-weight-bold"><i class="fa-solid fa-shop"></i> ${
						result.category
					}</span>
					<span class="text-body-secondary text-capitalize text-xs font-weight-bold "><i class="fa-solid fa-location-dot"></i> ${
						result.address
					}, ${result.city}</span>
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

async function search() {
	const searchQuery = searchInput.value.trim()
	if (/^\d+$/.test(searchQuery)) {
		searchFeedback.innerHTML = `Search query cannot contain only numbers`
		searchInput.classList.add('is-invalid')
		searchResults.classList.add('d-none')
		searchSpinner.classList.add('d-none')
		return
	}
	if (!/^[a-zA-Z]{3,20}$/.test(searchQuery)) {
		searchFeedback.innerHTML = `Search query must be between 3 and 20 characters long and contain only letters`
		searchInput.classList.add('is-invalid')
		searchResults.classList.add('d-none')
		searchSpinner.classList.add('d-none')
		return
	}
	searchSpinner.classList.remove('d-none')
	try {
		const response = await fetch(`search.php?q=${searchQuery}`)
		const data = await response.json()
		displaySearchResults(data)
	} catch (error) {
		console.error(error)
	}
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
