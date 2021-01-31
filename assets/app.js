/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import Siema from 'siema';

// any JS you import will output into a single css file (app.js in this case)
import './scripts/app.js';

//DOM loaded
document.addEventListener("DOMContentLoaded", function () {

    /*
    Light-Theme function
     */
    const btn = document.querySelector(".btn-toggle");

    btn.addEventListener("click", togglePageContentLightDark);

    function togglePageContentLightDark() {
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

    (function () {
        setThemeFromCookie()
    })();

    /*
    Toggle menu function
     */
    const menuBtn = document.querySelector(".nav-logo");
    const navMenu = document.querySelector(".nav-menu");

    menuBtn.addEventListener("click", toggleMenu);

    function toggleMenu() {
        let boolean = false;
        if (boolean) {
            navMenu.classList.toggle("opened");
            boolean = true;
        } else {
            navMenu.classList.toggle("opened");
            boolean = false;
        }
    }

    /*
    Toggle menu function
     */
    const header = document.getElementById('header');
    window.onscroll = function () {
        if (window.scrollY >= 10) {
            header.classList.add("nav-colored");
        } else {
            header.classList.remove("nav-colored");
        }
    };

    /*
    Toggle notifications
     */
    const closeButton = document.querySelector(".close");
    const notification = document.querySelector(".notification");

    const isActive = false;

    if(isActive){
        closeButton.addEventListener("click", toggleNotifications)
    }

    function toggleNotifications() {
        notification.style.display = "none";
    }

    let mySiema = Siema({
        selector: '.siema',
        duration: 200,
        easing: 'ease-out',
        perPage: 1,
        startIndex: 0,
        draggable: true,
        multipleDrag: true,
        threshold: 20,
        loop: true,
        onInit: () => {},
        onChange: () => {},
    });

    Siema.prototype.addPagination = function() {
        for (let i = 0; i < this.innerElements.length; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.addEventListener('click', () => this.goTo(i));
            this.selector.appendChild(btn);
        }
    }

    // Trigger pagination creator
    mySiema.addPagination();


});


