//Add class to show cookiebot placeholders after Cookiebot load
function CookiebotCallback_OnLoad(){
        let placeholder = document.querySelectorAll('.cookie-placeholder');

        for (let i = 0; i < placeholder.length; ++i) {
            placeholder[i].classList.add("cookie-show");
        }
};
