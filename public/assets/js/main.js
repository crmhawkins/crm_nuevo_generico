function slideToggle(t,e,o){0===t.clientHeight?j(t,e,o,!0):j(t,e,o)}function slideUp(t,e,o){j(t,e,o)}function slideDown(t,e,o){j(t,e,o,!0)}function j(t,e,o,i){void 0===e&&(e=400),void 0===i&&(i=!1),t.style.overflow="hidden",i&&(t.style.display="block");var p,l=window.getComputedStyle(t),n=parseFloat(l.getPropertyValue("height")),a=parseFloat(l.getPropertyValue("padding-top")),s=parseFloat(l.getPropertyValue("padding-bottom")),r=parseFloat(l.getPropertyValue("margin-top")),d=parseFloat(l.getPropertyValue("margin-bottom")),g=n/e,y=a/e,m=s/e,u=r/e,h=d/e;window.requestAnimationFrame(function l(x){void 0===p&&(p=x);var f=x-p;i?(t.style.height=g*f+"px",t.style.paddingTop=y*f+"px",t.style.paddingBottom=m*f+"px",t.style.marginTop=u*f+"px",t.style.marginBottom=h*f+"px"):(t.style.height=n-g*f+"px",t.style.paddingTop=a-y*f+"px",t.style.paddingBottom=s-m*f+"px",t.style.marginTop=r-u*f+"px",t.style.marginBottom=d-h*f+"px"),f>=e?(t.style.height="",t.style.paddingTop="",t.style.paddingBottom="",t.style.marginTop="",t.style.marginBottom="",t.style.overflow="",i||(t.style.display="none"),"function"==typeof o&&o()):window.requestAnimationFrame(l)})}

let sidebarItems = document.querySelectorAll('.sidebar-item.has-sub');
for(var i = 0; i < sidebarItems.length; i++) {
    let sidebarItem = sidebarItems[i];
	sidebarItems[i].querySelector('.sidebar-link').addEventListener('click', function(e) {
        e.preventDefault();

        let submenu = sidebarItem.querySelector('.submenu');
        if( submenu.classList.contains('active') ) submenu.style.display = "block"

        if( submenu.style.display == "none" ) submenu.classList.add('active')
        else submenu.classList.remove('active')
        slideToggle(submenu, 300)
    })
}

window.addEventListener('DOMContentLoaded', (event) => {
    var w = window.innerWidth;
    if(w < 1200) {
        var sb = document.getElementById('sidebar');
        if(sb) sb.classList.remove('active');
    }
});
window.addEventListener('resize', (event) => {
    var w = window.innerWidth;
    var sb = document.getElementById('sidebar');
    if(!sb) return;
    if(w < 1200) {
        sb.classList.remove('active');
    }else{
        sb.classList.add('active');
    }
});

var burgerBtn = document.querySelector('.burger-btn');
if (burgerBtn) {
    burgerBtn.addEventListener('click', () => {
        let sidebar = document.getElementById('sidebar');
        let overlay = document.getElementById('overlay');
        let main = document.getElementById('main');

        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            overlay.style.zIndex = '999';
            overlay.style.display = 'block';
            document.body.appendChild(overlay);

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebar.style.display = 'none';
                main.style.marginLeft = '0';
                overlay.remove();
            });
        }
        sidebar.classList.toggle('active');
        if(sidebar.classList.contains('active')){ sidebar.style.display = 'block'; } else {
            overlay.remove();
        }
    });
}

var sidebarHide = document.querySelector('.sidebar-hide');
if (sidebarHide) {
    sidebarHide.addEventListener('click', () => {
        let sidebar = document.getElementById('sidebar');
        let overlay = document.getElementById('overlay');
        let main = document.getElementById('main');
        sidebar.classList.toggle('active');
        if(sidebar.classList.contains('active')){
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
            main.style.marginLeft = '0';
            if (overlay) {
                overlay.remove();
            }
        }
    });
}

// Perfect Scrollbar Init
if(typeof PerfectScrollbar == 'function') {
    const container = document.querySelector(".sidebar-wrapper");
    if(container) {
        const ps = new PerfectScrollbar(container, {
            wheelPropagation: false
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.location.pathname.includes('/configuracion')) {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper) {
            sidebarWrapper.scrollTop = sidebarWrapper.scrollHeight;
        }
    }
});

// Scroll into active sidebar
var activeSidebarItem = document.querySelector('.sidebar-item.active');
if (activeSidebarItem) {
    activeSidebarItem.scrollIntoView(false);
}
