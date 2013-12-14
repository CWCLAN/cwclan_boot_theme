/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
    function addIcon(el, entity) {
        var html = el.innerHTML;
        el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
    }
    var icons = {
        'icon-home': '&#xe000;',
        'icon-image': '&#xe001;',
        'icon-play': '&#xe002;',
        'icon-file': '&#xe003;',
        'icon-paste': '&#xe004;',
        'icon-folder-open': '&#xe005;',
        'icon-credit': '&#xe006;',
        'icon-coin': '&#xe007;',
        'icon-envelop': '&#xe008;',
        'icon-location': '&#xe009;',
        'icon-mobile': '&#xe00a;',
        'icon-screen': '&#xe00b;',
        'icon-download': '&#xe00c;',
        'icon-upload': '&#xe00d;',
        'icon-bubbles': '&#xe00e;',
        'icon-users': '&#xe00f;',
        'icon-quotes-left': '&#xe010;',
        'icon-search': '&#xe011;',
        'icon-cog': '&#xe012;',
        'icon-bug': '&#xe013;',
        'icon-remove': '&#xe014;',
        'icon-switch': '&#xe015;',
        'icon-link': '&#xe016;',
        'icon-attachment': '&#xe017;',
        'icon-star': '&#xe018;',
        'icon-star-2': '&#xe019;',
        'icon-star-3': '&#xe01a;',
        'icon-steam': '&#xe01b;',
        'icon-twitter': '&#xe01c;',
        'icon-github': '&#xe01d;',
        'icon-youtube': '&#xe01e;',
        'icon-tux': '&#xe01f;',
        'icon-apple': '&#xe020;',
        'icon-android': '&#xe021;',
        'icon-windows8': '&#xe022;',
        'icon-skype': '&#xe023;',
        'icon-paypal': '&#xe024;',
        'icon-html5': '&#xe025;',
        'icon-css3': '&#xe026;'
    },
    els = document.getElementsByTagName('*'),
            i, attr, html, c, el;
    for (i = 0; ; i += 1) {
        el = els[i];
        if (!el) {
            break;
        }
        attr = el.getAttribute('data-icon');
        if (attr) {
            addIcon(el, attr);
        }
        c = el.className;
        c = c.match(/icon-[^\s'"]+/);
        if (c && icons[c[0]]) {
            addIcon(el, icons[c[0]]);
        }
    }
};