/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// any JS you import will output into a single css file (app.js in this case)
import './scripts/app.js';


/*
Light-Theme function
 */

// Select the button
const btn = document.querySelector(".btn-toggle");

btn.addEventListener("click", togglePageContentLightDark);

function togglePageContentLightDark() {
    console.log("OUI")
    let currentClass = document.body.className
    let newClass = document.body.className == 'dark-mode' ? 'light-mode' : 'dark-mode'
    document.body.className = newClass

    document.cookie = 'theme=' + (newClass == 'light-mode' ? 'light' : 'dark')
    console.log('Cookies are now: ' + document.cookie)
}
function isDarkThemeSelected() {
    return document.cookie.match(/theme=dark/i) != null
}
function setThemeFromCookie() {
    document.body.className = isDarkThemeSelected() ? 'dark-mode' : 'light-mode'
}
(function() {
    setThemeFromCookie()
})();


