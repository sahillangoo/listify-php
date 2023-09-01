// frontend validation for forms
const forms = document.querySelectorAll('.needs-validation')
// Loop over them and prevent submission
Array.prototype.slice.call(forms).forEach((form) => {
	form.addEventListener(
		'submit',
		(event) => {
			if (!form.checkValidity()) {
				event.preventDefault()
				event.stopPropagation()
			}
			form.classList.add('was-validated')
		},
		false,
	)
})

// validate form on submit
document.addEventListener('submit', (event) => {
	const form = event.target
	if (!form.checkValidity()) {
		event.preventDefault()
		event.stopPropagation()
	}
	form.classList.add('was-validated')
})

// validate username
const usernameInput = document.getElementById('username')
usernameInput.addEventListener('input', () => {
	validateInput(usernameInput, /^[a-zA-Z0-9]{3,20}$/)
})

// validate email
const emailInput = document.getElementById('email')
emailInput.addEventListener('input', () => {
	validateInput(emailInput, /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)
})

// validate phone number
const phoneInput = document.getElementById('phone')
phoneInput.addEventListener('input', () => {
	validateInput(phoneInput, /^[0-9]{10}$/)
})

// validate password
const passwordInput = document.getElementById('password')
const passwordFeedback = passwordInput.nextElementSibling
passwordInput.addEventListener('input', () => {
	validatePasswordInput(passwordInput, passwordFeedback)
})

// validate input
function validateInput(input, regex) {
	try {
		const value = input.value.trim()
		const sanitizedValue = sanitize(value)
		const isValid = regex.test(sanitizedValue)
		if (isValid) {
			input.classList.remove('is-invalid')
			input.classList.add('is-valid')
		} else {
			input.classList.remove('is-valid')
			input.classList.add('is-invalid')
		}
	} catch (error) {
		console.error(error)
	}
}

// validate password input
function validatePasswordInput(input, feedback) {
	try {
		const value = input.value.trim()
		const sanitizedValue = sanitize(value)

		// Check if the password meets the minimum length requirement
		if (sanitizedValue.length < 8) {
			input.classList.add('is-invalid')
			input.classList.remove('is-valid')
			feedback.classList.add('invalid-feedback')
			feedback.classList.remove('valid-feedback')
		} else {
			input.classList.remove('is-invalid')
			input.classList.add('is-valid')
			feedback.classList.remove('invalid-feedback')
			feedback.classList.add('valid-feedback')
		}

		// Check if the password meets the complexity requirement
		const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/
		if (!regex.test(sanitizedValue)) {
			input.classList.add('is-invalid')
			input.classList.remove('is-valid')
			feedback.textContent =
				'Password must contain at least one lowercase letter, one uppercase letter, and one number.'
			feedback.classList.add('invalid-feedback')
			feedback.classList.remove('valid-feedback')
		}
	} catch (error) {
		console.error(error)
	}
}

// sanitize input
function sanitize(input) {
	return input.replace(/[^a-zA-Z0-9._-]/g, '')
}

// clear inputs
function clearInputs() {
	const inputs = document.querySelectorAll('input')
	inputs.forEach((input) => {
		input.value = ''
		input.classList.remove('is-valid')
		input.classList.remove('is-invalid')
	})
}
