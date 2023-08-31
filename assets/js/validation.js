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
passwordInput.addEventListener('input', () => {
	validateInput(
		passwordInput,
		/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,18}$/,
	)
})

// validate input
function validateInput(input, regex) {
	try {
		const value = input.value.trim()
		const isValid = regex.test(value)
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

// clear inputs
function clearInputs() {
	const inputs = document.querySelectorAll('input')
	inputs.forEach((input) => {
		input.value = ''
		input.classList.remove('is-valid')
		input.classList.remove('is-invalid')
	})
}
