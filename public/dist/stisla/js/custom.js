/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').getAttribute('content')

// Logout
const logoutButtons = document.querySelectorAll('.logout-btn')
const logoutForm = document.querySelector('#logout-form')
for (let i = 0; i < logoutButtons.length; i++) {
  logoutButtons[i].addEventListener('click', () => logoutForm.submit())
}  