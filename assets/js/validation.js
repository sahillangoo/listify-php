document.addEventListener('DOMContentLoaded', () => {
	// frontend validation for forms
	const inputs = document.querySelectorAll('input')

	// Loop over them and prevent submission
	Array.prototype.slice.call(inputs).forEach((input) => {
		input.addEventListener(
			'input',
			async (event) => {
				const regex = getRegexForInput(input)
				await validateInput(input, regex)
			},
			false,
		)
	})

	// validate input
	async function validateInput(input, regex) {
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

	// get regex for input
	function getRegexForInput(input) {
		switch (input.name) {
			case 'username':
				return /^(?=[a-zA-Z0-9._]{6,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/ // Minimum three characters, maximum 20 characters, letters and numbers only
			case 'email':
				return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/ // email
			case 'phone':
				return /^[0-9]{10}$/ // 10 digits only
			case 'password':
				return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#\-_@$!%*?&+~|{}:;<>/])[A-Za-z\d#\-_@$!%*?&+~|{}:;<>/]{8,18}$/ // Minimum eight characters, at least one uppercase letter, one lowercase letter and one number and one special character and maximum 18 characters are allowed in password field. (special characters are $@$!%*?&+~|{}:;<>/) and no white spaces allowed in entire password field (before, in between or after characters) eg : Abcd@123
			default:
				return null
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
})
