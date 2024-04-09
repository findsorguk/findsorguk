window.addEventListener("CookiebotOnDialogDisplay", function () {
    ['Features', 'Purposes', 'Vendors'].forEach(e => {
        window.CookieConsent.dialog[`IABDeselect${e}`].call();
    })
});
