import Alpine from 'alpinejs'
import intlTelInput from 'intl-tel-input'
import 'intl-tel-input/styles'
import '../css/app.css'

window.Alpine = Alpine

Alpine.start()

const phoneInput = document.querySelector('[data-international-phone]')

if (phoneInput) {
    window.whatsappPhoneInput = intlTelInput(phoneInput, {
        initialCountry: 'za',
        separateDialCode: true,
        countrySearch: true,
        loadUtils: () => import('intl-tel-input/utils'),
    })
}
