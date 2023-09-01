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
	const password = document.getElementById('password')
	const toggle = document.getElementById('toggle-password')
	if (password.type === 'password') {
		password.type = 'text'
		toggle.classList.remove('fa-eye-slash')
		toggle.classList.add('fa-eye')
	} else {
		password.type = 'password'
		toggle.classList.remove('fa-eye')
		toggle.classList.add('fa-eye-slash')
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
})
