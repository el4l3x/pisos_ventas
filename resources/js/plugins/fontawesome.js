import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHome, faDollarSign, faStore, faCartArrowDown, faUser } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faHome, faDollarSign, faStore, faCartArrowDown, faUser)

Vue.component('font-awesome-icon', FontAwesomeIcon)