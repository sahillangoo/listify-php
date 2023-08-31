'use strict'
// function for focus input-group
const inputGroupClickHandler = (event) => {
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
}
// helper for adding on all elements multiple attributes
function setAttributes(el, options) {
	Object.keys(options).forEach((attr) => {
		el.setAttribute(attr, options[attr])
	})
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

	
})
