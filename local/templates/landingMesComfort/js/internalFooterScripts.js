// var onloadCaptchas = function() {
//     if (document.getElementById('g-recaptcha-review') != null){
//         grecaptcha.render("g-recaptcha-review", {
//             'sitekey' : '6LfyviEUAAAAANc_dH8Si69b9jXdUF5MQekxZ2NI',
//         });
//     }
//     if (document.getElementById('g-recaptcha-order') != null){
//         grecaptcha.render("g-recaptcha-order", {
//             'sitekey' : '6LfyviEUAAAAANc_dH8Si69b9jXdUF5MQekxZ2NI',
//         });
//     }
//     if (document.getElementById('g-recaptcha-order_page') != null){
//         grecaptcha.render("g-recaptcha-order_page", {
//             'sitekey' : '6LfyviEUAAAAANc_dH8Si69b9jXdUF5MQekxZ2NI',
//         });
//     }
// };

(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter38950510 = new Ya.Metrika({
                id:38950510,
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true
            });
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = "https://mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");